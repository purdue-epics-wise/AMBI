<?php 

include_once("includes/general.php");
include_once("includes/db.php");

initialize();
loggedIn();
db_connect();
loadVocabulary();

if(isset($_POST['dateFrom']) && ereg ("[0-9]{2}/[0-9]{2}/[0-9]{4}", $_POST['dateFrom']))
{
	$_SESSION['dateFrom'] = modDate($_POST['dateFrom']);
	
}
else 
{
	$_SESSION['dateFrom'] = date("Y-m-d", strtotime("-1 week"));
}

if(isset($_POST['dateTo']) && ereg ("[0-9]{2}/[0-9]{2}/[0-9]{4}", $_POST['dateTo']))
{
	$_SESSION['dateTo'] = modDate($_POST['dateTo']);
}
else 
{
	$_SESSION['dateTo'] = date("Y-m-d");
}

if($_SESSION['dateFrom'] > $_SESSION['dateTo']) {
	$_SESSION['ViewMessage'] = "Date Error";
	$_SESSION['ViewDetails'] = "Date To has to be the same date or a date before Date From.";
	$_SESSION['ViewType'] = 3;
}
$date1 = modDate2($_SESSION['dateFrom']);
$date2 = modDate2($_SESSION['dateTo']);


?>


<head>
<?php head_tag("LACTOR - "._("View Diary")); ?>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="js/jqplot/excanvas.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="js/jqplot/jquery.jqplot.js"></script>
<script language="javascript" type="text/javascript" src="js/jqplot/plugins/jqplot.barRenderer.js"></script>
<script language="javascript" type="text/javascript" src="js/jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script language="javascript" type="text/javascript" src="js/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
<script language="javascript" type="text/javascript" src="js/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<link rel="stylesheet" type="text/css" href="js/jqplot/jquery.jqplot.css" />
</head>

<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php page_menu(PAGE_VIEW_DIARY); ?>

<div id="pagecontent">
<div id="registercontent">

