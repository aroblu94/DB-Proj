<?php
require_once "inc/config.php";

$tit_pag="I Miei Tornei";
$my_class="active";

ob_start();

################
# FEED THE CAL #
################
$cres = sql("select g.id as id, g.data as data, t.nome as nome_torneo, t.ed as ed_torneo
				from gara g
					left join partecipa_a pa on g.id=pa.IDgara
					left join torneo t on t.id=g.IDtorneo
				where pa.IDutente=".$_SESSION[User2decide][id]."
					");
$out = array();
while($row=pg_fetch_array($cres)) {
	$row[data] = date('Y-m-d', strtotime($row[data]));
	$out[] = array(
		'id' => intval($row[id]),
		'title' => 'Gara del torneo '.$row[nome_torneo].' ed.'.$row[ed_torneo],
		'start' => strtotime($row[data]).'000',
		'end' => strtotime($row[data]).'000'
	);
}
$food = json_encode($out);
##############################

switch($_GET[op].$_POST[op]) {
	case "revoke":
		if(isset($_GET[torneo])) {
			$res = ssql("select revoca_iscr(".$_SESSION[User2decide][id].", ".$_GET[torneo].")");
			if($res>0) {
				$n = ssql("select get_nomet(".$_GET[torneo].")");
				$e = ssql("select get_edizione(".$_GET[torneo].")");
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Hai revocato con successo l'iscrizione al torneo $n ed.$e.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Attenzione! Operazione non effettuata, riprovare. Se l'errore persiste contattare l'amministratore.";
			}
		}
	break;

	case "read":
		if(isset($_GET[id2read])) {
			$res = ssql("select leggi_notifica(".$_GET[id2read].")");
		}
	break;

	default:
	break;
}

echo '
<div class="mycal mycenter">
	<h3 id="cal_title"></h3>
	<div class="btn-group mycenter">
		<button class="btn btn-primary" data-calendar-nav="prev"><< Prec</button>
		<button class="btn btn-default" data-calendar-nav="today">Oggi</button>
		<button class="btn btn-primary" data-calendar-nav="next">Succ >></button>
	</div>
	<div class="btn-group mycenter">
		<button class="btn btn-warning" data-calendar-view="year">Anno</button>
		<button class="btn btn-warning" data-calendar-view="month">Mese</button>
		<button class="btn btn-warning" data-calendar-view="week">Settimana</button>
		<button class="btn btn-warning" data-calendar-view="day">Giorno</button>
	</div>
</div>';
echo '<div id="calendar" class="mycal"></div>';
BSspacer();
BSheader("Tornei in corso");
# Ciclo i tornei in corso a cui partecipo
$res = sql("select distinct
					t.id as id,
					t.nome as nome,
					t.ed as ed,
					t.data_inizio as data_inizio,
					t.data_fine as data_fine,
					t.chiusura_iscr as chiusura_iscr,
					ty.nome as tipo,
					t.num_gironi as gironi,
					concat(u.nome,' ',u.cognome) as admin,
					t.quota_iscr as quota,
					(select count(*) from iscritto_a
						where IDtorneo=t.id
							and IDutente>2) as iscritti,
					t.partecipanti as max
				from torneo t 
					left join tipo_torneo ty on ty.id=t.tipo
					left join utenti u on u.id=t.admin
					left join iscritto_a ia on t.id=ia.IDtorneo
				where (data_fine>=now() or data_fine is null)
					and data_inizio<=now()
					and ia.IDutente=".$_SESSION[User2decide][id]."");
$count=0;
while($t = pg_fetch_array($res)) {
	if($t[data_fine]>0)
		$fine=$t[data_fine];
	else
		$fine="da destinarsi";
	if(ssql("select get_tipo(".$t[id].")")==2)
		$gironi="(a ".$t[gironi]." gironi)";
	else
		$gironi="";
	$list.='
	<div class="col-md col-sm-6">
		<div class="portlet portlet-boxed">
			<div class="portlet-header">
				<h4 class="portlet-title"><u>'.$t[nome].' ed.'.$t[ed].'</u></h4>
			</div>
			<div class="portlet-body">
				<p>Data inizio: '.$t[data_inizio].'</p>
				<p>Data fine: '.$fine.'</p>
				<p>Tipo torneo: '.$t[tipo].' '.$gironi.'</p>
				<p>Organizzatore: '.$t[admin].'</p>
				<div class="portlet-footer">
					<a href="mycomps.php?prog=1&torneo='.$t[id].'" class="btn btn-secondary btn-sm btn-sm left">Programma</a>
					<a href="mycomps.php?clas=1&torneo='.$t[id].'" class="btn btn-warning btn-sm btn-sm right">Classifica</a>
				</div>
			</div>
		</div>
	</div>';
	if($count==1) {
		$list.="<div class=\"clearfix visible-md visible-lg\"></div>";
		$count=0;
	}
	else
		$count++;
}
if(!$list)
	echo "Non sei iscritto a nessun torneo in corso";
echo $list;
$list="";
BSclearfix();
BSspacer();
BSheader("Tornei futuri");
# Ciclo i tornei futuri a cui partecipo
$res = sql("select distinct
					t.id as id,
					t.nome as nome,
					t.ed as ed,
					t.data_inizio as data_inizio,
					t.chiusura_iscr as chiusura_iscr,
					ty.nome as tipo,
					t.num_gironi as gironi,
					concat(u.nome,' ',u.cognome) as admin,
					case when ia.sconto_rincaro is not null then
							round(t.quota_iscr+((t.quota_iscr/100)*ia.sconto_rincaro),2)
						else
							t.quota_iscr
					end as quota
				from torneo t 
					left join tipo_torneo ty on ty.id=t.tipo
					left join utenti u on u.id=t.admin
					left join iscritto_a ia on t.id=ia.IDtorneo
				where (data_fine>=now() or data_fine is null)
					and data_inizio>now()
					and ia.IDutente=".$_SESSION[User2decide][id]."");
$count=0;
while($t = pg_fetch_array($res)) {
	if(ssql("select get_tipo(".$t[id].")")==2)
		$gironi="(a ".$t[gironi]." gironi)";
	else
		$gironi="";
	$list.='
	<div class="col-md col-sm-6">
		<div class="portlet portlet-boxed">
			<div class="portlet-header">
				<h4 class="portlet-title"><u>'.$t[nome].' ed.'.$t[ed].'</u></h4>
			</div>
			<div class="portlet-body">
				<p>Chiusura iscrizioni: '.$t[chiusura_iscr].'</p>
				<p>Data inizio: '.$t[data_inizio].'</p>
				<p>Tipo torneo: '.$t[tipo].' '.$gironi.'</p>
				<p>Organizzatore: '.$t[admin].'</p>
				<p>Quota iscrizione: '.$t[quota].'€</p>
				<div class="portlet-footer">
					<a href="mycomps.php?op=revoke&torneo='.$t[id].'&user='.$_SESSION[User2decide][id].'" class="btn btn-primary btn-sm btn-sm left">Revoca iscrizione</a>
				</div>
			</div>
		</div>
	</div>';
	if($count==1) {
		$list.="<div class=\"clearfix visible-md visible-lg\"></div>";
		$count=0;
	}
	else
		$count++;
}
if(!$list)
	echo "Non sei iscritto a nessun torneo futuro";
echo $list;

BSformomod("mycomps.php");
# calendario delle gare con rispettivi vincitori
if($_GET[prog]>0) {
	$start="
	<table class='table table-striped table-bordered table-hover ui-datatable' id='garet'>
	<thead>
		<tr>";

	if(ssql("select get_tipo(".$_GET[torneo].")")==2)
		$start.="<th>Girone</th>";

	$start.="
			<th>Data</th>
			<th>Vincitore</th>
			<th>Giocatori</th>
			<th>Risultati</th>
			<th>Punteggi</th>
		</tr>
	</thead>
	<tbody>
	";
	$end="
	</tbody>
	</table>
	";
	$rows="";
	$res=sql("select g.id as id,
					g.data as data,
					g.vincitore as vincitore,
					case when g.vincitore is null
						then '-'
						else concat(u.nome,' ',u.cognome)
					end	as vincitore,
					array_to_string(array_agg(concat(u2.nome,' ',u2.cognome)), '<br>') as giocatori,
					array_to_string(array_agg(pa.risultato), '<br>') as risultati,
					array_to_string(array_agg(concat('+',pa.punteggio)), '<br>') as punteggi,
					g.girone as girone
				from gara g
					left join utenti u on u.id=g.vincitore
					left join partecipa_a pa on pa.IDgara=g.id 
					left join utenti u2 on u2.id=pa.IDutente
				where g.IDtorneo='".$_GET[torneo]."'
				group by g.id, u.cognome, u.nome
				order by g.girone, g.data asc");

	while($g=pg_fetch_array($res)) {
		if(ssql("select get_tipo(".$_GET[torneo].")")==2)
			$gironi="<td>".$g[girone]."</td>";
		$rows.="
		<tr>
			".$gironi."
			<td class='data'>".$g[data]."</td>
			<td>".$g[vincitore]."</td>
			<td class='players'>".$g[giocatori]."</td>
			<td class='res'>".$g[risultati]."</td>
			<td class='points'>".$g[punteggi]."</td>
		</tr>
		";
	}
	$n = ssql("select get_nomet(".$_GET[torneo].")");
	$e = ssql("select get_edizione(".$_GET[torneo].")");
	$appendthat = str_replace("\n","",$start.$rows.$end);
	$jq2footer.='$("#modal-title").empty();
				$("#formomod").empty();
				$("#edithere").modal("show");
				$("#modal-title").append("Calendario gare del torneo '.$n.' ed.'.$e.'");
				';
	if($rows!="")
		$jq2footer.='$("#formomod").append("'.$appendthat.'");';
	else
		$jq2footer.='$("#formomod").append("Programma non ancora definito.");';
}
# classifica delle gare
elseif($_GET[clas]>0) {
	$start="
	<table class='table table-striped table-bordered table-hover ui-datatable'>
	<thead>
		<tr>
			<th>Posizione</th>
			<th>Giocatore</th>
			<th>Punteggio</th>
		</tr>
	</thead>
	<tbody>
	";
	$end="
	</tbody>
	</table>
	";
	$rows="";
	$res=sql("select concat(u.nome,' ',u.cognome) as giocatore, 
					sum(pa.punteggio),
					case  when sum(pa.punteggio) is null then 0
						else sum(pa.punteggio)
					end as punti
				from utenti u
					left join partecipa_a pa on pa.IDutente=u.id
					left join gara g on g.id=pa.IDgara
				where g.IDtorneo='".$_GET[torneo]."'
					and u.id>2
				group by pa.IDutente, u.nome, u.cognome
				order by punti desc");
	$position=1;
	while($r=pg_fetch_array($res)) {
		$rows.="
		<tr>
			<td>".$position."</td>
			<td>".$r[giocatore]."</td>
			<td>".$r[punti]."</td>
		</tr>
		";
		$position++;
	}
	$n = ssql("select get_nomet(".$_GET[torneo].")");
	$e = ssql("select get_edizione(".$_GET[torneo].")");
	$appendthat = str_replace("\n","",$start.$rows.$end);
	$jq2footer.='$("#modal-title").empty();
				$("#formo").empty();
				$("#edithere").modal("show");
				$("#modal-title").append("Classifica del torneo '.$n.' ed.'.$e.'");
				';
	if($position>1)
		$jq2footer.='$("#formomod").append("'.$appendthat.'");';
	else
		$jq2footer.='$("#formomod").append("Classifica non ancora definita.");';	
}
BSformcmod();

$ADDcss = '<link rel="stylesheet" href="./deps/bower_components/bootstrap-calendar/css/calendar.min.css">';
$ADDjs = '<script type="text/javascript" src="./deps/bower_components/underscore/underscore-min.js"></script>
		<script type="text/javascript" src="./deps/bower_components/bootstrap-calendar/js/calendar.min.js"></script>
		<script type="text/javascript" src="./deps/bower_components/bootstrap-calendar/js/language/it-IT.js"></script>';

$jq2footer.="
//var food = ".$food.";
var calendar = $('#calendar').calendar(
	{
		tmpl_path: '/deps/bower_components/bootstrap-calendar/tmpls/',
		events_source: ".$food.",
		language: 'it-IT',
		weekbox: 'false',
		onAfterViewLoad: function(view) {
			$('#cal_title').text(this.getTitle());
			$('.btn-group button').removeClass('active');
			$('button[data-calendar-view=\"' + view + '\"]').addClass('active');
		},
		classes: {
			months: {
				general: 'label'
			}
		}
	});

$('.btn-group button[data-calendar-nav]').each(function() {
	var \$this = $(this);
	\$this.click(function() {
		calendar.navigate(\$this.data('calendar-nav'));
	});
});

$('.btn-group button[data-calendar-view]').each(function() {
	var \$this = $(this);
	\$this.click(function() {
		calendar.view(\$this.data('calendar-view'));
	});
});";


$corpoPagina=ob_get_contents();
ob_end_clean();

$jq2footer.='
	$("#garet > tbody tr").each(function() {
		var players = $(this).find(".players").html();
		console.log(players);
		if(players.indexOf("Direttamente") >= 0){
			var data = $(this).find(".data");
			data.empty();
			var res = $(this).find(".res");
			res.empty();
			var points = $(this).find(".points");
			points.empty();
			points.append("+3");
			var mod = $(this).find(".mod");
			mod.empty();
		}
	});
';

include "inc/template_all.php";

?>