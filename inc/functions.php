<?php

function checkUser($sononellaindex=0) {
	if (strpos($_SERVER['SCRIPT_NAME'],'admin') !== false && !(isset($_SESSION[adminUser][id]) || isset($_SESSION[trustedUser][id])))
		die("No access.");
}

function getNotifiche() {
	$ret = array();
	$res = sql("select * from notifiche 
					where IDutente='".$_SESSION[User2decide][id]."'
						and letto=0");
	$c = 0;
	while ($n = pg_fetch_array($res)) {
		if(strpos($n[titolo], 'Nuova') !== false) {
			# notifica all'organizzatore
			$icon="<span class=notification-icon><i class=\"fa fa-warning text-warning\"></i></span>";
			$link="admin_giocatori.php?torneo=".$n[nome_torneo]."&ed=".$n[ed_torneo]."&gamers=1&op=read&id2read=".$n[id];
		}
		else if(strpos($n[titolo], 'annullato') !== false) {
			# notifica annullamento torneo
			$icon="<span class=notification-icon><i class=\"fa fa-warning text-danger\"></i></span>";
			$link=$_SERVER[SCRIPT_NAME]."?op=read&id2read=".$n[id];
		}
		else {
			# notifica al giocatore
			$icon="<span class=notification-icon><i class=\"fa fa-check-circle text-success\"></i></span>";
			$link=$_SERVER[SCRIPT_NAME]."?op=read&id2read=".$n[id];
		}
		$ret[0] = "<a href=\"".$link."\" class=notification>
						".$icon."
						<span class=notification-title>".$n[titolo]."</span>
						<span class=notification-description>".$n[descr]."</span>
						<span class=notification-time>".$n[data]."</span>
					</a>";
		$c++;
	}
	return $ret;
}

# Single-SQL: used if we need a single result from a query
function ssql($query) {
	$res = pg_query($_SESSION[conn], $query);
	if(!$res) 
		return false;
	while ($row = pg_fetch_row($res)) {
		return $row[0];
	}
};

function sql($query) {
	$res = pg_query($_SESSION[conn], $query);
	return $res;
}

function multisqlarr($query) {
	$res = pg_query($_SESSION[conn], $query);
	$a = pg_fetch_array($res);
	return $a;
}

################
# BS Functions #
################

# Open a Bootstrap form
function BSformo() {
	echo "<form action=\"\" method=\"POST\" enctype=\"multipart/form-data\" name=\"formo\" id=\"formo\" class=\"form-horizontal\">";
}

# Close a Bootstrap form
function BSformc() {
	echo "</form>";
}

# Create an hidden input for $_GET[op]
function hidden($val, $name="op") {
	echo "<input name=\"$name\" value=\"$val\" type=\"hidden\">";
}

# Create a Bootstrap input row (use inside BSform)
function BSinput($nome, $label, $value) {
	echo "<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
			<div class=\"col-sm-4\"><input id=\"$nome\" name=\"$nome\" value=\"$value\" class=\"form-control\" type=\"text\"></div>
		</div>";
}

function BSpass($nome, $label, $value) {
	echo "<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
			<div class=\"col-sm-4\"><input id=\"$nome\" name=\"$nome\" value=\"$value\" class=\"form-control\" type=\"password\"></div>
		</div>";
}

function BSdate($nome, $label, $value) {
	echo "<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
			<div class=\"col-sm-4\"><input id=\"$nome\" name=\"$nome\" value=\"$value\" class=\"form-control\" type=\"date\"></div>
		</div>";
}

function BStime($nome, $label, $value) {
    echo "<div class=\"form-group\">
            <label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
            <div class=\"col-sm-4\"><input id=\"$nome\" name=\"$nome\" value=\"$value\" class=\"form-control\" type=\"time\"></div>
        </div>";
}

function BSnum($nome, $label, $value=0) {
	echo "<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
			<div class=\"col-sm-4\"><input id=\"$nome\" name=\"$nome\" value=\"$value\" class=\"form-control\" type=\"number\" min=\"0\" default=\"0\"></div>
		</div>";
}

function BSdropdown($nome, $label, $query, $val, $show, $default) {
	$res=sql($query);
	while($r=pg_fetch_array($res)) {
		$options.="<option value=\"".$r[$val]."\">".$r[$show]."</option>";
	}
	echo "<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
			<select class=\"col-sm-4 form-control myformcontrol\" name=\"$nome\" id=\"$nome\" default=\"$default\">
			".$options."
			</select>
		</div>";
}

function BSmulticheck($nome, $label, $query, $val, $show, $default) {
    $res=sql($query);
    while($r=pg_fetch_array($res)) {
        $options.="<div class=\"checkbox\">
                        <label><input type=\"checkbox\" value=".$r[id]." id=\"$nome\" name=\"".$nome."[]\">".$r[name]."</label>
                    </div>";
    }
    echo "<div class=\"form-group\">
            <label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
            <div class=\"col-sm-4\">
            ".$options."
            </div>
        </div>";
}

function BScheck($nome, $label, $default=0) {
    if($default>0)
        $chk = "checked";
    echo "<div class=\"form-group\">
            <label class=\"col-sm-2 control-label\" for=\"$nome\">$label</label>
            <div class=\"col-sm-4\">
                <input type='checkbox' value='1' id=\"$nome\" name=\"$nome\" $chk>
            </div>
        </div>";
}

function BSimg($nome, $label, $default) {
	if($default=="")
		$default="default.jpg";
	echo "
	<div class=\"form-group\">
		<label class=\"col-sm-2 control-label\">$label</label>
		<div class=\"col-sm-4\">
			<div class=\"fileinput fileinput-exists\" data-provides=\"fileinput\">
				<div class=\"fileinput-preview thumbnail\" style=\"width: 125px; height: 125px;\">
					<img src=\"../imgs/$default\" alt=\"$label\">
				</div>
			<div>
			<span>Seleziona un'altra immagine...</span>
			<input type=\"file\" class=\"form-control\" id=\"$nome\" name=\"$nome\" />
		</div>
	</div>
	</div>
	</div>";
}

# Create a Bootstrap submit button (use inside BSform)
function BSbutton($label) {
	echo "<div class=\"form-group\">
			<div class=\"col-sm-offset-2 col-sm-4\">
				<button class=\"btn btn-primary btn-large\" type=\"submit\" id=\"tbutton\">$label</button>
			</div>
		</div>";
}

# Open a Bootstrap form
function BSformomod($page) {
	echo '
	<div id="edithere" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="document.location.href=\''.$page.'\';">×</button>
						<h3 class="modal-title" id="modal-title"></h3>
				</div>
			<div class="modal-body" id="modal-body">
				<form action="'.$page.'" method="POST" enctype="multipart/form-data" name="formomod" id="formomod" class="form-horizontal">
	';
}

# Close a Bootstrap form
function BSformcmod($page="#") {
	echo '
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="document.location.href=\''.$page.'\';">Close</button>
				</div>
			</div>
		</div>
	</div>
	';
}

function BSheader($title) {
	echo '<div class="portlet-header">
			<h3 class="portlet-title sublined"><u>'.$title.'</u></h3>
		</div>';
}

function BSspacer() {
	echo "<hr class=\"spacer-sm\">";
}

function BSclearfix() {
	echo "<div class=\"clearfix visible-md visible-lg\"></div>";
}

function br($num=1) {
	for($i=0; $i<$num; $i++) {
		echo "<br>";
	}
}
?>