<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - "._("System Perception")); ?>
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('PerMessage','PerDetails', 'PerType'); ?> 

<p><?php echo _("Please fill out all the fields.") ?></p>
<p><?php echo _("A maximum of 500 letters per field.") ?></p>

<div id="container">
<ul class="menu">
<li id="breastfeeding" class="active"><?php echo _("System Perception") ?></li>
</ul>
<span class="clear"></span>
<div class="content breastfeeding" width="500px">
<div id="standardinput">
<form id="standardform" action="post/system_perception.post.php" method="post">
<pre><?php echo _("Was the web-based breastfeeding monitoring helpful in recognizing your baby's breastfeeding problems?") ?></pre>
<pre><?php echo _("How did it help?") ?></pre>
<textarea  cols="60" rows="5" name="q1" wrap="virtual"><?php echo $_SESSION["q1"]; ?></textarea>
<pre><?php echo _("Was the data entry time consuming for you? Do you have any suggestions?") ?> </pre>
<textarea  cols="60" rows="5" name="q2" wrap="virtual"><?php echo $_SESSION["q2"]; ?></textarea>
<!--
<pre><?php echo _("Was the data entry a burden for you or overwhelming? Do you have any suggestions?") ?> </pre>
<textarea  cols="60" rows="5" name="q3" wrap="virtual"><?php echo $_SESSION["q3"]; ?></textarea>
-->
<pre><?php echo _("How did the web-based monitoring help in overcoming your baby's health problems?") ?> </pre>
<textarea  cols="60" rows="5" name="q4" wrap="virtual"><?php echo $_SESSION["q4"]; ?></textarea>
<pre><?php echo _("Do you think the system helped you to brestfeed longer? Explain.") ?> </pre>
<textarea  cols="60" rows="5" name="q5" wrap="virtual"><?php echo $_SESSION["q5"]; ?></textarea>
<pre><?php echo _("Do you think the monitoring helped you to decrease supplementation with any substance other than breast milk?") ?></pre>
<textarea  cols="60" rows="5" name="q6" wrap="virtual"><?php echo $_SESSION["q6"]; ?></textarea>
<pre><?php echo _("Would you recommend this web-based monitoring for a friend? Why and why not?") ?> </pre>
<textarea  cols="60" rows="5" name="q7" wrap="virtual"><?php echo $_SESSION["q7"]; ?></textarea>
<pre>                                                                                   <input type="submit" value="<?php echo _("Finish Feedback") ?>"/><pre>

</div>
</form>
</div>   
</div>
</div>
<?php unset($_SESSION["q1"]);unset($_SESSION["q2"]);unset($_SESSION["q3"]);unset($_SESSION["q4"]);unset($_SESSION["q5"]);unset($_SESSION["q6"]);unset($_SESSION["q7"]); ?>
<?php page_footer(); ?>
</div>
</body>
</html>
