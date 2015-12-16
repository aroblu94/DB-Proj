<?php

error_reporting(E_ALL ^ (E_NOTICE  | E_DEPRECATED));
ini_set("display_errors", 1); 

session_start();

$_SESSION[debug]=0;

$dbhost="127.10.181.130";

$db="aronne";

$user="aronne";
$pass="rebecca";
$port=5432;

$debug=0;

$conn = pg_connect("host=$dbhost port=$port dbname=$db user=$user password=$pass") or die("Sito in temporanea manutenzione. Ci scusiamo per il disagio");

$_SESSION[conn] = $conn;

require "functions.php";
?>
