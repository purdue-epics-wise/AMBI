<?php 
include_once("includes/general.php");
include_once("includes/db.php");


initialize();
loggedIn();
db_connect();

loadVocabulary();

?>

<head>
<?php head_tag("LACTOR - "._("Profile")); ?>
<link rel="stylesheet" href="css/profile.css" type="text/css" media="all" />
<script type='text/javascript' src='js/passwords.js'></script>
</head>


<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php page_menu(PAGE_PROFILE); ?>

<div id="pagecontent">
<?php if(isset($_SESSION['s_mid'])) {
	$_SESSION['Smessage']="Logged in as scientist.";$_SESSION['Stype']=2;$_SESSION['Sdetail']="";
	displayMessage('Smessage', 'Sdetail', 'Stype');
} ?>
<?php displayNotification(); ?> 
<?php displayMessage('ChangePassMessage','ChangePassDetails', 'ChangePassType'); ?> 

<div id="registercontent">
<div id="container">
<div class="tabs">
<ul class="menu">
<li><a href="#motherInfo"><?php echo _("Mother Information") ?></a></li>
<li><a href="#childInfo"><?php echo _("Child Information") ?></a></li>
<li><a href="#password"><?php echo _("Change Password") ?></a></li>
</ul>
<span class="clear"></span>

<div id="motherInfo" class="content breastfeeding">
<h1><img src="image/female.png" width=48 height=48> <?php echo _("Mother Information") ?></h1>
<?php
//get breastfeeding entries
$query = "SELECT * FROM MotherInfo WHERE mid = " . $_SESSION['mid'] . ";";
//echo $query;
$result = mysql_query($query);
if(mysql_num_rows($result) == 0) {
	echo _("No data has been entered.")." <a href=\"mailto:LACTOR <".WEB_SUPPORT_EMAIL.">?subject=No Maternal Information for " . $_SESSION['mid'] . "\">"._("Please contact the lactation consultant.")."</a><br />";
} else {
while($row = mysql_fetch_array($result))
{
echo _("If any of this information is incorrect").", <a href=\"mailto:LACTOR <".WEB_SUPPORT_EMAIL.">?subject=Change Maternal Information for " . $_SESSION['mid'] . "\">"._("Please contact the lactation consultant.")."</a><br />";
echo "<br />";
echo "<table border='0'><tbody>";
echo "<tr><td>"._("Name").":</td><td>" . $row['Name'] . "</td></tr>";
echo "<tr><td>"._("Address").":</td><td>" . $row['Address'] . "</td></tr>";
echo "<tr><td>"._("Phone").":</td><td>" . $row['Phone'] . "</td></tr>";
echo "<br />";
echo "<tr><td>"._("Age").":</td><td>" . getVocab('Age', $row['Age']) . "</td></tr>";
echo "<tr><td>"._("Ethnicity").":</td><td>" . getVocab('Ethnicity', $row['Ethnicity']) . "</td></tr>";
echo "<tr><td>"._("Race").":</td><td>" . getVocab('Race', $row['Race']) . "</td></tr>";
echo "<tr><td>"._("Education").":</td><td>" . getVocab('Education', $row['Education']) . "</td></tr>";
echo "<tr><td>"._("HouseIncome").":</td><td>" . getVocab('HouseIncome', $row['HouseIncome']) . "</td></tr>";
echo "<tr><td>"._("Occupation").":</td><td>" . getVocab('Occupation', $row['Occupation']) . "</td></tr>";
echo "<tr><td>"._("Residence").":</td><td>" . getVocab('Residence', $row['Residence']) . "</td></tr>";
echo "<tr><td>"._("Parity").":</td><td>" . getVocab('Parity', $row['Parity']) . "</td></tr>";
echo "<tr><td>"._("Past Obstetrical History").":</td><td>" . getPOH($row['POH']) . "</td></tr>";
echo "<tr><td>"._("Maternal History During Pregnancy").":</td><td>" . getMHDP($row['MHDP']) . "</td></tr>";
echo "<tr><td>"._("Method Of Delivery").":</td><td>" . getVocab('MODel', $row['MethodOfDelivery']) . "</td></tr>";
echo "<tr><td>"._("Past breastfeeding experience").":</td><td>" . getVocab('PBE', $row['PBE']) . "</td></tr>";
echo "</tbody></table>";
}
}

?>
</div>

