<?php

session_start();

include_once("./mobile.include.php");

if(!isset($_SESSION['mid'])) {
	$_SESSION['LoginMessage'] = "Session expired.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
}

if(!isset($_SESSION['writeid'])) {
	header('Location: ./m.add_entry.php');
	exit;
}


$conn = mysql_connect('localhost', 'lactor', 'breast1010');
if (!$conn) {
	die('Could not connect');
}

mysql_select_db("Breast1", $conn);
$_SESSION['db_con'] = $conn;

?>


<html>
<head>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="viewport" content="user-scalable=no,width=device-width" />
<title> Lactor Mobile - <?php echo _("Modify Entry") ?> </title>

<script type="text/javascript">
function disableDropDown()
{   
	var arr = new Array();
	arr = document.getElementsByName('pumping-amount');
	amount = arr.item(0);
	arr = document.getElementsByName('pumping-method');
	method = arr.item(0);
	if(method.value==4) {
		amount.disabled=true;
		amount.selectedIndex=0;
	} else {
		amount.disabled=false;
	}
}
</script>

</head>


<body>

<div id="maincontainer">

<div id="header"><img alt="Welcome" src="./logo.png" /></div>
<?php 
if(isset($_SESSION['writetype'])) {
	echo "<div id=\"message\">";
	if($_SESSION['writetype'] == 3) {
		echo "<div id=\"errorcontent\"><b>" . $_SESSION['writemessage'] . "</b></div>";
	} else if ($_SESSION['writetype'] == 1){
		echo "<div id=\"goodcontent\"><b>" . $_SESSION['writemessage'] . "</b></div>";
	} else if ($_SESSION['writetype'] == 2){
		echo "<div id=\"warncontent\"><b>" . $_SESSION['writemessage'] . "</b></div>";
	}
	echo "</div>";
	unset($_SESSION['writetype']);
	unset($_SESSION['writemessage']);
}
?>

<form name="modifyentry" method="post" action="./m.modify_entry.post.php">

<div id="container">

<?php
if($_SESSION['writewhat'] == 1) {
	$query = "SELECT * FROM Diary, BreastfeedEntry WHERE EntryType = 1 AND Diary.EntryId = BreastfeedEntry.EntryId AND BreastfeedEntry.EntryId = " . $_SESSION['writeid'];
	$result = mysql_query($query);
	$row = mysql_fetch_array($result, MYSQL_BOTH);
	echo "<ul class=\"menu\">";
	echo "<li id=\"breastfeeding\" class=\"active\">Breastfeeding</li>";
	echo "</ul>";
	echo "<div class=\"content breastfeeding\" style=\"display:Block;\">";
	echo "<div id=\"addentryform\">";
	echo "<b>Duration:</b> ";    
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"duration\">";
	selectSelectedControlledVocabulary("duration", $row['BreastfeedingDuration']) ;
	echo "</select>";
	echo "<br/>";
	echo "<b>Side:</b>";
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"side\">";
	selectSelectedControlledVocabulary("Side", $row['Side']);
	echo "</select>  ";
	echo "<br/>";
	echo "<b>Latching:</b>";
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"latching\">";
	selectSelectedControlledVocabulary("latching", $row['Latching']);
	echo "</select>";
	echo "<br/>";
	echo "<b>Pumping method:</b>";
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"pumping-method\" onchange=\"disableDropDown()\">";
	selectSelectedControlledVocabulary("pumping-method", $row['PumpingMethod']);
	echo "</select>";
	echo "<br/>";
	echo "<b>Pumping amount:</b>";
	echo "<br/>";
	if($row['PumpingMethod'] == 4) $c = "disabled=\"true\""; else $c = "";
	echo "<select id=\"standardselect\" name=\"pumping-amount\" " . $c . " >";
	selectSelectedControlledVocabulary("TotalAmount", $row['PumpingAmount']);
	echo "</select>   ";               
	echo "<br/>";
	echo "<b>Infant state:</b>";
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"infant-state\">";
	selectSelectedControlledVocabulary("infant-state", $row['InfantState']);
	echo "</select>";
	echo "<br/>";
	echo "<b>Maternal problems:</b>";
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"maternal-problems\">";
	selectSelectedControlledVocabulary("maternal-problems", $row['MaternalProblems']);
	echo "</select>";
	echo "<br/>";
	echo "<br/>";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Modify Entry")."\" name=\"breast\"  accesskey=\"u\" />";
	echo "<br/>";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Cancel")."\" name=\"cancel\"  accesskey=\"u\" />";
	echo "</div>";
	echo "</div>";
}

?>

