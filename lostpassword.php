<?php
require_once("connectnolimit.php");
if(strlen($_POST['email'])>0){
	$email = escape_data(strtolower($_POST['email']));
	$q = "SELECT * FROM user WHERE email='" . $email . "'";
	$r = query($q);
	if(num_rows($r)>0){
		while($row=fetch_array($r)){
			$headers = 'From: automailer@rallypointhq.net' . "\r\n" . 'Reply-To: automailer@rallypointhq.net' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
			mail(unescape_data($row['email']), "Your Lost Password at " . $titleofsite, "Your password is: " . unescape_data($row['pass']) . "\nGo to " . $addressofwebsite. " to log in", $headers);
		}
		$note = "Your password has been mailed to " . strtolower($_POST['email']);
	}else{
		$note = "I'm sorry, but it looks like you haven't registered yet";
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
<br /><div class="error" id="error" name="error"><?=$note?></div>
<?php
}
?>
<form action="lostpassword.php" method="post">
<br />email: <input type="text" name="email" id="email" value="<?=$_POST['email']?>"/>
<br /><input type="submit" name="login" id="login" value="e-mail me my lost password" />
</form>
<div name="bottom" id="bottom" class="bottom">
<a class="bot" href="reg.php">register</a>
<a class="bot" href="login.php">login</a>
<?php if($currentplan==0){ ?>
<a class="bot" href="http://heap.wbpsystems.com">Heap</a>
<?php } ?>
</div>
</div></center>
</body>
</html>