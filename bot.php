<div class="noprint"><div class="everyonebug"><a href="rssnotes.php" class="dellink">RSS (NOTES)</a></div></div>
<div class="noprint"><div class="everyonebug"><a href="rssfiles.php" class="dellink">RSS (FILES)</a></div></div>
<div class="noprint"><div class="everyonebug"><a href="rss.php" class="dellink">RSS (FAQ)</a></div></div>
<?php
if($_SESSION['admin']==1){
?>
<div class="noprint"><div class="everyonebug"><a href="rsslog.php" class="dellink">RSS (LOG)</a></div></div>
<?php
}
?>
<?php
if($_SESSION['owner']==1){
?>
<div class="noprint"><div class="everyonebug"><a href="rssfin.php" class="dellink">RSS (FINANCES)</a></div></div>
<?php
}
?>

<div class="noprint"><br /><br /></div><div class="noprint"><div class="whatisrss"><br />(<a href="http://helpdocs.wbpsystems.com/rss.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>what is rss?</a>)</div></div>
<div class="noprint"><br /></div><div class="noprint"><div class="reallysmall"><a href="http://helpdocs.wbpsystems.com/privacy.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>Privacy</a> | <a href="http://helpdocs.wbpsystems.com/terms.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>Terms and Conditions</a></div></div>
<div class="noprint"><br />
<br />
</div>
<?php if($currentplan==0){ ?>
<br />
<div class="hidefromscreen">
This report created using Rally Point.  For more information go to RallyPointHQ.net
</div>
<?php
}
?>
</div>
<div name="bottom" id="bottom" class="bottom">
<a class="bot" href="help.php">Help</a>
<?php if($currentplan==0){ ?>
<a class="bot" href="http://heap.wbpsystems.com">Heap</a>
<?php } ?>
</div>
</body>
</html>