<?php
require_once("connect.php");
$titleext = " - search";
require_once("top.php");
$search = escape_data($_POST['search']);
$search = str_replace("%", "", $search);
$search = str_replace("_", "", $search);
$searchtwo = str_replace("-", "", $search);
$searchthree = str_replace("-", " ", $search);
$err="";
$q = "SELECT * FROM car WHERE plate LIKE '" . $search . "%' OR plate LIKE '" .$searchtwo ."%' OR plate LIKE '" .$searchthree ."%'";
$r = query($q);
$resultgo = false;
if(num_rows($r)>0){
	if(num_rows($r)>1){
		$err ="This search matched more than one plate, try something a bit more specific";
	}
	$resultgo = true;
	if($_SESSION['admin']==1){
		while($rr=fetch_array($r)){
			$goadmin=1;
			$plate = unescape_data($rr['plate']);
			$query = "SELECT user.name AS n, user.email AS e, unit.name AS u, user.phone AS p FROM user, unit WHERE unit.id=user.unitid AND user.id=" . escape_data($rr['userid']);
			$result = query($query);
		}
	}
}

?>
<div class="title" name="title" id="title" >
Search
</div>
<?php
if(strlen($err)==0){
?>
<br /><div class="result" name="result" id="result">
<?php if($resultgo){ ?><?=$plate?> is registered<?php }else{ ?>This plate is not in our system<?php } ?>
</div>

<br />
<?php
if($goadmin==1){
	while($row=fetch_array($result)){
?>
<div class="error" id="error" name="error">
<b><?=unescape_data($row['n'])?></b>
<br /><a href="mailto:<?=unescape_data($row['e'])?>"><?=unescape_data($row['e'])?></a>
<br /><?=unescape_data($row['p'])?>
<br /><?=unescape_data($row['u'])?>
</div>
<?php
	}
}
}else{
?>
<br /><div class="error" name="error" id="error">
<?=$err?>
</div>
<?php
}
require_once("bot.php");
?>