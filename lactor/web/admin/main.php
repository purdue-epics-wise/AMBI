<?php
	
	include_once("../includes/general.php");
	include_once("../includes/db.include.php");
	
	loggedIn();
	initialize();
	db_connect();
	loadVocabulary();
	
?>


<head>
	<?php head_tag("Admin LACTOR - Dashboard"); ?>
  <style type="text/css">
    th { 
      background-color: #ddd;
    }
    td:first-child {
      background-color: #ddd;
    }
  </style>
</head>

<body>
	<div id="maincontainer">
	<?php page_header(); ?>
	<?php admin_menu(0); ?>
	
	
	<div id="pagecontent">

	
	<form method="post" action="admin/display_data.php" name="dash">
	<input type="hidden" name="dateFrom" value=<?php echo "\"" . date("Y-m-d") . "\""; ?>\>
	<input type="hidden" name="dateTo" value=<?php echo "\"" . date("Y-m-d") . "\""; ?>\>
	<table cellspacing="0" cellpadding="3" border="1">
	<tr>
		<th>Mother</th>
		<th><input type="checkbox" checked name="option2" value="BreastfeedingEntry">Breastfeeding</th>
		<th><input type="checkbox" checked name="option3" value="SupplementEntry">Supplement</th>
		<th><input type="checkbox" checked name="option4" value="OutputEntry">Output</th>
		<th><input type="checkbox" checked name="option5" value="MorbidityEntry">Health Issue</th>
		<th id="tablespace"></th>
		<th>Notifications</th>
	</tr>
	
	
	<?php 
		$query="SELECT * FROM Mothers;";
		$result=mysql_query($query);
		$c = 0;
		while($row=mysql_fetch_array($result)) {
			$mother[$c++] = $row["mid"];
		}
		
		for($i = 0; $i < $c; $i++) {
			//get email
			$query="SELECT * FROM Mothers WHERE mid = " . $mother[$i] . ";";
			$result=mysql_query($query);
			$row=mysql_fetch_array($result);
			
			
			//check if there are any notifications
			if(hasAnyNotification($mother[$i])==0)
			{
				continue;
			}
						
						
			echo "<tr>";
			echo "<td>" . "<input type=\"checkbox\" checked name=\"mid[" . $i . "]\" value=\"" . $mother[$i] . "\">" . $row["email"] . "</td>\n";
			
			//get latest breastfeeding entry
			$query="SELECT * FROM Diary, BreastfeedEntry T WHERE mid = " . $mother[$i] . " AND Diary.EntryId=T.EntryId AND Diary.EntryType = 1 AND DATE(EntryDate) = DATE(NOW()) ORDER BY EntryDate DESC;";
			$result=mysql_query($query);
			echo "<td>";
			if($row=mysql_fetch_array($result)) {
				echo "<div><b>Total: " . mysql_num_rows($result) . "</b></div>";
				echo "<div>Time: " . getTime($row["EntryDate"]) . "</div>";
				echo "<div>Dur.: " . getVocab("duration", $row["BreastfeedingDuration"]) . "</div>";
				echo "<div>Latch: " . getVocab("latching", $row["Latching"]) . "</div>";
				echo "<div>Inf. State: " . getVocab("latching", $row["InfantState"]) . "</div>";
			} else {
				echo "<div>No new entries</div>";
			}
			echo "</td>";
			
			//get latest supplement entry
			$query="SELECT * FROM Diary, SupplementEntry T WHERE mid = " . $mother[$i] . " AND Diary.EntryId=T.EntryId AND Diary.EntryType = 2 AND DATE(EntryDate) = DATE(NOW()) ORDER BY EntryDate DESC;";
			$result=mysql_query($query);
			echo "<td>";
			if($row=mysql_fetch_array($result)) {
				echo "<div><b>Total: " . mysql_num_rows($result) . "</b></div>";
				echo "<div>Time: " . getTime($row["EntryDate"]) . "</div>";
				echo "<div>Type: " . getVocab("sup-type", $row["SupType"]) . "</div>";
				echo "<div>Amount: " . getVocab("TotalAmount", $row["TotalAmount"]) . "</div>";
			} else {
				echo "<div>No new entries</div>";
			}
			echo "</td>";
			
			//get latest output entry
			$query="SELECT * FROM Diary, OutputEntry T WHERE mid = " . $mother[$i] . " AND Diary.EntryId=T.EntryId AND Diary.EntryType = 3 AND DATE(EntryDate) = DATE(NOW()) ORDER BY EntryDate DESC;";
			$result=mysql_query($query);
			echo "<td>";
			if($row=mysql_fetch_array($result)) {
				echo "<div><b>Total: " . mysql_num_rows($result) . "</b></div>";
				echo "<div>Time: " . getTime($row["EntryDate"]) . "</div>";
				echo "<div>Urine: " . getVocab("out-u-color", $row["UrineColor"]) . " / " . getVocab("out-u-saturation", $row["UrineSaturation"]) . "</div>";
				echo "<div>Stool: " . getVocab("out-s-color", $row["StoolColor"]) . " / " . getVocab("out-u-consistency", $row["StoolConsistency"]) . "</div>";
			} else {
				echo "<div>No new entries</div>";
			}
			echo "</td>";
			
			//get latest morbidity entry
			$query="SELECT * FROM Diary, MorbidityEntry T WHERE mid = " . $mother[$i] . " AND Diary.EntryId=T.EntryId AND Diary.EntryType = 4 AND DATE(EntryDate) = DATE(NOW()) ORDER BY EntryDate DESC;";
			$result=mysql_query($query);
			echo "<td>";
			if($row=mysql_fetch_array($result)) {
				echo "<div><b>Total: " . mysql_num_rows($result) . "</b></div>";
				echo "<div>Time: " . getTime($row["EntryDate"]) . "</div>";
				echo "<div>Type: " . getVocab("morb-type", $row["Type"]) . "</div>";
			} else {
				echo "<div>No new entries</div>";
			}
			echo "</td>";
			
			//white row
			echo "<td id=\"tablespace\"><pre>        </pre></td>";
			
			//get 1st notifications
			$query="SELECT * FROM Notifications, NotificationDescription WHERE mid = " . $mother[$i] . " AND Notifications.ntype = NotificationDescription.ntype AND Notifications.astatus=1 AND DATE(NotificationIssued) = DATE(NOW());";
			$result=mysql_query($query);
			echo "<td>";
			if($row=mysql_fetch_array($result)) {
				echo "<p><b>Total: " . mysql_num_rows($result) . "</b></p>";
				echo "<p>Problem: " . $row["Problem"] . "<p>";
			} else {
				echo "<p>No new notifications</p>";
			}
			
			echo "</td>\n";
			
			echo "</tr>";
			
			$_SESSION['nmid']=$c;
			
			
		}

		function hasAnyNotification($motherId)
		{	
			$shouldDisplay = 0 ;
			//get latest breastfeeding entry
			$query="SELECT * FROM Diary, BreastfeedEntry T WHERE mid = " . $motherId . " AND Diary.EntryId=T.EntryId AND Diary.EntryType in (1,2,3,4) AND DATE(EntryDate) = DATE(NOW()) ORDER BY EntryDate DESC;";
			$result=mysql_query($query);
			$result_array = mysql_fetch_array($result);
			if($result_array) { $shouldDisplay = 1;}
			
			return $shouldDisplay;
		}
		
	?>
	</table>
	<br/>
	<pre>                                                                     <input type="submit" name="entries" value="<?php echo _("See Entries") ?>">    <input type="submit" name="noti" value="<?php echo _("See Notifications") ?>"></pre>
	</form>
</div>
	<?php page_footer(); ?>
	</div>
	</body>
</html>
