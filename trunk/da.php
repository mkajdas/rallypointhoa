<?php
error_reporting(0);
set_magic_quotes_runtime(0);

$subdomaina = explode(".", $_SERVER['HTTP_HOST']);
$subdomain = $subdomaina[0];

if(strtolower($subdomain)=="www" OR strtolower($subdomain)=="rallypointhq"){
	header("Location:http://rallypoint.wbpsystems.com/");
	exit;
}

define('DB_USER', 'db3732');
define('DB_PASSWORD', 'omega4del');
define('DB_HOST', 'internal-db.s3732.gridserver.com');
define('DB_NAME', 'db3732_wbp');
$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
mysql_select_db(DB_NAME);

function myescape_data($data){
    global $dbc;
    if (ini_get('magic_quotes_gpc')){
	$data = strtolower(stripslashes($data));
    }
    return mysql_escape_string ($data);
}

function myunescape_data($data){
    if (!ini_get('magic_quotes_gpc')){
	$data = stripslashes($data);
    }
    return $data;
}

$querymy = "SELECT subdomain AS s, id AS idd FROM rallypoint WHERE subdomain='" . myescape_data($subdomain) . "'";
$resultmy = mysql_query($querymy);
$found = false;
while($rowmy=mysql_fetch_array($resultmy)){
	$nameofdb = strtolower(myunescape_data($rowmy['s']));
	$found = true;
	$idofmysql = myunescape_data($rowmy['idd']);
}
if($found){
}else{
	header("Location:http://rallypoint.wbpsystems.com/");
	exit;
}
session_name($nameofdb);
session_start();
$cfilesize = filesize('../../rpstore/' . $nameofdb .'.db');
if($_SESSION['admin']==1 && $_POST['del']=="one"){
	$q = "DELETE FROM rallypoint WHERE id=" . myescape_data($idofmysql);
	$r = mysql_query($q);
	$s = unlink('../../rpstore/' . $nameofdb .'.db');
	header("Location:http://rallypoint.wbpsystems.com/");
	exit;
}else{
	header("Location:cars.php");
	exit;
}
?>