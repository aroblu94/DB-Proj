<?php
header("Pragma: no-cache");
header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the pastheader("Pragma: no-cache");

include "menus.php";

checkUser($sononellaindex);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Aronne Brivio">
	<meta name="theme-color" content="#2b3d4c">

	<link rel="icon" type="image/x-icon" href="../inc/img/favicon.ico"/>
	<title>
		<?php echo $tit_pag ?>
	</title>

	<!-- Google Font: Open Sans -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="../deps/mvp-theme/bower_components/fontawesome/css/font-awesome.min.css">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="../deps/mvp-theme/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Plugin CSS -->
	<link rel="stylesheet" href="../deps/mvp-theme/bower_components/select2/select2.css">
	<link rel="stylesheet" href="../deps/mvp-theme/bower_components/jquery-icheck/skins/minimal/_all.css">
	<link rel="stylesheet" href="../deps/mvp-theme/bower_components/bootstrap-3-timepicker/css/bootstrap-timepicker.css">
	<link rel="stylesheet" href="../deps/mvp-theme/bower_components/bootstrap-datepicker/css/datepicker3.css">
	<link rel="stylesheet" href="../deps/mvp-theme/bower_components/bootstrap-jasny/dist/css/jasny-bootstrap.css">
	<!-- App CSS -->
	<link rel="stylesheet" href="../inc/mvpready-admin.css">
	<link rel="stylesheet" href="../inc/custom.css">

	<?php echo $ADDcss; ?>

</head>
<body>
	<div id="wrapper">
		<header class="navbar" role="banner">
			<div class="container">
				<div class="navbar-header">
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<i class="fa fa-cog"></i>
					</button>
					<a href="index.php" class="navbar-brand navbar-brand-img">
						<img src="./inc/img/logo2.png" height="52" style="margin-top: 2px;" alt="Progetto BD">
					</a>
				</div>
				<nav class="collapse navbar-collapse" role="navigation">
					<?php echo $menu; ?>
				</nav>
			</div>
		</header>
		<div class="mainnav">
			<div class="container">
				<a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
					<span class="sr-only">Toggle navigation</span>
					<i class="fa fa-bars"></i>
				</a>
				<nav class="collapse mainnav-collapse" role="navigation">
					<ul class="mainnav-menu">
						<?php echo $usr_menu; ?>
					</ul>
				</nav>
			</div>
		</div>

		<div class="content">
			<!-- Page content of course! -->
			<div class="container">
				<div class="row">
					<?php
						if($_SESSION[notify][type]=="err"){
								echo "<div class='alert alert-danger".$_SESSION[notify][cl]."'><button type='button' class='close' data-dismiss='alert'>×</button>
									".$_SESSION[notify][text]."</div>";
						}
						elseif ($_SESSION[notify][type]=="ok"){
								echo "<div class='alert alert-success ".$_SESSION[notify][cl]."'><button type='button' 
									class='close' data-dismiss='alert'>×</button>".$_SESSION[notify][text]."</div>";
						}
						elseif ($_SESSION[notify][type]=="warn"){
								echo "<div class='alert alert-warning ".$_SESSION[notify][cl]."'><button type='button' 
									class='close' data-dismiss='alert'>×</button>".$_SESSION[notify][text]."</div>";
						}
						elseif ($_SESSION[notify][type]=="info"){
								echo "<div class='alert alert-info ".$_SESSION[notify][cl]."'><button type='button' 
									class='close' data-dismiss='alert'>×</button>".$_SESSION[notify][text]."</div>";
						}
						unset($_SESSION[notify]);
						$_SESSION[notify][cl]="";
						$_SESSION[notify][login]="";

						echo "<div class=\"portlet-header\">
								<h3 id=\"title\" class=\"portlet-title sublined\"><u>$tit_pag $tit_link</u></h3>
							</div>";
						echo $corpoPagina;
					?>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
		<div class="container">
			<p class="pull-left">Copyright © 2015 Aronne Brivio</p>
		</div>
	</footer>
 
	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Core JS -->
	<script src="../deps/mvp-theme/bower_components/jquery/dist/jquery.js"></script>
	<script src="../deps/mvp-theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="../deps/mvp-theme/bower_components/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../deps/mvp-theme/bower_components/select2/select2.min.js"></script>
	<script src="../deps/mvp-theme/bower_components/jquery-icheck/icheck.min.js"></script>
	<script src="../deps/mvp-theme/bower_components/parsleyjs/dist/parsley.js"></script>
	<script src="../deps/mvp-theme/bower_components/bootstrap-3-timepicker/js/bootstrap-timepicker.js"></script>

	<!-- App JS -->
	<script src="../deps/mvp-theme/global/js/mvpready-core.js"></script>
	<script src="../deps/mvp-theme/global/js/mvpready-helpers.js"></script>
	<script src="../deps/mvp-theme/templates/admin/js/mvpready-admin.js"></script>

	<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
	<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>

	<script src="../inc/js/custom_drop.js"></script>
	<script src="../inc/js/alert-webapp-ffos.js"></script>

	<?php echo $ADDjs; ?>

	<script>
		$(document).ready(function() { 
			// type="date" for any browser
			webshims.activeLang('it');
			webshims.setOptions('waitReady', false);
			webshims.setOptions('forms-ext', {types: 'date'});
			webshims.polyfill('forms forms-ext');
			<?php echo $jq2footer; ?>
		})
	</script>
</body>
</html>
