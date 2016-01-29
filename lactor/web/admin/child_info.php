<?php 
include_once("../includes/general.php");
include_once("../includes/db.include.php");


initialize();
loggedIn();
db_connect();

loadVocabulary();

?>

<head>
<?php head_tag("Admin LACTOR - "._("Child Info")); ?>
<link rel="stylesheet" href="css/child.css" type="text/css" media="all" />
</head>


<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php admin_menu(4); ?>

<div id="pagecontent">
<?php displayMessage('ChildMessage','ChildDetails', 'ChildType'); ?> 

<div id="registercontent">

<p> <?php echo _("Note: Once a child's information has been added for a certain mother, it cannot be undone or modified.") ?></p>
<br />

<div id="container">
<ul class="menu">
<li id="breastfeeding" class="active"><?php echo _("Child Information") ?></li>
</ul>
<span class="clear"></span>


<div class="content breastfeeding">
<h1><img src="image/baby.png" width=32 height=32> <?php echo _("Add Child Information") ?></h1>
<ul>
<form name="child" method="post" action="admin/post/child_info.post.php">
<pre><?php echo _("Mother") ?>:                                      <select name="mid"> 
		<?php 		
		$query = "select Mothers.mid mid, email from Mothers LEFT JOIN InfantProfile ON Mothers.mid=InfantProfile.mid WHERE InfantProfile.mid IS NULL;";
		$result = mysql_query($query);
		
		while($row=mysql_fetch_array($result)) {
			echo "<option value=\"" . $row['mid'] . "\">" . $row['email'] . "</option>";
		} 
		?> </select>
</pre>
<br />
<pre><?php echo _("Infant initials") ?>:                             <input type="text" id="ttbox" name="InfantInitials"/></pre> 
<pre><?php echo _("Gestational age") ?>:                             <select name="GestationalAge"><?php selectControlledVocabulary('gestate'); ?></select></pre>
<pre><?php echo _("Appropiateness for gestational age") ?>:          <select name="AppropAge"><?php selectControlledVocabulary('GestationalAge'); ?></select></pre>
<br />
<pre><?php echo _("Date of birth") ?>:                               <input type="text" name="dateBirth" /><script language="JavaScript">new tcal ({'formname': 'child','controlname': 'dateBirth'});</script></pre>
<pre><?php echo _("Infant weight at birth") ?>:                      <select name="BirthWeightPounds"><?php selectControlledVocabulary('pounds'); ?></select> <select name="BirthWeightOunces"><?php selectControlledVocabulary('ounces'); ?></select></pre>
<pre><?php echo _("Date of discharge") ?>:                           <input type="text" name="dateDischarge" /><script language="JavaScript">new tcal ({'formname': 'child','controlname': 'dateDischarge'});</script></pre>
<pre><?php echo _("Weight at discharge") ?>:                         <select name="DischargeWeightPounds"><?php selectControlledVocabulary('pounds'); ?></select> <select name="DischargeWeightOunces"><?php selectControlledVocabulary('ounces'); ?></select></pre>
<br />
<pre><?php echo _("Type of first feeding") ?>:                       <select name="TypeFirstBreast"><?php selectControlledVocabulary('TypeFirstDischarge'); ?></select></pre>
<pre><?php echo _("Infant's age at first feeding session") ?>:       <select name="AgeFirstFeed"><?php selectControlledVocabulary('AgeFirstFeed'); ?></select></pre>
<pre><?php echo _("Time of starting breast milk expression") ?>:     <select name="TimeStartBreast"><?php selectControlledVocabulary('TimeStartBreast'); ?></select></pre>
<pre><?php echo _("Frequency of breast milk expression") ?>:         <select name="FreqBreastExpr"><?php selectControlledVocabulary('FreqBreastExpr'); ?></select></pre>
<pre><?php echo _("Morbidities") ?>:                                 <select name="morb-type"><?php selectControlledVocabulary('morb-type'); ?></select></pre>
<pre><?php echo _("First primary care provider visit") ?>:           <select name="FirstPrimCare"><?php selectControlledVocabulary('FirstPrimCare'); ?></select></pre>
<pre><?php echo _("Need for extra primary care provider") ?>:        <select name="NeedExtraCare"><?php selectControlledVocabulary('NeedExtraCare'); ?></select></pre>
<pre><?php echo _("Times of extra primary care on first month") ?>:  <select name="TimesExtraCare"><?php selectControlledVocabulary('TimesExtraCare'); ?></select></pre>
<pre><?php echo _("Hospitalization during the first month") ?>:      <select name="HospFirstMonth"><?php selectControlledVocabulary('HospFirstMonth'); ?></select></pre>
<br />
<pre>                                                  <input type="submit" /></pre>
</form>
<ul>
</div>


<script type="text/javascript" src="js/tabs.js"></script>

<input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />

</div>
</div>
</div>


<?php page_footer(); ?>
</div>

</div>
</body>
</html>
