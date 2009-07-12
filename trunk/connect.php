<?php
error_reporting(0);
set_magic_quotes_runtime(0);
ini_set(auto_detect_line_endings, true);
include('config.php');
session_name($nameofdb);
session_start();
$cfilesize = filesize('../../rpstore/' . $nameofdb .'.db');
if($cfilesize>$sizelimit){
	header("Location:limithit.php");
	exit;
}
if(strlen($experdate)>0){
	if(time()>$experdate){
		header("Location:expired.php");
		exit;
	}
	$t = time()+1209600;
	if($t >$experdate){
		$expwar = "Your account is within two weeks of expiring, please <a href=\"account.php\">renew your account now</a>.";
	}else{
		$expwar="";
	}
}
$addressofwebsite = "http://" . $_SERVER['HTTP_HOST'] . "/";
$db = sqlite_open('../../rpstore/' . strtolower($nameofdb) . '.db');
function query($string){
	global $db;
	$data = sqlite_query($db, $string);
	return $data;
}
function createHTML($strText) {
	$output = eregi_replace("(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))", "<a href=\"mailto:\\1\" target=\"_new\">\\1</a>", $strText);
	$output = nl2br($output);
	return stripslashes($output);
}
function fetch_array($string){
	global $db;
	$data = sqlite_fetch_array($string, SQLITE_ASSOC, TRUE);
	return $data;
}

function xmlreplace($string){
	$data = str_ireplace("&", "&amp;",$string);
	$data = str_ireplace("<", "&lt;",$data);
	$data = str_ireplace(">", "&gt;",$data);
	$data = str_ireplace("'", "&apos;",$data);
	$data = str_ireplace('"', '&quot;',$data);
	return $data;
}

function insert_id($string){
	global $db;
	$data = sqlite_last_insert_rowid($db);
	return $data;
}

function escape_data($string){
	global $db;
	$data = sqlite_escape_string($string);
	return $data;
}

function unescape_data($string){
	global $db;
	$data = stripslashes($string);
	return $data;
}


function num_rows($string){
	global $db;
	$data =sqlite_num_rows($string);
	return $data;
}

function table_exists($string){
	global $db;
	$result = query("SELECT * FROM sqlite_master WHERE type='table' AND name='{$string}'");
	if(num_rows($result)>0){
		return true;
	}else{
		return false;
	}
}

function check_email_address($email) {
// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
	if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
		return false;
	}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	$domain_array = explode(".", $email_array[1]);
	if (sizeof($domain_array) < 2) {
	return false; // Not enough parts to domain
	}
	for ($i = 0; $i < sizeof($domain_array); $i++) {
	if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
	return false;
	}
	}
	}
	return true;
}

function formatPhone($phone) {
	$area = "";
	$prefix = "";
	$exchange = "";
	$extension = "";
	$phone = str_replace("(", "", $phone);
	$phone = str_replace(")", "", $phone);
	$phone = str_replace("-", "", $phone);
	$phone = str_replace(" ", "", $phone);
	$phone = str_replace(".", "", $phone);
	$phone = str_replace("e", "", $phone);
	$phone = str_replace("x", "", $phone);
	$phone = str_replace("t", "", $phone);
	$phone = str_replace("/", "", $phone);
	$phone = str_replace("\\", "", $phone);

	if (empty($phone)){ return "";}
	if (strlen($phone) == 7){
		sscanf($phone, "%3s%4s", $prefix, $exchange);
	}else if (strlen($phone) == 10){
		sscanf($phone, "%3s%3s%4s", $area, $prefix, $exchange);
	}else if (strlen($phone) > 10){
		sscanf($phone, "%3s%3s%4s%s", $area, $prefix, $exchange, $extension);
	}else{
		return $phone;
	}
	$out = "";
	$out .= isset($area) ? '(' . $area . ') ' : "";
	$out .= $prefix . '-' . $exchange;
	$out .= isset($extension) ? ' ' . $extension : "";
return $out;
}

if(table_exists('unit')==false){
	$result = query("CREATE TABLE unit(id INTEGER PRIMARY KEY, name TEXT)");
}


if(table_exists('user')==false){
	$result = query("CREATE TABLE user(id INTEGER PRIMARY KEY, unitid INTEGER, phone TEXT, name TEXT, email TEXT, pass TEXT, admin INTEGER, owner INTEGER, board INTEGER)");
}

if(table_exists('car')==false){
	$result = query("CREATE TABLE car(id INTEGER PRIMARY KEY, userid INTEGER, plate TEXT)");
}

if(table_exists('faq')==false){
	$result = query("CREATE TABLE faq(id INTEGER PRIMARY KEY, catid INTEGER, question TEXT, answer TEXT, dt INTEGER)");
}

if(table_exists('cat')==false){
	$result = query("CREATE TABLE cat(id INTEGER PRIMARY KEY, name TEXT, email TEXT)");
}
if(table_exists('file')==false){
	$result = query("CREATE TABLE file(id INTEGER PRIMARY KEY, board INTEGER, name TEXT, ext TEXT, owner INTEGER, b BLOB, t TEXT, dt INTEGER)");
}
if(table_exists('note')==false){
	$result = query("CREATE TABLE note(id INTEGER PRIMARY KEY, board INTEGER, content TEXT, owner INTEGER, dt INTEGER)");
}
if(table_exists('log')==false){
	$result = query("CREATE TABLE log(id INTEGER PRIMARY KEY, note TEXT, dt INTEGER, userid INTEGER)");
}
if(table_exists('logcomment')==false){
	$result = query("CREATE TABLE logcomment(id INTEGER PRIMARY KEY, note TEXT, dt INTEGER, userid INTEGER, noteid INTEGER)");
}

if(table_exists('logpicture')==false){
	$result = query("CREATE TABLE logpicture(id INTEGER PRIMARY KEY, b BLOB, type TEXT, ext TEXT, name TEXT)");
}

if(table_exists('financial')==false){
	$result = query("CREATE TABLE financial(id INTEGER PRIMARY KEY, unitid INTEGER, amount NUMERIC, description TEXT, dt INTEGER)");
}


?>