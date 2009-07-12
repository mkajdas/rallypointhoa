<?php
require_once("connectnolimit.php");
if(strlen($_POST['email'])>0){
	$email = escape_data(strtolower($_POST['email']));
	$pass = escape_data($_POST['pass']);
	$q = "SELECT * FROM user WHERE email='" . $email . "' AND pass='" . $pass ."'";
	$r = query($q);
	if(num_rows($r)>0){
		while($row=fetch_array($r)){
			if($_POST['dontbug']==1){
				setcookie('email', $_POST['email'], time()+2592000, '/');
				setcookie('pass', $_POST['pass'], time()+2592000, '/');
			}
			$_SESSION['loginid'] = $row['id'];
			$_SESSION['admin'] = $row['admin'];
			$_SESSION['phone'] = $row['phone'];
			$_SESSION['name'] = $row['name'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['unitid'] = $row['unitid'];
			$_SESSION['board'] = $row['board'];
			$_SESSION['owner'] = $row['owner'];
			header("Location:cars.php");
			exit();
		}
	}else{
		$note = "I'm sorry, but it looks like there was a problem with your login information";
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
<form action="login.php" method="post">
<br />email: <input type="text" name="email" id="email" value="<?=$_POST['email']?>"/>
<br />password: <input type="password" name="pass" id="pass" /><br /><input name="dontbug" id="dontbug" type="checkbox" value="1" CHECKED /><span class="checkbox">&nbsp;Remember me on this computer</span><br /><input type="submit" name="login" id="login" value="login" />
</form>
<div name="bottom" id="bottom" class="bottom">

<a class="bot" href="reg.php">register</a>
<a class="bot" href="lostpassword.php">lost password</a>
<?php if($currentplan==0){ ?>
<a class="bot" href="http://heap.wbpsystems.com">Heap</a>
<?php } ?>

</div>
</div>
</center>
</body>
</html>