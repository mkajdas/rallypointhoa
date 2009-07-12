<?php


// Put your MySQL connection info in the four defines bellow.
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_HOST', '');
define('DB_NAME', '');

// Stop Editing
$subdomaina = explode(".", $_SERVER['HTTP_HOST']);
$subdomain = $subdomaina[0];


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
$querymy = "SELECT name AS n, subdomain AS s, filesize AS f, units AS u, numcars AS c, id AS idd, currentplan AS cp, MONTH(experdate) AS m, YEAR(experdate) AS y, DAYOFMONTH(experdate) AS d, reglimit AS rl FROM rallypoint WHERE subdomain='" . myescape_data($subdomain) . "'";
$resultmy = mysql_query($querymy);
$found = false;
while($rowmy=mysql_fetch_array($resultmy)){
	$titleofsite = myunescape_data($rowmy['n']);
	$nameofdb = strtolower(myunescape_data($rowmy['s']));
	$sizelimit = myunescape_data($rowmy['f']);
	$unitlimit = myunescape_data($rowmy['u']);
	$found = true;
	$countnumcars = myunescape_data($rowmy['c']);
	$idofmysql = myunescape_data($rowmy['idd']);
	$currentplan = myunescape_data($rowmy['cp']);
	$numregsperunitlimit = myunescape_data($rowmy['rl'])-1; 
	$experdate ="";
	$expdateprint ="";
}
if($found){
}else{
	header("Location:http://rallypoint.wbpsystems.com/");
	exit;
}


?>