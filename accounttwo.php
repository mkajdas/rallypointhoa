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
		}
	}
}





if($_SESSION['admin']==1){
require_once("google.php");

if(strlen($_POST['title'])>0){
	$name = myescape_data($_POST['title']);
	$numcars = myescape_data($_POST['cars']);
	$reglimit = myescape_data($_POST['reglimit']);
	$q ="UPDATE rallypoint SET name='" . $name ."', numcars=" . $numcars . ", reglimit=" . $reglimit . " WHERE id=" . $idofmysql;
	$r = mysql_query($q);
	$notee = "It may take as long as two minutes to update this info in our system";
}



$titleext = " - account";

$aaccount = true;

require_once("top.php");

$accountquery = "SELECT numcars AS n, name AS t, currentplan AS c, MONTH(experdate) AS m, YEAR(experdate) AS y, DAYOFMONTH(experdate) AS d, reglimit AS rl FROM rallypoint WHERE id=" . $idofmysql;
$accountresult = mysql_query($accountquery);
while($accountrow=mysql_fetch_array($accountresult)){
	$countnumcars = myunescape_data($accountrow['n']);
	$titleofsite = myunescape_data($accountrow['t']);
	$currentplan = myunescape_data($accountrow['c']);
	$reglimit = myunescape_data($accountrow['rl']);
	if($currentplan>0){
		$cmonth = myunescape_data($accountrow['m']);
		$cyear = myunescape_data($accountrow['y']);
		$cday = myunescape_data($accountrow['d']);
		$experdate = mktime(0,0,0,$cmonth,$cday,$cyear);
		$expdateprint = "Your plan expires on " . $cmonth . "-" . $cday . "-" . $cyear;
	}else{
		$experdate ="";
		$expdateprint ="";
	}
}
?>
<div class="title" name="title" id="title" >
Account Settings:
</div>
<br /><?php 
if(strlen($notee)>0){
?>
<br /><div class="error" id="error" name="error"><?=$notee?></div><br />
<?php
}
?>
<form action="account.php" method="post">
<br />title: <input name="title" id="title" value="<?=$titleofsite?>" />
<br />maximum number of cars per unit: <select name="cars" id="cars">
<?php
$num=0;
while($num < 21){
?>
<option value="<?=$num?>" <?php if($countnumcars==$num){ ?>SELECTED<?php } ?> ><?=$num?></option>
<?php
	$num=$num+1;
}
?>
</select>
<br />maximum number of registrations per unit: <select name="reglimit" id="reglimit">
<?php
$num=1;
while($num < 21){
?>
<option value="<?=$num?>" <?php if($reglimit==$num){ ?>SELECTED<?php } ?> ><?=$num?></option>
<?php
	$num=$num+1;
}
?>
</select>
<br /><input type="submit" value="edit" />
</form>
<br />
<br />
<div class="title" name="title" id="title" >
<?=$expdateprint?>
</div>
<br />
<script type="text/javascript">
<!--
function confirmdel(){
	var answer = confirm("Are you sure you wish to delete this Rally Point, once you delete you can never retrieve your data and this subdomain will be placed back in the available pool.");
	if(answer){
		return true;
	}else{
		return false;
	}
}
//-->
</script>
<div class="title" name="title" id="title" >
Let's delete this Rally Point:
<form action="da.php" method="post">
<input type="hidden" name="del" id="del" value="one" />
<input type="submit" name="letsdelete" id="letsdelete" onclick="return confirmdel();" value="delete" />
</form>
</div>

<?php
}else{
	header("Location:cars.php");
	exit;
}
require_once("bot.php");
?>