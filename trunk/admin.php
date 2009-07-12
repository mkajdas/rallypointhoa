<?php
require_once("connect.php");
$aadmin = true;


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




if($_GET['databasecp']==1){
$vac = query("VACUUM unit");
$vac = query("VACUUM user");
$vac = query("VACUUM car");
$vac = query("VACUUM faq");
$vac = query("VACUUM cat");
$vac = query("VACUUM file");
$vac = query("VACUUM note");
}
function resize_bytes($size)
{
$count = 0;
$format = array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
while(($size/1024)>1 && $count<8)
{
$size=$size/1024;
$count++;
}
$return = number_format($size,0,'','.')." ".$format[$count];
return $return;
}
if($_SESSION['admin']==1){
if(strlen($_GET['makeadmin'])>0){
	$q = "UPDATE user SET admin=1 WHERE id=" . escape_data($_GET['makeadmin']);
	$r = query($q);
}

if(strlen($_GET['del'])>0){
	$q = "DELETE FROM user WHERE id=" . escape_data($_GET['del']);
	$r = query($q);
	if($r){
		$query = "DELETE FROM car WHERE userid=" . escape_data($_GET['del']);
		$result = query($query);
	}
}


if($_GET['makenormal']==1){
	$q = "UPDATE user SET admin=0 WHERE id=" . escape_data($_SESSION['loginid']);
	$r = query($q);
	header("Location:logout.php");
}
if($_GET['maken']==1){
	$_SESSION['admin']=0;
	header("Location:cars.php");
}

if(strlen($_GET['makenowner'])>0){
	$q = "UPDATE user SET owner=0 WHERE id=" . escape_data($_GET['makenowner']);
	$r = query($q);	
}

if(strlen($_GET['makeowner'])>0){
	$q = "UPDATE user SET owner=1 WHERE id=" . escape_data($_GET['makeowner']);
	$r = query($q);	
}

if(strlen($_GET['makenboard'])>0){
	$q = "UPDATE user SET board=0 WHERE id=" . escape_data($_GET['makenboard']);
	$r = query($q);	
}

if(strlen($_GET['makeboard'])>0){
	$q = "UPDATE user SET board=1 WHERE id=" . escape_data($_GET['makeboard']);
	$r = query($q);	
}

$titleext = " - admin";


require_once("top.php");


?>
<div class="title" name="title" id="title" >
Registered Users
</div>
<br /><?php 
if(strlen($notee)>0){
?>
<br /><div class="error" id="error" name="error"><?=$notee?></div><br />
<?php
}
?>
<table class="table" id="table" name="table">
<tr class="top" name="top" id="top"><td>Name</td><td>Phone</td><td>E-Mail</td><td>Unit</td><td></td></tr>
<?php
$q = "SELECT user.name AS n, user.phone AS p, user.email AS e, unit.name AS un, user.admin AS a, user.id AS idd, user.owner AS o, user.board AS b FROM user, unit WHERE user.unitid=unit.id GROUP BY user.id ORDER BY user.name ASC";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
?>
<tr><td><?=unescape_data($rr['n'])?></td><td><?=unescape_data($rr['p'])?></td><td><a href="mailto:<?=$rr['e']?>"><?=unescape_data($rr['e'])?></a></td><td><?=unescape_data($rr['un'])?></td><td><div class="noprint"><?php if($rr['a']!=1){ ?><a href="admin.php?del=<?=$rr['idd']?>">remove</a> | <a href="admin.php?makeadmin=<?=$rr['idd']?>">make admin user</a><?php } ?><?php if($rr['a']!=1){ ?> | <?php } ?><?php if($rr['o']==1){ ?><a href="admin.php?makenowner=<?=$rr['idd']?>">make a non-owner</a><?php }else{ ?><a href="admin.php?makeowner=<?=$rr['idd']?>">make owner</a><?php } ?> | <?php if($rr['b']==1){ ?><a href="admin.php?makenboard=<?=$rr['idd']?>">make a non-board</a><?php }else{ ?><a href="admin.php?makeboard=<?=$rr['idd']?>">make board</a><?php } ?></div></td></tr>
<?php
}
?>
</table>
<br /><div class="noprint"><a href="csv.php">plate csv</a> (<a href="http://helpdocs.wbpsystems.com/csv.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is csv?</a>) | 
<a href="usercsv.php">user csv</a> (<a href="http://helpdocs.wbpsystems.com/csv.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is csv?</a>) | 
<a href="ownercsv.php">owner csv</a> (<a href="http://helpdocs.wbpsystems.com/csv.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is csv?</a>) | 
<a href="boardcsv.php">board csv</a> (<a href="http://helpdocs.wbpsystems.com/csv.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is csv?</a>) | 
<a href="xml.php">plate xml</a> (<a href="http://helpdocs.wbpsystems.com/xml.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is xml?</a>) | <a href="userxml.php">user xml</a> (<a href="http://helpdocs.wbpsystems.com/xml.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is xml?</a>)
 | <a href="ownerxml.php">owner xml</a> (<a href="http://helpdocs.wbpsystems.com/xml.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is xml?</a>)
 | <a href="boardxml.php">board xml</a> (<a href="http://helpdocs.wbpsystems.com/xml.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is xml?</a>)
</div><br />
<div class="noprint">
<br />
<div class="title" name="title" id="title" >
I no longer wish to be an admin user.
</div>
<br /><a href="admin.php?makenormal=1"><span class="platew">yes</span></a>
<br /><br />
<div class="title" name="title" id="title" >
I want to become a "normal" user until I logout.  I will be an admin again the next time I login.
</div>
<br /><a href="admin.php?maken=1"><span class="platew">yes</span></a>
<br /><br />
<div class="title" name="title" id="title" >
<?php
$cfilesize = filesize('../../rpstore/' . $nameofdb .'.db');
?>
We have used <?=resize_bytes($cfilesize)?> of our <?=resize_bytes($sizelimit)?> limit
</div>
<br /><a href="admin.php?databasecp=1"><span class="platew">run a database cleanup! (this will decrease the size of the database, but will also reduce the speed)</span></a>&nbsp;&nbsp;<a href="account.php"><span class="platew">upgrade our account</span></a>
</div>
<br />

<?php
}else{
	header("Location:cars.php");
	exit;
}
require_once("bot.php");
?>