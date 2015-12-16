<?php
require_once "inc/config.php";

switch ($_POST[op]) {
	case "logreg":
		$user = $_POST[user];
		$pass = $_POST[pass];

		if($_POST[register]>0) {
			$nome = $_POST[nome];
			$cognome = $_POST[cognome];
			# Check if user already exists
			$res = ssql("select esiste('".$user."')");
			if($res>0) {
				$_SESSION[notify][type]="warn";
				$_SESSION[notify][cl]="col-md-4 myalert";
				$_SESSION[notify][text]="<strong>Attenzione!</strong> Username già esistente.";
			}
			# If not register him
			else {
				$res = ssql("select iscrivi('".$user."', md5('".$pass."'), '".$nome."', '".$cognome."')");
				if($res) {
					$_SESSION[notify][type]="ok";
					$_SESSION[notify][cl]="col-md-4 myalert";
					$_SESSION[notify][text]="Utente creato con successo.";
				}
				else {
					$_SESSION[notify][type]="err";
					$_SESSION[notify][cl]="col-md-4 myalert";
					$_SESSION[notify][text]="<strong>Errore!</strong> Non è stato possibile registrare l'account, prego riprovare. Se l'errore persiste contattare l'amministratore. Ci scusiamo per il disagio.";				
				}
			}
		}
		else {
			# Check if user exists
			$id = ssql("select esiste('".$user."')");
			if($id<1) {
				$_SESSION[notify][type]="warn";
				$_SESSION[notify][cl]="col-md-4 myalert";
				$_SESSION[notify][text]="<strong>Attenzione!</strong> Utente non esistente, prego registrarsi.";
			}
			else {
				# Check if password is ok
				$res = ssql("select login_corretto(".$id.",md5('".$pass."'))");
				if($res > 0) {
					# Then login him
					setcookie("user", $id);
					$_SESSION[User2decide][id] = $id;
					$org = ssql("select get_org(".$id.")");
					if($org==1)
						$_SESSION[adminUser][id] = $id;
					else if($org==2)
						$_SESSION[trustedUser][id] = $id;
					else if($org==3)
						$_SESSION[loggedUser][id] = $id;
					header("location:index.php");
				}
				else {
					$_SESSION[notify][type]="warn";
					$_SESSION[notify][cl]="col-md-4 myalert";
					$_SESSION[notify][text]="<strong>Attenzione!</strong> Password errata, riprovare.";
				}
			}
		}
	break;

	default:
	break;
}

$tit_pag="Login";
$host="Progetto BD";
$camponome="nome";
$campocognome="cognome";
$campouser="user";
$campopass="pass";
$submit="submit";

include "inc/template_login.php";
?>