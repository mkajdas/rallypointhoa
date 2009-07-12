<?php
require_once("connect.php");
$titleext = " - help";
require_once("top.php");

?>
<div class="title" name="title" id="title" >
Help - FAQ
</div>
<br /><div><a href="javascript:myVoid();" onclick="Effect.toggle('qone','slide',{duration:0.5});">
How does the Registration System Work?
</a></div>

<div id="qone" style="display:none"><div id="qonec" class="dropdown">
When a unit does not have any users, the system makes that unit available for registration. Once one user is registered, the unit is no longer available for registration. However, once one user is registered for a unit, they can create as many users as they like within that unit simply by clicking on the "people in my unit" tab. If all of the users of a particular unit are deleted, the unit becomes available for registration.
</div>
</div>



<br /><div><a href="javascript:myVoid();" onclick="Effect.toggle('qtwo','slide',{duration:0.5});">
But we need more then one person to be able to register for a unit!
</a></div>

<div id="qtwo" style="display:none"><div id="qtwoc" class="dropdown">
If the association decides that they want more then one person to be able to register for a unit (before registering is limited to using the "people in my unit" tab). Then one of the administrators can go to the account page and increase this number.
</div>
</div>


<br /><div><a href="javascript:myVoid();" onclick="Effect.toggle('qthree','slide',{duration:0.5});">
Can you explain to me who has access to what?
</a></div>

<div id="qthree" style="display:none"><div id="qthreec" class="dropdown">
So there is four groups of people in a Rally Point:<ol>
<li>Everyone</li>
<li>Owners</li>
<li>Board Members</li>
<li>Admins</li>
</ol>
The "Everyone" group includes every user. It doesn't matter how the user was created or if the user has been defined as an admin, board member, owner or nothing at all.
<br /><br />The "Owners" group includes anyone who has been marked as an "Owner" by one of the admin users. Files and notes can created just for owners (they have a green background and have the badge "OWNER"). Membership in owner group is not exclusive; a single user can be an owner, admin and board member.
<br /><br />Just like "Owners," the "Board Members" group includes anyone who has been marked as a "Board Member" by one of the admin users. Files and notes can created just for board members (they have a red background and have the badge "BOARD").
<br /><br />Being a member of the admin group doesn't inherently grant you permission to see files for the "Owners" or the "Board." However, admins can create content (such as files, logs or notes), can change permissions and can look at the list of users and plates. In fact when an admin searches for a license plate, it not only shows whether it is registered or not, but who owns the car, their unit, their email and their phone number.
</div>
</div>
<br />
<br /><div class="title" name="title" id="title" >
Help - Technologies
</div>

<br /><div><a href="http://helpdocs.wbpsystems.com/rss.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>What is rss?</a></div>
<br /><div><a href="http://helpdocs.wbpsystems.com/csv.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>What is csv?</a></div>
<br /><div><a href="http://helpdocs.wbpsystems.com/xml.php?rallypoint=1&title=<?=urlencode($titleofsite)?>" target=_blank>What is xml?</a></div>
<?php
if($_SESSION['admin']==1){
?>

<?php
}
?>

<?php
require_once("bot.php");
?>