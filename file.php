<?php
require_once("connect.php");
$titleext = " - files";

$afile = true;
$showrssfile = true;
require_once("top.php");

$fileext = array();
$fileext['xls'] = "xls.gif";
$fileext['pdf'] = "pdf.gif";
$fileext['doc'] = "doc.gif";
$fileext['jpg'] = "jpg.gif";
$fileext['gif'] = "gif.gif";
$fileext['zip'] = "zip.gif";
$fileext['tif'] = "tif.gif";
$fileext['tiff'] = "tif.gif";
$fileext['psd'] = "psd.gif";

if(strlen($_GET['delid'])>0 && $_SESSION['admin']==1){
	$q = "DELETE FROM file WHERE id=" . escape_data($_GET['delid']);
	$r = query($q);
}

if(strlen($_POST['who'])>0 && $_SESSION['admin']==1){
	
	$extension = explode('.', $_FILES['upload']['name']);
	$name = escape_data($extension[0]);
	$ext = strtolower(escape_data($extension[1]));
	$uploadfilesize = intval($_FILES['upload']['size']);
	
	$t = escape_data($_FILES['upload']['type']);
	$filehandle = fopen($_FILES['upload']['tmp_name'], "r");
	$b = fread($filehandle, $_FILES['upload']['size']);
	$b = escape_data($b);
	
	$query = "";
	$dt = time();
	if($_POST['who']=="everyone"){
		$query = "INSERT INTO file(name, ext, t, b, dt) VALUES('{$name}','{$ext}','{$t}','{$b}',{$dt})";
	}elseif($_POST['who']=="board"){
		$query = "INSERT INTO file(name, ext, t, b, board, dt) VALUES('{$name}','{$ext}','{$t}','{$b}',1,{$dt})";
	}elseif($_POST['who']=="owners"){
		$query = "INSERT INTO file(name, ext, t, b, owner, dt) VALUES('{$name}','{$ext}','{$t}','{$b}',1,{$dt})";
	}
	
	if(strlen($query)<(1024*1024)){
		$result = query($query);
	}else{
		$note = "I'm sorry you can't upload a file that large (you can upload any file less than 1 MB)";
	}
}

if($_SESSION['board']==1){
	$board = "";
}else{
	$board = " AND (board !=1 OR board IS NULL)";
}

if($_SESSION['owner']==1){
	$owner = "";
}else{
	$owner = " AND (owner !=1 OR owner IS NULL)";
}


$q = "SELECT name, ext, board, t, owner, id FROM file WHERE id=id" . $board . $owner ." ORDER BY name ASC";
$r = query($q);
?>
<div class="title" name="title" id="title" >
Files
</div>
<?php
if(strlen($note)>0){
?>
<br /><div class="error"><?=$note?></div>
<?php
}
?>
<?php
while($rr=fetch_array($r)){
?>
<br /><div class="<?php if($rr['board']==1){?>board<?php }elseif($rr['owner']==1){ ?>owner<?php }else{ ?>file<?php } ?>">
<div class="fileicon">
<?php
$ext =strtolower($rr['ext']);
if(isset($fileext[$ext])){
?>
<a href="showfile.php?id=<?=$rr['id']?>" target=_blank><img src="<?=$fileext[$ext]?>" border="0" width="32" height="32" class="icon" /></a>
<?php
}else{
?>
<a href="showfile.php?id=<?=$rr['id']?>" target=_blank><img src="file.gif" border="0" width="32" height="32" class="icon" /></a>	
<?php
}
?>
</div>
<div class="filetext">
<?php if($rr['board']==1){ ?><?php if($_SESSION['admin']==1){?><div class="noprint"><div class="boardbug"><a href="file.php?delid=<?=$rr['id']?>" class="dellink">DELETE</a></div></div><?php } ?><div class="boardbug">BOARD</div><?php } ?><?php if($rr['owner']==1){ ?><?php if($_SESSION['admin']==1){?><div class="noprint"><div class="ownerbug"><a href="file.php?delid=<?=$rr['id']?>" class="dellink">DELETE</a></div></div><?php } ?><div class="ownerbug">OWNERS</div><?php } ?><? if($rr['board']!=1 && $rr['owner']!=1){ ?><?php if($_SESSION['admin']==1){?><div class="noprint"><div class="everyonebug"><a href="file.php?delid=<?=$rr['id']?>" class="dellink">DELETE</a></div></div><?php } ?><div class="everyonebug">EVERYONE</div><?php } ?>
<a href="showfile.php?id=<?=$rr['id']?>" class="filelinktext" target=_blank><?=unescape_data($rr['name'])?></a> </div>
</div>
<?php
}
if($_SESSION['admin']==1){
?>
<div class="noprint">
<br /><div class="addfile">
<form action="file.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="9999999" />
file: <input name="upload" id="upload" type="file" /> who has access: <select name="who" id="who">
<option value="everyone">everyone</option>
<option value="board">board</option>
<option value="owners">owners</option>
</select>
<br /><input type="submit" value="Upload File" />
</form>
</div></div>
<?php
}
require_once("bot.php");
?>