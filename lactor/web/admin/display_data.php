<?php 
include_once("../includes/general.php");
include_once("../includes/db.php");

loggedIn();
db_connect();

loadVocabulary();

if(isset($_POST['noti'])) {
	$_SESSION['opt6']=1;
	$_SESSION['allmids']=$_POST['mid'];
	go('notifications.php');
}

if(isset($_POST['dateFrom']) && ereg ("[0-9]{2}/[0-9]{2}/[0-9]{4}", $_POST['dateFrom']))
{
	$_SESSION['dateFrom'] = modDate($_POST['dateFrom']);
	
}
else 
{
	$_SESSION['dateFrom'] = date("Y-m-d");
}

if(isset($_POST['dateTo']) && ereg ("[0-9]{2}/[0-9]{2}/[0-9]{4}", $_POST['dateTo']))
{
	$_SESSION['dateTo'] = modDate($_POST['dateTo']);
}
else 
{
	$_SESSION['dateTo'] = date("Y-m-d");
}
//echo $_SESSION['dateFrom'] . " " . $_SESSION['dateTo'];

$date1 = modDate2($_SESSION['dateFrom']);
$date2 = modDate2($_SESSION['dateTo']);

if(isset($_POST['download'])) {
	$app = "";
	if(isset($_POST['option2'])) {
		$app = $app . "1";
	} else {
		$app = $app . "0";
	}
	if(isset($_POST['option3'])) {
		$app = $app . "1";
	} else {
		$app = $app . "0";
	}
	if(isset($_POST['option4'])) {
		$app = $app . "1";
	} else {
		$app = $app . "0";
	}
	if(isset($_POST['option5'])) {
		$app = $app . "1";
	} else {
		$app = $app . "0";
	}
	$query = "select * from Mothers;";
	$result = mysql_query($query);
	$c = ""; 
	while($row=mysql_fetch_array($result)) {
		for($i = 0; $i < $_SESSION['nmid']; $i++) {
			if ($_POST['mid'][$i] == $row['mid']) {
				$c = $c . "-" . $row['mid'];
			}
		}
	}
	
	$record = "INSERT INTO ScienceDownloads VALUES(" . $_SESSION['sid'] . ", NOW(), '" . $c . "', '" . $_SESSION['dateFrom'] . " 00:00:00', '" .  $_SESSION['dateTo'] . " 00:00:00', " . "1, '" . $app . "');";
	mysql_query($record);
	
	$filename="ent" . $_SESSION["sid"] . "-" . date("Y-m-d-G-i-s") . "-" . $_SESSION['dateFrom'] . "-" . $_SESSION['dateTo'] . "-" . $app . "" . $c . ".csv";
	
	$where = " EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' ";

	$count = 0;
	for($i = 0; $i < $_SESSION['nmid']; $i++) {
		if(isset($_POST['mid'][$i])) {
			if($count == 0) {
				$where = $where . " AND (Diary.mid=" . $_POST['mid'][$i] . " ";
			} else {
				$where = $where . " OR Diary.mid=" . $_POST['mid'][$i] . " ";
			}
			$count++;
		}
	}	
	if($count != 0) {
		$where = $where . ") ";
	}
	$where .= " ORDER BY Diary.mid ASC, EntryDate DESC;";
	
	header('Content-type: text/csv');
	header("Content-Disposition: attachment; filename=".$filename);

	echo "Entries\r\n";
	echo "From," . $_SESSION['dateFrom'] . ",To," . $_SESSION['dateTo'] . "\r\n\r\n";
	
	
	if(isset($_POST['option2'])){
		echo "\r\n\r\nBreastfeeding\r\n";
		echo "Name,Email,Date,Duration,Side,Latching,Pumping Method,Pumping Amount,Infant Alertness,Maternal Problems\r\n";
		$query = "SELECT * FROM Diary,BreastfeedEntry,Mothers 
		          LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
							WHERE 
							EntryType = 1 AND 
							Diary.mid = Mothers.mid AND 
							Diary.EntryId = BreastfeedEntry.EntryId AND $where";
		$result=mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			printf( "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\r\n",
				$row['Name'], $row['email'], getDateTime($row['EntryDate']), 
				getVocab('duration', $row['BreastfeedingDuration']),
				getVocab('Side', $row['Side']),
				getVocab('latching', $row['Latching']),
				getVocab('pumping-method', $row['PumpingMethod']),
				getVocab('TotalAmount', $row['PumpingAmount']),
				getVocab('infant-state', $row['InfantState']),
				getVocab('maternal-problems', $row['MaternalProblems']));
		} 

	}
	
	if(isset($_POST['option3'])){
		echo "\r\n\r\nSupplement\r\n";
		echo "Name,Email,Date,Total Amount,Number of Times,Type,Method\r\n";
		$query = "SELECT * FROM Diary, SupplementEntry, Mothers 
		          LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
		          WHERE 
							EntryType = 2 AND 
							Diary.mid = Mothers.mid AND 
							Diary.EntryId = SupplementEntry.EntryId AND $where";
		$result=mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
	
			printf( "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\r\n",
			        $row['Name'], $row['email'], getDateTime($row['EntryDate']),
							getVocab('TotalAmount', $row['TotalAmount']),
							getVocab('NumberTimes', $row['NumberTimes']),
							getVocab('sup-type', $row['SupType']),
							getVocab('sup-method', $row['SupMethod']));
		} 

	}
	
	if(isset($_POST['option4'])){
		echo "\r\n\r\nOutput\r\n";
		echo "Name,Email,Date,Number of Diapers,Urine Color,Urine Saturation,Stool Color,Stool Consistency\r\n";
		$query = "SELECT * FROM Diary, OutputEntry, Mothers 
		          LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
							WHERE 
							EntryType = 3 AND 
							Diary.mid = Mothers.mid AND 
							Diary.EntryId = OutputEntry.EntryId AND $where ";
		$result=mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
	
			printf( "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\r\n",
			        $row['Name'], $row['email'], getDateTime($row['EntryDate']),
							getVocab('NumberDiapers', $row['NumberDiapers']),
							getVocab('out-u-color', $row['UrineColor']),
							getVocab('out-u-saturation', $row['UrineSaturation']),
							getVocab('out-s-color', $row['StoolColor']),
							getVocab('out-s-consistency', $row['StoolConsistency']));
		} 

	}
	
	if(isset($_POST['option5'])){
		echo "\r\n\r\nMorbidity\r\n";
		echo "Name,Email,Date,Type\r\n";
		$query = "SELECT * FROM 
		          Diary, MorbidityEntry, Mothers 
		          LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
							WHERE 
							EntryType = 4 AND 
							Diary.mid = Mothers.mid AND 
							Diary.EntryId = MorbidityEntry.EntryId AND $where";
		$result=mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			printf( "\"%s\",\"%s\",\"%s\",\"%s\"\r\n",
			        $row['Name'], $row['email'], getDateTime($row['EntryDate']),
			        getVocab('morb-type', $row['Type']));
		} 

	}
	
	exit;
}

