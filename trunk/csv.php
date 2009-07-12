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
header("Content-Type: text/csv");
header("Content-Disposition: inline; filename=platecsv.csv");

function removec($string){
	return trim(str_replace(",", "",$string));
}
echo "unit, first, last, phone, email, plate" . "\n";
$q = "SELECT unit.name AS u, user.name AS n, user.phone AS p, user.email AS e, car.plate AS pl, car.id AS idd FROM unit, user, car WHERE car.userid=user.id AND unit.id=user.unitid GROUP BY car.id ORDER BY unit.name ASC";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
	$name = split(" ", unescape_data(removec($rr['n'])),2);
	echo unescape_data(removec($rr['u'])) . "," . $name[0] . "," . $name[1] . "," . unescape_data(removec($rr['p'])) . "," . unescape_data(removec($rr['e'])) . "," . unescape_data(removec($rr['pl'])) . "\n";
}
?>