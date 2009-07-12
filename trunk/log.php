<?php
require_once("connect.php");
$alog = true;
$showrsslog = true;

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

	if(strlen($_GET['dellognoteid'])>0 && is_numeric($_GET['dellognoteid'])){
		$q = "DELETE FROM log WHERE id=" . $_GET['dellognoteid'];
		$r = query($q);
		$q = "DELETE FROM logcomment WHERE noteid=" . $_GET['dellognoteid'];
		$r = query($q);
	}
	
	if(strlen($_GET['dellognotecommentid'])>0 && is_numeric($_GET['dellognotecommentid'])){
		$q = "DELETE FROM logcomment WHERE id=" . $_GET['dellognotecommentid'];
		$r = query($q);
	}

	

	if(strlen($_POST['contentlog'])>0){
		$dt = time();
		$userid = escape_data($_SESSION['loginid']);
		$note = escape_data($_POST['contentlog']);
		$q = "INSERT INTO log(note,dt,userid) VALUES('{$note}',{$dt},{$userid})";
		$r = query($q);
	}
	if(strlen($_POST['commentlog'])>0 && is_numeric($_POST['logid'])){
		$dt = time();
		$userid = escape_data($_SESSION['loginid']);
		$note = escape_data($_POST['commentlog']);
		$logid=$_POST['logid'];
		$q = "INSERT INTO logcomment(note,dt,userid,noteid) VALUES('{$note}',{$dt},{$userid},{$logid})";
		$r = query($q);
		
	}

$titleext = " - log";


require_once("top.php");


?>
<div class="title" name="title" id="title" >
Log
</div>
<br /><?php 
if(strlen($notee)>0){
?>
<br /><div class="error" id="error" name="error"><?=$notee?></div><br />
<?php
}
$q = "SELECT log.note AS n, log.dt AS d, user.name AS name, log.id AS id FROM log LEFT JOIN user ON log.userid=user.id ORDER BY dt DESC";
$r = query($q);
while($row=fetch_array($r)){
	$query = "SELECT logcomment.note AS n, logcomment.dt AS d, user.name AS name, logcomment.id AS id FROM logcomment LEFT JOIN user ON logcomment.userid=user.id WHERE logcomment.noteid=" . escape_data($row['id']). " ORDER BY dt DESC";
	$result = query($query);
?>
<br /><div class="owner"><div class="noprint"><div class="ownerbug"><a href="log.php?dellognoteid=<?=$row['id']?>" class="dellink">DELETE</a></div><div class="ownerbug"><a href="javascript:myVoid();" onclick="Effect.toggle('newcomment<?=$row['id']?>a','slide',{duration:0.5})" class="dellink">ADD COMMENT</a></div>
<?php
if(num_rows($result)>0){
?>
<div class="ownerbug"><a href="javascript:myVoid();"  onclick="Effect.toggle('comments<?=$row['id']?>a','slide',{duration:0.5})" class="dellink">SHOW COMMENTS (<?=num_rows($result)?>)</a></div>
<?php
}
?>
<div class="ownerbug"><a href="rsslogc.php?id=<?=$row['id']?>" class="dellink">RSS</a></div>
</div>
<div class="ownernotetext"><?=createHTML($row['n'])?>
<br /><span class="makesmall"><?=date("jS \of F Y",$row['d'])?><?php if(strlen($row['name'])>0){ ?> - <?=$row['name']?><?php } ?></span>
</div>
<div id="comments<?=$row['id']?>a" style="display:none;"><div id="comments<?=$row['id']?>s" style="margin:20px;">
<?php
while($rr=fetch_array($result)){
?>
<br /><div id="comment<?=$rr['id']?>c" class="file"><div class="noprint"><div class="everyonebug"><a href="log.php?dellognotecommentid=<?=$rr['id']?>" class="dellink">DELETE</a></div></div>
<div class="everyonenotetext"><?=createHTML($rr['n'])?>
<br /><span class="makesmall"><?=date("jS \of F Y",$rr['d'])?><?php if(strlen($rr['name'])>0){ ?> - <?=$rr['name']?><?php } ?></span>
</div>
</div>
<?php
}
?>
</div></div>
<div id="newcomment<?=$row['id']?>a" style="display:none;"><div id="newcomment<?=$row['id']?>s" style="margin:20px;"><div id="newcomment<?=$row['id']?>c" class="file">
<div class="everonenotetext">
<form action="log.php" method="post"><input type="hidden" name="logid" id="logid" value="<?=$row['id']?>">
<textarea name="commentlog" id="commentlog" rows="10" cols="60" style="width:90%;"></textarea>
<br /><input type="submit" value="Post Comment" />
</form>
</div>
</div></div>
</div>
</div>
<?php
}
?>
<br /><div class="noprint"><div name="notelogon" id="notelogon" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('notelog','slide',{duration:0.5}); changeDiv('notelogon', 'none'); changeDiv('notelogoff', 'block')">add to log</a></div><div name="notelogoff" id="notelogoff" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('notelog','slide',{duration:0.5}); changeDiv('notelogon', 'block'); changeDiv('notelogoff', 'none')">close add to log</a></div></div>

<div id="notelog" style="display:none"><div name="noteinlog" id="noteinlog" class="dropdown">
<form action="log.php" method="post">
<textarea name="contentlog" id="contentlog" rows="10" cols="60" style="width:95%;"></textarea>
<br /><input type="submit" value="Post to Log" />
</form>
</div></div>
<br />



<?php
}else{
	header("Location:cars.php");
	exit;
}
require_once("bot.php");
?>