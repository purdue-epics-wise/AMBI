<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - System Perception"); ?>
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

<p><?php echo _("Please fill out all the fields.") ?></p>

<div id="container">
<ul class="menu">
<li id="breastfeeding" class="active"><?php echo _("Breastfeeding follow-up") ?></li>
</ul>
<span class="clear"></span>
<div class="content breastfeeding" width="500px">
<div id="standardinput">
<form id="standardform" action="post/breastfeeding_followup.post.php" method="post">

<pre><?php echo _("Which survey number is this for you?"); ?></pre>
<label><input type='radio' name='q1' value='First' /><?php echo _("First") ?></label>
<label><input type='radio' name='q1' value='Second' /><?php echo _("Second") ?></label>
<label><input type='radio' name='q1' value='Third' /><?php echo _("Third") ?></label>

<br /></br />
<pre><?php echo _("How long are you planning to breastfeed your baby?") ?></pre>
<label><input type='radio' class='customAnswer' name='q2radio' value='3 months' />3 <?php echo _("months") ?></label>
<label><input type='radio' class='customAnswer' name='q2radio' value='6 months' />6 <?php echo _("months") ?></label>
<label><input type='radio' class='customAnswer' name='q2radio' value='12 months' />12 <?php echo _("months") ?></label>
<span class='nowrap'>
<label><input type='radio' class='customAnswer' name='q2radio' value='' /><?php echo _("Other (specify)") ?></label>
<input type='text' name='q2' value='' style='visibility:hidden;' />
</span>

<br /></br />
<pre><?php echo _("How many times do you breastfeed your baby per day?") ?></pre>
<label><input type='text' name='q3' /></label>

<br /></br />
<pre><?php echo _("Do you give any other substances (supplementation) for your baby?") ?></pre>
<label><input type='radio' name='q4' value='yes' /><?php echo _("Yes") ?></label>
<label><input type='radio' name='q4' id="supplementNA" value='no' /><?php echo _("No") ?></label>

<div class="supplementQuestions">
<br /></br />
<pre><?php echo _("If yes, what kind of supplement do you give?") ?></pre>
<label><input type='checkbox' class='multiAnswer' name='q5checkbox' value='Herbs' /><?php echo _("Herbs") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q5checkbox' value='Formula' /><?php echo _("Formula") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q5checkbox' value='Cereal' /><?php echo _("Cereal") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q5checkbox' value='Others' /><?php echo _("Others") ?></label>
<input type='hidden' name='q5' value='' />

<br /></br />
<pre><?php echo _("How often do you give a supplemental feeding per day?") ?></pre>
<label><input type='radio' name='q6' value='Two times or less' /><?php echo _("Two times or less") ?></label>
<label><input type='radio' name='q6' value='3-4 times' /><?php echo _("3-4 times") ?></label>
<label><input type='radio' name='q6' value='5-6 times' /><?php echo _("5-6 times") ?></label>
<label><input type='radio' name='q6' value='More' /><?php echo _("More") ?></label>

<br /></br />
<pre><?php echo _("Causes of supplemental feeding") ?>:</pre>
<label><input type='checkbox' class='multiAnswer' name='q7checkbox' value="Don't have enough milk" /><?php echo _("Don't have enough milk") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q7checkbox' value='Going back to class/work' /><?php echo _("Going back to class/work") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q7checkbox' value='Baby is fussy and crying' /><?php echo _("Baby is fussy and crying") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q7checkbox' value='Cultural/tradition' /><?php echo _("Cultural/tradition") ?></label>
<span class='nowrap'>
<label><input type='checkbox' class='multiAnswer' name='q7checkbox' value='' /><?php echo _("Other (specify)") ?></label>
<input type='text' name='q7specify' class='multiAnswer specifyAnswer' value='' style='visibility:hidden;width:400px;' />
</span>
<input type='hidden' name='q7' value='' />
</div>

