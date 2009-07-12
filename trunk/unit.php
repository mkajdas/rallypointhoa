<?php
require_once("connect.php");
$aunit = true;
$titleext = " - units";

if($_SESSION['admin']==1){


if(strlen($_GET['delid'])>0){
	$q = "DELETE FROM unit WHERE id=" . escape_data($_GET['delid']);
	$r = query($q);
	if($r){
		$query = "SELECT * FROM user WHERE unitid=" . escape_data($_GET['delid']);
		$result = query($query);
		while($rr=fetch_array($result)){
			$delq = query("DELETE FROM car WHERE userid=" . escape_data($rr['id']));
		}
		$query = "DELETE FROM user WHERE unitid=" . escape_data($_GET['delid']);
		$result = query($query);
		$query = "DELETE FROM financial WHERE unitid=" . escape_data($_GET['delid']);
		$result = query($query);
	}
}

if(strlen($_POST['editid'])>0){
	$query = "UPDATE unit SET name='" . escape_data($_POST['name']) ."' WHERE id=" . escape_data($_POST['editid']);
	$result = query($query);
}

if(strlen($_POST['nameadd'])>0){
	$q = "SELECT COUNT(id) AS c FROM unit";
	$r = query($q);
	while($rr=fetch_array($r)){
		$count = $rr['c'];
	}
	if($count < $unitlimit){
		$query = "INSERT INTO unit(name) VALUES('" . escape_data($_POST['nameadd']) . "')";
		$result = query($query);
	}else{
		$notee = "I'm sorry but you have used all of the units available to your account.  Click here to <a href=\"account.php\">upgrade your account</a>.";
	}
}


if(strlen($_POST['billamount'])>0 && is_numeric($_POST['billamount'])){
	$amount = $_POST['billamount'];
	$des = escape_data($_POST['descriptionadd']);
	$dt = time();
	$userid = escape_data($_SESSION['loginid']);
	$q = "SELECT id FROM unit";
	$r = query($q);
	while($rr=fetch_array($r)){
		$unitid=$rr['id'];
		$query="INSERT INTO financial(unitid, amount, description, dt) VALUES({$unitid},{$amount},'{$des}',{$dt})";
		$result = query($query);
	}
}
if(strlen($_POST['billamounttwo'])>0 && is_numeric($_POST['billamounttwo']) && is_numeric($_POST['billunitid']) && $_POST['billunitid']>0){
	$amount = $_POST['billamounttwo'];
	$des = escape_data($_POST['descriptionadd']);
	$dt = time();
	$userid = escape_data($_SESSION['loginid']);
	$unitid=$_POST['billunitid'];
	$query="INSERT INTO financial(unitid, amount, description, dt) VALUES({$unitid},{$amount},'{$des}',{$dt})";
	$result = query($query);
}
if(strlen($_POST['billamountthree'])>0 && is_numeric($_POST['billamountthree']) && is_numeric($_POST['billunitid']) && $_POST['billunitid']>0){
	$amount = ($_POST['billamountthree'])*(-1);
	$des = escape_data($_POST['descriptionadd']);
	$dt = time();
	$userid = escape_data($_SESSION['loginid']);
	$unitid=$_POST['billunitid'];
	$query="INSERT INTO financial(unitid, amount, description, dt) VALUES({$unitid},{$amount},'{$des}',{$dt})";
	$result = query($query);
}
if(strlen($_FILES['uploadcsv']['type'])>0){
	$row=1;
	$csvhandle = fopen($_FILES['uploadcsv']['tmp_name'], "r");
	while(($csvdata=fgetcsv($csvhandle)) !== FALSE){
		$num = count($csvdata);
		if($num>0){
			$unitname = str_replace("%", "", escape_data(trim($csvdata[0])));
		}else{
			$unitname = "";
		}
		if($num>1){
			$des = escape_data(trim($csvdata[1]));
		}else{
			$des = "";
		}
		if($num>2){
			$amount = escape_data(trim($csvdata[2]));
		}else{
			$amount = "";
		}
		if(is_numeric($amount)){
			$dt = time();
			$query = "SELECT * FROM unit WHERE name LIKE '" . escape_data($unitname) . "'";
			$result = query($query);
			while($row=fetch_array($result)){
				$unitid=$row['id'];
				$q="INSERT INTO financial(unitid, amount, description, dt) VALUES({$unitid},{$amount},'{$des}',{$dt})";
				$r = query($q);
			}
		}
		$row++;
	}
}

require_once("top.php");


?>
<div class="title" name="title" id="title" >
Units
</div>
<br /><?php 
if(strlen($notee)>0){
?>
<br /><div class="error" id="error" name="error"><?=$notee?></div><br />
<?php
}
?>
<table class="table" id="table" name="table">
<tr class="top" name="top" id="top"><td>Name</td><td>Balance</td><td></td></tr>
<?php
$q = "SELECT unit.id AS id, unit.name AS name, SUM(financial.amount) AS bal FROM unit LEFT JOIN financial ON unit.id=financial.unitid GROUP BY unit.id ORDER BY unit.name ASC";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
	$count = $count +1;
?>
<tr><td><?=unescape_data($rr['name'])?></td><td>$<?=number_format($rr['bal'],2);?></td><td><div class="noprint"><a href="unit.php?delid=<?=$rr['id']?>">remove (will remove all users and cars in this unit as well)</a><div name="editon<?=$rr['id']?>" id="editon<?=$rr['id']?>" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'none'); changeDiv('editoff<?=$rr['id']?>', 'block')">edit</a></div><div name="editoff<?=$rr['id']?>" id="editoff<?=$rr['id']?>" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'block'); changeDiv('editoff<?=$rr['id']?>', 'none')">close edit</a></div>
<div name="fediton<?=$rr['id']?>" id="fediton<?=$rr['id']?>" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('fe<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('fediton<?=$rr['id']?>', 'none'); changeDiv('feditoff<?=$rr['id']?>', 'block')">show account</a></div><div name="feditoff<?=$rr['id']?>" id="feditoff<?=$rr['id']?>" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('fe<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('fediton<?=$rr['id']?>', 'block'); changeDiv('feditoff<?=$rr['id']?>', 'none')">close show account</a></div>

</div></td></tr>
<tr><td colspan="3"><div class="noprint">
<div id="e<?=$rr['id']?>" style="display:none"><div name="ec<?=$rr['id']?>" id="ec<?=$rr['id']?>" class="dropdown">
<form action="unit.php" method="post">
<input type="hidden" name="editid" id="editid" value="<?=$rr['id']?>" />name: <input type="text" name="name" id="name" value="<?=unescape_data($rr['name'])?>" /><input type="submit" value="edit" />
</form>
</div>
</div>
</div>

<div id="fe<?=$rr['id']?>" style="display:none"><div name="fec<?=$rr['id']?>" id="fec<?=$rr['id']?>" class="dropdown">
<table class="table" id="table" name="table">
<tr class="top" name="top" id="top"><td>Date</td><td>Description</td><td>Amount</td></tr>
<?php
$fq = "SELECT * FROM financial WHERE unitid=" . $rr['id'] . " ORDER BY dt DESC";
$fr = query($fq);
while($frr=fetch_array($fr)){
?>
<tr><td><?=date("jS \of F Y",$frr['dt'])?></td><td><?=unescape_data($frr['description'])?></td><td style="text-align:right;"><b>$<?=number_format($frr['amount'],2)?></b></td></tr>
<?php
}
?>
</table>
</div>
</div>


</td></tr>
<?php
}
?>
</table>
<?php if($unitlimit > $count){ ?>
<br /><div class="noprint"><div name="uniton" id="uniton" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('unit','slide',{duration:0.5}); changeDiv('uniton', 'none'); changeDiv('unitoff', 'block')">add</a></div><div name="unitoff" id="unitoff" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('unit','slide',{duration:0.5}); changeDiv('uniton', 'block'); changeDiv('unitoff', 'none')">close add</a></div></div>
<div class="noprint">
<div id="unit" style="display:none"><div name="unitin" id="unitin" class="dropdown">
<form action="unit.php" method="post">
name: <input type="text" name="nameadd" id="nameadd" value="<?=unescape_data($rr['name'])?>" /><input type="submit" value="add" />
</form>
</div></div>
</div>
<br />
<?php }else{
		$notee = "I'm sorry but you have used all of the units available to your account.  Click here to <a href=\"account.php\">upgrade your account</a>.";

?>
<br /><div class="error" id="error" name="error"><?=$notee?></div><br />
<?php
}
?>
<div class="title" name="title" id="title" >
Financial
</div>
<br /><div class="noprint"><div name="financialon" id="financialon" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('financial','slide',{duration:0.5}); changeDiv('financialon', 'none'); changeDiv('financialoff', 'block')">bill all units</a></div><div name="financialoff" id="financialoff" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('financial','slide',{duration:0.5}); changeDiv('financialon', 'block'); changeDiv('financialoff', 'none')">close bill all units</a></div></div>
<div class="noprint">
<div id="financial" style="display:none"><div name="financialin" id="financialin" class="dropdown">
<form action="unit.php" method="post">
description: <input type="text" name="descriptionadd" id="descriptionadd"  /> amount: $<input type="text" name="billamount" id="billamount" size="5" /><br />
<input type="submit" value="bill all units" />
</form>
</div></div>
</div>
<br /><div class="noprint"><div name="financialtwoon" id="financialtwoon" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('financialtwo','slide',{duration:0.5}); changeDiv('financialtwoon', 'none'); changeDiv('financialtwooff', 'block')">bill a unit</a></div><div name="financialtwooff" id="financialtwooff" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('financialtwo','slide',{duration:0.5}); changeDiv('financialtwoon', 'block'); changeDiv('financialtwooff', 'none')">close bill a unit</a></div></div>
<div class="noprint">
<div id="financialtwo" style="display:none"><div name="financialtwoin" id="financialtwoin" class="dropdown">
<form action="unit.php" method="post">
<select name="billunitid" id="billunitid">
<?php
$query = "SELECT * FROM unit ORDER BY name";
$result = query($query);
while($row=fetch_array($result)){
?>
<option value="<?=$row['id']?>"><?=unescape_data($row['name'])?></option>
<?php
}
?>
</select>
description: <input type="text" name="descriptionadd" id="descriptionadd"  /> amount: $<input type="text" name="billamounttwo" id="billamounttwo" size="5" /><br />
<input type="submit" value="bill this unit" />
</form>
</div></div>
</div>
<br /><div class="noprint"><div name="financialthreeon" id="financialthreeon" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('financialthree','slide',{duration:0.5}); changeDiv('financialthreeon', 'none'); changeDiv('financialthreeoff', 'block')">receive payment from this unit</a></div><div name="financialthreeoff" id="financialthreeoff" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('financialthree','slide',{duration:0.5}); changeDiv('financialthreeon', 'block'); changeDiv('financialthreeoff', 'none')">close receive payment from this unit</a></div></div>
<div class="noprint">
<div id="financialthree" style="display:none"><div name="financialthreein" id="financialthreein" class="dropdown">
<form action="unit.php" method="post">
<select name="billunitid" id="billunitid">
<?php
$query = "SELECT * FROM unit ORDER BY name";
$result = query($query);
while($row=fetch_array($result)){
?>
<option value="<?=$row['id']?>"><?=unescape_data($row['name'])?></option>
<?php
}
?>
</select>
description: <input type="text" name="descriptionadd" id="descriptionadd"  /> amount: $<input type="text" name="billamountthree" id="billamountamount" size="5" /><br />
<input type="submit" value="receive payment from this unit" />
</form>
</div></div>
</div>
<br /><div class="noprint"><div name="financialfouron" id="financialfouron" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('financialfour','slide',{duration:0.5}); changeDiv('financialfouron', 'none'); changeDiv('financialfouroff', 'block')">import data from csv</a></div><div name="financialfouroff" id="financialfouroff" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('financialfour','slide',{duration:0.5}); changeDiv('financialfouron', 'block'); changeDiv('financialfouroff', 'none')">close import data from csv</a></div></div>
<div class="noprint">
<div id="financialfour" style="display:none"><div name="financialfourin" id="financialfourin" class="dropdown">
<form action="unit.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="9999999" />
file: <input name="uploadcsv" id="uploadcsv" type="file" />
<br /><input type="submit" value="Import data from csv" />
<br /><i>note: this file must be in the following format:</i><br />unit name, description, amount<br />unit name, description, amount<br />...
</form>
</div></div>
</div>
<br /><div class="noprint"><a href="trancsv.php" target=_blank>export data to csv</a></div>
<br /><div class="noprint"><a href="unitcsv.php" target=_blank>export balances to csv</a></div>
<?php
}else{
	header("Location:cars.php");
	exit;
}
require_once("bot.php");
?>