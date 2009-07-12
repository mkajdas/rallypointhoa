<?php
require_once("connect.php");


if(strlen($_GET['del'])>0){
	$q = "DELETE FROM user WHERE id=" . escape_data($_GET['del']) . " AND unitid=" . escape_data($_SESSION['unitid']);
	$r = query($q);
	if($r){
			$q = "DELETE FROM car WHERE userid=" . escape_data($_GET['del']);
			$r = query($q);
			if($_GET['del']==$_SESSION['loginid']){
				header("Location:logout.php");
				exit;
			}
	}
}

$titleext = " - people in your unit";

$apeople = true;
require_once("top.php");

if(strlen($_POST['check'])>0){
	$e = true;
	
	
		if(strlen($_POST['useremail'])>5){
			$email = escape_data(strtolower($_POST['useremail']));
			$emailcheck = query("SELECT * FROM user WHERE email='" . $email . "'");
			if(num_rows($emailcheck)>0){
				$emaile=true;
				$e=false;
				$emailext = "<ul><li>that e-mail is already in our system, please use another</li></ul>";
			}
			if(check_email_address($email)){
			}else{
					$emaile=true;
					$e=false;
			}

		}else{
			$emaile=true;
			$e=false;
		}

		if(strlen($_POST['username'])>3){
			$name = escape_data($_POST['username']);
		}else{
			$namee=true;
			$e=false;
		}
	
	
		if(strlen($_POST['userphone'])>9){
			$phone = formatPhone(escape_data($_POST['userphone']));
		}else{
			$phonee=true;
			$e=false;
		}
	
		if(strlen($_POST['userpass'])>4 && $_POST['userpasstwo']==$_POST['userpass']){
			$pass= escape_data($_POST['userpass']);
		}else{
			$e =false;
			$passe =true;
		}

	if($e){
	
			$q = "INSERT INTO user(unitid, phone, name, email, pass, admin) VALUES({$_SESSION['unitid']}, '{$phone}', '{$name}', '{$email}', '{$pass}', 0)";	
			$r = query($q);
		
	}else{
		$note = "I'm sorry there was something wrong with your data, please check below.";
	}
		
	
}

if(strlen($_POST['editid'])>0){
	$e = true;
	
	
		if(strlen($_POST['useremail'])>5){
		
			$email = escape_data(strtolower($_POST['useremail']));
			$checkifdif = query("SELECT * FROM user WHERE email='" . $email . "' AND id=" . escape_data($_POST['editid']));
			if(num_rows($checkifdif)>0){
			}else{
				$emailcheck = query("SELECT * FROM user WHERE email='" . $email . "'");
				if(num_rows($emailcheck)>0){
					$emailee=true;
					$e=false;
					$emailexte = "<ul><li>that e-mail is already in our system, please use another</li></ul>";
				}
			}
		if(check_email_address($email)){
		}else{
				$emaile=true;
				$e=false;
		}
		}else{
			$emailee=true;
			$e=false;
		}

		if(strlen($_POST['username'])>3){
			$name = escape_data($_POST['username']);
		}else{
			$nameee=true;
			$e=false;
		}
	
	
		if(strlen($_POST['userphone'])>9){
			$phone = formatPhone(escape_data($_POST['userphone']));
		}else{
			$phoneee=true;
			$e=false;
		}

	if($e){
	
			$q = "UPDATE user SET phone='{$phone}', name='{$name}', email='{$email}' WHERE id=" . escape_data($_POST['editid']);	
			$r = query($q);
			
		if(strlen($_POST['userpass'])>4 && $_POST['userpasstwo']==$_POST['userpass']){
			$pass= escape_data($_POST['userpass']);
			$q = "UPDATE user SET pass='{$pass}' WHERE id=" . escape_data($_POST['editid']);	
			$r = query($q);
			$passee =true;
		}else{
			
		}
			
			
		
	}else{
		$notee = "I'm sorry there was something wrong with your data, please check below.";
	}
		
	
}







?>
<div class="title" name="title" id="title" >
Registered Users
</div>
<br /><?php 
if(strlen($notee)>0){
?>
<br /><div class="error" id="error" name="error"><?=$notee?><?=$emailexte?></div><br />
<?php
}
?>
<table class="table" id="table" name="table">
<tr class="top" name="top" id="top"><td>Name</td><td>Phone</td><td>E-Mail</td><td></td></tr>
<?php
$q = "SELECT * FROM user WHERE unitid=" . $_SESSION['unitid'] . " ORDER BY name ASC";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
	$count = $count+1;