<br /></br />
<pre><?php echo _("Pattern of breastfeeding (How did you breastfeed your baby in the last 7 days)") ?></pre>
<label><input type='radio' name='q8' value='Exclusive breastfeeding (only breast milk, no other supplement with even water)' /><?php echo _("Exclusive breastfeeding (only breast milk, no other supplement with even water)") ?></label>
<label><input type='radio' name='q8' value='Predominant breastfeeding (Give herbs, liquid, no formula)' /><?php echo _("Predominant breastfeeding (Give herbs, liquid, no formula)") ?></label>
<label><input type='radio' name='q8' value='Partial breastfeeding, High:  50-80% breastfeeding' /><?php echo _("Partial breastfeeding, High:  50-80% breastfeeding") ?></label>
<label><input type='radio' name='q8' value='Partial breastfeeding, Medium:  50% breastfeeding' /><?php echo _("Partial breastfeeding, Medium:  50% breastfeeding") ?></label>
<label><input type='radio' name='q8' value='Partial breastfeeding, Low:  20-50% breastfeeding' /><?php echo _("Partial breastfeeding, Low:  20-50% breastfeeding") ?></label>
<label><input type='radio' name='q8' value='Token breastfeeding (<20% breastfeeding, breastfeed for comfort)' /><?php echo _("Token breastfeeding (&lt;20% breastfeeding, breastfeed for comfort)") ?></label>
<label><input type='radio' name='q8' value='No breastfeeding (exclusive formula)' /><?php echo _("No breastfeeding (exclusive formula)") ?></label>

<br /></br />
<pre><?php echo _("Breastfeeding/baby Problems") ?></pre>
<label><input type='checkbox' class='multiAnswer' name='q9checkbox' value='Inability to latch' /><?php echo _("Inability to latch") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q9checkbox' value='Baby tires easily' /><?php echo _("Baby tires easily") ?></label>
<label><input type='checkbox' class='multiAnswer' name='q9checkbox' value='Sleepy baby' /><?php echo _("Sleepy baby") ?></label>
<span class='nowrap'>
<label><input type='checkbox' class='multiAnswer' name='q9checkbox' value='' /><?php echo _("Other (specify)") ?></label>
<input type='text' name='q9specify' class='multiAnswer specifyAnswer' value='' style='visibility:hidden;width:400px;' />
</span>
<input type='hidden' name='q9' value=''/>

<pre>                                                                                   <input type="submit" value="<?php echo _("Finish Feedback") ?>"/><pre>

</div>
</form>
</div>   
</div>
</div>
<?php unset($_SESSION["q1"]);unset($_SESSION["q2"]);unset($_SESSION["q3"]);unset($_SESSION["q4"]);unset($_SESSION["q5"]);unset($_SESSION["q6"]);unset($_SESSION["q7"]); ?>
<?php page_footer(); ?>
</div>
<script type='text/javascript'><!--
jQuery(function() {
  updateValue = function (element) {
    element = jQuery(element);
    var inputElement = jQuery("input[name='"+element.attr('name').substring(0,2)+"']")
    inputElement.val(element.val());
    if (element.val().length) {
      inputElement.css('visibility', 'hidden');
    } else {
      inputElement.css('visibility', 'visible');
    }
  }

  updateMulti = function (element) {
    element = jQuery(element);
    var values = [];
    var inputElement = jQuery("input.specifyAnswer[name^='"+element.attr('name').substring(0,2)+"']");
    var submissionElement = jQuery("input[name='"+element.attr('name').substring(0,2)+"']");
    if (!element.val().length) {
      if (element.attr('checked')) {
        inputElement.css('visibility', 'visible');
      } else {
        inputElement.css('visibility', 'hidden');
      }
    }
    jQuery("input.multiAnswer[name^='"+element.attr('name').substring(0,2)+"']").each(function(i, ele) {
      ele= jQuery(ele);
      if ((ele.val().length) && (ele.attr('checked') || ele.attr('type') == 'text')) {
        values.push(ele.val());
      }
    });
    submissionElement.val(values.join('; '));
  }

  jQuery('.customAnswer').bind('change', function(event) {
    updateValue(event.target);
  });
  jQuery('.multiAnswer').bind('change', function(event) {
    updateMulti(event.target);
  });
  // hide not applicable questions
  jQuery('input[name="q4"]').bind('change', function(event) {
    if (jQuery('#supplementNA').attr('checked')) {
      console.log("hiding");
      jQuery('.supplementQuestions').css('display', 'none')
    } else {
      jQuery('.supplementQuestions').css('display', '')
      console.log("showing");
    }
  });
});

//--></script>
</body>
</html>
