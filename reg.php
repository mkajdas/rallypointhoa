<?php
require_once("connectnolimit.php");
if(strlen($_POST['check'])>0){
	$e = true;
	if(strlen($_POST['email'])>5){
		$email = escape_data(strtolower($_POST['email']));
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

	if(strlen($_POST['name'])>3){
		$name = escape_data($_POST['name']);
	}else{
		$namee=true;
		$e=false;
	}
	
	if(is_numeric($_POST['unitid'])){
		$unitid = escape_data($_POST['unitid']);
	}else{
		$unitide=true;
		$e=false;
	}
	
	if(strlen($_POST['phone'])>9){
		$phone = formatPhone(escape_data($_POST['phone']));
	}else{
		$phonee=true;
		$e=false;
	}
	
	if(strlen($_POST['pass'])>4 && $_POST['passtwo']==$_POST['pass']){
		$pass= escape_data($_POST['pass']);
	}else{
		$e =false;
		$passe =true;
	}
	
	if($e){
		$q = "INSERT INTO user(unitid, phone, name, email, pass, admin) VALUES({$unitid}, '{$phone}', '{$name}', '{$email}', '{$pass}', 0)";	
		$r = query($q);
		if($r){
			header("Location:login.php");
			exit;
		}
	}else{
		$note = "I'm sorry there was something wrong with your data, please check below.";
	}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$titleofsite?></title>
<link rel="stylesheet" type="text/css" href="css.css" />
<link rel="SHORTCUT ICON" href="favicon.ico" />
</head>
<body><br /><br /><br /><br />
<center><div name="login" id="login" class="login">
<span class="logotext"><?=$titleofsite?></span><br />
<?php
if(strlen($note)>0){
?>
<br /><div class="error" id="error" name="error"><?=$note?><?=$emailext?></div>
<?php
}
?>
<form action="reg.php" method="post"><input type="hidden" name="check" id="check" value="check" />
<br /><?php if($namee){?><div class="error" id="error" name="error"><?php } ?>name (First Last): <input type="text" name="name" id="name" value="<?=$_POST['name']?>" /><?php if($namee){?></div><?php } ?>
<br /><?php if($emaile){?><div class="error" id="error" name="error"><?php } ?>email: <input type="text" name="email" id="email" value="<?=$_POST['email']?>" /><?php if($emaile){?></div><?php } ?>
<br /><?php if($phonee){?><div class="error" id="error" name="error"><?php } ?>phone: <input type="text" name="phone" id="phone" value="<?=formatPhone($_POST['phone'])?>" /><?php if($phonee){?></div><?php } ?>
<br /><?php if($unitide){?><div class="error" id="error" name="error"><?php } ?>unit: <select name="unitid" id="unitid">
<?php
$query = "SELECT * FROM unit ORDER BY name ASC";
$result = query($query);
while($row=fetch_array($result)){
	$q = "SELECT * FROM user WHERE unitid=" . $row['id'];
	$r = query($q);
	if(num_rows($r)>$numregsperunitlimit){
	}else{
?>
	<option value="<?=$row['id']?>"<?php if($row['id']==$_POST['unitid']){ ?>SELECTED<?php } ?>><?=$row['name']?></option>
<?php
	}
}
?>
</select><?php if($unitide){?></div><?php } ?>
<?php if($passe){?><br /><div class="error" id="error" name="error">your passwords must match and be greater then 4 characters long</div><?php } ?>
<br />password: <input type="password" name="pass" id="pass" />
<br />again: <input type="password" name="passtwo" id="passtwo" /><br /><input type="submit" name="reg" id="reg" value="register" />
</form>
<div name="bottom" id="bottom" class="bottom">
<a class="bot" href="login.php">login</a>
<a class="bot" href="lostpassword.php">lost password</a>
<?php if($currentplan==0){ ?>
<a class="bot" href="http://heap.wbpsystems.com">Heap</a>
<?php } ?>
</div>
</div></center>
</body>
</html>