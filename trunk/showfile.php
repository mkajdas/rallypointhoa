<?php
require('connect.php');
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


if(strlen($_SESSION['loginid'])>0){
$q = "SELECT * FROM file WHERE id=" . $_GET['id'];
$r = query($q);
while($rr=fetch_array($r)){
	$blobtype = $rr['t'];
	$name = $rr['name'];
	$ext = $rr['ext'];
	$blob = $rr['b'];
	$board = $rr['board'];
	$owner = $rr['owner'];
}
if($rr['owner']==1 && $_SESSION['owner']!=1){
	header("Location:login.php");
	exit;
}
if($rr['board']==1 && $_SESSION['board']!=1){
	header("Location:login.php");
	exit;
}
$name = str_replace("'", "", $name);
$name = str_replace(" ", "", $name);
$name = str_replace("/", "", $name);
$name = str_replace("\\", "", $name);
$name = str_replace(",", "", $name);
$name = str_replace("-", "", $name);
$name = str_replace("\"", "", $name);
$name = str_replace("?", "", $name);
$name = str_replace(":", "", $name);
$name = str_replace(";", "", $name);
$name = str_replace("*", "", $name);
$name = str_replace("<", "", $name);
$name = str_replace(">", "", $name);
$name = str_replace("|", "", $name);
$name = str_replace("\$", "", $name);
$name = strtolower(substr($name,0,7));
header("Content-type: $blobtype"); 
header("Content-Disposition: inline; filename=" . $name . "." . $ext);
echo $blob;
}
?>