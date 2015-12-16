<?php
require_once "inc/config.php";

$tit_pag="Gestione password";

switch($_POST[op].$_GET[op]) {
	case "mod":
		if($_POST[new1]==$_POST[new2]) {
			$res = ssql("select mod_pass('".$_SESSION[User2decide][chi]."', md5('".$_POST[old]."'), md5('".$_POST[new1]."'))");
			if($res>0) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Password modificata con successo.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Attenzione! Hai inserito la password errata.";
			}
		}
		else {
			$_SESSION[notify][type]="warn";
			$_SESSION[notify][text]="Attenzione! Le due password non corrispondono.";
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
BSformo();
hidden("mod");
BSpass("old", "Vecchia password", "");
BSspacer();
BSpass("new1", "Nuova password", "");
BSpass("new2", "Ripeti password", "");
BSbutton("Aggiorna");
BSformc();
$corpoPagina=ob_get_contents();
ob_end_clean();

include "inc/template_all.php";
?>