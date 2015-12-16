<?php
require_once "inc/config.php";

$sononellaindex=1;

$tit_pag="Home";
$home_class="active";

switch ($_GET[op].$_POST[op]) {
	case "logout":
		unset($_COOKIE['user']);
		setcookie('user', null);
		pg_close($_SESSION[conn]);
		unset($_SESSION[User2decide]);
		unset($_SESSION[loggedUser]);
		unset($_SESSION[trustedUser]);
		unset($_SESSION[adminUser]);
		session_unset();
		session_destroy();
		header("location:index.php");
		die();
	break;

	case "join":
		if(isset($_GET[user])) {
			if(ssql("select reached_max_iscr(".$_GET[torneo].")")<1) {
				if(ssql("select iscrivi_torneo(".$_GET[user].", ".$_GET[torneo].")")>0) {
					$n = ssql("select get_nomet(".$_GET[torneo].")");
					$e = ssql("select get_edizione(".$_GET[torneo].")");
					$_SESSION[notify][type]="ok";
					$_SESSION[notify][text]="Ti sei iscritto con successo al torneo $n ed.$e. Verrai notificato non appena l'organizzatore approverà l'iscrizione.";
				}
				else {
					$_SESSION[notify][type]="warn";
					$_SESSION[notify][text]="Attenzione! Sei gia iscritto al torneo $n ed.$e.";
				}
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Attenzione! È stato raggiunto il numero massimo di iscrizioni per il torneo $n ed.$e, 
										iscrizione non effettuata.";
			}
		}
	break;

	case "leggi":
		$res = ssql("select read_all_notifications(".$_SESSION[User2decide][id].")");
		header("location:".$_GET[ret].".php");
	break;

	case "read":
		if(isset($_GET[id2read])) {
			$res = ssql("select leggi_notifica(".$_GET[id2read].")");
		}
	break;

	default:
	break;
}

ob_start();

if(!isset($_COOKIE[user])) {
	$disable="disabled";
	$_SESSION[notify][type]="warn";
	$_SESSION[notify][text]="Benvenuto visitatore! Per partecipare ai tornei è necessaria la registrazione al sito.";
	$quota="t.quota_iscr";
}
elseif(ssql("select is_ban(".$_SESSION[User2decide][id].")")>0) {
	$disable="disabled";
	$_SESSION[notify][type]="err";
	$_SESSION[notify][text]="Attenzione, il tuo utente è disabilitato, contattare l'amministratore.";
	$quota="t.quota_iscr";
}
else {
	$and="and ia.IDutente='".$_SESSION[User2decide][id]."'";
	$quota="round(t.quota_iscr+((t.quota_iscr/100)*ia.sconto_rincaro),2)";
}