<?php 
if(isset($_SESSION['writemessage'])) {
	if($_SESSION['writetype'] == 1) {

		$query = "SELECT * FROM Diary, BreastfeedEntry WHERE EntryType = 1 AND Diary.EntryId = BreastfeedEntry.EntryId AND BreastfeedEntry.EntryId = " . $_SESSION['writeid'];
		$result=mysql_query($query);
		$row = mysql_fetch_array($result);	
		
		$m  = "<form action='modify_entry.php' method='post'>";
		$m .= "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
		$m .= "<input name='entrydate' type='hidden' value='" . $row['EntryDate'] . "'/>";
		$m .= "<input name='duration' type='hidden' value='" . $row['BreastfeedingDuration'] . "'/>";
		$m .= "<input name='side' type='hidden' value='" . $row['Side'] . "'/>"; //
		$m .= "<input name='pumping-amount' type='hidden' value='" . $row['PumpingAmount'] . "'/>";//
		$m .= "<input name='latching' type='hidden' value='" . $row['Latching'] . "'/>";
		$m .= "<input name='pumping-method' type='hidden' value='" . $row['PumpingMethod'] . "'/>";
		$m .= "<input name='infant-state' type='hidden' value='" . $row['InfantState'] . "'/>";
		$m .= "<input name='maternal-problems' type='hidden' value='" . $row['MaternalProblems'] . "'/>";
		$m .= "<input name='breast' value='Modify' type='submit' />";
		$m .= "<input name='direction' value='add' type='hidden' />";
		$m .= "</form>";
		
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Date").":              " . getDateTime($row['EntryDate']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Duration").":          " . getVocab('duration', $row['BreastfeedingDuration']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Side").":              " . getVocab('Side', $row['Side']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Latching").":          " . getVocab('latching', $row['Latching']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Pumping Method").":    " . getVocab('pumping-method', $row['PumpingMethod']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Pumping Amount").":    " . getVocab('TotalAmount', $row['PumpingAmount']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Infant Alertness").":  " . getVocab('infant-state', $row['InfantState']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Maternal Problems").":" . getVocab('maternal-problems', $row['MaternalProblems']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']."                   " . $m . "<br/>";


	} else if ($_SESSION['writetype'] == 2) {
		$query="SELECT * FROM Diary, SupplementEntry WHERE EntryType = 2 AND Diary.EntryId = SupplementEntry.EntryId AND SupplementEntry.EntryId = " . $_SESSION['writeid'];
		$result=mysql_query($query);
		$row = mysql_fetch_array($result);



		$m  = "<form action='modify_entry.php' method='post'>";
		$m .= "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
		$m .= "<input name='entrydate' type='hidden' value='" . $row['EntryDate'] . "'/>";
		$m .= "<input name='NumberTimes' type='hidden' value='" . $row['NumberTimes'] . "'/>";
		$m .= "<input name='TotalAmount' type='hidden' value='" . $row['TotalAmount'] . "'/>";
		$m .= "<input name='sup-type' type='hidden' value='" . $row['SupType'] . "'/>";
		$m .= "<input name='sup-method' type='hidden' value='" . $row['SupMethod'] . "'/>";
		$m .= "<input name='sup' value='Modify' type='submit' />";
		$m .= "<input name='direction' value='add' type='hidden' />";
		$m .= "</form>";
		

		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Date").":           " . getDateTime($row['EntryDate']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Type").":           " . getVocab('sup-type', $row['SupType']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Method").":         " . getVocab('sup-method', $row['SupMethod']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Frequency").":         " . getVocab('NumberTimes', $row['NumberTimes']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails']._("Total Amount Today").":           " . getVocab('TotalAmount', $row['TotalAmount']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails'] . "                " . $m . "<br/>";

	} else if ($_SESSION['writetype'] == 3) {
		$query = "SELECT * FROM Diary, OutputEntry WHERE EntryType = 3 AND Diary.EntryId = OutputEntry.EntryId AND OutputEntry.EntryId = " . $_SESSION['writeid'];
		$result=mysql_query($query);
		$row = mysql_fetch_array($result);
		$m  = "<form action='modify_entry.php' method='post'>";
		$m .= "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
		$m .= "<input name='entrydate' type='hidden' value='" . $row['EntryDate'] . "'/>";
		$m .= "<input name='NumberDiapers' type='hidden' value='" . $row['NumberDiapers'] . "'/>";
		$m .= "<input name='out-u-color' type='hidden' value='" . $row['UrineColor'] . "'/>";
		$m .= "<input name='out-u-saturation' type='hidden' value='" . $row['UrineSaturation'] . "'/>";
		$m .= "<input name='out-s-color' type='hidden' value='" . $row['StoolColor'] . "'/>";
		$m .= "<input name='out-s-consistency' type='hidden' value='" . $row['StoolConsistency'] . "'/>";
		$m .= "<input name='out' value='Modify' type='submit' />";
		$m .= "<input name='direction' value='add' type='hidden' />";
		$m .= "</form>";
		
		
		$_SESSION['writedetails'] .= ""._("Date").":               " . getDateTime($row['EntryDate']) . "<br/>";
		$_SESSION['writedetails'] .= ""._("Number of Diapers").":  " . getVocab('NumberDiapers', $row['NumberDiapers']) . "<br/>";
		$_SESSION['writedetails'] .= ""._("Urine Color").":        " . getVocab('out-u-color', $row['UrineColor']) . "<br/>";
		$_SESSION['writedetails'] .= ""._("Urine Saturation").":   " . getVocab('out-u-saturation', $row['UrineSaturation']) . "<br/>";
		$_SESSION['writedetails'] .= ""._("Stool Color").":        " . getVocab('out-s-color', $row['StoolColor']) . "<br/>";
		$_SESSION['writedetails'] .= ""._("Stool Consistency").":  " . getVocab('out-s-consistency', $row['StoolConsistency']) . "<br/>";
		$_SESSION['writedetails'] .= $m . "<br/>";
	} else if ($_SESSION['writetype'] == 4) {
		$query = "SELECT * FROM Diary, MorbidityEntry WHERE EntryType = 4 AND Diary.EntryId = MorbidityEntry.EntryId AND MorbidityEntry.EntryId = " . $_SESSION['writeid'];
		$result=mysql_query($query);
		$row = mysql_fetch_array($result);
	
		$c = '#FFF';
		$m  = "<form action='modify_entry.php' method='post'>";
		$m .= "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
		$m .= "<input name='entrydate' type='hidden' value='" . $row['EntryDate'] . "'/>";
		$m .= "<input name='morb-type' type='hidden' value='" . $row['Type'] . "'/>";
		$m .= "<input name='morb' value='Modify' type='submit' />";
		$m .= "<input name='direction' value='add' type='hidden' />";
		$m .= "</form>";
		
		$_SESSION['writedetails'] = $_SESSION['writedetails'] . "Date:  " . getDateTime($row['EntryDate']) . "<br/>";
		$_SESSION['writedetails'] = $_SESSION['writedetails'] . "Type:  " . getVocab('morb-type', $row['Type']) . "<br/>";
		
	} else if ($_SESSION['writetype'] == 5) {
		$_SESSION['writedetails'] = $_SESSION['writecontent'];
	}
	
	
	unset($_SESSION['writecontent']);

}