?>
<tr><td><?=unescape_data($rr['name'])?></td><td><?=unescape_data($rr['phone'])?></td><td><a href="mailto:<?=unescape_data($rr['email'])?>"><?=unescape_data($rr['email'])?></a></td><td><div class="noprint"><a href="people.php?del=<?=$rr['id']?>">remove</a><div name="editon<?=$rr['id']?>" id="editon<?=$rr['id']?>" style="display:<?php if($rr['id']==$_POST['editid']){ ?>none<?php }else{ ?>block<?php } ?>;"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'none'); changeDiv('editoff<?=$rr['id']?>', 'block')">edit</a></div><div name="editoff<?=$rr['id']?>" id="editoff<?=$rr['id']?>" style="display:<?php if($rr['id']==$_POST['editid']){ ?>block<?php }else{ ?>none<?php } ?>;"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'block'); changeDiv('editoff<?=$rr['id']?>', 'none')">close</a></div></div></td></tr>
<tr><td colspan="4"><div class="noprint"><div id="e<?=$rr['id']?>" style="display:<?php if($rr['id']==$_POST['editid']){ ?>block<?php }else{ ?>none<?php } ?>"><div name="ec<?=$rr['id']?>" id="ec<?=$rr['id']?>" class="dropdown">
<form action="people.php" method="post"><input type="hidden" name="editid" id="editid" value="<?=$rr['id']?>" />
<?php if($nameee && $rr['id']==$_POST['editid']){ ?><div class="error" id="error" name="error"><?php } ?>name (First Last): <input type="text" name="username" id="username" value="<?php if($rr['id']==$_POST['editid']){ echo $_POST['username']; }else{ echo unescape_data($rr['name']); } ?>" /><?php if($nameee && $rr['id']==$_POST['editid']){ ?></div><?php } ?>
<br /><?php if($phoneee && $rr['id']==$_POST['editid']){ ?><div class="error" id="error" name="error"><?php } ?>phone: <input type="text" name="userphone" id="userphone"  value="<?php if($rr['id']==$_POST['editid']){ echo $_POST['userphone']; }else{ echo unescape_data($rr['phone']); } ?>"/><?php if($phoneee && $rr['id']==$_POST['editid']){ ?></div><?php } ?>
<br /><?php if($emailee && $rr['id']==$_POST['editid']){ ?><div class="error" id="error" name="error"><?php } ?> email: <input type="text" name="useremail" id="useremail"  value="<?php if($rr['id']==$_POST['editid']){ echo $_POST['useremail']; }else{ echo unescape_data($rr['email']); } ?>"/><?php if($emailee && $rr['id']==$_POST['editid']){ ?></div><?php } ?>
<?php if($passee && $rr['id']==$_POST['editid']){ ?><br /><br /><div class="error" id="error" name="error">the password has been changed</div><?php }?><br /> password: <input type="password" name="userpass" id="userpass" /><br /> again: <input type="password" name="userpasstwo" id="userpasstwo" /></b>
<br /><input type="submit" name="edituser" id="edituser" value="edit" />
</form></div></div>
</div></td></tr>
<?php
}
?>
</table>
<div class="noprint"><br /></div><?php if($_GET['add']==1){?><div class="noprint"><a href="people.php">close</a></div><?php }else{ ?><div class="noprint"><a href="people.php?add=1">add</a></div><?php } ?>
<?php if($_GET['add']==1){ ?>
<?php 
if(strlen($note)>0){
?>
<br /><div class="error" id="error" name="error"><?=$note?><?=$emailext?></div><br />
<?php
}
?><div class="noprint">
<form action="people.php?add=1" method="post" ><input type="hidden" name="check" id="check" value="check" />
<?php if($namee){ ?><div class="error" id="error" name="error"><?php } ?>name (First Last): <input type="text" name="username" id="username" value="<?=$_POST['username']?>" /><?php if($namee){ ?></div><?php } ?><br /><?php if($phonee){ ?><div class="error" id="error" name="error"><?php } ?>phone: <input type="text" name="userphone" id="userphone" value="<?=$_POST['userphone']?>" /><?php if($phonee){ ?></div><?php } ?><br /><?php if($emaile){ ?><div class="error" id="error" name="error"><?php } ?> email: <input type="text" name="useremail" id="useremail" value="<?=$_POST['useremail']?>" /><?php if($emaile){ ?></div><?php } ?><br /> password: <input type="password" name="userpass" id="userpass" /><br /> again: <input type="password" name="userpasstwo" id="userpasstwo" />
<br /><input type="submit" name="adduser" id="adduser" value="add user" />
</form></div>
<?php
}
?>
<?php
require_once("bot.php");
?>