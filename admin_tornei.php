<?php
require_once "inc/config.php";

$tit_pag="Tornei";
$tit_link="<a href=#edithere data-toggle=modal data-target=#edithere><i class='fa fa-plus-circle'></i></a>";
$admin_class="active";

$ngironi=$gironi="";
switch($_POST[op].$_GET[op]) {
	case "new":
		if($_POST[ried]<1) {
			# NON riedizione
			if($_POST[tipo]==2)
				$res = ssql("select new_torneo_misto('".$_POST[nome]."', ".$_POST[ed].", ".$_POST[tipo].", '".$_POST[data_inizio]."', 
						'".$_POST[chiusura_iscr]."',".$_POST[partecipanti].", ".$_SESSION[User2decide][id].", ".$_POST[quota].", '".$_POST[n_gironi]."')");
			else
				$res = ssql("select new_torneo('".$_POST[nome]."', ".$_POST[ed].", ".$_POST[tipo].", '".$_POST[data_inizio]."', '".$_POST[chiusura_iscr]."', 
							".$_POST[partecipanti].", ".$_SESSION[User2decide][id].", ".$_POST[quota].")");
		}
		else {
			# RIEDIZIONE DI $_POST[rieddi]
			$res = ssql("select new_torneo_ried('".$_POST[rieddi]."','".$_POST[data_inizio]."','".$_POST[chiusura_iscr]."',".$_POST[quota].")");

		}

		if($res>0) {
			$_SESSION[notify][type]="ok";
			$_SESSION[notify][text]="Torneo creato con successo.";
		}
		else {
			$_SESSION[notify][type]="err";
			$_SESSION[notify][text]="Errore! Torneo non creato, controllare che la coppia di attributi Nome ed Edizione non sia già stata utilizzata e riprovare. Se il problema persiste contattare l'amministratore.";			
		}
	break;

	case "mod":
		if($_POST[tipo]==2)
			$res = ssql("select edit_torneo_misto(".$_POST[idt].", ".$_POST[tipo].", '".$_POST[data_inizio]."', 
					'".$_POST[chiusura_iscr]."',".$_POST[partecipanti].", ".$_SESSION[User2decide][id].", ".$_POST[quota].", '".$_POST[n_gironi]."')");
		else
			$res = ssql("select edit_torneo(".$_POST[idt].", ".$_POST[tipo].", '".$_POST[data_inizio]."', 
					'".$_POST[chiusura_iscr]."',".$_POST[partecipanti].", ".$_SESSION[User2decide][id].", ".$_POST[quota].")");
		if($res) {
			$_SESSION[notify][type]="ok";
			$_SESSION[notify][text]="Torneo aggiornato con successo.";
		}
		else {
			$_SESSION[notify][type]="err";
			$_SESSION[notify][text]="Errore! Torneo non aggiornato, riprovare. Se il problema persiste contattare l'amministratore.";
		}
	break;

	case "read":
		if(isset($_GET[id2read])) {
			$res = ssql("select leggi_notifica(".$_GET[id2read].")");
		}
	break;

	case "kill":
		if(isset($_GET[torneo])) {
			$res = ssql("select elimina_torneo(".$_GET[torneo].")");
			if($res) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Torneo eliminato, sono stati notificati tutti gli iscritti.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Torneo non eliminato, riprovare. Se il problema persiste contattare l'amministratore.";
			}
		}
	break;
	
	default:
	break;
}

ob_start();

# Ciclo i tornei
if(!isset($_SESSION[adminUser][id])) {
	$where="and t.admin=".$_SESSION[trustedUser][id]."";
	$where1="where admin=".$_SESSION[trustedUser][id]."";
}