if(isset($_SESSION['s_mid'])) {
	$_SESSION['Smessage']="Logged in as scientist.";$_SESSION['Stype']=2;$_SESSION['Sdetail']="";
	displayMessage('Smessage', 'Sdetail', 'Stype');
} 

displayNotification();

if (isset($_SESSION['writetype'])) {
  if($_SESSION['writetype'] == 5) {
    $_SESSION['writetype'] = 3;
    displayMessage('writemessage','writedetails', 'writetype');
  } else if($_SESSION['writetype'] >= 1 || @$_SESSION['writetype'] <= 4) {
    $_SESSION['writetype'] = 1;
    displayMessage('writemessage','writedetails', 'writetype');
  } else if($_SESSION['writetype'] == 6) {
    $_SESSION['writetype'] = 2;
    displayMessage('writemessage','writedetails', 'writetype');
  }
}

if(isset($_SESSION['ViewMessage'])) {
	displayMessage('ViewMessage','ViewDetails', 'ViewType');
}


?>

<div id="container">
<div class="tabs">
  <ul class="menu">
    <li id="time" class="active"><a href="#dates"><?php echo _("Date Range") ?></a></li>
  </ul>
  <div id="dates" class="content time">
  <div id="entrycontent">
  <div id="entryinput">
  <form id="standardform" name="testform" action="view_diary.php" method="post">

  <?php echo _("From") ?>: <input class='datepicker' type="text" value="<?php echo $date1; ?>" name="dateFrom" /> <?php echo _("To") ?>: <input class='datepicker' type="text" value=<?php echo "\"" . $date2 . "\""; ?> name="dateTo" /> <input  type="submit" value="<?php echo _("Search Diary") ?>"

  <?php 
  $query = "SELECT * FROM Diary, BreastfeedEntry WHERE EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' AND EntryType = 1 AND Diary.EntryId = BreastfeedEntry.EntryId AND mid = " . $_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
  $result=mysql_query($query);
  //if(mysql_num_rows($result)!=0)
  //{
  //	echo "<input type=\"submit\" id=\"graph\" value=\"View Graph\" style=\"color: blue; text-align: right; margin-left: 5em;\" name=\"viewgraph\" />";
  //}
  ?>
  </div>
  <input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />
  </div>

  </form>
  </div>
  </div>
</div>
<br />

<!-- temporary location? -->
<div id='chartdiv' style='width:90%;margin:auto;margin-bottom:1em;;height:200px;'></div>
<div style="clear:both;height:0"></div>
<!-- temporary location? -->

<div id="container">
<div class="tabs" id="entryData">
<ul class="menu">
<li><a href="#breastfeeding"><?php echo _("Breastfeed Diary") ?></a></li>
<li><a href="#pumping"><?php echo _("Pumping") ?></a></li>
<li><a href="#supplement"><?php echo _("Supplement Diary") ?></a></li>
<li><a href="#output"><?php echo _("Output Diary") ?></a></li>
<li><a href="#weight"><?php echo _("Infant Weight Diary") ?></a></li>
<li><a href="#health"><?php echo _("Health Issues Diary") ?></a></li>
</ul>

<?php
if(isset($_SESSION['sid'])) {
	$record = "INSERT INTO ScienceView VALUES (" . $_SESSION['s_mid'] . ", NOW(), " . $_SESSION['mid'] . ", '" . $_SESSION['dateFrom'] . " 00:00:00', '" . $_SESSION['dateTo'] . " 23:59:59');";
	mysql_query($record);
} ?>

