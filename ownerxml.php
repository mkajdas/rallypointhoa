<?php
require_once("connect.php");
if(strlen($_SESSION['loginid'])>0){
}else{
	$email = escape_data(strtolower($_COOKIE['email']));
	$pass = escape_data($_COOKIE['pass']);
	if(strlen($email)>0&&strlen($pass)>0){
	$q = "SELECT * FROM user WHERE email='" . $email . "' AND pass='" . $pass ."'";
	$r = query($q);
	if(num_rows($r)>0){
		while($row=fetch_array($r)){
			setcookie('email', $_COOKIE['email'], time()+2592000, '/');
			setcookie('pass', $_COOKIE['pass'], time()+2592000, '/');
			$_SESSION['loginid'] = $row['id'];
			$_SESSION['admin'] = $row['admin'];
			$_SESSION['phone'] = $row['phone'];
			$_SESSION['name'] = $row['name'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['unitid'] = $row['unitid'];
			$_SESSION['board'] = $row['board'];
			$_SESSION['owner'] = $row['owner'];
		}
	}else{
		header("Location:login.php");
		exit;
	}
	}else{
		header("Location:login.php");
		exit;
	}
}
if($_SESSION['admin']==1){
}else{
	header("Location:login.php");
	exit;
}
header("Content-Type: application/xml");
header("Content-Disposition: inline; filename=ownerxml.xml");

function removec($string){
	return trim(str_replace(",", "",$string));
}
echo "<" . "?xml version='1.0' " . "?" . ">\n";
echo "<document>" . "\n";
echo "<row>\n<unit>unit</unit>\n<first>first</first>\n<last>last</last>\n<phone>phone</phone>\n<email>email</email>\n</row>" . "\n";
$q = "SELECT unit.name AS u, user.name AS n, user.phone AS p, user.email AS e FROM unit, user WHERE unit.id=user.unitid AND user.owner=1 ORDER BY user.name ASC";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
	$name = split(" ", unescape_data(trim(xmlreplace($rr['n']))),2);
	echo "<row>\n<unit>". unescape_data(trim(xmlreplace($rr['u']))) . "</unit>\n<first>" . $name[0] . "</first>\n<last>" . $name[1]. "</last>\n<phone>" . unescape_data(trim(xmlreplace($rr['p']))) . "</phone>\n<email>" . unescape_data(trim(xmlreplace($rr['e']))) . "</email>" . "" . "\n</row>\n";
}
echo "</document>";
?>