initialize();
$mothers = getMotherInfo( );

?>

<head>
<?php head_tag("Admin LACTOR - "._("Display Data")); ?>
</head>


<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php admin_menu(1); ?>

<div id="pagecontent">

<div id="registercontent">

<div id="container">
<div class='tabs'>
  <ul class="menu">
  <li><a href='#displayData'><?php echo _("Display Data") ?></a></li>
  </ul>
  <div id='displayData'>
  <h1><img src="image/Search.png" width=32 height=32 alt=""/> <?php echo _("Diary Query Values") ?></h1>
  <ul>
  <form name="feedback" method="post" action="admin/display_data.php">
  <?php echo _("Events") ?>: <input type="checkbox" onclick="selectEvents( this )"><?php echo _("All") ?> <input type="checkbox" <?php if(isset($_POST['option2'])) echo " checked "; ?> name="option2" value="BreastfeedingEntry"> <?php echo _("Breastfeeding") ?> <input type="checkbox" <?php if(isset($_POST['option3'])) echo " checked "; ?>   name="option3" value="SupplementEntry"> <?php echo _("Supplement") ?> <input type="checkbox" name="option4" <?php if(isset($_POST['option4'])) echo " checked "; ?>  value="OutputEntry"> <?php echo _("Output") ?> <input type="checkbox" name="option5"<?php if(isset($_POST['option5'])) echo " checked "; ?>  value="MorbidityEntry"> <?php echo _("Health Issues") ?>
  <br />
  <pre><?php echo _("From") ?>: <input type="text" value=<?php echo "\"" . $date1 . "\""; ?> name="dateFrom" class='datepicker'/><script language="JavaScript">new tcal ({'formname': 'feedback','controlname': 'dateFrom'});</script>  To: <input type="text" value=<?php echo "\"" . $date2 . "\""; ?> name="dateTo" class='datepicker'/><script language="JavaScript">new tcal ({'formname': 'feedback','controlname': 'dateTo'});</script> </pre>
  <br />
  <pre><?php echo _("Mother") ?>:    <select multiple size="5" name="mid[]"> 
      <?php 		
      
      foreach( $mothers as $mother ) {
        printf( "<option value='%s', %s>%s (%s)</option>", $mother['mid'], 
                ($_POST['mid'][1] == $mother['mid']) ? "selected" : "", $mother['Name'],
                $mother['email'] );
      }
      ?> </select>
  </pre>
  <pre>                  <input type="submit" name="query" value="<?php echo _("Search") ?>">     <input type="submit" name="download" value="<?php echo _("Download") ?>"></pre>
  </form>
  <ul>