<div id="breastfeeding" class="content breastfeeding">
<h1><img src="image/babyface.gif" width=48 height=48 alt=""/> <?php echo _("Breastfeeding Diary") ?></h1>

<?php
$query = "SELECT * FROM Diary, BreastfeedEntry WHERE PumpingMethod IS NULL AND EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' AND EntryType = 1 AND Diary.EntryId = BreastfeedEntry.EntryId AND mid = " . $_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
$result=mysql_query($query);
?>

<?php
if(mysql_num_rows($result) == 0) {
	echo "<h3>"._("No breastfeeding entries for date range").": " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . "</h3>";
} else {
echo "<table id='breast' class='diary' border='1' cellpadding='3' cellspacing='0'>";
echo "<thead>";
echo "<tr>";

echo "<th>"._("Date &amp; Time")."</th>";
echo "<th>"._("Duration")."</th>";
echo "<th>"._("Side")."</th>";
echo "<th>"._("Latching")."</th>";
echo "<th>"._("Infant Alertness")."</th>";
echo "<th>"._("Maternal Problems")."</th>";
echo "<th>"._("Modify")."</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";
														
while($row = mysql_fetch_array($result))
{

if(passed($row['EntryDate']) == 1) {
$c = 'old'; 
$m = "-";
}
else {
$c = '';
$m  = "<form action='modify_entry.php' method='post'>";
$m .= "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
$m .= "<input name='breast' value='Modify' type='submit' />";
$m .= "<input name='direction' value='view' type='hidden' />";
$m .= "</form>";
} 


echo "<tr class='$c'>";
echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
echo "<td>" . getVocab('duration', $row['BreastfeedingDuration']) . "</td>";
echo "<td>" . getVocab('Side', $row['Side']) . "</td>";
echo "<td>" . getVocab('latching', $row['Latching']) . "</td>";
echo "<td>" . getVocab('infant-state', $row['InfantState']) . "</td>";
echo "<td>" . getVocab('maternal-problems', $row['MaternalProblems']) . "</td>";
echo "<td>" . $m . "</td>";
echo "</tr>";
}

echo "</tbody>";
echo "</table>";
}
?>
<ul>
</div>

<div id="pumping" class="content pumping">
<h1><img src="image/babyface.gif" width=48 height=48 alt=""/><?php echo _("Pumping") ?></h1>

<?php
$query = "SELECT * FROM Diary, BreastfeedEntry WHERE Latching IS NULL AND EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' AND EntryType = 1 AND Diary.EntryId = BreastfeedEntry.EntryId AND mid = " . $_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
$result=mysql_query($query);
?>

<?php
if(mysql_num_rows($result) == 0) {
	echo "<h3>"._("No breastfeeding entries for date range").": " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . "</h3>";
} else {
echo "<table id='breast' class='diary' border='1' cellpadding='3' cellspacing='0'>";
echo "<thead>";
echo "<tr>";

echo "<th>"._("Date &amp; Time")."</th>";
echo "<th>"._("Duration")."</th>";
echo "<th>"._("Pumping Method")."</th>";
echo "<th>"._("Pumping Amount")."</th>";
echo "<th>"._("Modify")."</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";
														
while($row = mysql_fetch_array($result))
{

if(passed($row['EntryDate']) == 1) {
$c = 'old'; 
$m = "-";
}
else {
$c = '';
$m  = "<form action='modify_entry.php' method='post'>";
$m .= "<input name='entryid' type='hidden' value='".$row['EntryId']."'/>";
$m .= "<input name='pumping' value='Modify' type='submit' />";
$m .= "</form>";
} 


echo "<tr class='$c'>";
echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
echo "<td>" . getVocab('duration', $row['BreastfeedingDuration']) . "</td>";
echo "<td>" . getVocab('pumping-method', $row['PumpingMethod']) . "</td>";
echo "<td>" . getVocab('TotalAmount', $row['PumpingAmount']) . "</td>";
echo "<td>" . $m . "</td>";
echo "</tr>";
}

echo "</tbody>";
echo "</table>";
}
?>
<ul>
</div>