<?php
if($_SESSION['writewhat'] == 2) {
	$query="SELECT * FROM Diary, SupplementEntry WHERE EntryType = 2 AND Diary.EntryId = SupplementEntry.EntryId AND SupplementEntry.EntryId = " . $_SESSION['writeid'];
	$result = mysql_query($query);
	$row = mysql_fetch_array($result, MYSQL_BOTH);
	echo "<ul class=\"menu\">";
	echo "<li id=\"supplement\" class=\"active\">Supplement</li>";
	echo "</ul>";
	echo "<div class=\"content supplement\" style=\"display:Block;\">";
	echo "<div id=\"addentryform\">";
	echo "<b>Type:</b>    ";     
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"sup-type\">";
	selectSelectedControlledVocabulary("sup-type", $row['SupType']) ;
	echo "</select>";
	echo "<br/>";
	echo "<b>Method:</b>";                
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"sup-method\">";
	selectSelectedControlledVocabulary("sup-method", $row['SupMethod']);
	echo "</select>";
	echo "<br/>";
	echo "<b>Frequency:</b> ";
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"NumberTimes\">";
	selectSelectedControlledVocabulary("NumberTimes", $row['NumberTimes']) ;
	echo "</select>";
	echo "<br/>";
	echo "<b>Total Amount Today:</b>  ";  
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"TotalAmount\">";
	selectSelectedControlledVocabulary("TotalAmount", $row['TotalAmount']) ;
	echo "</select>";
	echo "<br/>";
	echo "<br/>";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Modify Entry")."\" name=\"sup\"  accesskey=\"u\" />";
	echo "<br/>";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Cancel")."\" name=\"cancel\"  accesskey=\"u\" />";
	echo "</div>";
	echo "</div>";
}
?>

<?php
if($_SESSION['writewhat'] == 3) {
	$query = "SELECT * FROM Diary, OutputEntry WHERE EntryType = 3 AND Diary.EntryId = OutputEntry.EntryId AND OutputEntry.EntryId = " . $_SESSION['writeid'];
	$result = mysql_query($query);
	$row = mysql_fetch_array($result, MYSQL_BOTH);
	echo "<ul class=\"menu\">";
	echo "<li id=\"output\" class=\"active\">Output</li>";
	echo "</ul>";
	echo "<div class=\"content output\" style=\"display:Block;\">";
	echo "<div id=\"addentryform\">";
	echo "<b>Number of Diapers:</b> ";
	echo "<br />";
	echo "<select id=\"standardselect\" name=\"NumberDiapers\">";
	selectSelectedControlledVocabulary("NumberDiapers", $row['NumberDiapers']);
	echo "</select>";
	echo "<br />";
	echo "<br />";
	echo "<b>Urine Color:</b>";
	echo "<br />";
	echo "<select id=\"standardselect\" name=\"out-u-color\">";
	selectSelectedControlledVocabulary("out-u-color", $row['UrineColor']) ;
	echo "</select>";
	echo "<br />";
	echo "<b>Urine Saturation:</b>  ";
	echo "<br />";
	echo "<select id=\"standardselect\" name=\"out-u-saturation\">";
	selectSelectedControlledVocabulary("out-u-saturation", $row['UrineSaturation']);
	echo "</select>";
	echo "<br/>";
	echo "<br />";
	echo "<b>Stool Color:</b>     ";  
	echo "<br />";
	echo "<select id=\"standardselect\" name=\"out-s-color\">";
	selectSelectedControlledVocabulary("out-s-color", $row['OutputColor']) ;
	echo "</select>";
	echo "<br />";
	echo "<b>Stool Consistency:</b> ";
	echo "<br />";
	echo "<select id=\"standardselect\" name=\"out-s-consistency\">";
	selectSelectedControlledVocabulary("out-s-consistency", $row['OutputSaturation']);
	echo "</select>";
	echo "<br />";
	echo "<br />";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Modify Entry")."\" name=\"out\"  accesskey=\"u\" />";
	echo "<br/>";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Cancel")."\" name=\"cancel\"  accesskey=\"u\" />";
	echo "</div>";
	echo "</div>";
}
?>

<?php
if($_SESSION['writewhat'] == 4) {
	$query = "SELECT * FROM Diary, MorbidityEntry WHERE EntryType = 4 AND Diary.EntryId = MorbidityEntry.EntryId AND MorbidityEntry.EntryId = " . $_SESSION['writeid'];
	$result = mysql_query($query);
	$row = mysql_fetch_array($result, MYSQL_BOTH);
	echo "<ul class=\"menu\">";
	echo "<li id=\"morbidity\" class=\"active\">Health Issue</li>";
	echo "</ul>";
	echo "<div class=\"content morbidity\" style=\"display:Block;\">";
	echo "<div id=\"addentryform\">";
	echo "<b>Type:</b> ";
	echo "<br/>";
	echo "<select id=\"standardselect\" name=\"morb-type\">";
	selectSelectedControlledVocabulary("morb-type", $row['Type']) ;	
	echo "</select>";
	echo "<br/>";
	echo "<br/>";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Modify Entry")."\" name=\"morb\"  accesskey=\"u\" />";
	echo "<br/>";
	echo "<input id=\"addentrybutton\" type=\"submit\" value=\""._("Cancel")."\" name=\"cancel\"  accesskey=\"u\" />";	
	echo "</div>";
	echo "</div>";
}
?>

</form>
</div>

<br/>
<br/>
<div id="footer">
<b><a href="./m.logout.php"><?php echo _("Logout") ?></a></b>
<br/>
<br/>
<?php include('footer.php'); ?>
</div>

<br/>
<br/>

</div>

</body>


</html>