</div>
</div>


<?php 
	$where = " EntryDate BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' ";

	$count = 0;
	for($i = 0; $i < @$_SESSION['nmid']; $i++) {
		if(isset($_POST['mid'][$i])) {
			if($count == 0) {
				$where = $where . " AND (Diary.mid=" . $_POST['mid'][$i] . " ";
			} else {
				$where = $where . " OR Diary.mid=" . $_POST['mid'][$i] . " ";
			}
			$count++;
		}
	}	
	if($count != 0) {
		$where = $where . ") ";
	}
	$where .= " ORDER BY Diary.mid ASC, EntryDate DESC;";
?>


<?php

if(@$_POST['option2'] == "BreastfeedingEntry") {
	$query = "SELECT * FROM Diary,BreastfeedEntry,Mothers 
						LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
						WHERE 
						EntryType = 1 AND 
						Diary.mid = Mothers.mid AND 
						Diary.EntryId = BreastfeedEntry.EntryId AND $where";
	$result=mysql_query($query);
	echo "<div id=\"container\">";
	echo "<ul class=\"menu\">";
	echo "<li id=\"breastfeeding\" class=\"active\">"._("Breastfeeding")."</li>";
	echo "</ul>";
	echo "<span class=\"clear\"></span>";
	echo "<div class=\"content breastfeeding\">";
	if(mysql_num_rows($result) == 0) {
		echo "<br /><h3>No breastfeeding entries for date range: " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . " for the selected users</h3>";
	} else {

		echo "<h1><img src=\"../image/babyface.gif\" width=\"48\" height=\"48\" alt=\"\"/> Breastfeeding Entries</h1>";
		echo "<ul>";
		echo "<table id=\"breast\">";
		echo "<thead>";
		echo "<tr>";
		echo "<td>"._("Name")."</td>";
		echo "<td>"._("Email")."</td>";
		echo "<td>"._("Date &amp; Time")."</td>";
		echo "<td>"._("Duration")."</td>";
		echo "<td>"._("Side")."</td>";
		echo "<td>"._("Latching")."</td>";
		echo "<td>"._("Pumping Method")."</td>";
		echo "<td>"._("Pumping Amount")."</td>";
		echo "<td>"._("Infant Alertness")."</td>";
		echo "<td>"._("Maternal Problems")."</td>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		
		

		 
		$m = 0;
		$c = 0;
		$bgcol = array("ff", "ff", "ff");$bgcolfin = "#DDDDDD";
		while($row = mysql_fetch_array($result))
		{
			if($m != $row['mid']) {
				$m = $row['mid'];
				if($bgcolfin == "#FFFFFF") {
					$bgcolfin = "#DDDDDD";
				} else {
					$bgcolfin = "#FFFFFF";
				}
			}
		
			echo "<tr BGCOLOR=\"" . $bgcolfin . "\">";
			echo "<td>" . $row['Name'] . "</td>";
			echo "<td>" . $row['email'] . "</td>";
			echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
			echo "<td>" . getVocab('duration', $row['BreastfeedingDuration']) . "</td>";
			echo "<td>" . getVocab('Side', $row['Side']) . "</td>";
			echo "<td>" . getVocab('latching', $row['Latching']) . "</td>";
			echo "<td>" . getVocab('pumping-method', $row['PumpingMethod']) . "</td>";
			echo "<td>" . getVocab('TotalAmount', $row['PumpingAmount']) . "</td>";
			echo "<td>" . getVocab('infant-state', $row['InfantState']) . "</td>";
			echo "<td>" . getVocab('maternal-problems', $row['MaternalProblems']) . "</td>";
			echo "</tr>";
		
		} 

		echo "</tbody>";
		echo "</table>";
		echo "<ul>";
	}
	echo "</div>";
	echo "</div>";
} 