<div id="supplement" class="content supplement">
<h1><img src="image/supplement.gif" width=48 height=48 alt=""/> <?php echo _("Supplement Diary") ?></h1>
<ul>
<?php
$query="SELECT * FROM Diary, SupplementEntry WHERE EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' AND EntryType = 2 AND Diary.EntryId = SupplementEntry.EntryId AND mid = " . $_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
$result=mysql_query($query);
if(mysql_num_rows($result) == 0) {
	echo "<h3>"._("No supplement entries for date range").": " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . "</h3>";
} else {
echo "<table id='sup' class='diary' border='1' cellpadding='3' cellspacing='0'>";
echo "<thead>";
echo "<tr>";

echo "<th>"._("Date &amp; Time")."</th>";
echo "<th>"._("Frequency")."</th>";
echo "<th>"._("Total Amount")."</th>";
echo "<th>"._("Type")."</th>";
echo "<th>"._("Method")."</th>";	
echo "<th>"._("Modify")."</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";

while($row = mysql_fetch_array($result))
{
if(passed($row['EntryDate']) == 1) {
$c = 'old'; 
$m = "-";
}
else {
$c = '';
$m  = "<form action='modify_entry.php' method='post'>";
$m .= "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
$m .= "<input name='sup' value='Modify' type='submit' />";
$m .= "<input name='direction' value='view' type='hidden' />";
$m .= "</form>";
}  


echo "<tr class='$c'>";
echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
echo "<td>" . getVocab('NumberTimes', $row['NumberTimes']) . "</td>";
echo "<td>" . getVocab('TotalAmount', $row['TotalAmount']) . "</td>";
echo "<td>" . getVocab('sup-type', $row['SupType']) . "</td>";
echo "<td>" . getVocab('sup-method', $row['SupMethod']) . "</td>";
echo "<td>" . $m . "</td>";
echo "</tr>";
}			

echo "</tbody>";
echo "</table>";
}
?>
<ul>
</div>

<div id="output" class="content output">
<h1><img src="image/output.gif" width=48 height=48 alt=""/> <?php echo _("Output Diary") ?></h1>
<ul>
<?php
$query = "SELECT * FROM Diary, OutputEntry WHERE EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' AND EntryType = 3 AND Diary.EntryId = OutputEntry.EntryId AND mid = " . $_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
$result=mysql_query($query);
if(mysql_num_rows($result) == 0) {
	echo "<h3>"._("No output entries for date range").": " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . "</h3>";
} else {

echo "<table id='out' class='diary' border='1' cellpadding='3' cellspacing='0'>";
echo "<thead>";
echo "<tr>";

echo "<th>"._("Date &amp; Time")."</th>";
echo "<th>"._("Number of Diapers")."</th>";
echo "<th>"._("Urine Color")."</th>";
echo "<th>"._("Urine Saturation")."</th>";	
echo "<th>"._("Stool Color")."</th>";
echo "<th>"._("Stool Consistency")."</th>";	
echo "<th>"._("Modify")."</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";

while($row = mysql_fetch_array($result))
{
if(passed($row['EntryDate']) == 1) {
$c = 'old'; 
$m = "-";
}
else {
$c = '';
$m  = "<form action='modify_entry.php' method='post'>";
$m .= "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
$m .= "<input name='out' value='Modify' type='submit' />";
$m .= "<input name='direction' value='view' type='hidden' />";
$m .= "</form>";
}  


echo "<tr class='$c'>";
echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
echo "<td>" . getVocab('NumberDiapers', $row['NumberDiapers']) . "</td>";
echo "<td>" . getVocab('out-u-color', $row['UrineColor']) . "</td>";
echo "<td>" . getVocab('out-u-saturation', $row['UrineSaturation']) . "</td>";
echo "<td>" . getVocab('out-s-color', $row['StoolColor']) . "</td>";
echo "<td>" . getVocab('out-s-consistency', $row['StoolConsistency']) . "</td>";
echo "<td>" . $m . "</td>";
echo "</tr>";
}


echo "</tbody>";
echo "</table>";
}
?>
<ul>
</div>

