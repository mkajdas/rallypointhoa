<?php
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

if(strlen($_GET['delnoteid'])>0 && $_SESSION['admin']==1){
	$q = "DELETE FROM note WHERE id=" . escape_data($_GET['delnoteid']);
	$r = query($q);
}


if(strlen($_POST['whonote'])>0 && $_SESSION['admin']==1){
	
	$content = escape_data($_POST['content']);	
	$query = "";
	$dt = time();
	if($_POST['whonote']=="everyone"){
		$query = "INSERT INTO note(content,dt) VALUES('{$content}',{$dt})";
	}elseif($_POST['whonote']=="board"){
		$query = "INSERT INTO note(content, board,dt) VALUES('{$content}',1,{$dt})";
	}elseif($_POST['whonote']=="owners"){
		$query = "INSERT INTO note(content, owner,dt) VALUES('{$content}',1,{$dt})";
	}
	$result = query($query);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$titleofsite?><?=$titleext?></title>
<link rel="stylesheet" type="text/css" media="screen" rel="Stylesheet" href="css.css" />
<link rel="stylesheet" type="text/css" media="print" rel="Stylesheet" href="print.css" />
<script src="prototype.js" type="text/javascript"></script>
<script src="scriptaculous.js?load=effects" type="text/javascript"></script>
<script src="utility.js" type="text/javascript"></script>
<link rel="SHORTCUT ICON" href="favicon.ico" />
<?php if($rss){?>
<link rel="alternate" type="application/rss+xml" title="<?=$titleofsite?> FAQ" href="<?=$addressofwebsite?>rss.php" />
<?php
}elseif($showrssfile){
?>
<link rel="alternate" type="application/rss+xml" title="<?=$titleofsite?> Files" href="<?=$addressofwebsite?>rssfiles.php" />
<?php
}elseif($showrssfin){
?>
<link rel="alternate" type="application/rss+xml" title="<?=$titleofsite?> My Finances" href="<?=$addressofwebsite?>rssfin.php" />
<?php
}elseif($showrsslog){
?>
<link rel="alternate" type="application/rss+xml" title="<?=$titleofsite?> Log" href="<?=$addressofwebsite?>rsslog.php" />
<?php
}else{
?>
<link rel="alternate" type="application/rss+xml" title="<?=$titleofsite?> Notes" href="<?=$addressofwebsite?>rssnotes.php" />
<?php
}
?>
</head>
<body>
<div class="header" id="header" name="header">
<?php
if($countnumcars>0){
?>
<div name="searcharea" id="searcharea" class="searcharea">
		<form action="search.php" method="post" class="addaccount">
		<br /><input type="search" name="search" id="search" placeholder="Search Plates" results="5" autosave="<?=$subdomain?>" size="35" /><input type="submit" name="submit" id="submit" value="search plates" />
		<br /><span class="small">enter the plate exactly as it appears on the vehicle&nbsp;&nbsp;</span>

		</form>
</div>
<?php
}
?>

<br />
		<div style="min-height:30px;"><span class="logotext"><?=$titleofsite?></span><span class="logodash"> - </span><span class="headname"><?=$_SESSION['name']?> (<a href="logout.php" class="logologout">logout</a>)</span></div>
		
<br /><br />
<div class="tabArea" id="tabArea" name="tabArea">
<?php
if($countnumcars>0){
?>
  <a class="tab<?php if($acars==true){?> activeTab<?php } ?>" href="cars.php">cars for your unit</a>
<?php
}
?>
  <a class="tab<?php if($apeople==true){?> activeTab<?php } ?>" href="people.php">people in your unit</a>
<?php
if($_SESSION['owner']==1){
?>
  <a class="tab<?php if($afin==true){?> activeTab<?php } ?>" href="fin.php">my finances</a>
<?php
}
?>
  <a class="tab<?php if($afaq==true){?> activeTab<?php } ?>" href="faq.php">faq</a>
  <a class="tab<?php if($afile==true){?> activeTab<?php } ?>" href="file.php">files</a>

  <?php if($_SESSION['admin']==1){ ?>
  <a class="tab<?php if($aadmin==true){?> activeTab<?php } ?>" href="admin.php">admin</a>
  <a class="tab<?php if($alog==true){?> activeTab<?php } ?>" href="log.php">log</a>
  <a class="tab<?php if($aunit==true){?> activeTab<?php } ?>" href="unit.php">units</a>
  <a class="tab<?php if($aaccount==true){?> activeTab<?php } ?>" href="account.php">account</a>
  <?php } ?>
</div>
</div>
<div class="bar" id="bar" name="bar"></div>
<div class="logotextprint"><?=$titleofsite?><?=$titleext?></div>
<div class="printbar" id="printbar" name="printbar"><hr height="1" /></div>
<br />
<div class="content" name="content" id="content">




<?php
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


$q = "SELECT * FROM note WHERE id=id" . $board . $owner ." ORDER BY id DESC";
$r = query($q);


while($rr=fetch_array($r)){
?>
<br /><div class="<?php if($rr['board']==1){?>board<?php }elseif($rr['owner']==1){ ?>owner<?php }else{ ?>file<?php } ?>">
<div class="<?php if($rr['board']==1){?>boardnotetext<?php }elseif($rr['owner']==1){ ?>ownernotetext<?php }else{ ?>notetext<?php } ?>"><?php if($_SESSION['admin']==1){?><?php if($rr['board']==1){ ?><div class="noprint"><div class="boardbug"><a href="<?=str_replace ('/rallypointhq/','',$_SERVER['PHP_SELF'])?>?delnoteid=<?=$rr['id']?>" class="dellink">DELETE</a></div></div><?php } ?><?php if($rr['owner']==1){ ?><div class="noprint"><div class="ownerbug"><a href="<?=str_replace ('/rallypointhq/','',$_SERVER['PHP_SELF'])?>?delnoteid=<?=$rr['id']?>" class="dellink">DELETE</a></div></div><?php } ?><? if($rr['board']!=1 && $rr['owner']!=1){ ?><div class="noprint"><div class="everyonebug"><a href="<?=str_replace ('/rallypointhq/','',$_SERVER['PHP_SELF'])?>?delnoteid=<?=$rr['id']?>" class="dellink">DELETE</a></div></div><?php } ?><?php } ?><?php if($rr['board']==1){ ?><div class="boardbug">BOARD</div><?php } ?><?php if($rr['owner']==1){ ?><div class="ownerbug">OWNERS</div><?php } ?><? if($rr['board']!=1 && $rr['owner']!=1){ ?><div class="everyonebug">EVERYONE</div><?php } ?><?=createHTML($rr['content'])?></div>
</div>
<?php
}
?>





<?php
if($_SESSION['admin']==1){
?>
<br /><div style="text-align: right;" class="noprint"><div name="noteon" id="noteon" style="display:block;"><a href="javascript:myVoid();" onclick="Effect.toggle('note','slide',{duration:0.5}); changeDiv('noteon', 'none'); changeDiv('noteoff', 'block')">add note</a></div><div name="noteoff" id="noteoff" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('note','slide',{duration:0.5}); changeDiv('noteon', 'block'); changeDiv('noteoff', 'none')">close add note</a></div></div>

<div id="note" style="display:none"><div name="notein" id="notein" class="dropdown">
<form action="<?=str_replace ('/rallypointhq/','',$_SERVER['PHP_SELF'])?>" method="post">
<textarea name="content" id="content" rows="10" cols="60" style="width:95%;"></textarea>
<br />who has access: <select name="whonote" id="whonote">
<option value="everyone">everyone</option>
<option value="board">board</option>
<option value="owners">owners</option>
</select>
<br /><input type="submit" value="Post Note" />
</form>
</div></div>
<?php
}


if(num_rows($r)>0){
?>
<br />
<?php
}
?>
<?php
if($_SESSION['admin']==1){
	$sizelimitnine = $sizelimit*0.9;
	if($cfilesize>$sizelimitnine){
?>
<br /><div class="error" id="error" name="error">you are within 10% of your file size limit, please <a href="account.php">upgrade your account</a></div><br />
<?php
	}
	if(strlen($expwar)>0){
	?>
<br /><div class="error" id="error" name="error"><?=$expwar?></div><br />	
	<?php
	}
}
?>