<div id="childInfo" class="content output">
<h1><img src="image/baby.png" width=48 height=48> <?php echo _("Child Information") ?></h1>
<?php

function getWeight($str) {
	$values=explode(" ", $str);
	$retstr=getVocab('pounds', $values[0]);
	$retstr=$retstr . " " . getVocab('ounces', $values[1]);
	return $retstr;
}
//get breastfeeding entries
$query = "SELECT * FROM InfantProfile WHERE mid = " . $_SESSION['mid'] . ";";
//echo $query;
$result = mysql_query($query);
if(mysql_num_rows($result) == 0) {
	echo _("The data has yet to be entered by the lactation consultant.")."<br />";
} else {
while($row = mysql_fetch_array($result))
{
echo _("If any of this information is incorrect").", <a href=\"mailto:LACTOR <".WEB_SUPPORT_EMAIL.">?subject=Change Infant Information for " . $_SESSION['mid'] . "\">"._("please contact the lactation consultant").".</a><br />";
echo "<br />";
echo "<table border='0'><tbody>";
echo "<tr><td>"._("Infant initials").":</td><td>" . $row['InfantInitials'] . "</td></tr>";
echo "<tr><td>"._("Gestational age").":</td><td>" . getVocab('gestate', $row['GestationalAge']) . "</td></tr>";
echo "<tr><td>"._("Appropiateness for gestational age").":</td><td>" . getVocab('GestationalAge', $row['AppropAge']) . "</td></tr>";
echo "<br />";
echo "<tr><td>"._("Date of birth").":</td><td>" . modDate2($row['DOB']) . "</td></tr>";
echo "<tr><td>"._("Infant weight at birth").":</td><td>" . getWeight($row['BirthWeight']) . "</td></tr>";
echo "<tr><td>"._("Date of discharge").":</td><td>" . modDate2($row['DOD']) . "</td></tr>";
echo "<tr><td>"._("Weight at discharge").":</td><td>" . getWeight($row['DischargeWeight']) . "</td></tr>";
echo "<br />";
echo "<tr><td>"._("Type of first feeding").":</td><td>" . getVocab('TypeFirstDischarge', $row['TypeFirstBreast']) . "</td></tr>";
echo "<tr><td>"._("Infant's age at first feeding session").":</td><td>" . getVocab('AgeFirstFeed', $row['TypeFirstBreast']) . "</td></tr>";
echo "<tr><td>"._("Time of starting breast milk expression").":</td><td>" . getVocab('TimeStartBreast', $row['AgeFirstFeed']) . "</td></tr>";
echo "<tr><td>"._("Frequency of breast milk expression").":</td><td>" . getVocab('FreqBreastExpr', $row['FreqBreastExpr']) . "</td></tr>";
echo "<br />";
echo "<tr><td>"._("First primary care provider visit").":</td><td>" . getVocab('FirstPrimCare', $row['FirstPrimCare']) . "</td></tr>";
echo "<tr><td>"._("Need for extra primary care provider").":</td><td>" . getVocab('NeedExtraCare', $row['NeedExtraCare']) . "</td></tr>";
echo "<tr><td>"._("Times of extra primary care on first month").":</td><td>" . getVocab('TimesExtraCare', $row['TimesExtraCare']) . "</td></tr>";
echo "<tr><td>"._("Hospitalization during the first month").":</td><td>" . getVocab('HospFirstMonth', $row['HospFirstMonth']) . "</td></tr>";
echo "</tbody></table>";
}
}

?>
</div>

<div id="password" class="content supplement">
  <h1><img src="image/reset.gif" width=48 height=48 alt =""/> <?php echo _("Change Password") ?></h1>
  <form id="standardform" name="changepassform" action="post/change_pass.post.php" method="post">
    <table border='0'><tbody>
      <tr><td><?php echo _("New Password") ?>:</td><td><input id="standardtextform" type="password" name="newpass" id='passwd' onkeyup="testPassword(this.value);" value=""/> <span id='passwordStrength'></span><br /></td></tr>
      <tr><td><?php echo _("Repeat New Password") ?>:</td><td><input id="standardtextform" type="password" name="rnewpass" /></td></tr>
    </tbody></table>
    <br/><br/>
    <input id="standardsubmit" type="submit" value="<?php echo _("Change Password") ?>"/>
  </form>
</div>
</div>
<p>
<input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />
</p>

</div>
</div>
</div>

	
		
<?php page_footer(); ?>

</div>
</body>
</html>
	
	
