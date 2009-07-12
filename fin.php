<?php
require_once("connect.php");
$showrssfin = true;

$titleext = " - finances for your unit";

$afin = true;
if(is_numeric($_SESSION['unitid']) && $_SESSION['owner']==1){
require_once("top.php");
?>
<div class="title" name="title" id="title" >
Finances for your Unit
</div>
<table class="table" id="table" name="table">
<tr class="top" name="top" id="top"><td>Date</td><td>Description</td><td>Amount</td></tr>
<?php
$fq = "SELECT * FROM financial WHERE unitid=" . $_SESSION['unitid']  . " ORDER BY dt DESC";
$fr = query($fq);
$bal=0;
while($frr=fetch_array($fr)){
$bal=$bal+$frr['amount'];
?>
<tr><td><?=date("jS \of F Y",$frr['dt'])?></td><td><?=unescape_data($frr['description'])?></td><td style="text-align:right;"><b>$<?=number_format($frr['amount'],2)?></b></td></tr>
<?php
}
?>
</table><br />
<div class="title" name="title" id="title" >
Your balance is $<?=number_format($bal,2)?>
</div>
<br />
<?php
}else{
	header("Location:cars.php");
	exit;
}
require_once("bot.php");
?>