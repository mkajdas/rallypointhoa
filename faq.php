<?php
require_once("connect.php");
$titleext = " - faq";

$afaq = true;
$rss=true;
require_once("top.php");

if(strlen($_POST['check'])>0){
	$e = true;
	$dt = time();
	$q = escape_data($_POST['question']);
	$a = escape_data($_POST['answer']);
	$q = "INSERT INTO faq(question, answer, dt) VALUES('{$q}', '{$a}',{$dt})";	
	$r = query($q);
}


if(strlen($_POST['editid'])>0){
	$dt = time();
	$q = escape_data($_POST['question']);
	$a = escape_data($_POST['answer']);
	$q = "UPDATE faq SET question='{$q}', answer='{$a}', dt={$dt} WHERE id=" . escape_data($_POST['editid']);	
	$r = query($q);
}



if(strlen($_GET['del'])>0 && $_SESSION['admin']==1){
	$q = "DELETE FROM faq WHERE id=" . escape_data($_GET['del']);
	$r = query($q);
}



?>
<div class="title" name="title" id="title" >
Frequently Asked Questions
</div>
<br /><?php 
if(strlen($notee)>0){
?>
<br /><div class="error" id="error" name="error"><?=$notee?></div><br />
<?php
}
?>
<?php
$q = "SELECT * FROM faq ORDER BY question ASC";
$r = query($q);
$count =0;
while($rr=fetch_array($r)){
?>
<?php if($_SESSION['admin']==1){ ?>
<form action="faq.php" method="post"><input type="hidden" name="editid" id="editid" value="<?=$rr['id']?>" />
<div id="a<?=$rr['id']?>" style="display:none;"><div id="ac<?=$rr['id']?>" name="ac<?=$rr['id']?>"><div class="noprint"><div class="everyonebug"><a href="faq.php?del=<?=$rr['id']?>" class="dellink">DELETE</a></div></div><textarea name="question" id="question" rows="10" cols="60" style="width:95%;"><?=unescape_data($rr['question'])?></textarea></div></div>
<div id="normal<?=$rr['id']?>"><div name="normalc<?=$rr['id']?>" id="normalc<?=$rr['id']?>"><i><?=createHTML($rr['question'])?></i></div></div>
<?php }else{ ?>
<br /><i><?=createHTML($rr['question'])?></i>
<?php } ?>
<br /><?php if($_SESSION['admin']==1){ ?>
<br />
<div style="text-align: right;" class="noprint">
<div name="editon<?=$rr['id']?>" id="editon<?=$rr['id']?>"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'none'); changeDiv('editoff<?=$rr['id']?>', 'block'); changeDiv('editoffa<?=$rr['id']?>', 'none'); changeDiv('editona<?=$rr['id']?>', 'block'); changeDiv('a<?=$rr['id']?>', 'none'); changeDiv('ea<?=$rr['id']?>', 'none'); changeDiv('normal<?=$rr['id']?>', 'block');">view</a></div><div name="editoff<?=$rr['id']?>" id="editoff<?=$rr['id']?>" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'block'); changeDiv('editoff<?=$rr['id']?>', 'none'); changeDiv('normal<?=$rr['id']?>', 'block');">close</a></div>
<div name="editona<?=$rr['id']?>" id="editona<?=$rr['id']?>"><a href="javascript:myVoid();" onclick="Effect.toggle('ea<?=$rr['id']?>','slide',{duration:0.5});Effect.toggle('a<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editona<?=$rr['id']?>', 'none'); changeDiv('editoffa<?=$rr['id']?>', 'block'); changeDiv('editon<?=$rr['id']?>', 'block'); changeDiv('editoff<?=$rr['id']?>', 'none'); changeDiv('normal<?=$rr['id']?>', 'none'); changeDiv('e<?=$rr['id']?>', 'none')">edit</a></div><div name="editoffa<?=$rr['id']?>" id="editoffa<?=$rr['id']?>" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('ea<?=$rr['id']?>','slide',{duration:0.5}); Effect.toggle('a<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editona<?=$rr['id']?>', 'block'); changeDiv('editoffa<?=$rr['id']?>', 'none'); changeDiv('normal<?=$rr['id']?>', 'block');">close</a></div>

</div>

<div id="e<?=$rr['id']?>" style="display:none"><div name="ec<?=$rr['id']?>" id="ec<?=$rr['id']?>" class="dropdown">
<?=createHTML($rr['answer'])?>
</div>
</div>


<div id="ea<?=$rr['id']?>" style="display:none;"><div name="eac<?=$rr['id']?>" id="eac<?=$rr['id']?>" class="dropdown">
<textarea name="answer" id="answer" rows="10" cols="60" style="width:95%;"><?=unescape_data($rr['answer'])?></textarea><br /><input type="submit" name="editfaq" id="editfaq" value="edit" />
</div>
</div>
</form>
<?php }else{ ?>
<br /><div style="text-align: right;" class="noprint"><div name="editon<?=$rr['id']?>" id="editon<?=$rr['id']?>" style="display:<?php if($rr['id']==$_POST['editid']){ ?>none<?php }else{ ?>block<?php } ?>;"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'none'); changeDiv('editoff<?=$rr['id']?>', 'block')">view</a></div><div name="editoff<?=$rr['id']?>" id="editoff<?=$rr['id']?>" style="display:none;"><a href="javascript:myVoid();" onclick="Effect.toggle('e<?=$rr['id']?>','slide',{duration:0.5}); changeDiv('editon<?=$rr['id']?>', 'block'); changeDiv('editoff<?=$rr['id']?>', 'none')">close</a></div></div>
<div id="e<?=$rr['id']?>" style="display:none;"><div name="ec<?=$rr['id']?>" id="ec<?=$rr['id']?>" class="dropdown">
<?=createHTML($rr['answer'])?>
</div>
</div>
<?php } ?>
<br />
<?php
}
?>
<br /><div class="noprint"><?php  if($_SESSION['admin']==1){ ?><?php if($_GET['add']==1){ ?><a href="faq.php">close</a><?php }else{ ?><a href="faq.php?add=1">add</a><?php } ?><?php } ?>
<?php if($_GET['add']==1 && $_SESSION['admin']==1){ ?>
<?php 
if(strlen($note)>0){
?>
<br /><div class="error" id="error" name="error"><?=$note?></div><br />
<?php
}
?>
<form action="faq.php" method="post" ><input type="hidden" name="check" id="check" value="check" />
<br />Question:
<br /><textarea name="question" id="question" rows="10" cols="60" style="width:95%;"></textarea>
<br />
<br />Answer:
<br /><textarea name="answer" id="answer" rows="10" cols="60" style="width:95%;"></textarea>
<br /><input type="submit" name="addfaq" id="addfaq" value="add faq" />
</form>
<?php
}
?>
</div>
<?php
require_once("bot.php");
?>