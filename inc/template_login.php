<?php
header("Pragma: no-cache");
header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the pastheader("Pragma: no-cache");

checkUser($sononellaindex);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Aronne Brivio">>
  <link rel="shortcut icon" type="image/x-icon" href="../inc/img/favicon.ico"/>
  <title>Login</title>
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

	<?php echo $ADDcssFile; ?>
	  
	<style>
		<?php echo $ADDcss; ?>
	</style>
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
		</div>
	  </header>

	  <hr class="spacer-sm">

	  <?php
		if($_SESSION[notify][type]=="err"){
			echo "<div class='alert alert-error ".$_SESSION[notify][cl]."'><button type='button' class='close' data-dismiss='alert'>×</button>
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
		unset($_SESSION[notify]);
		$_SESSION[notify][cl]="";
		$_SESSION[notify][login]="";
	  ?>

	  <div class="account-wrapper">

		<div class="account-body">

		  <h3>Benvenuto su <?php echo $host ?></h3>

		  <h5 id="h5">Per accedere immetti le tue credenziali</h5>

		  <form class="form account-form" method="POST" action="./login.php">

			<div class="form-group hidden" id="name_form">
				<label for="signup-fullname" class="placeholder-hidden">Nome</label>
				<input type="text" class="form-control" id="<?php echo $camponome ?>" name="<?php echo $camponome ?>" placeholder="Nome" tabindex="1">
			</div>

			<div class="form-group hidden" id="surname_form">
				<label for="signup-fullname" class="placeholder-hidden">Cognome</label>
				<input type="text" class="form-control" id="<?php echo $campocognome ?>" name="<?php echo $campocognome ?>" placeholder="Cognome" tabindex="2">
			</div>
			<div class="form-group">
			  <label for="login-username" class="placeholder-hidden">Email</label>
			  <input type="text" class="form-control" id="<?php echo $campouser ?>" name="<?php echo $campouser ?>" placeholder="Username" tabindex="3">
			</div>

			<div class="form-group">
			  <label for="login-password" class="placeholder-hidden">Password</label>
			  <input type="password" class="form-control" id="<?php echo $campopass ?>" name="<?php echo $campopass ?>" placeholder="Password" tabindex="4">
			</div>

			<div class="form-group">
			  <button type="submit" class="btn btn-primary btn-block btn-lg" id="submit" tabindex="6">
				Accedi &nbsp; <i class="fa fa-play-circle"></i>
			  </button>
			</div>

			<input type="hidden" id="register" name="register" value="0">
			<?php hidden("logreg") ?>

		  </form>
		</div>
		<div class="account-footer"  id="foot_reg">
		  <p>Non hai un account? <a href="#" id="reg_link">Registrati ora!</a></p>
		</div>
		<div class="account-footer hidden" id="foot_log">
		  <p>Hai già un account? <a href="#" id="log_link">Accedi</a></p>
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

	<script src="../inc/js/custom_drop.js"></script>

	<?php echo $ADDjs; ?>

	<script>
		$(document).ready(function() { 
			$("#reg_link").on("click", function() {
				$("#name_form").removeClass("hidden");
				$("#surname_form").removeClass("hidden");
				$("#foot_reg").addClass("hidden");
				$("#foot_log").removeClass("hidden");
				$("#register").val(1);
				$("#submit").removeClass("btn-primary");
				$("#submit").addClass("btn-secondary");
				$("#submit").empty();
				$("#submit").append("Registrati &nbsp; <i class=\"fa fa-play-circle\"></i>");
			});
			$("#log_link").on("click", function() {
				$("#name_form").addClass("hidden");
				$("#surname_form").addClass("hidden");
				$("#foot_log").addClass("hidden");
				$("#foot_reg").removeClass("hidden");
				$("#register").val(0);
				$("#submit").removeClass("btn-secondary");
				$("#submit").addClass("btn-primary");
				$("#submit").empty();
				$("#submit").append("Accedi &nbsp; <i class=\"fa fa-play-circle\"></i>");
			});
			<?php echo $jq2footer; ?>
		})
	</script>
</body>
</html>
