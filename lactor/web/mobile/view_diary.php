<?php

session_start();

include_once("./mobile.include.php");
include_once("../includes/db.php");
include_once("../includes/constants.php");
include_once("../includes/general.php");
$conn = db_connect( );
loadVocabulary( );

if(!isset($_SESSION['mid'])) {
	$_SESSION['LoginMessage'] = "Session expired.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
}


if (!$conn) {
	die('Could not connect');
}

mysql_select_db("Breast1", $conn);
$_SESSION['db_con'] = $conn;

?>


<html>
<head>
<title>View Diary</title>
<?php include('head.php'); ?>
</head>
<?php

$query = "SELECT * FROM Notifications WHERE mid = " . $_SESSION['mid'] . ";";
$counter = 0;
$result = mysql_query($query);

while($row = mysql_fetch_array($result)) {
	if($row['status'] != 2 && $row['status'] != 8) {
		$counter = $counter + 1;
	}
	if($row['status'] == 0) {
		if($row['ntype'] == 1) {
			$title="Latching Difficulty";
			$body = "You have recently experienced difficulty with your baby latching. Please do the following: <br/>
        1. Pump every 3 hours<br/>
        2. Contact local lactation consultant.<br/>
        3. Provide jaw support during feeding.";			
		}
		else if($row['ntype'] == 2) {
			$title="Sleep Difficulty";
			$body = "Your baby has been difficult to wake for breastfeeding lately. Please do the following: <br/>1. Wake the baby every 3 hours. <br/>2. Skin-to-skin care for 30 min. <br/>3. Provide jaw support during feeding. <br/> 4. Pump after each feeding (every 3 hours).<br/>";			
	
		}
    $consultant_query = sprintf("SELECT S.email FROM Mothers M LEFT JOIN Mothers_Scientists MS ON MS.mid=M.mid LEFT JOIN Scientists S ON S.sid=MS.sid WHERE M.mid=%s;",
                     $_SESSION['mid']);
    $consultant_result = mysql_query( $consultant_query );
    if ( !$consultant_result ) {
      error_log(mysql_error( ));
    } else if (!mysql_num_rows($consultant_result)) {
      error_log('Unable to determine consultant for Mother '.$_SESSION['email']);
    } else {
      $title="Notification issued to " . $_SESSION['email'] . " .";
      if($row['ntype'] == 1) {
        $body = "Latching ";			
      }
      else if($row['ntype'] == 2) {
        $body = "Sleepy ";			
      }			
      $body = "<h2>Notification issued to mother " . $_SESSION['email'] . "</h2><br />".$body;
      $consultant_row = mysql_fetch_assoc($consultant_result);
      $mail = generateNotMail($title,$consultant_row['email'], $body);
    }
	}
} 
?>
<body>
<div data-role='page'>
<?php include('header.php'); ?>
<div data-role='content'>
<h1><?php echo _("View Diary") ?></h1>
<form class="long" name="addentry" method="post" action="./m.add_entry.php">

