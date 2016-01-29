<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - System Perception"); ?>
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
<li id="breastfeeding" class="active"><?php echo _("Breastfeeding Evaluation") ?></li>
</ul>
<span class="clear"></span>
<div class="content breastfeeding" width="500px">
<div id="standardinput">
<h2><?php echo _("Breastfeeding Evaluation") ?></h2>
<p><?php echo _("If you breastfed more than one baby, base your answers on the most recent experience. Consder the overall breastfeeding experience, and please do not skip any questions.") ?></p>
<p><?php echo _("Indicate your agreement or disagreement with each statement by selecting the best answer.") ?></p>
<form id="standardform" action="post/breastfeeding_evaluation.post.php" method="post">
<table class='questions'>
  <tr>
    <th>&nbsp;</th>
    <th><?php echo _("Strongly<br />Disagree") ?></th>
    <th><?php echo _("Somewhat<br />Disagree") ?></th>
    <th><?php echo _("Neither Agree<br />Nor Disagree") ?></th>
    <th><?php echo _("Somewhat<br />Agree") ?></th>
    <th><?php echo _("Strongly<br />Agree") ?></th>
  </tr>
  <tr id='q1'>
    <td><?php echo _("With breastfeeding I felt a sense of inner contentment.") ?></td>
    <td><input type='radio' name='q1' value='-2' /></td>
    <td><input type='radio' name='q1' value='-1' /></td>
    <td><input type='radio' name='q1' value='0' /></td>
    <td><input type='radio' name='q1' value='1' /></td>
    <td><input type='radio' name='q1' value='2' /></td>
  </tr>
  <tr id='q2'>
    <td><?php echo _("Breastfeeding was a special time with my baby.") ?></td>
    <td><input type='radio' name='q2' value='-2' /></td>
    <td><input type='radio' name='q2' value='-1' /></td>
    <td><input type='radio' name='q2' value='0' /></td>
    <td><input type='radio' name='q2' value='1' /></td>
    <td><input type='radio' name='q2' value='2' /></td>
  </tr>
  <tr id='q3'>
    <td><?php echo _("My baby wasn't interested in breastfeeding.") ?></td>
    <td><input type='radio' name='q3' value='-2' /></td>
    <td><input type='radio' name='q3' value='-1' /></td>
    <td><input type='radio' name='q3' value='0' /></td>
    <td><input type='radio' name='q3' value='1' /></td>
    <td><input type='radio' name='q3' value='2' /></td>
  </tr>
  <tr id='q4'>
    <td><?php echo _("My baby loved to nurse.") ?></td>
    <td><input type='radio' name='q4' value='-2' /></td>
    <td><input type='radio' name='q4' value='-1' /></td>
    <td><input type='radio' name='q4' value='0' /></td>
    <td><input type='radio' name='q4' value='1' /></td>
    <td><input type='radio' name='q4' value='2' /></td>
  </tr>
  <tr id='q5'>
    <td><?php echo _("It was a burden being my baby's main source of food.") ?></td>
    <td><input type='radio' name='q5' value='-2' /></td>
    <td><input type='radio' name='q5' value='-1' /></td>
    <td><input type='radio' name='q5' value='0' /></td>
    <td><input type='radio' name='q5' value='1' /></td>
    <td><input type='radio' name='q5' value='2' /></td>
  </tr>
  <tr id='q6'>
    <td><?php echo _("I felt extremely close to my baby when I breastfed.") ?></td>
    <td><input type='radio' name='q6' value='-2' /></td>
    <td><input type='radio' name='q6' value='-1' /></td>
    <td><input type='radio' name='q6' value='0' /></td>
    <td><input type='radio' name='q6' value='1' /></td>
    <td><input type='radio' name='q6' value='2' /></td>
  </tr>
  <tr id='q7'>
    <td><?php echo _("My baby was an eager breastfeeder.") ?></td>
    <td><input type='radio' name='q7' value='-2' /></td>
    <td><input type='radio' name='q7' value='-1' /></td>
    <td><input type='radio' name='q7' value='0' /></td>
    <td><input type='radio' name='q7' value='1' /></td>
    <td><input type='radio' name='q7' value='2' /></td>
  </tr>
  <tr id='q8'>
    <td><?php echo _("Breastfeeding was physically draining.") ?></td>
    <td><input type='radio' name='q8' value='-2' /></td>
    <td><input type='radio' name='q8' value='-1' /></td>
    <td><input type='radio' name='q8' value='0' /></td>
    <td><input type='radio' name='q8' value='1' /></td>
    <td><input type='radio' name='q8' value='2' /></td>
  </tr>
  <tr id='q9'>
    <td><?php echo _("It was important to me to be able to nurse.") ?></td>
    <td><input type='radio' name='q9' value='-2' /></td>
    <td><input type='radio' name='q9' value='-1' /></td>
    <td><input type='radio' name='q9' value='0' /></td>
    <td><input type='radio' name='q9' value='1' /></td>
    <td><input type='radio' name='q9' value='2' /></td>
  </tr>
  <tr id='q10'>
    <td><?php echo _("While breastfeeding, my baby's growth was excellent.") ?></td>
    <td><input type='radio' name='q10' value='-2' /></td>
    <td><input type='radio' name='q10' value='-1' /></td>
    <td><input type='radio' name='q10' value='0' /></td>
    <td><input type='radio' name='q10' value='1' /></td>
    <td><input type='radio' name='q10' value='2' /></td>
  </tr>
  <tr id='q11'>
    <td><?php echo _("My baby and I worked together to make breastfeeding go smoothly.") ?></td>
    <td><input type='radio' name='q11' value='-2' /></td>
    <td><input type='radio' name='q11' value='-1' /></td>
    <td><input type='radio' name='q11' value='0' /></td>
    <td><input type='radio' name='q11' value='1' /></td>
    <td><input type='radio' name='q11' value='2' /></td>
  </tr>
  <tr id='q12'>
    <td><?php echo _("Breastfeeding was a very nurturing, maternal experience.") ?></td>
    <td><input type='radio' name='q12' value='-2' /></td>
    <td><input type='radio' name='q12' value='-1' /></td>
    <td><input type='radio' name='q12' value='0' /></td>
    <td><input type='radio' name='q12' value='1' /></td>
    <td><input type='radio' name='q12' value='2' /></td>
  </tr>
  <tr id='q13'>
    <td><?php echo _("While breastfeeding, I felt self-concious about my body.") ?></td>
    <td><input type='radio' name='q13' value='-2' /></td>
    <td><input type='radio' name='q13' value='-1' /></td>
    <td><input type='radio' name='q13' value='0' /></td>
    <td><input type='radio' name='q13' value='1' /></td>
    <td><input type='radio' name='q13' value='2' /></td>
  </tr>
  <tr id='q14'>
    <td><?php echo _("With breastfeeding, I felt too tied down all the time.") ?></td>
    <td><input type='radio' name='q14' value='-2' /></td>
    <td><input type='radio' name='q14' value='-1' /></td>
    <td><input type='radio' name='q14' value='0' /></td>
    <td><input type='radio' name='q14' value='1' /></td>
    <td><input type='radio' name='q14' value='2' /></td>
  </tr>
  <tr id='q15'>
    <td><?php echo _("While breastfeeding, I worried about my baby gaining enough weight.") ?></td>
    <td><input type='radio' name='q15' value='-2' /></td>
    <td><input type='radio' name='q15' value='-1' /></td>
    <td><input type='radio' name='q15' value='0' /></td>
    <td><input type='radio' name='q15' value='1' /></td>
    <td><input type='radio' name='q15' value='2' /></td>
  </tr>
  <tr id='q16'>
    <td><?php echo _("Breastfeeding was soothing when my baby was upset or crying.") ?></td>
    <td><input type='radio' name='q16' value='-2' /></td>
    <td><input type='radio' name='q16' value='-1' /></td>
    <td><input type='radio' name='q16' value='0' /></td>
    <td><input type='radio' name='q16' value='1' /></td>
    <td><input type='radio' name='q16' value='2' /></td>
  </tr>
  <tr id='q17'>
    <td><?php echo _("Breastfeeding was like a high of sorts.") ?></td>
    <td><input type='radio' name='q17' value='-2' /></td>
    <td><input type='radio' name='q17' value='-1' /></td>
    <td><input type='radio' name='q17' value='0' /></td>
    <td><input type='radio' name='q17' value='1' /></td>
    <td><input type='radio' name='q17' value='2' /></td>
  </tr>
  <tr id='q18'>
    <td><?php echo _("The fact that I could produce the food to feed my own baby was very satisfying.") ?></td>
    <td><input type='radio' name='q18' value='-2' /></td>
    <td><input type='radio' name='q18' value='-1' /></td>
    <td><input type='radio' name='q18' value='0' /></td>
    <td><input type='radio' name='q18' value='1' /></td>
    <td><input type='radio' name='q18' value='2' /></td>
  </tr>
  <tr id='q19'>
    <td><?php echo _("In the beginning, my baby had trouble breastfeeding.") ?></td>
    <td><input type='radio' name='q19' value='-2' /></td>
    <td><input type='radio' name='q19' value='-1' /></td>
    <td><input type='radio' name='q19' value='0' /></td>
    <td><input type='radio' name='q19' value='1' /></td>
    <td><input type='radio' name='q19' value='2' /></td>
  </tr>
  <tr id='q20'>
    <td><?php echo _("Breastfeeding made me feel like a good mother.") ?></td>
    <td><input type='radio' name='q20' value='-2' /></td>
    <td><input type='radio' name='q20' value='-1' /></td>
    <td><input type='radio' name='q20' value='0' /></td>
    <td><input type='radio' name='q20' value='1' /></td>
    <td><input type='radio' name='q20' value='2' /></td>
  </tr>
  <tr id='q21'>
    <td><?php echo _("I really enjoyed nursing.") ?></td>
    <td><input type='radio' name='q21' value='-2' /></td>
    <td><input type='radio' name='q21' value='-1' /></td>
    <td><input type='radio' name='q21' value='0' /></td>
    <td><input type='radio' name='q21' value='1' /></td>
    <td><input type='radio' name='q21' value='2' /></td>
  </tr>
  <tr id='q22'>
    <td><?php echo _("While breastfeeding, I was anxious to have my body back.") ?></td>
    <td><input type='radio' name='q22' value='-2' /></td>
    <td><input type='radio' name='q22' value='-1' /></td>
    <td><input type='radio' name='q22' value='0' /></td>
    <td><input type='radio' name='q22' value='1' /></td>
    <td><input type='radio' name='q22' value='2' /></td>
  </tr>
  <tr id='q23'>
    <td><?php echo _("Breastfeeding made me feel more confident as a mother.") ?></td>
    <td><input type='radio' name='q23' value='-2' /></td>
    <td><input type='radio' name='q23' value='-1' /></td>
    <td><input type='radio' name='q23' value='0' /></td>
    <td><input type='radio' name='q23' value='1' /></td>
    <td><input type='radio' name='q23' value='2' /></td>
  </tr>
  <tr id='q24'>
    <td><?php echo _("My baby gained weight really well with breastmilk.") ?></td>
    <td><input type='radio' name='q24' value='-2' /></td>
    <td><input type='radio' name='q24' value='-1' /></td>
    <td><input type='radio' name='q24' value='0' /></td>
    <td><input type='radio' name='q24' value='1' /></td>
    <td><input type='radio' name='q24' value='2' /></td>
  </tr>
  <tr id='q25'>
    <td><?php echo _("Breastfeeding made me feel more confident as a mother.") ?></td>
    <td><input type='radio' name='q25' value='-2' /></td>
    <td><input type='radio' name='q25' value='-1' /></td>
    <td><input type='radio' name='q25' value='0' /></td>
    <td><input type='radio' name='q25' value='1' /></td>
    <td><input type='radio' name='q25' value='2' /></td>
  </tr>
  <tr id='q26'>
    <td><?php echo _("I could easily fit my baby's breastfeeding with my other activities.") ?></td>
    <td><input type='radio' name='q26' value='-2' /></td>
    <td><input type='radio' name='q26' value='-1' /></td>
    <td><input type='radio' name='q26' value='0' /></td>
    <td><input type='radio' name='q26' value='1' /></td>
    <td><input type='radio' name='q26' value='2' /></td>
  </tr>
  <tr id='q27'>
    <td><?php echo _("Breastfeeding made me feel like a cow.") ?></td>
    <td><input type='radio' name='q27' value='-2' /></td>
    <td><input type='radio' name='q27' value='-1' /></td>
    <td><input type='radio' name='q27' value='0' /></td>
    <td><input type='radio' name='q27' value='1' /></td>
    <td><input type='radio' name='q27' value='2' /></td>
  </tr>
  <tr id='q28'>
    <td><?php echo _("My baby did not relax while nursing.") ?></td>
    <td><input type='radio' name='q28' value='-2' /></td>
    <td><input type='radio' name='q28' value='-1' /></td>
    <td><input type='radio' name='q28' value='0' /></td>
    <td><input type='radio' name='q28' value='1' /></td>
    <td><input type='radio' name='q28' value='2' /></td>
  </tr>
  <tr id='q29'>
    <td><?php echo _("Breastfeeding was emotionally draining.") ?></td>
    <td><input type='radio' name='q29' value='-2' /></td>
    <td><input type='radio' name='q29' value='-1' /></td>
    <td><input type='radio' name='q29' value='0' /></td>
    <td><input type='radio' name='q29' value='1' /></td>
    <td><input type='radio' name='q29' value='2' /></td>
  </tr>
  <tr id='q30'>
    <td><?php echo _("Breastfeeding felt wonderful to me.") ?></td>
    <td><input type='radio' name='q30' value='-2' /></td>
    <td><input type='radio' name='q30' value='-1' /></td>
    <td><input type='radio' name='q30' value='0' /></td>
    <td><input type='radio' name='q30' value='1' /></td>
    <td><input type='radio' name='q30' value='2' /></td>
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
