<?php
require_once "inc/config.php";

$tit_pag="Tornei Passati";
$past_class="active";
$sononellaindex=1;

ob_start();

switch ($_GET[op].$_POST[op]) {
	case "read":
		if(isset($_GET[id2read])) {
			$res = ssql("select leggi_notifica(".$_GET[id2read].")");
		}
	break;

	default:
	break;
}

if(!isset($_COOKIE[user])) {
	$disable="disabled";
	$_SESSION[notify][type]="warn";
	$_SESSION[notify][text]="Benvenuto visitatore! Per partecipare ai tornei è necessaria la registrazione al sito.";
}
elseif(ssql("select is_ban(".$_SESSION[User2decide][id].")")>0) {
	$disable="disabled";
	$_SESSION[notify][type]="err";
	$_SESSION[notify][text]="Attenzione, il tuo utente è disabilitato, contattare l'amministratore.";	
}

# Ciclo i tornei
$res = sql("select t.id as id,
					t.nome as nome,
					t.ed as ed,
					t.data_inizio as data_inizio,
					t.data_fine as data_fine,
					t.chiusura_iscr as chiusura_iscr,
					t.nome as tipo,
					t.num_gironi as gironi,
					concat(u.nome,' ',u.cognome) as admin,
					t.quota_iscr as quota,
					(select count(*) from iscritto_a
						where IDtorneo=t.id
							and IDutente>2) as iscritti,
					t.partecipanti as max,
					array_to_string(array_agg(concat(u2.nome,' ',u2.cognome)), ', ') as vincitore
				from torneo t 
					left join tipo_torneo ty on ty.id=t.tipo
					left join utenti u on u.id=t.admin
					left join vince v on v.IDtorneo=t.id
					left join utenti u2 on u2.id=v.IDutente
				where data_fine<now() and data_fine is not null
				group by t.id, u.nome, u.cognome
				order by data_inizio desc");
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
				<p>Data inizio: '.$t[data_inizio].'</p>
				<p>Data fine: '.$t[data_fine].'</p>
				<p>Tipo torneo: '.$t[tipo].' '.$gironi.'</p>
				<p>Organizzatore: '.$t[admin].'</p>
				<p>Vincitore: '.$t[vincitore].'</p>
				<div class="portlet-footer">
					<a href="passati.php?prog=1&torneo='.$t[id].'" class="btn btn-secondary btn-sm btn-sm left">Programma</a>
					<a href="passati.php?clas=1&torneo='.$t[id].'" class="btn btn-warning btn-sm btn-sm right">Classifica</a>
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
	echo "Nessun torneo passato";

echo $list;

BSformomod("passati.php");
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
				$("#formomod").empty();
				$("#edithere").modal("show");
				$("#modal-title").append("Classifica del torneo '.$n.' ed.'.$e.'");
				';
	if($position>1)
		$jq2footer.='$("#formomod").append("'.$appendthat.'");';
	else
		$jq2footer.='$("#formomod").append("Classifica non ancora definita.");';	
}
BSformcmod("passati.php");

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