# Ciclo i tornei in corso
$res = sql("select t.id as id,
					t.nome as nome,
					t.ed as ed,
					t.data_inizio as data_inizio,
					t.data_fine as data_fine,
					ty.nome as tipo,
					t.num_gironi as gironi,
					concat(u.nome,' ',u.cognome) as admin,
					(select count(*) from iscritto_a
						where IDtorneo=t.id
							and IDutente>2) as iscritti
				from torneo t 
					left join tipo_torneo ty on ty.id=t.tipo
					left join utenti u on u.id=t.admin
				where (data_fine>=now() or data_fine is null)
					and data_inizio<=now()
				order by data_inizio asc");
$count=0;
while($t = pg_fetch_array($res)) {
	$fine=$approved=$gironi="";
	if(isset($_COOKIE[user])) {
		$approvato=ssql("select is_iscr_approved(".$_SESSION[User2decide][id].", ".$t[id].")");
		$iscritto=ssql("select is_iscritto(".$_SESSION[User2decide][id].", ".$t[id].")");
	}
	if($t[data_fine]>0)
		$fine=$t[data_fine];
	else
		$fine="da destinarsi";
	if($iscritto) {
		if($approvato)
			$approved="<i class='fa fa-check-circle green right'></i>";
		else
			$approved="<i class='fa fa-exclamation-triangle yellow right'></i>";
	}
	if(ssql("select get_tipo(".$t[id].")")==2)
		$gironi="(a ".$t[gironi]." gironi)";
	$list.='
	<div class="col-md col-sm-6">
		<div class="portlet portlet-boxed">
			<div class="portlet-header">
				<h4 class="portlet-title"><u>'.$t[nome].' ed.'.$t[ed].' </u>'.$approved.'</h4>
			</div>
			<div class="portlet-body">
				<p>Data inizio: '.$t[data_inizio].'</p>
				<p>Data fine: '.$fine.'</p>
				<p>Tipo torneo: '.$t[tipo].' '.$gironi.'</p>
				<p>Organizzatore: '.$t[admin].'</p>
				<p>Partecipanti: '.$t[iscritti].'</p>
				<div class="portlet-footer">
					<a href="index.php?prog=1&torneo='.$t[id].'" class="btn btn-secondary btn-sm btn-sm left">Programma</a>
					<a href="index.php?clas=1&torneo='.$t[id].'" class="btn btn-warning btn-sm btn-sm right">Classifica</a>
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
	echo "Nessun torneo in corso.";
else
	echo $list;

BSclearfix();
BSspacer();
BSheader("Tornei futuri");
$list="";
# Ciclo i tornei futuri
$res = sql("select distinct t.id as id,
					t.nome as nome,
					t.ed as ed,
					t.data_inizio as data_inizio,
					t.data_fine as data_fine,
					t.chiusura_iscr as chiusura_iscr,
					ty.nome as tipo,
					t.num_gironi as gironi,
					concat(u.nome,' ',u.cognome) as admin,
					".$quota." as quota,
					(select count(*) from iscritto_a
						where IDtorneo=t.id
							and IDutente>2) as iscritti,
					t.partecipanti as max
				from torneo t 
					left join tipo_torneo ty on ty.id=t.tipo
					left join utenti u on u.id=t.admin
					left join iscritto_a ia on t.id=ia.IDtorneo
											".$and."
				where data_inizio>now() and (data_fine>=now() or data_fine is null)
				order by data_inizio asc");
$count=0;
while($t = pg_fetch_array($res)) {
	$d=$closed_class=$iscr_class=$fine=$approved=$gironi="";
	if(isset($_COOKIE[user])) {
		$approvato=ssql("select is_iscr_approved(".$_SESSION[User2decide][id].",".$t[id].")");
		$iscritto=ssql("select is_iscritto(".$_SESSION[User2decide][id].",".$t[id].")");
	}
	if(ssql("select reached_iscr_date(".$t[id].")")) {
		$closed_class="red";
		$d="disabled";
	}
	if($t[iscritti]>=$t[max]) {
		$iscr_class="red";
		$d="disabled";
	}
	
	if($t[data_fine]>0)
		$fine=$t[data_fine];
	else
		$fine="da destinarsi";
	if($iscritto) {
		$d="disabled";
		if($approvato)
			$approved="<i class='fa fa-check-circle green right'></i>";
		else
			$approved="<i class='fa fa-exclamation-triangle yellow right'></i>";
	}
	if(ssql("select get_tipo(".$t[id].")")==2)
		$gironi="(a ".$t[gironi]." gironi)";
	$list.='
	<div class="col-md col-sm-6">
		<div class="portlet portlet-boxed">
			<div class="portlet-header">
				<h4 class="portlet-title"><u>'.$t[nome].' ed.'.$t[ed].' </u>'.$approved.'</h4>
			</div>
			<div class="portlet-body">
				<p>Data inizio: '.$t[data_inizio].'</p>
				<p class="'.$closed_class.'">Chiusura iscrizioni: '.$t[chiusura_iscr].'</p>
				<p>Data fine: '.$fine.'</p>
				<p>Tipo torneo: '.$t[tipo].' '.$gironi.'</p>
				<p>Organizzatore: '.$t[admin].'</p>
				<p>Quota iscrizione: '.$t[quota].'€</p>
				<p class="'.$iscr_class.'">Iscritti: '.$t[iscritti].'/'.$t[max].'</p>
				<div class="portlet-footer">
					<a href="index.php?op=join&torneo='.$t[id].'&user='.$_SESSION[User2decide][id].'" class="btn btn-primary btn-sm btn-sm right '.$d.' '.$disable.'">Iscriviti</a>
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
	echo "Nessun torneo futuro.";
else
	echo $list;

BSformomod("index.php");
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
	$appendthat = str_replace("\n","",$start.$rows.$end);
	$n = ssql("select get_nomet(".$_GET[torneo].")");
	$e = ssql("select get_edizione(".$_GET[torneo].")");
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
						when ia.ban=1 then -1
						else sum(pa.punteggio)
					end as punti
				from utenti u
					left join partecipa_a pa on pa.IDutente=u.id
					left join gara g on g.id=pa.IDgara
					left join iscritto_a ia on ia.IDutente=pa.IDutente
				where g.IDtorneo='".$_GET[torneo]."'
					and ia.IDtorneo='".$_GET[torneo]."'
					and u.id>2
				group by pa.IDutente, u.nome, u.cognome, ia.ban
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
BSformcmod("index.php");

$jq2footer.='
$("#title > u").empty();
$("#title > u").append("Tornei in corso");

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

$corpoPagina=ob_get_contents();
ob_end_clean();

include "inc/template_all.php";

?>


