<?php 
include_once("includes/general.php");
include_once("includes/db.php");
initialize();
db_connect();

loggedIn();

?>

<head>
<?php head_tag("LACTOR - "._("Mother Information")); ?>
</head>


<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('InfoMessage','InfoDetails', 'InfoType'); ?> 
<?php 
if(!isset($_SESSION['InfoInput'][0])) {
	$_SESSION['InfoInput'][0] = "";
	$_SESSION['InfoInput'][1] = "";
	$_SESSION['InfoInput'][2] = "";
	$_SESSION['InfoInput'][3] = 0;
	$_SESSION['InfoInput'][4] = 0;
	$_SESSION['InfoInput'][5] = 0;
	$_SESSION['InfoInput'][6] = 0;
	$_SESSION['InfoInput'][7] = 0;
	$_SESSION['InfoInput'][8] = 0;
	$_SESSION['InfoInput'][9] = 0;
	$_SESSION['InfoInput'][10] = 0;
	$_SESSION['InfoInput'][11] = 0;
	$_SESSION['InfoInput'][12] = 0;
	$_SESSION['InfoInput'][13] = 0;
	$_SESSION['InfoInput'][14] = 0;
	$_SESSION['InfoInput'][15] = 0;
	$_SESSION['InfoInput'][16] = 0;
	$_SESSION['InfoInput'][17] = 0;
	$_SESSION['InfoInput'][18] = 0;
	$_SESSION['InfoInput'][19] = 0;
	$_SESSION['InfoInput'][20] = 0;
	$_SESSION['InfoInput'][21] = 0;
	$_SESSION['InfoInput'][22] = 0;
	$_SESSION['InfoInput'][23] = 0;
} 
?>


<div id="mothercontent">
<p><?php echo _("Fields indicated with a (*) MUST be filled.") ?></p><br /> 
<p><?php echo _("ATTENTION: Mistakes in this page cannot be reverted once submitted.") ?></p><br />

<div class="tabs">
<ul class="menu">
  <li><a href="#contactInfo"><?php echo _("Personal / Contact Information") ?></a></li>
</ul>
<div id="contactInfo" class="content breastfeeding">
<form id="standardform" action="post/mother_info.post.php" method="post">
<div id="standardinput">
<table border="0"><tbody>
<tr><td><?php echo _("First and Last name") ?>:    </td><td><input id="standardtextform" type="text" name="FormalName" value=<?php echo "\"" . $_SESSION["InfoInput"][0] . "\""; ?>/> * </td></tr>
<tr><td><?php echo _("Phone number") ?>:           </td><td><input id="standardtextform" type="text" name="Phone" value=<?php echo "\"" . $_SESSION["InfoInput"][2] . "\""; ?>/> * Format: XXX-XXX-XXXX</td></tr>
<tr><td><?php echo _("Address") ?>:                </td><td><input id="standardtextform" type="text" name="Address" value=<?php echo "\"" . $_SESSION["InfoInput"][1] . "\""; ?>/> * </td></tr>
<tr><td><?php echo _("Age") ?>:                    </td><td><select name="Age" id="standardselect"><?php echo selectControlledVocabulary("Age", $_SESSION["InfoInput"][3]); ?></select> * </td></tr>
</tbody></table>
</div>
</div>   
</div>

<br/>

<div class="tabs">
<ul class="menu">
  <li><a href="#demoInfo"><?php echo _("Demographic Information") ?></a></li>
</ul>
<div id="demoInfo" class="content breastfeeding">
<div id="standardinput">
<table border="0"><tbody>
<tr><td><?php echo _("Ethnicity") ?>:         </td><td><select name="Ethnicity" id="standardselect"><?php echo selectControlledVocabulary("Ethnicity", $_SESSION["InfoInput"][4]); ?></select> * </td></tr>
<tr><td><?php echo _("Race") ?>:              </td><td><select name="Race" id="standardselect"><?php echo selectControlledVocabulary("Race", $_SESSION["InfoInput"][5]); ?></select> * </td></tr>
<tr><td><?php echo _("Educational Level") ?>: </td><td><select name="Education" id="standardselect"><?php echo selectControlledVocabulary("Education", $_SESSION["InfoInput"][6]); ?></select> * </td></tr>
<tr><td><?php echo _("Household Income") ?>:  </td><td><select name="HouseIncome" id="standardselect"><?php echo selectControlledVocabulary("HouseIncome", $_SESSION["InfoInput"][7]); ?></select> * </td></tr>
<tr><td><?php echo _("Occupation") ?>:        </td><td><select name="Occupation" id="standardselect"><?php echo selectControlledVocabulary("Occupation", $_SESSION["InfoInput"][8]); ?></select> * </td></tr>
<tr><td><?php echo _("Residence") ?>:         </td><td><select name="Residence" id="standardselect"><?php echo selectControlledVocabulary("Residence", $_SESSION["InfoInput"][9]); ?></select> * </td></tr>
</tbody></table>
</div>
</div>   
</div>

<br />

<div class="tabs">
<ul class="menu">
  <li><a href="#medInfo"><?php echo _("Medical Information") ?></a></li>
</ul>
<div id="medInfo" class="content breastfeeding">
<div id="standardinput">
<table border="0"><tbody>
<tr><td><?php echo _("This baby is your") ?>:                   </td><td><select name="Parity" id="standardselect"><?php echo selectControlledVocabulary("Parity", $_SESSION["InfoInput"][10]); ?></select> * </td</tr>
<tr><td><?php echo _("Past Obstetrical History") ?>:            </td><td><br /><?php echo checkControlledVocabulary("POB", "POB", 1); ?> <br /></td></tr>
<tr><td><?php echo _("Maternal History During Pregnancy") ?>:  </td><td><?php echo checkControlledVocabulary("MHDP", "MHDP", 2); ?> <br /></td></tr>
<tr><td><?php echo _("Latest Method Of Delivery") ?>:           </td><td><select name="MODel" id="standardselect"><?php echo selectControlledVocabulary("MODel", $_SESSION["InfoInput"][22]); ?></select> * </td></tr>
<tr><td><?php echo _("Cumulative Breastfeeding Experience") ?>: </td><td><select name="PBE" id="standardselect"><?php echo selectControlledVocabulary("PBE", $_SESSION["InfoInput"][23]); ?></select> * </td></tr>
</tbody></table>
</div>
</div>   
</div>

<br />

<input type="submit" id="mothersubmit" value="<?php echo _("Finish Registering") ?>"/>

<br />
<br />

</form>

</div>    
</div>


<?php page_footer(); ?>
</div>

<br />

</body>
</html>
