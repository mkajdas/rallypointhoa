<?php
require_once("connectnolimit.php");

if($_SESSION['admin']==1){
	require_once("google.php");
	$accountquery = "SELECT numcars AS n, name AS t, currentplan AS c, MONTH(experdate) AS m, YEAR(experdate) AS y, DAYOFMONTH(experdate) AS d FROM rallypoint WHERE id=" . $idofmysql;
	$accountresult = mysql_query($accountquery);
	while($accountrow=mysql_fetch_array($accountresult)){
		$countnumcars = myunescape_data($accountrow['n']);
		$titleofsite = myunescape_data($accountrow['t']);
		$currentplan = myunescape_data($accountrow['c']);
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
}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Your File Size Limit</title>
<link rel="stylesheet" type="text/css" href="css.css" />
<link rel="SHORTCUT ICON" href="favicon.ico" />
</head>
<body><br /><br /><br /><br />
<center>
<span class="logotext">File Size Limit</span><br />

<br />Your file size limit has been hit, please upgrade your account!
<?php
if($_SESSION['admin']==1){
?>
<div class="buynow">
<div class="buynowitemnoborder" >
<div class="buynowitemtitle" <?php if($currentplan==0){?>style="background-color:#FFFFFF"<?php } ?>>Free</div>
<br />5 Units
<br />1 MB of storage
<br />Rally Point Link
</div>

<div class="buynowitem" >
<div class="buynowitemtitle" <?php if($currentplan==1){?>style="background-color:#FFFFFF"<?php } ?>>$15 per Month</div>
<br />50 Units
<br />100 MB of storage
<br />No Branding
<br /><br /><?=createcart($idofmysql, $currentplan, $experdate, 50, (15*6), 1)?>

</div>

<div class="buynowitem" >
<div class="buynowitemtitle" <?php if($currentplan==2){?>style="background-color:#FFFFFF"<?php } ?>>$30 per Month</div>
<br />100 Units
<br />300 MB of storage
<br />No Branding

<br /><br /><?=createcart($idofmysql, $currentplan, $experdate, 100, (30*6), 2)?>
</div>

<div class="buynowitem" >
<div class="buynowitemtitle" <?php if($currentplan==3){?>style="background-color:#FFFFFF"<?php } ?>>$50 per Month</div>
<br />200 Units
<br />800 MB of storage
<br />No Branding

<br /><br /><?=createcart($idofmysql, $currentplan, $experdate, 200, (50*6), 3)?>
</div>

<div class="buynowitem" >
<div class="buynowitemtitle" <?php if($currentplan==4){?>style="background-color:#FFFFFF"<?php } ?>>$100 per Month</div>
<br />500 Units
<br />2,000 MB of storage
<br />No Branding

<br /><br /><?=createcart($idofmysql, $currentplan, $experdate, 500, (100*6), 4)?>
</div>
</div>
<?php
}
?>
</center>
</body>
</html>