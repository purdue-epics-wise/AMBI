<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - "._("Self-efficacy Survey")); ?>
<style type='text/css'>
.nowrap {
  white-space: nowrap;
}
td > input {
  margin: 10px;
}
th, td {
  padding: 2px;
}
</style>
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('PerMessage','PerDetails', 'PerType'); ?> 

<p>Please fill out all the fields.</p>

<div id="container">
<ul class="menu">
<li id="breastfeeding" class="active">Self-efficacy Survey</li>
</ul>
<span class="clear"></span>
<div class="content breastfeeding" width="500px">
<div id="standardinput">
<h2><?php echo _("Breastfeeding Self-Efficacy Scale - Short Form") ?></h2>
<p><?php echo _("For each of the following statements, please choose the answer that best describes how confident you are with breastfeeding your new baby. Please mark your answer by clicking the button that is closes to how you feel. There is no right or wrong answer.") ?></p>
<form id="standardform" action="post/self-efficacy_survey.post.php" method="post">
<table class='questions'>
  <tr>
    <th>&nbsp;</th>
    <th><?php echo _("Not at all<br />Confident") ?></th>
    <th><?php echo _("Not very<br />Confident") ?></th>
    <th><?php echo _("Sometimes<br />Confident") ?></th>
    <th><?php echo _("Confident") ?></th>
    <th><?php echo _("Very<br />Confident") ?></th>
  </tr>
  <tr id='q1'>
    <td><?php echo _("I can always determine that my baby is getting enough milk.") ?></td>
    <td><input type='radio' name='q1' value='-2' /></td>
    <td><input type='radio' name='q1' value='-1' /></td>
    <td><input type='radio' name='q1' value='0' /></td>
    <td><input type='radio' name='q1' value='1' /></td>
    <td><input type='radio' name='q1' value='2' /></td>
  </tr>
  <tr id='q2'>
    <td><?php echo _("I can always successfully cope with breastfeeding like I have with other challenging tasks.") ?></td>
    <td><input type='radio' name='q2' value='-2' /></td>
    <td><input type='radio' name='q2' value='-1' /></td>
    <td><input type='radio' name='q2' value='0' /></td>
    <td><input type='radio' name='q2' value='1' /></td>
    <td><input type='radio' name='q2' value='2' /></td>
  </tr>
  <tr id='q3'>
    <td><?php echo _("I can always breastfeed my baby without first using formula as a supplement.") ?></td>
    <td><input type='radio' name='q3' value='-2' /></td>
    <td><input type='radio' name='q3' value='-1' /></td>
    <td><input type='radio' name='q3' value='0' /></td>
    <td><input type='radio' name='q3' value='1' /></td>
    <td><input type='radio' name='q3' value='2' /></td>
  </tr>
  <tr id='q4'>
    <td><?php echo _("I can always ensure that my baby is properly latched on for the whole feeding.") ?></td>
    <td><input type='radio' name='q4' value='-2' /></td>
    <td><input type='radio' name='q4' value='-1' /></td>
    <td><input type='radio' name='q4' value='0' /></td>
    <td><input type='radio' name='q4' value='1' /></td>
    <td><input type='radio' name='q4' value='2' /></td>
  </tr>
  <tr id='q5'>
    <td><?php echo _("I can always manage the breastfeeding situation to my satisfaction.") ?></td>
    <td><input type='radio' name='q5' value='-2' /></td>
    <td><input type='radio' name='q5' value='-1' /></td>
    <td><input type='radio' name='q5' value='0' /></td>
    <td><input type='radio' name='q5' value='1' /></td>
    <td><input type='radio' name='q5' value='2' /></td>
  </tr>
  <tr id='q6'>
    <td><?php echo _("I can always manage to breastfeed even if my baby is crying.") ?></td>
    <td><input type='radio' name='q6' value='-2' /></td>
    <td><input type='radio' name='q6' value='-1' /></td>
    <td><input type='radio' name='q6' value='0' /></td>
    <td><input type='radio' name='q6' value='1' /></td>
    <td><input type='radio' name='q6' value='2' /></td>
  </tr>
  <tr id='q7'>
    <td><?php echo _("I can always keep wanting to breastfeed") ?></td>
    <td><input type='radio' name='q7' value='-2' /></td>
    <td><input type='radio' name='q7' value='-1' /></td>
    <td><input type='radio' name='q7' value='0' /></td>
    <td><input type='radio' name='q7' value='1' /></td>
    <td><input type='radio' name='q7' value='2' /></td>
  </tr>
  <tr id='q8'>
    <td><?php echo _("I can always comfortably breastfeed with my family members present.") ?></td>
    <td><input type='radio' name='q8' value='-2' /></td>
    <td><input type='radio' name='q8' value='-1' /></td>
    <td><input type='radio' name='q8' value='0' /></td>
    <td><input type='radio' name='q8' value='1' /></td>
    <td><input type='radio' name='q8' value='2' /></td>
  </tr>
  <tr id='q9'>
    <td><?php echo _("I can always be satisfied with my breastfeeding experience.") ?></td>
    <td><input type='radio' name='q9' value='-2' /></td>
    <td><input type='radio' name='q9' value='-1' /></td>
    <td><input type='radio' name='q9' value='0' /></td>
    <td><input type='radio' name='q9' value='1' /></td>
    <td><input type='radio' name='q9' value='2' /></td>
  </tr>
  <tr id='q10'>
    <td><?php echo _("I can always deal with the fact that breastfeeding can be time consuming.") ?></td>
    <td><input type='radio' name='q10' value='-2' /></td>
    <td><input type='radio' name='q10' value='-1' /></td>
    <td><input type='radio' name='q10' value='0' /></td>
    <td><input type='radio' name='q10' value='1' /></td>
    <td><input type='radio' name='q10' value='2' /></td>
  </tr>
  <tr id='q11'>
    <td><?php echo _("I can always finish feeding my baby on one breast before switching to the other breast.") ?></td>
    <td><input type='radio' name='q11' value='-2' /></td>
    <td><input type='radio' name='q11' value='-1' /></td>
    <td><input type='radio' name='q11' value='0' /></td>
    <td><input type='radio' name='q11' value='1' /></td>
    <td><input type='radio' name='q11' value='2' /></td>
  </tr>
  <tr id='q12'>
    <td><?php echo _("I can always continue to breastfeed my baby for every feeding.") ?></td>
    <td><input type='radio' name='q12' value='-2' /></td>
    <td><input type='radio' name='q12' value='-1' /></td>
    <td><input type='radio' name='q12' value='0' /></td>
    <td><input type='radio' name='q12' value='1' /></td>
    <td><input type='radio' name='q12' value='2' /></td>
  </tr>
  <tr id='q13'>
    <td><?php echo _("I can always manage to keep up with my baby's breastfeeding demands.") ?></td>
    <td><input type='radio' name='q13' value='-2' /></td>
    <td><input type='radio' name='q13' value='-1' /></td>
    <td><input type='radio' name='q13' value='0' /></td>
    <td><input type='radio' name='q13' value='1' /></td>
    <td><input type='radio' name='q13' value='2' /></td>
  </tr>
  <tr id='q14'>
    <td><?php echo _("I can always tell when my baby is finished breastfeeding.") ?></td>
    <td><input type='radio' name='q14' value='-2' /></td>
    <td><input type='radio' name='q14' value='-1' /></td>
    <td><input type='radio' name='q14' value='0' /></td>
    <td><input type='radio' name='q14' value='1' /></td>
    <td><input type='radio' name='q14' value='2' /></td>
  </tr>
</table>
<br />
<button type="submit"><?php echo _("Submit") ?></button>
</form>
</div>   
</div>
</div>
<?php 
unset($_SESSION["q1"]);
unset($_SESSION["q2"]);
unset($_SESSION["q3"]);
unset($_SESSION["q4"]);
unset($_SESSION["q5"]);
unset($_SESSION["q6"]);
unset($_SESSION["q7"]);
unset($_SESSION["q8"]);
unset($_SESSION["q9"]);
unset($_SESSION["q10"]);
unset($_SESSION["q11"]);
unset($_SESSION["q12"]);
unset($_SESSION["q13"]);
unset($_SESSION["q14"]);

page_footer(); 
?>
</div>
<script type='text/javascript'>
jQuery( function( ) {
  jQuery( 'td' ).click( function( ) {

  });
});
</script>
</body>
</html>
