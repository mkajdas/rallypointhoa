<?php
require_once("connect.php");
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


header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT");
header("Expires: Fri, 11 Jul 1980 22:06:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Cache-Control: post-check=0, pre-check=0");
header("Content-Type: application/xml");
?>
<rss version="0.91">
    <channel>
        <title><?=$titleofsite?> Notes</title>
        <description></description>
        <link><?=$addressofwebsite?></link>
        <lastBuildDate><?=date("r")?></lastBuildDate>
<?php
$q = "SELECT * FROM note WHERE id=id" . $board . $owner ." ORDER BY id DESC";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
$titlen = strpos($rr['content'], "\n",1);
if($titlen===false){
	$title = xmlreplace(unescape_data($rr['content']));
}else{
	$title = xmlreplace(substr(unescape_data($rr['content']),0,$titlen));
}

?><item>
            <title><?=$title?></title>
            <link><?=$addressofwebsite?></link>
            <description><?=xmlreplace(createHTML($rr['content']))?></description>
            <pubDate><?php if($rr['dt']>0){ echo date("r",$rr['dt']); }else{ echo "Fri, 29 Sep 2006 20:52:01 -0400"; } ?></pubDate>
</item>
<?php
}
?>
</channel>
</rss>