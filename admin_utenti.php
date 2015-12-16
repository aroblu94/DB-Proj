<?php
require_once "inc/config.php";

$tit_pag="Utenti";
$admin_class="active";

switch($_POST[op].$_GET[op]) {
	case "ban":
		if(isset($_GET[user2ban])) {
			$res = ssql("select ban(".$_GET[user2ban].")");
			if($res) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Utente bannato dal portale con successo.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Utente non bannato, riprovare. Se il problema persiste contattare l'amministratore.";			
			}
		}
	break;

	case "unban":
		if(isset($_GET[user2ban])) {
			$res = ssql("select unban(".$_GET[user2ban].")");
			if($res) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Utente riabilitato con successo.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Utente non riabilitato, riprovare. Se il problema persiste contattare l'amministratore.";			
			}
		}
	break;

	case "makeorg":
		if(isset($_GET[user])) {
			$res = ssql("select make_organizzatore(".$_GET[user].")");
			if($res) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Utente promosso a organizzatore con successo.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Utente non promosso a organizzatore, riprovare. Se il problema persiste contattare l'amministratore.";			
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

ob_start();

# Ciclo gli utenti
$res = sql("select * from utenti where id>2");
$count=0;
while($u = pg_fetch_array($res)) {
	$nome = $u[nome]." ".$u[cognome];
	$list="";
	if(ssql("select is_ban(".$u[id].")")>0) {
		$dban="disabled";
		$dunban="";
	}
	else {
		$dban="";
		$dunban="disabled";	
	}
	if(ssql("select get_org(".$u[id].")")>2) {
		$cert="";
		$setorg="<br><br><a href='admin_utenti.php?op=makeorg&user=".$u[id]."' class='btn btn-warning btn-sm btn-sm'>Rendi organizzatore</a>";
	}
	else {
		$setorg="";
		$cert="<i class='fa fa-certificate right'></i>";
	}
	# Tornei a cui partecipa l'utente
	$r = sql("select t.id as id, concat(t.nome,' ed.',t.ed) as nome, t.data_inizio as inizio
					from torneo as t
						join iscritto_a on t.id=IDtorneo
					where IDutente='".$u[id]."'");
	while($t = pg_fetch_array($r)) {
		$list.='<p>'.$t[nome].' '.$t[inizio].'</p>';
	}
	# Stampo
	$usrs.='
	<div class="col-md-3 col-sm-6">
		<div class="portlet portlet-boxed">
			<div class="portlet-header">
				<h5 class="portlet-title"><u>'.$u[nome].' '.$u[cognome].'</u>'.$cert.'</h5>
			</div>
			<div class="portlet-body">
				'.$list.'
				<div class="portlet-footer mycenter">
					<a href="admin_utenti.php?op=ban&user2ban='.$u[id].'" class="btn btn-primary btn-sm btn-sm" '.$dban.'>Ban</a>
					<a href="admin_utenti.php?op=unban&user2ban='.$u[id].'" class="btn btn-success btn-sm btn-sm" '.$dunban.'>Unban</a>
					'.$setorg.'
				</div>
			</div>
		</div>
	</div>';
	if($count==3) {
		$usrs.="<div class=\"clearfix visible-md visible-lg\"></div>";
		$count=0;
	}
	else
		$count++;
}

echo $usrs;

$corpoPagina=ob_get_contents();
ob_end_clean();

include "inc/template_all.php";

?>