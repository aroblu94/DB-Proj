<?php
require_once "inc/config.php";

$tit_pag="Giocatori";
$admin_class="active";

ob_start();

switch($_GET[op].$_POST[op]) {
	case "approve":
		if(isset($_GET[u])) {
			$res = ssql("select approve_iscr(".$_GET[u].",".$_GET[torneo].")");
			if($res) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Iscrizione approvata.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Iscrizione non approvata, riprovare. Se il problema persiste contattare l'amministratore.";
			}
		}
	break;

	case "ban":
		if(isset($_GET[u])) {
			$res = sql("select ban_local(".$_GET[u].",".$_GET[torneo].")");
			if($res) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Giocatore bannato dal torneo.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Ban del giocatore non effettuato, riprovare. Se il problema persiste contattare l'amministratore.";			
			}
		}
	break;

	case "sconto":
		$tot=$ok=0;
		foreach($_POST as $key => $val) {
			if($key!="op") {
				$tot++;
				$ret = ssql("select agg_quota(".$key.", ".$_GET[torneo].", '".$val."')");
				if($ret>0)
					$ok++;
			}
		}
		if($ok==$tot) {
			$_SESSION[notify][type]="ok";
			$_SESSION[notify][text]="Quote aggiornate con successo.";
		}
		else {
			$_SESSION[notify][type]="err";
			$_SESSION[notify][text]="Errore! Quote non aggiornate, riprovare. Se il problema persiste contattare l'amministratore.";
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

if(isset($_GET[torneo])) {
	$n = ssql("select get_nomet(".$_GET[torneo].")");
	$e = ssql("select get_edizione(".$_GET[torneo].")");
	$tit_pag.=" del torneo $n ed.$e";
	$res=sql("select g.username as username,
					g.nome as nome,
					g.cognome as cognome,
					g.id as id,
					ia.sconto_rincaro as sconto_rincaro
				from utenti g
					join iscritto_a ia on ia.IDutente=g.id
				where ia.IDtorneo='".$_GET[torneo]."'
					and g.id>2");
	$start = ssql("select reached_start_date(".$_GET[torneo].")");
	if($start>0)
		$dis="disabled";
	while($g=pg_fetch_array($res)) {
		$dapp=$dban="";
		$app = ssql("select is_iscr_approved(".$g[id].", ".$_GET[torneo].")");
		if($app)
			$dapp = "disabled";
		$ban=ssql("select is_ban_local(".$g[id].",".$_GET[torneo].")");
		if($ban)
			$dban = "disabled";
		$usrs.='
		<div class="col-md-3 col-sm-6">
			<div class="portlet portlet-boxed">
				<div class="portlet-header">
					<h4 class="portlet-title"><u>'.$g[nome].' '.$g[cognome].'</u></h4>
				</div>
				<div class="portlet-body">
					<input id="'.$g[id].'" name="'.$g[id].'" value="'.$g[sconto_rincaro].'" class="form-control" type="number" step="5" '.$dapp.'>
					<br>
					<a href="admin_giocatori.php?op=approve&u='.$g[id].'&torneo='.$_GET[torneo].'" class="btn btn-success btn-sm" '.$dapp.'>Approva</a>
					<a href="admin_giocatori.php?op=ban&u='.$g[id].'&torneo='.$_GET[torneo].'" class="btn btn-primary btn-sm" '.$dban.'>Ban</a>
				</div>
			</div>
		</div>';
	}
	BSformo();
	echo '<div class="form-group">
			<button class="btn btn-warning btn-large" type="submit" id="tbutton" '.$dis.'>Aggiorna quote</button>
		</div>';			
	echo $usrs;
	hidden("sconto");
	BSformc();
}
else
	echo "Per poter gestire i giocatori dei tuoi tornei devi selezionare il torneo da <a href='admin_tornei.php'>qui</a>.";

$corpoPagina=ob_get_contents();
ob_end_clean();

include "inc/template_all.php";

?>