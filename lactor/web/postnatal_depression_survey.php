<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - "._("Postnatal Depression")); ?>
<style type='text/css'>
label {
  padding-right: 20px;
  white-space: nowrap;
}
.nowrap {
  white-space: nowrap;
}
</style>
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('PerMessage','PerDetails', 'PerType'); ?> 

<p><?php echo _("Please fill out all the fields") ?>.</p>

<div id="container">
<div class="tabs">
<ul class="menu">
  <li id="breastfeeding" class="active"><a href="#scale"><?php echo _("Edinburgh Postnatal Depression Scale") ?></a></li>
</ul>
<span class="clear"></span>
<div id="scale" width="500px">
<div id="standardinput">
<form id="standardform" action="post/postnatal_depression_survey.post.php" method="post">

<br /></br />
<pre><?php echo _("In the past 7 days") ?>:</pre>
<div style="padding-left: 2em;">
<pre><?php echo _("I have been able to laugh and see the funny side of things") ?></pre>
<label><input type='radio' name='q1' value='1' /><?php echo _("As much as I always could") ?></label>
<label><input type='radio' name='q1' value='2' /><?php echo _("Not quite so much now") ?></label>
<label><input type='radio' name='q1' value='3' /><?php echo _("Definitely not so much now") ?></label>
<label><input type='radio' name='q1' value='4' /><?php echo _("Not at all") ?></label>

<br /></br />
<pre><?php echo _("I have looked forward with enjoyment to things") ?></pre>
<label><input type='radio' name='q2' value='1' /><?php echo _("As much as I ever did") ?></label>
<label><input type='radio' name='q2' value='2' /><?php echo _("Rather less than I used to") ?></label>
<label><input type='radio' name='q2' value='3' /><?php echo _("Definitely less than I used to") ?></label>
<label><input type='radio' name='q2' value='4' /><?php echo _("Hardly at all") ?></label>

<br /></br />
<pre><?php echo _("I have blamed myself unnecessarily when things went wrong") ?></pre>
<label><input type='radio' name='q3' value='1' /><?php echo _("Yes, most of the time") ?></label>
<label><input type='radio' name='q3' value='2' /><?php echo _("Yes, some of the time") ?></label>
<label><input type='radio' name='q3' value='3' /><?php echo _("Not very often") ?></label>
<label><input type='radio' name='q3' value='4' /><?php echo _("No, never") ?></label>

<br /></br />
<pre><?php echo _("I have been anxious or worried for no good reason") ?></pre>
<label><input type='radio' name='q4' value='1' /><?php echo _("No, not at all") ?></label>
<label><input type='radio' name='q4' value='2' /><?php echo _("Hardly ever") ?></label>
<label><input type='radio' name='q4' value='3' /><?php echo _("Yes, sometimes") ?></label>
<label><input type='radio' name='q4' value='4' /><?php echo _("Yes, very often") ?></label>

<br /></br />
<pre><?php echo _("I have felt scared or panicky for no very good reason") ?></pre>
<label><input type='radio' name='q5' value='1' /><?php echo _("Yes, quite a lot") ?></label>
<label><input type='radio' name='q5' value='2' /><?php echo _("Yes, sometimes") ?></label>
<label><input type='radio' name='q5' value='3' /><?php echo _("No, not much") ?></label>
<label><input type='radio' name='q5' value='4' /><?php echo _("No, not at all") ?></label>

<br /></br />
<pre><?php echo _("Things have been getting on top of me") ?></pre>
<label><input type='radio' name='q6' value='1' /><?php echo _("Yes, most of the time I haven't been able to cope at all") ?></label>
<label><input type='radio' name='q6' value='2' /><?php echo _("Yes, sometimes I haven't been coping as well as usual") ?></label>
<label><input type='radio' name='q6' value='3' /><?php echo _("No, most of the time I have coped quite well") ?></label>
<label><input type='radio' name='q6' value='4' /><?php echo _("No, I have been coping as well as ever") ?></label>

<br /></br />
<pre><?php echo _("I have been so unhappy that I have had difficulty sleeping") ?></pre>
<label><input type='radio' name='q7' value='1' /><?php echo _("Yes, most of the time") ?></label>
<label><input type='radio' name='q7' value='2' /><?php echo _("Yes, sometimes") ?></label>
<label><input type='radio' name='q7' value='3' /><?php echo _("Not very often") ?></label>
<label><input type='radio' name='q7' value='4' /><?php echo _("No, not at all") ?></label>

<pre><?php echo _("I have felt sad or miserable") ?>:</pre>
<label><input type='radio' name='q8' value='1' /><?php echo _("Yes, most the time") ?></label>
<label><input type='radio' name='q8' value='2' /><?php echo _("Yes, some of the time") ?></label>
<label><input type='radio' name='q8' value='3' /><?php echo _("Not very often") ?></label>
<label><input type='radio' name='q8' value='4' /><?php echo _("No, not at all") ?></label>

<br /></br />
<pre><?php echo _("I have been so unhappy that I have been crying") ?></pre>
<label><input type='radio' name='q9' value='1' /><?php echo _("Yes, most of the time") ?></label>
<label><input type='radio' name='q9' value='2' /><?php echo _("Yes quite often") ?></label>
<label><input type='radio' name='q9' value='3' /><?php echo _("Only occasionally") ?></label>
<label><input type='radio' name='q9' value='4' /><?php echo _("No, never") ?></label>

<br /></br />
<pre><?php echo _("The thought of harming myself has occurred to me") ?></pre>
<label><input type='radio' name='q10' value='1' /><?php echo _("Yes, quite often") ?></label>
<label><input type='radio' name='q10' value='2' /><?php echo _("Sometimes") ?></label>
<label><input type='radio' name='q10' value='3' /><?php echo _("Hardly ever") ?></label>
<label><input type='radio' name='q10' value='4' /><?php echo _("Never") ?></label>
</div>
</div>

<pre>                                                                                   <input type="submit" value="<?php echo _("Finish Feedback") ?>"/><pre>

</div>
</form>
</div>   
</div>
</div>
<?php 
for ( $i=1; $i <= 10; $i++ ) {
  unset($_SESSION["q".$i]);
}
?>
<?php page_footer(); ?>
</div>
</body>
</html>