<div id="weight" class="content weight">
<h1><img src="image/output.gif" width=48 height=48 alt=""/> <?php echo _("Infant Weight Diary") ?></h1>
<ul>
<?php
$query = "SELECT * FROM Weight WHERE EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' AND mid = " . $_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
$result=mysql_query($query);
if(mysql_num_rows($result) == 0) {
	echo "<h3>"._("No health issue entries for date range").": " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . "</h3>";
} else {
echo "<table id='weight' class='diary' border='1' cellpadding='3' cellspacing='0'>";
echo "<thead>";
echo "<tr>";

echo "<th>"._("Date &amp; Time")."</th>";
echo "<th>"._("Weight")."</th>";	
echo "<th>"._("Modify")."</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";

while($row = mysql_fetch_array($result))
{
if(passed($row['EntryDate']) == 1) {
$c = 'old'; 
$m = "-";
}
else {
$c = '';
$m  = "<form action='modify_entry.php' method='post'>";
$m .= "<input name='entryid' type='hidden' value='".$row['EntryId']."'/>";
$m .= "<input name='morb' value='Modify' type='submit' />";
$m .= "<input name='direction' value='view' type='hidden' />";
$m .= "</form>";
}  


echo "<tr class='$c'>";
echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
echo "<td>". (int)($row['weight'] / 16) . "lb. ". ($row['weight'] % 16) . "oz.</td>";
echo "<td>" . $m . "</td>";
echo "</tr>";
}			

echo "</tbody>";
echo "</table>";
}
?>
<ul>
</div>
<div id="health" class="content morbidity">
<h1><img src="image/morbidity.gif" width=48 height=48 alt=""/> <?php echo _("Health Issue Diaries") ?></h1>
<ul>
<?php
$query = "SELECT * FROM Diary, MorbidityEntry WHERE EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' AND EntryType = 4 AND Diary.EntryId = MorbidityEntry.EntryId AND mid = " . $_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
$result=mysql_query($query);
if(mysql_num_rows($result) == 0) {
	echo "<h3>"._("No health issue entries for date range").": " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . "</h3>";
} else {
echo "<table id='morb' class='diary' border='1' cellpadding='3' cellspacing='0'>";
echo "<thead>";
echo "<tr>";

echo "<th>"._("Date &amp; Time")."</th>";
echo "<th>"._("Type")."</th>";	
echo "<th>"._("Modify")."</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";

while($row = mysql_fetch_array($result))
{
if(passed($row['EntryDate']) == 1) {
$c = 'old'; 
$m = "-";
}
else {
$c = '';
$m  = "<form action='modify_entry.php' method='post'>";
$m .= "<input name='entryid' type='hidden' value='".$row['EntryId']."'/>";
$m .= "<input name='morb' value='Modify' type='submit' />";
$m .= "<input name='direction' value='view' type='hidden' />";
$m .= "</form>";
}  


echo "<tr class='$c'>";
echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
echo "<td>". getVocab('morb-type', $row['Type']) . "</td>";
echo "<td>" . $m . "</td>";
echo "</tr>";
}			

echo "</tbody>";
echo "</table>";
}
?>
<ul>
</div>

<?php //echo strtotime(date("Y-m-d")) . " " . "";  ?>

</div>
</div>
</div>	
</div>
	
		
<?php page_footer(); ?>

</div>
<script type="text/javascript">
$( function( ) {
  $('.datepicker').datepicker( );
  graph($('#entryData .ui-tabs-selected a').attr('href').substr(1));

  // listen for tab clicks
	$('#entryData > ul > li > a').click( function( ) {
    location.hash = $(this).attr('href');
    graph(location.hash.substr(1));
	});
//  $('a[href="#'+location.hash+'"]').click( );
});

function graph( type ) {
  $.ajax({
    url:'services/diary_count.php?type=' + type,
    dataType: 'json',
    success: function(response) {
      data = response;
      $('#chartdiv').empty();
      $.jqplot('chartdiv',  [response.count], {
//        series:[{renderer:$.jqplot.BarRenderer}],
        title: response.title,
        axes: {
          xaxis: {
            min: 0,
            ticks: response.dates,
            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
            renderer:$.jqplot.DateAxisRenderer,
            tickOptions: {
              formatString:'%b %#d, %y',
              angle: -90
            }
          },
          yaxis: { 
            min: 0,
            tickInterval: 1,
            tickOptions: {
              formatString:'%d',
            }
          }
        }
      });
    },
    error: function(jqxhr, status, error) {
      console.log(error);
    }
  });
}
</script>
</body>
</html>