if(@$_POST['option3'] == "SupplementEntry") {
	$query = "SELECT * FROM Diary, SupplementEntry, Mothers 
						LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
						WHERE 
						EntryType = 2 AND 
						Diary.mid = Mothers.mid AND 
						Diary.EntryId = SupplementEntry.EntryId AND $where";
	$result=mysql_query($query);
	echo "<div id=\"container\">";
	echo "<ul class=\"menu\">";
	echo "<li id=\"breastfeeding\" class=\"active\">Supplements</li>";
	echo "</ul>";
	echo "<span class=\"clear\"></span>";
	echo "<div class=\"content breastfeeding\">";
	if(mysql_num_rows($result) == 0) {
	echo "<br /><h3>No supplement entries for date range: " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . " for the selected users</h3>";
	} else {
	echo "<h1><img src=\"../image/supplement.gif\" alt=\"\"/> Supplement Entries</h1>";
	echo "<ul>";
	echo "<table id=\"breast\">";
	echo "<thead>";
	echo "<tr>";
	echo "<td>"._("Name")."</td>";
	echo "<td>"._("Email")."</td>";
	echo "<td>"._("Date &amp; Time")."</td>";
	echo "<td>"._("Total")."</td>";
	echo "<td>"._("Frequency")."</td>";
	echo "<td>"._("Type")."</td>";
	echo "<td>"._("Method")."</td>";	
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	

	 
	$m = 0;
	$c = 0;
	$bgcol = array("ff", "ff", "ff");$bgcolfin = "#DDDDDD";
	while($row = mysql_fetch_array($result))
	{
		if($m != $row['mid']) {
			$m = $row['mid'];
			if($bgcolfin == "#FFFFFF") {
				$bgcolfin = "#DDDDDD";
			} else {
				$bgcolfin = "#FFFFFF";
			}
		}
	
	echo "<tr BGCOLOR=\"" . $bgcolfin . "\">";
	echo "<td>" . $row['Name'] . "</td>";
	echo "<td>" . $row['email'] . "</td>";
	echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
	echo "<td>" . getVocab('TotalAmount', $row['TotalAmount']) . "</td>";
	echo "<td>" . getVocab('NumberTimes', $row['NumberTimes']) . "</td>";
	echo "<td>" . getVocab('sup-type', $row['SupType']) . "</td>";
	echo "<td>" . getVocab('sup-method', $row['SupMethod']) . "</td>";
	echo "</tr>";
	
	} 

	echo "</tbody>";
	echo "</table>";
	echo "<ul>";
	}
	echo "</div>";
	echo "</div>";
} 

