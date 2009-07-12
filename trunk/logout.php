<?php
session_name("wheatridge");
error_reporting(0);
set_magic_quotes_runtime(0);
session_start();
		$_SESSION['loginid'] = "";
			$_SESSION['admin'] = "";
			$_SESSION['phone'] = "";
			$_SESSION['name'] = "";
			$_SESSION['email'] = "";
			$_SESSION['unitid'] = "";
			$_SESSION['board'] = "";
			$_SESSION['owner'] = "";
			setcookie('email', '', time()-2592000, '/');
			setcookie('pass', '', time()-2592000, '/');

header("Location:login.php");
exit;
?>