$res = sql("select t.id as id,
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
				where (t.data_fine>now()
					or t.data_fine is null)
				".$where."
				order by data_inizio asc");
$count=0;
while($t = pg_fetch_array($res)) {
	$fine=$dis=$nogmrs=$gironi="";
	if($t[data_fine]>0)
		$fine=$t[data_fine];
	else
		$fine="da destinarsi";
	if($t[iscritti]<1)
		$nogmrs="disabled";
	if(ssql("select get_tipo(".$t[id].")")==2)
		$gironi="(a ".$t[gironi]." gironi)";
	if(ssql("select reached_iscr_date(".$t[id].")"))
		$dis="disabled";
	$list.='
	<div class="col-md col-sm-6">
		<div class="portlet portlet-boxed">
			<div class="portlet-header">
				<h4 class="portlet-title"><u>'.$t[nome].' ed.'.$t[ed].'</u>
					<a href="admin_tornei.php?op=kill&torneo='.$t[id].'" class="right"><i class="fa fa-trash"></i></a>
				</h4>
			</div>
			<div class="portlet-body">
				<p>Data inizio: '.$t[data_inizio].'</p>
				<p>Chiusura iscrizioni: '.$t[chiusura_iscr].'</p>
				<p>Data fine: '.$fine.'</p>
				<p>Tipo torneo: '.$t[tipo].' '.$gironi.'</p>
				<p>Organizzatore: '.$t[admin].'</p>
				<p>Quota iscrizione: '.$t[quota].'€</p>
				<p>Iscritti: '.$t[iscritti].'/'.$t[max].'</p>
				<p><a href="admin_gare.php?torneo='.$t[id].'" '.$nogmrs.'>Gare e gironi <i class="fa fa-arrow-circle-right"></i></a></p>
				<div class="portlet-footer">
					<a href="admin_tornei.php?open=1&torneo='.$t[id].'" class="btn btn-secondary btn-sm btn-sm left" '.$dis.'>Modifica</a>
					<a href="admin_giocatori.php?torneo='.$t[id].'&gamers=1" class="btn btn-primary btn-sm btn-sm right" '.$nogmrs.'>Giocatori</a>
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
	echo "Non hai organizzato nessun torneo.";

echo $list;

BSformomod("admin_tornei.php");
$list="<ul>";
if(isset($_GET[open])) {
	if(isset($_GET[gamers])) {
		$gamers=sql("select * 
						from utenti
							left join iscritto_a on id=IDutente
						where IDtorneo='".$_GET[torneo]."'");
		while($g=pg_fetch_array($gamers)) {
			$jq2footer='$("#modal-title").empty();
						$("#modal-title").append("Iscritti");
						$("#edithere").modal("show");';
			$list.="<li>".$g[nome]." ".$g[cognome]."</li>";		
		}
		$list.="</ul>";
		echo $list;
	}
	else {
		$torneo=sql("select * from torneo where id='".$_GET[torneo]."'");
		while($t=pg_fetch_array($torneo)) {
			$jq2footer='$("#modal-title").empty();
						$("#modal-title").append("Modifica '.$t[nome].' ed.'.$t[ed].'");
						$("#edithere").modal("show");';
			hidden("mod");
			hidden("idt", $_GET[torneo]);
			BSinput("nome", "Nome", $t[nome]);
			BSnum("ed", "Edizione", $t[ed]);
			BSdropdown("tipo", "Tipo", "select id,nome from tipo_torneo", "id", "nome", $t[tipo]);
			BSnum("n_gironi", "Numero Gironi", $t[num_gironi]);
			BSdate("chiusura_iscr", "Chiusura Iscrizioni", $t[chiusura_iscr]);
			BSdate("data_inizio", "Data Inizio", $t[data_inizio]);
			BSnum("partecipanti", "Max Partecipanti", $t[partecipanti]);
			BSinput("quota", "Quota", $t[quota_iscr]);
			BSbutton("Aggiorna");

			$jq2footer.='
				$("#nome").prop("disabled", true);
				$("#ed").prop("disabled", true);
			';
		}
	}
}
else {
	$jq2footer.='$("#modal-title").empty();
				$("#modal-title").append("Nuovo torneo");
				//$("#n_gironi").prop("disabled", true);';
	hidden("new");
	BScheck("ried", "Riedizione", 0);
	BSdropdown("rieddi", "Riedizione di", "select nome,id from torneo ".$where1." 
												where ed<=all(select ed from torneo t1 where t1.nome=nome)", "id", "nome", "");
	BSinput("nome", "Nome", "");
	BSnum("ed", "Edizione", "1");
	BSdropdown("tipo", "Tipo", "select id,nome from tipo_torneo", "id", "nome", "1");
	BSnum("n_gironi", "Numero Gironi", 0);
	BSdate("chiusura_iscr", "Chiusura Iscrizioni", date("Y-m-d"));
	BSdate("data_inizio", "Data Inizio", date("Y-m-d", time()+86400));
	BSnum("partecipanti", "Max Partecipanti", "2");
	BSinput("quota", "Quota", "1");
	BSbutton("Inserisci");
}
BSformcmod("admin_tornei.php");

$jq2footer.='
// se torneo misto allora posso scegliere num gironi
if($("#tipo").val()!=2)
	$("#n_gironi").prop("disabled", true);
else
	$("#n_gironi").prop("disabled", false);
$("#tipo").change(function() {
	if($(this).val()!=2)
		$("#n_gironi").prop("disabled", true);
	else
		$("#n_gironi").prop("disabled", false);
});

// se checkato riedizione disabilita tutto
// eccetto le date e la quota
// altrimenti disabilita dropdown riedizione
if($("#ried").is(":checked")) {
	$("#nome").prop("disabled", true);
	$("#ed").prop("disabled", true);
	$("#tipo").prop("disabled", true);
	$("#n_gironi").prop("disabled", true);
	$("#partecipanti").prop("disabled", true);
}
else
	$("#rieddi").prop("disabled", true);
$(":checkbox").change(function() {
	if($("#ried").is(":checked")) {
		$("#nome").prop("disabled", true);
		$("#ed").prop("disabled", true);
		$("#tipo").prop("disabled", true);;
		$("#n_gironi").prop("disabled", true);
		$("#partecipanti").prop("disabled", true);
		$("#rieddi").prop("disabled", false);
	}
	else {
		$("#rieddi").prop("disabled", true);
		$("#nome").prop("disabled", false);
		$("#ed").prop("disabled", false);
		$("#tipo").prop("disabled", false);
		$("#n_gironi").prop("disabled", false);
		$("#partecipanti").prop("disabled", false);
	}
});

';

$corpoPagina=ob_get_contents();
ob_end_clean();

include "inc/template_all.php";

?>