if(@$_POST['option4'] == "OutputEntry") {
	$query = "SELECT * FROM Diary, OutputEntry, Mothers 
						LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
						WHERE 
						EntryType = 3 AND 
						Diary.mid = Mothers.mid AND 
						Diary.EntryId = OutputEntry.EntryId AND $where ";
	$result=mysql_query($query);
	echo "<div id=\"container\">";
	echo "<ul class=\"menu\">";
	echo "<li id=\"breastfeeding\" class=\"active\">Output</li>";
	echo "</ul>";
	echo "<span class=\"clear\"></span>";
	echo "<div class=\"content breastfeeding\">";
	if(mysql_num_rows($result) == 0) {
	echo "<br /><h3>No output entries for date range: " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . " for the selected users</h3>";
	} else {
	echo "<h1><img src=\"../image/output.gif\" alt=\"\"/> Output Entries</h1>";
	echo "<ul>";
	echo "<table id=\"breast\">";
	echo "<thead>";
	echo "<tr>";
	echo "<td>"._("Name")."</td>";
	echo "<td>"._("Email")."</td>";
	echo "<td>"._("Date &amp; Time")."</td>";
	echo "<td>"._("Number of Diapers")."</td>";
	echo "<td>"._("Urine Color")."</td>";
	echo "<td>"._("Urine Saturation")."</td>";	
	echo "<td>"._("Stool Color")."</td>";
	echo "<td>"._("Stool Consistency")."</td>";	
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	
	
	 
	$m = 0;
	$c = 0;
	$bgcol = array("ff", "ff", "ff");
	$bgcolfin = "#DDDDDD";
	while($row = mysql_fetch_array($result))
	{
		if($m != $row['mid']) {
			$m = $row['mid'];
			if($bgcolfin == "#FFFFFF") {
				$bgcolfin = "#DDDDDD";
			} else {
				$bgcolfin = "#FFFFFF";
			}
		}
	
	echo "<tr BGCOLOR=\"" . $bgcolfin . "\">";
	echo "<td>" . $row['Name'] . "</td>";
	echo "<td>" . $row['email'] . "</td>";
	echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
	echo "<td>" . getVocab('NumberDiapers', $row['NumberDiapers']) . "</td>";
	echo "<td>" . getVocab('out-u-color', $row['UrineColor']) . "</td>";
	echo "<td>" . getVocab('out-u-saturation', $row['UrineSaturation']) . "</td>";
	echo "<td>" . getVocab('out-s-color', $row['StoolColor']) . "</td>";
	echo "<td>" . getVocab('out-s-consistency', $row['StoolConsistency']) . "</td>";
	echo "</tr>";
	
	} 

	echo "</tbody>";
	echo "</table>";
	echo "<ul>";
	}
	echo "</div>";
	echo "</div>";
} 

if(@$_POST['option5'] == "MorbidityEntry") {
	$query = "SELECT * FROM 
						Diary, MorbidityEntry, Mothers 
						LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
						WHERE 
						EntryType = 4 AND 
						Diary.mid = Mothers.mid AND 
						Diary.EntryId = MorbidityEntry.EntryId AND $where";
	$result=mysql_query($query);
	
	echo "<div id=\"container\">";
	echo "<ul class=\"menu\">";
	echo "<li id=\"breastfeeding\" class=\"active\">Health Issues</li>";
	echo "</ul>";
	echo "<span class=\"clear\"></span>";
	echo "<div class=\"content breastfeeding\">";
	if(mysql_num_rows($result) == 0) {
	echo "<br /><h3>No health issues entries for date range: " . modDate2($_SESSION['dateFrom']) . " to " . modDate2($_SESSION['dateTo']) . " for the selected users</h3>";
	} else {	
	echo "<h1><img src=\"../image/morbidity.gif\" alt=\"\"/> Health Issues Entries</h1>";
	echo "<ul>";
	echo "<table id=\"breast\">";
	echo "<thead>";
	echo "<tr>";
	echo "<td>"._("Name")."</td>";
	echo "<td>"._("Email")."</td>";
	echo "<td>"._("Date &amp; Time")."</td>";
	echo "<td>"._("Type")."</td>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	

	 
	$m = 0;
	$c = 0;
	$bgcol = array("ff", "ff", "ff");
	$bgcolfin = "#DDDDDD";
	while($row = mysql_fetch_array($result))
	{
		if($m != $row['mid']) {
			$m = $row['mid'];
			if($bgcolfin == "#FFFFFF") {
				$bgcolfin = "#DDDDDD";
			} else {
				$bgcolfin = "#FFFFFF";
			}
		}
	
	echo "<tr BGCOLOR=\"" . $bgcolfin . "\">";
	echo "<td>" . $row['Name'] . "</td>";
	echo "<td>" . $row['email'] . "</td>";
	echo "<td>" . getDateTime($row['EntryDate']) . "</td>";
	echo "<td>". getVocab('morb-type', $row['Type']) . "</td>";
	echo "</tr>";
	
	} 

	echo "</tbody>";
	echo "</table>";
	echo "<ul>";
	}
	echo "</div>";
	echo "</div>";
} 


?>

<script type="text/javascript" src="js/tabs.js"></script>

<input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />

</div>
</div>
</div>


<?php page_footer(); ?>
</div>

</div>
<script type="text/javascript"><!--
function selectEvents( box ) {
	$( 'pre.events input[type="checkbox"]' ).attr( 'checked', box.checked );	
}
$(function( ) {
  $('.datepicker').datepicker( );
});
//--></script>
</body>
</html>
