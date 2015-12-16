<?php
require_once "inc/config.php";

$tit_pag="Gare e gironi";
$admin_class="active";

ob_start();

switch($_POST[op].$_GET[op]) {
	case "gen":
		$type = ssql("select get_tipo(".$_GET[torneo].")");
		if($type != 1) {
			$res = ssql("select genera_torneo(".$_GET[torneo].")");
		}
		else
			$res=0;

		if($res>0) {
			$_SESSION[notify][type]="ok";
			$_SESSION[notify][text]="Giornate create con successo.";
		}
		else {
			$_SESSION[notify][type]="err";
			$_SESSION[notify][text]="Errore! Giornate non create, riprovare. Se il problema persiste contattare l'amministratore.";
		}
	break;

	case "regris":
		if(isset($_POST[gara])) {
			$altro=$resf=0;
			$res = ssql("select registra_risultato('".$_POST[gara]."', '".$_POST[g1]."', '".$_POST[gioc1]."', '".$_POST[g2]."', '".$_POST[gioc2]."')");
			if($res>0) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Risultato aggiornato con successo.";

				if($_POST[tipo]==3)
					$resf = ssql("select genera_fase_succ(".$_GET[torneo].")");
				elseif($_POST[tipo]==2)
					$resf = ssql("select genera_ita_misto(".$_GET[torneo].")");
				else
					$altro=1;
				if($altro) {
					$end = ssql("select is_ended(".$_GET[torneo].")");
					if($end>0)
						$res = ssql("select ins_vincitore(".$_GET[torneo].")");
				}
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Risultato non aggiornato, riprovare. Se il problema persiste contattare l'amministratore.";
			}
		}
	break;

	case "new":
		if(isset($_POST[data]) && $_POST[gg1]!=$_POST[gg2]) {
			$res = ssql("select ins_gara(".$_GET[torneo].", '".$_POST[gg1]."', '".$_POST[gg2]."', '".$_POST[data]."')");
			if($res>0) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Gara inserita con successo.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Gara non inserita, riprovare. Se il problema persiste contattare l'amministratore.";
			}		
		}
		else {
			$_SESSION[notify][type]="err";
			$_SESSION[notify][text]="Errore! Gara non inserita, controllare il form e riprovare.";
		}
	break;

	case "close":
		if(isset($_GET[torneo])) {
			$res = ssql("select set_fine(".$_GET[torneo].")");
			if($res>0) {
				$_SESSION[notify][type]="ok";
				$_SESSION[notify][text]="Generazione manuale del torneo terminata con successo.";
			}
			else {
				$_SESSION[notify][type]="err";
				$_SESSION[notify][text]="Errore! Operazione non effettuata, riprovare. Se il problema persiste contattare l'amministratore.";
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

if(isset($_GET[torneo])) {
	$n = ssql("select get_nomet(".$_GET[torneo].")");
	$e = ssql("select get_edizione(".$_GET[torneo].")");
	$tit_pag.=" del torneo $n ed.$e";
	$tipo = ssql("select get_tipo(".$_GET[torneo].")");
	$end = ssql("select is_ended(".$_GET[torneo].")");
	$isend = ssql("select has_fine(".$_GET[torneo].")");
	if($end>0) {
		$res=sql("select concat(u.nome,' ',u.cognome,' ') as winner
					from utenti u
						join vince v on u.id=v.IDutente
					where v.IDtorneo='".$_GET[torneo]."'");
		$vincitori="";
		while($v=pg_fetch_array($res)) {
			$vincitori.=$v[winner];
			$_SESSION[notify][type]="ok";
			$_SESSION[notify][text]="Torneo concluso e vinto da $vincitori";
		}
	}
	$hasg = ssql("select has_gare(".$_GET[torneo].")");
	if($hasg>0) {
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
		if($tipo==1 && !$isend) {
			while($g=pg_fetch_array($res)) {
				$rows.="
				<tr>
					<td>".$g[data]."</td>
					<td>".$g[vincitore]."</td>
					<td>".$g[giocatori]."</td>
					<td>".$g[risultati]."</td>
					<td>".$g[punteggi]."</td>
					<td><a href='#' disabled><i class='fa fa-edit fadisabled'></i></a></td>
				</tr>
				";
			}			
		}
		elseif($tipo==2) {
			$girone = "<th>Girone</th>";
			while($g=pg_fetch_array($res)) {
				$rows.="
				<tr>
					<td>".$g[girone]."</td>
					<td class='data'>".$g[data]."</td>
					<td>".$g[vincitore]."</td>
					<td class='players'>".$g[giocatori]."</td>
					<td class='res'>".$g[risultati]."</td>
					<td class='points'>".$g[punteggi]."</td>
					<td class='mod'><a href='admin_gare.php?open=1&torneo=".$_GET[torneo]."&gid=".$g[id]."'><i class='fa fa-edit'></i></a></td>
				</tr>
				";
			}
		}
		else {
			while($g=pg_fetch_array($res)) {
				$rows.="
				<tr>
					<td class='data'>".$g[data]."</td>
					<td>".$g[vincitore]."</td>
					<td class='players'>".$g[giocatori]."</td>
					<td class='res'>".$g[risultati]."</td>
					<td class='points'>".$g[punteggi]."</td>
					<td class='mod'><a href='admin_gare.php?open=1&torneo=".$_GET[torneo]."&gid=".$g[id]."'><i class='fa fa-edit'></i></a></td>
				</tr>
				";
			}
		}
		$start="
		<table class='table table-striped table-bordered table-hover ui-datatable' id='garet'>
		<thead>
			<tr>
				".$girone."
				<th>Data</th>
				<th>Vincitore</th>
				<th>Giocatori</th>
				<th>Risultati</th>
				<th>Punteggi</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		";
		$end="
		</tbody>
		</table>
		";
		echo $start.$rows.$end;
	}
	elseif($tipo!=1) {
		echo '<a href="admin_gare.php?op=gen&torneo='.$_GET[torneo].'" class="btn btn-warning btn-sm">Genera gare</a>
			<br><br>Per questo torneo non sono state ancora generate gare e/o gironi.';
	}
	else {
		echo 'Per questo torneo non sono state ancora generate gare e/o gironi.';
	}

	# modulo di creazione gare per torneo libero
	if($tipo==1) {
		BSspacer();
		if(!$isend)
			echo '<a href="admin_gare.php?op=close&torneo='.$_GET[torneo].'" class="btn btn-warning btn-sm">Termina la creazione delle gare</a>';
		BSspacer();
		BSheader("Inserisci gara");
		BSformo();
		hidden("new");
		$data = ssql("select get_inizio(".$_GET[torneo].")");
		BSdate("data", "Data", $data);
		BSdropdown("gg1", "Giocatore 1", "select u.id as user, concat(u.nome,' ',u.cognome) as nome 
											from utenti u
												join iscritto_a ia on u.id=ia.IDutente
											where ia.IDtorneo='".$_GET[torneo]."'
												and u.id>2", "user", "nome", "");
		BSdropdown("gg2", "Giocatore 2", "select u.id as user, concat(u.nome,' ',u.cognome) as nome 
											from utenti u
												join iscritto_a ia on u.id=ia.IDutente
											where ia.IDtorneo='".$_GET[torneo]."'
												and u.id>2", "user", "nome", "");
		BSbutton("Inserisci");
		BSformc();
		$jq2footer.='
		var fine = '.$isend.';
		if(fine) {
			$("#data").prop("disabled", true);
			$("#gg1").prop("disabled", true);
			$("#gg2").prop("disabled", true);
			$("#formo button").prop("disabled", true);
		}
		';
	}


	BSformomod("admin_gare.php?torneo=".$_GET[torneo]);
	if($_GET[open]>0 && $_GET[gid]>0) {
		hidden("regris");
		hidden($_GET[gid], "gara");
		$gara = multisqlarr("select * from gara where id='".$_GET[gid]."'");
		$tipo = ssql("select get_tipo(".$_GET[torneo].")");
		hidden($tipo, "tipo");
		$jq2footer='$("#modal-title").empty();
					$("#modal-title").append("Risultati della gara del '.$gara[data].'");
					$("#edithere").modal("show");';
		$res = sql("select nome,
							cognome,
							risultato,
							id
						from utenti 
							left join partecipa_a on IDutente=id
						where IDgara='".$gara[id]."'");
		$c=1;
		while($g=pg_fetch_array($res)) {
			BSnum("gioc$c", $g[nome]." ".$g[cognome], $g[risultato]);
			hidden($g[id], "g$c");
			$c++;
		}
		BSbutton("Inserisci");
	}
	BSformcmod("admin_gare.php?torneo=".$_GET[torneo]);
}
else
	echo "Per poter gestire le gare e i gironi dei tuoi tornei devi selezionare il torneo da <a href='admin_tornei.php'>qui</a>.";
					

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
	})
';

$corpoPagina=ob_get_contents();
ob_end_clean();

include "inc/template_all.php";

?>