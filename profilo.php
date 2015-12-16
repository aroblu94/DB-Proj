<?php

require_once "inc/config.php";

$tit_pag="Profilo";

switch ($_POST[op]) {
	case "save":
		if (!empty($_FILES['img'])) {
			$ext1=explode(".",$_FILES['img']['name']);
			$ext=strtolower($ext1[count($ext1)-1]);
			$fld="imgs/".$_SESSION[User2decide][id].".".$ext;
			move_uploaded_file($_FILES['img']['tmp_name'],$fld);
			$res = ssql("select set_avatar('".$_SESSION[User2decide][id]."', '".$_SESSION[User2decide][id].".".$ext."')");
		}
		$res = ssql("select set_nome('".$_SESSION[User2decide][id]."', '".$_POST[nome]."', '".$_POST[cognome]."')");
		if($res>0) {
			$_SESSION[notify][type]="ok";
			$_SESSION[notify][text]="Utente aggiornato con successo.";
		}
		else {
			$_SESSION[notify][type]="err";
			$_SESSION[notify][text]="Errore! Utente non aggiornato, riprovare. Se il problema persiste contattare l'amministratore.";
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
$res = sql("select * from utenti where id='".$_SESSION[User2decide][id]."'");
while ($usr = pg_fetch_array($res)) {
	$nome = $usr[nome];
	$cognome = $usr[cognome];
	$user = $usr[username];
	$img = $usr[avatar];
}

bsformo();
hidden("save");
BSimg("img", "Avatar", $img);
BSinput("user", "Username", $user);
BSinput("nome", "Nome", $nome);
BSinput("cognome", "Cognome", $cognome);
BSbutton("Aggiorna");
bsformc();

$corpoPagina=ob_get_contents();
ob_end_clean();

$jq2footer.='
$("#user").prop("disabled", true);
';

include "inc/template_all.php";

?>

