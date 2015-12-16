<?php


if (isset($_SESSION[adminUser][id]))
	$usr_menu='
	<li class="'.$home_class.'"><a href="index.php">Home</a></li>
	<li class="'.$past_class.'"><a href="passati.php">Tornei Passati</a></li>
	<li class="'.$my_class.'"><a href="mycomps.php">I Miei Tornei</a></li>
	<li class="'.$hof_class.'"><a href="myhof.php">Il Mio Albo d\'Oro</a></li>
	<li class="dropdown '.$admin_class.'" id="cstmdrop1">
		<a href="admin_tornei.php" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
			Admin<i class="mainnav-caret"></i>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="admin_tornei.php">Tornei</a></li>
			<li><a href="admin_gare.php">Gare e gironi</a></li>
			<li><a href="admin_giocatori.php">Giocatori</a></li>
			<li><a href="admin_utenti.php">Utenti</a></li>
		</ul>
	</li>';

elseif (isset($_SESSION[loggedUser][id])) {
	$ban = ssql("select is_ban('".$_SESSION[User2decide][id]."')");
	if(!$ban) {
		$usr_menu='
		<li class="'.$home_class.'"><a href="index.php">Home</a></li>
		<li class="'.$past_class.'"><a href="passati.php">Tornei Passati</a></li>
		<li class="'.$my_class.'"><a href="mycomps.php">I Miei Tornei</a></li>
		<li class="'.$hof_class.'"><a href="myhof.php">Il Mio Albo d\'Oro</a></li>';
	}
	else
		$usr_menu='
		<li class="'.$home_class.'"><a href="index.php">Home</a></li>
		<li class="'.$past_class.'"><a href="passati.php">Tornei Passati</a></li>';
}

elseif(isset($_SESSION[trustedUser][id]))
	$usr_menu='
	<li class="'.$home_class.'"><a href="index.php">Home</a></li>
	<li class="'.$past_class.'"><a href="passati.php">Tornei Passati</a></li>
	<li class="'.$my_class.'"><a href="mycomps.php">I Miei Tornei</a></li>
	<li class="'.$hof_class.'"><a href="myhof.php">Il Mio Albo d\'Oro</a></li>
	<li class="dropdown '.$admin_class.'" id="cstmdrop1">
		<a href="admin_tornei.php" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
			Admin<i class="mainnav-caret"></i>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="admin_tornei.php">Tornei</a></li>
			<li><a href="admin_gare.php">Gare e gironi</a></li>
			<li><a href="admin_giocatori.php">Giocatori</a></li>
		</ul>
	</li>';
else
	$usr_menu='
	<li class="'.$home_class.'"><a href="index.php">Home</a></li>
	<li class="'.$past_class.'"><a href="passati.php">Tornei Passati</a></li>';



if(isset($_SESSION[User2decide])) {
	$nome = ssql("select get_nome('".$_SESSION[User2decide][id]."')");
	$img = ssql("select get_img('".$_SESSION[User2decide][id]."')");
	if($img=="")
		$img="default.jpg";
	$notifiche = getNotifiche();
	$n_notifiche = count($notifiche);
	if($n_notifiche==0)
		$notifichezz = "<a href=# class=notification><span class=notification-title>Nessuna notifica</span></a>";
	else {
		for($i=0; $i<$n_notifiche; $i++) {
			$notifichezz.=$notifiche[$i];
		}
		$badge='<b class="badge badge-primary" id="badge">'.$n_notifiche.'</b>';
	}
	$menu='
	<ul class="nav navbar-nav navbar-left">
		<li class="dropdown navbar-notification">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-bell navbar-notification-icon"></i>
				<span class="visible-xs-inline">Notifiche</span>
				'.$badge.'
			</a>
			<div class="dropdown-menu">
				<div class="dropdown-header">Notifiche</div>
					<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 225px;"><div class="notification-list" style="overflow: hidden; width: auto; height: 225px;">
						'.$notifichezz.'
			  		</div>
				  	<div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 152.485px; background: rgb(0, 0, 0);"></div>
					<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div>
			  	</div>
				<a href="index.php?op=leggi&ret='.str_replace('.php', '', str_replace('/','',$_SERVER['SCRIPT_NAME'])).'" class="notification-link">Segna come lette</a>
			</div>
		</li>
	</ul>
	<ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown"  data-hover="dropdown" id="usermenu">
				<img src="../imgs/'.$img.'" class="navbar-profile-avatar" alt="">
				<span>'.$nome.'</span>
				<i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="profilo.php">
						<i class="fa fa-user"></i>
						Profilo
					</a>
				</li>
				<li>
					<a href="set_pass.php">
						<i class="fa fa-lock"></i>
						Password
					</a>
				</li>
				<li>
					<a href="index.php?op=logout">
						<i class="fa fa-sign-out"></i>
						Logout
					</a>
				</li>
			</ul>
		</li>
	</ul>';
}
else {
	$menu=
	'<ul class="nav navbar-nav navbar-right">
		<li><a href="login.php">Login/Registrati</a></li>
	</ul>';
}