<div data-role='collapsible-set'>
	<div data-role='collapsible'>
	<h3><?php echo _("Breastfeeding") ?></h3>
		<?php

		$dateFrom = new DateTime(( isset( $_POST[ 'dateFrom' ]) ? $_POST[ 'dateFrom' ] : "30 days ago" ));
		$dateTo   = new DateTime(( isset( $_POST[ 'dateTo'   ]) ? $_POST[ 'dateTo'   ] : "now" ));

		$query = "SELECT * FROM Diary, BreastfeedEntry WHERE EntryDate BETWEEN '". 
			$dateFrom->format( 'Y-m-d' ) . " 00:00:00' AND '" . $dateTo->format( 'Y-m-d' ) .
			" 23:59:59' AND EntryType = ".ENTRYTYPE_BREASTFEEDING." AND Diary.EntryId = BreastfeedEntry.EntryId AND mid = " . $_SESSION['mid'] . 
			" AND PumpingMethod IS NULL ORDER BY EntryDate DESC, InputDate DESC";
		$result=mysql_query($query);
		if(mysql_num_rows($result) == 0) {
			echo "<h3>"._("No breastfeeding entries for date range").": " . $dateFrom->format( DATE_FORMAT ) . " to " . $dateTo->format( DATE_FORMAT ) . "</h3>";
		} else {
			echo "<table border='1' cellpadding='3' cellspacing='0' id='breast'>";
			echo "<thead>";
			echo "<tr>";

			echo "<td>"._("Date &amp; Time")."</td>";
			echo "<td>"._("Duration")."</td>";
			echo "<td>"._("Side")."</td>";
			echo "<td>"._("Latching")."</td>";
			echo "<td>"._("Infant Alertness")."</td>";
			echo "<td>"._("Maternal Problems")."</td>";
	//		echo "<td>Modify</td>";

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
				$m = "<form action='modify_entry.php' method='post'>";
				$m = $m . "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
				$m = $m . "<input name='breast' value='"._("Modify")."' type='submit' />";
				$m = $m . "<input name='direction' value='view' type='hidden' />";
				$m = $m . "</form>";
			} 


			$entryDate = new Datetime( $row[ 'EntryDate' ]);
			echo "<tr class='$c'>";
			echo "<td>" . $entryDate->format( DATE_TIME_FORMAT ) . "</td>";
			echo "<td>" . getVocab('duration', $row['BreastfeedingDuration']) . "</td>";
			echo "<td>" . getVocab('Side', $row['Side']) . "</td>";
			echo "<td>" . getVocab('latching', $row['Latching']) . "</td>";
			echo "<td>" . getVocab('infant-state', $row['InfantState']) . "</td>";
			echo "<td>" . getVocab('maternal-problems', $row['MaternalProblems']) . "</td>";
	//		echo "<td>" . $m . "</td>";
			echo "</tr>";
			}

			echo "</tbody>";
			echo "</table>";
		}
		?>
	</div>

	<div data-role='collapsible'>
		<h3>Pumping</h3>
		<?php
			$query = "SELECT * FROM Diary, BreastfeedEntry WHERE EntryDate BETWEEN '". 
				$dateFrom->format( 'Y-m-d' ) . " 00:00:00' AND '" . $dateTo->format( 'Y-m-d' ) .
				" 23:59:59' AND EntryType = ".ENTRYTYPE_PUMPING." AND Diary.EntryId = BreastfeedEntry.EntryId AND mid = " . $_SESSION['mid'] . 
				" AND PumpingMethod IS NOT NULL ORDER BY EntryDate DESC, InputDate DESC";
			$result=mysql_query($query);
			if(mysql_num_rows($result) == 0) {
				echo "<h3>"._("No pumping entries for date range").": " . $dateFrom->format( DATE_FORMAT ) . " to " . $dateTo->format( DATE_FORMAT ) . "</h3>";
			} else {
				echo "<table border='1' cellpadding='3' cellspacing='0' id='pumping'>";
				echo "<thead>";
				echo "<tr>";

				echo "<td>"._("Date &amp; Time")."</td>";
				echo "<td>"._("Duration")."</td>";
				echo "<td>"._("Pumping Method")."</td>";
				echo "<td>"._("Pumping Amount")."</td>";
		//		echo "<td>Modify</td>";

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
				$m = "<form action='modify_entry.php' method='post'>";
				$m = $m . "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
				$m = $m . "<input name='breast' value='"._("Modify")."' type='submit' />";
				$m = $m . "<input name='direction' value='view' type='hidden' />";
				$m = $m . "</form>";
			} 


			$entryDate = new Datetime( $row[ 'EntryDate' ]);
			echo "<tr class='$c'>";
			echo "<td>" . $entryDate->format( DATE_TIME_FORMAT ) . "</td>";
			echo "<td>" . getVocab('duration', $row['BreastfeedingDuration']) . "</td>";
			echo "<td>" . getVocab('pumping-method', $row['PumpingMethod']) . "</td>";
			echo "<td>" . getVocab('TotalAmount', $row['PumpingAmount']) . "</td>";
	//		echo "<td>" . $m . "</td>";
			echo "</tr>";
			}

			echo "</tbody>";
			echo "</table>";
			}
		?>
	</div>

	<div data-role='collapsible'>
    <h3>Supplement</h3>
		<?php
			$query = "SELECT * FROM Diary, SupplementEntry WHERE EntryDate BETWEEN '" . 
				$dateFrom->format( 'Y-m-d' ) . " 00:00:00' AND '" . $dateTo->format( 'Y-m-d' ) .
				" 23:59:59' AND EntryType = ".ENTRYTYPE_SUPPLEMENT." AND Diary.EntryId = SupplementEntry.EntryId AND mid = " . 
				$_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
			$result=mysql_query($query);
			if(mysql_num_rows($result) == 0) {
				echo "<h3>"._("No supplement entries for date range").": " . $dateFrom->format( DATE_FORMAT ) . " to " . $dateTo->format( DATE_FORMAT ) . "</h3>";
			} else {
			echo "<table border='1' cellpadding='3' cellspacing='0' id='pumping'>";
			echo "<thead>";
			echo "<tr>";

			echo "<td>"._("Date &amp; Time")."</td>";
			echo "<td>"._("TotalAmount")."</td>";
			echo "<td>"._("Frequency")."</td>";
			echo "<td>"._("Type")."</td>";
			echo "<td>"._("Method")."</td>";
	//		echo "<td>Modify</td>";

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
				$m = "<form action='modify_entry.php' method='post'>";
				$m = $m . "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
				$m = $m . "<input name='breast' value='"._("Modify")."' type='submit' />";
				$m = $m . "<input name='direction' value='view' type='hidden' />";
				$m = $m . "</form>";
			} 


			$entryDate = new Datetime( $row[ 'EntryDate' ]);
			echo "<tr class='$c'>";
			echo "<td>" . $entryDate->format( DATE_TIME_FORMAT ) . "</td>";
			echo "<td>" . getVocab('TotalAmount', $row['TotalAmount']) . "</td>";
			echo "<td>" . getVocab('NumberTimes', $row['NumberTimes']) . "</td>";
			echo "<td>" . getVocab('sup-type', $row['SupType']) . "</td>";
			echo "<td>" . getVocab('sup-method', $row['SupMethod']) . "</td>";
	//		echo "<td>" . $m . "</td>";
			echo "</tr>";
			}

			echo "</tbody>";
			echo "</table>";
			}
		?>
	</div>

	<div data-role='collapsible'>
    <h3>Output</h3>
		<?php
			$query = "SELECT * FROM Diary, OutputEntry WHERE EntryDate BETWEEN '" .
				$dateFrom->format( 'Y-m-d' ) . " 00:00:00' AND '" . $dateTo->format( 'Y-m-d' ) .
				" 23:59:59' AND EntryType = ".ENTRYTYPE_OUTPUT." AND Diary.EntryId = OutputEntry.EntryId AND mid = " .
				$_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
			$result=mysql_query($query);
			if(mysql_num_rows($result) == 0) {
				echo "<h3>"._("No output entries for date range").": " . $dateFrom->format( DATE_FORMAT ) . " to " . $dateTo->format( DATE_FORMAT ) . "</h3>";
			} else {
			echo "<table border='1' cellpadding='3' cellspacing='0' id='pumping'>";
			echo "<thead>";
			echo "<tr>";

			echo "<td>"._("Date &amp; Time")."</td>";
			echo "<td>"._("Number of Diapers")."</td>";
			echo "<td>"._("Urine Color")."</td>";
			echo "<td>"._("Urine Saturation")."</td>";	
			echo "<td>"._("Stool Color")."</td>";
			echo "<td>"._("Stool Consistency")."</td>";	
	//		echo "<td>Modify</td>";

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
				$m = "<form action='modify_entry.php' method='post'>";
				$m = $m . "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
				$m = $m . "<input name='breast' value='"._("Modify")."' type='submit' />";
				$m = $m . "<input name='direction' value='view' type='hidden' />";
				$m = $m . "</form>";
			} 


			$entryDate = new Datetime( $row[ 'EntryDate' ]);
			echo "<tr class='$c'>";
			echo "<td>" . $entryDate->format( DATE_TIME_FORMAT ) . "</td>";
			echo "<td>" . getVocab('NumberDiapers', $row['NumberDiapers']) . "</td>";
			echo "<td>" . getVocab('out-u-color', $row['UrineColor']) . "</td>";
			echo "<td>" . getVocab('out-u-saturation', $row['UrineSaturation']) . "</td>";
			echo "<td>" . getVocab('out-s-color', $row['StoolColor']) . "</td>";
			echo "<td>" . getVocab('out-s-consistency', $row['StoolConsistency']) . "</td>";
	//		echo "<td>" . $m . "</td>";
			echo "</tr>";
			}

			echo "</tbody>";
			echo "</table>";
			}
		?>
	</div>
	<div data-role='collapsible'>
		<h3>Health Issue</h3>
		<?php
			$query = "SELECT * FROM Diary, MorbidityEntry WHERE EntryDate BETWEEN '" .
				$dateFrom->format( 'Y-m-d' ) . " 00:00:00' AND '" . $dateTo->format( 'Y-m-d' ) .
				" 23:59:59' AND EntryType = ".ENTRYTYPE_HEALTH." AND Diary.EntryId = MorbidityEntry.EntryId AND mid = " .
				$_SESSION['mid'] . " ORDER BY EntryDate DESC, InputDate DESC";
			$result=mysql_query($query);
			if(mysql_num_rows($result) == 0) {
				echo "<h3>"._("No health issue entries for date range").": " . $dateFrom->format( DATE_FORMAT ) . " to " . $dateTo->format( DATE_FORMAT ) . "</h3>";
			} else {
			echo "<table border='1' cellpadding='3' cellspacing='0' id='pumping'>";
			echo "<thead>";
			echo "<tr>";

			echo "<td>"._("Date &amp; Time")."</td>";
			echo "<td>Type</td>";
	//		echo "<td>Modify</td>";

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
				$m = "<form action='modify_entry.php' method='post'>";
				$m = $m . "<input name='entryid' type='hidden' value='" . $row['EntryId'] . "'/>";
				$m = $m . "<input name='breast' value='Modify' type='submit' />";
				$m = $m . "<input name='direction' value='view' type='hidden' />";
				$m = $m . "</form>";
			} 


			$entryDate = new Datetime( $row[ 'EntryDate' ]);
			echo "<tr class='$c'>";
			echo "<td>" . $entryDate->format( DATE_TIME_FORMAT ) . "</td>";
			echo "<td>". getVocab('morb-type', $row['Type']) . "</td>";
	//		echo "<td>" . $m . "</td>";
			echo "</tr>";
			}

			echo "</tbody>";
			echo "</table>";
			}
		?>
</form>
</div>
<?php include('footer.php'); ?>
</div>
</div>
</div>
</body>
</html>
