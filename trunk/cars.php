<?php
require_once("connect.php");
$titleext = " - cars for your unit";
$acars = true;
if($countnumcars==0){
	header("Location:people.php");
	exit;
}

require_once("top.php");

if(strlen($_POST['check'])>0){
	$e = true;
	$userid = escape_data($_POST['userid']);
	$plate = escape_data($_POST['plate']);
	
	if($userid==0){	
	
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
	}

	if($e){
	
		if($userid == 0){
			$q = "INSERT INTO user(unitid, phone, name, email, pass, admin) VALUES({$_SESSION['unitid']}, '{$phone}', '{$name}', '{$email}', '{$pass}', 0)";	
			$r = query($q);
			$iddd=insert_id("dog");
			if($r){
				$query = "INSERT INTO car(userid, plate) VALUES({$iddd}, '{$plate}')";
				$result = query($query);
			}
		}else{
			$query = "INSERT INTO car(userid, plate) VALUES({$userid}, '{$plate}')";
			$result = query($query);		
		}
		
	}else{
		$note = "I'm sorry there was something wrong with your data, please check below.";
	}
		
	
}


if(strlen($_GET['del'])>0){
	$q = "DELETE FROM car WHERE id=" . escape_data($_GET['del']);
	$r = query($q);
}



?>
<div class="title" name="title" id="title" >
Registered Vehicles
</div>
<br />
<table class="table" id="table" name="table">
<tr class="top" name="top" id="top"><td>Plate</td><td>User</td><td></td></tr>
<?php
$q = "SELECT car.plate AS p, user.name AS n, car.id AS idd FROM user, car WHERE car.userid=user.id AND user.unitid=" . $_SESSION['unitid'] . " GROUP BY car.id";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
	$count = $count+1;
?>
<tr><td><?=unescape_data($rr['p'])?></td><td><?=unescape_data($rr['n'])?></td><td><div class="noprint"><a href="cars.php?del=<?=$rr['idd']?>">remove</a></div></td></tr>
<?php
}
?>
</table>
<br /><?php if($count<$countnumcars){ ?><?php if($_GET['add']==1){?><div class="noprint"><a href="cars.php">close</a></div><?php }else{ ?><div class="noprint"><a href="cars.php?add=1">add</a></div><?php } ?><?php }else{ ?><div class="error" id="error" name="error">you must delete a vehicle to add another</div><?php } ?>
<?php if($_GET['add']==1){ ?>
<?php if($count<$countnumcars){ ?>
<?php 
if(strlen($note)>0){
?>
<br /><div class="error" id="error" name="error"><?=$note?><?=$emailext?></div><br />
<?php
}
?><div class="noprint">
<form action="cars.php?add=1" method="post" ><input type="hidden" name="check" id="check" value="check" />
<br />plate: <input type="text" name="plate" id="plate" value="<?=$_POST['plate']?>" />  <span class="platew">enter the plate exactly as it appears on the vehicle</span>
<br />user: <select name="userid" id="userid" onchange="userdropdown(this)">
<?php
$query = "SELECT * FROM user WHERE unitid=" . $_SESSION['unitid'] . " ORDER BY name ASC";
$result = query($query);
while($row = fetch_array($result)){
?>
<option value="<?=$row['id']?>"><?=$row['name']?></option>
<?php
}
?>
<option value="0">other</option>
</select>
<div id="d1" style="display:none;">
<div id="userbox" class="dropdown" name="userbox">
<b><?php if($namee){ ?><div class="error" id="error" name="error"><?php } ?>name (First Last): <input type="text" name="username" id="username" value="<?=$_POST['username']?>" /><?php if($namee){ ?></div><?php } ?><br /><?php if($phonee){ ?><div class="error" id="error" name="error"><?php } ?>phone: <input type="text" name="userphone" id="userphone" value="<?=$_POST['userphone']?>" /><?php if($phonee){ ?></div><?php } ?><br /><?php if($emaile){ ?><div class="error" id="error" name="error"><?php } ?> email: <input type="text" name="useremail" id="useremail" value="<?=$_POST['useremail']?>" /><?php if($emaile){ ?></div><?php } ?><br /> password: <input type="password" name="userpass" id="userpass" /><br /> again: <input type="password" name="userpasstwo" id="userpasstwo" /></b>
</div></div>
<br /><input type="submit" name="addcar" id="addcar" value="add car" />
</form></div>
<?php
}
}
?>
<?php
require_once("bot.php");
?>