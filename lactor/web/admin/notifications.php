<?php 
include_once("../includes/general.php");
include_once("../includes/db.php");

loggedIn();
db_connect();

loadVocabulary();

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

if(isset($_SESSION['notamount'])) {
	for($i = 0; $i < $_SESSION['notamount']; $i++) {
		if(isset($_POST['sub'][$i])) {
			$query="UPDATE Notifications SET astatus=2 WHERE mid=". $_POST['notmid'][$i] . " AND nid=" . $_POST['notnid'][$i] . ";";
			mysql_query($query);
		}
	}
}


if(isset($_SESSION['allmids'])) {
	$_POST['mid']=$_SESSION['allmids'];
	unset($_SESSION['allmids']);
}

if(isset($_POST['download'])) {

	$query = "select * from Mothers;";
	$result = mysql_query($query);
	
	$c = "";
	while($row=mysql_fetch_array($result)) {
		 
		for($i = 0; $i < $_SESSION['nmid']; $i++) {
			if ($_POST['mid'][$i] == $row['mid']) {
				$c .= "-" . $row['mid'];
			}
		}
	} 
	
	$record = "INSERT INTO ScienceDownloads VALUES(" . $_SESSION['sid'] . ", NOW(), '" . $c . "', '" . $_SESSION['dateFrom'] . " 00:00:00', '" .  $_SESSION['dateTo'] . " 00:00:00', " . "2, '');";
	mysql_query($record);
	
	$filename="not" . $_SESSION["sid"] . "-" . date("Y-m-d-G-i-s") . "-" . $_SESSION['dateFrom'] . "-" . $_SESSION['dateTo'] . $c . ".csv";
	
	header('Content-type: application/csv');
	header("Content-Disposition: attachment; filename=".$filename);

	$where = " NotificationIssued BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' ";
	$count = 0;
	for($i = 0; $i < $_SESSION['nmid']; $i++) {
		if(isset($_POST['mid'][$i])) {
			if($count == 0) {
				$where = $where . " AND (Notifications.mid=" . $_POST['mid'][$i] . " ";
			} else {
				$where = $where . " OR Notifications.mid=" . $_POST['mid'][$i] . " ";
			}
			$count++;
		}
	}	
	if($count != 0) {
		$where = $where . ") ";
	}
	
	$n = 0;
	if(isset($_POST['option5']) || isset($_POST['option6'])) {
		$n = 1;
		$where = $where . "AND (";
	}
	if(isset($_POST['option5'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " status = 1 ";
		$n++;
	}  
	if(isset($_POST['option6'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " status = 2 ";
		$n++;
	}  
	if($n>0) {
		$where = $where . ") ";
	}
	
	$n = 0;
	if(isset($_POST['option7']) || isset($_POST['option8']) || isset($_SESSION['opt6'])) {
		$n = 1;
		$where = $where . "AND (";
	}
	if(isset($_POST['option7']) || isset($_SESSION['opt6'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " astatus = 1 ";
		$n++;
	}  
	if(isset($_POST['option8'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " astatus = 2 ";
		$n++;
	}  
	if($n>0) {
		$where = $where . ") ";
	}
	
	if(!isset($_POST['option5']) && !isset($_POST['option6']) && !isset($_POST['option7']) && !isset($_POST['option8']) && !isset($_SESSION['opt6'])){
		$where = $where . " AND status = 10 ";
	}
	
	if($_POST['type'] == 0 || isset($_SESSION['opt6'])) {
	} else {
		$where = $where . " AND Notifications.ntype = " . $_POST['type'] . " ";
	}
	
	if(isset($_SESSION['opt6'])){
		unset($_SESSION['opt6']);
	}

	
	echo "Notifications\n";
	echo "From," . $_SESSION['dateFrom'] . ",To," . $_SESSION['dateTo'] . "\n\n";
	
	

	echo "Email,Issue Date,Problem,Read By User,Read by Admin\n";
	
	$query = "SELECT * FROM Notifications, NotificationDescription, Mothers 
						LEFT JOIN MotherInfo ON (Mothers.mid=MotherInfo.mid) 
	          WHERE 
						Notifications.ntype = NotificationDescription.ntype AND 
						Mothers.mid = Notifications.mid AND 
						status != 8 AND $where 
						ORDER BY Notifications.mid ASC, astatus ASC, NotificationIssued DESC, status ASC;";
	$result=mysql_query($query);
	
	while($row = mysql_fetch_array($result))
	{
		$line = $row['email'] . "," . getDateTime($row['NotificationIssued']) . "," . $row['Problem'] . "," . yesOrNo($row['status']) . "," . yesOrNo($row['astatus']) . "\n";
		echo $line;
	} 
	
	exit; 
}

function yesOrNo($t) {
	if($t == 1) return "No"; else return "Yes";
}

initialize();

?>

<head>
<?php head_tag("Admin LACTOR - Notifications"); ?>
</head>


<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php admin_menu(6); ?>

<div id="pagecontent">

<div id="registercontent">

<div id="container">
<div class="tabs">
  <ul class="menu">
  <li><a href="#displayData"><?php echo _("Notifications") ?></a></li>
  </ul>
  <div id="displayData">
  <h1><img src="image/notification.gif" width=32 height=32 alt=""/> <?php echo _("Notifications Query Values") ?></h1>
  <ul>
  <form name="feedback" method="post" action="admin/notifications.php">
  <pre><?php echo _("Events") ?>: <input type="checkbox" <?php if(isset($_POST['option5'])) echo " checked "; ?> name="option5" value="BreastfeedingEntry"> <?php echo _("Unread by User") ?> <input type="checkbox" <?php if(isset($_POST['option6'])) echo " checked "; ?>   name="option6" value="SupplementEntry"> Read by User <input type="checkbox" name="option7" <?php if(isset($_POST['option7'])  || isset($_SESSION['opt6']) ) echo " checked "; ?>  value="OutputEntry"> <?php echo _("Unread by Admin") ?> <input type="checkbox" name="option8"<?php if(isset($_POST['option8'])) echo " checked "; ?>  value="MorbidityEntry"> Read by Admin </pre>
  <br />
  <pre><?php echo _("From") ?>: <input class='datepicker' type="text" value=<?php echo "'" . $date1 . "'"; ?> name="dateFrom" /><script language="JavaScript">new tcal ({'formname': 'feedback','controlname': 'dateFrom'});</script>  To: <input class='datepicker' type="text" value=<?php echo "'" . $date2 . "'"; ?> name="dateTo" /><script language="JavaScript">new tcal ({'formname': 'feedback','controlname': 'dateTo'});</script></pre>
  <br />
  <pre><?php echo _("Notification") ?>: <select name="type">
  <?php 
  $query = "select * from NotificationDescription;"; 
  $result = mysql_query($query); 
  $r = "";
  if($_POST['type'] == 0 || isset($_SESSION['opt6'])) { 
    $r = " selected "; 
  } 
  echo "<option value='0'" . $r . ">All</option>";
  $r = "";
  while($row=mysql_fetch_array($result)) {
    if($_POST['type'] == $row['ntype']) { 
      $r = " selected "; 
      } 
      echo "<option value='" . $row['ntype'] . "' " . $r . ">" . _($row['Problem']) . "</option>"; $r = ""; 
    } 
  ?> 
  </select></pre>
  <pre><?php echo _("Mother") ?>:    <select multiple size="5" name="mid[]"> 
      <?php 		
      $mothers = getMotherInfo( );
      foreach( $mothers as $mother ) {
        printf( "<option value='%s' %s>%s (%s)</option>", $mother['mid'], 
                in_array($mother['mid'], $_POST['mid']) ? "selected" : "", $mother['Name'],
                $mother['email'] );
      }
      ?> </select></pre>
  <pre>                  <input type="submit" name="query" value="<?php echo _("Query") ?>">     <input type="submit" name="download" value="<?php echo _("Download") ?>"></pre>
  <ul>
</div>
</div>

<br />
<a class="link" href="admin/instructions.php">View Notification Instructions</a><br /><br/>


<?php 
	$where = " NotificationIssued BETWEEN '" . $_SESSION['dateFrom'] . " 00:00:00' AND '" . $_SESSION['dateTo'] . " 23:59:59' ";
	$count = 0;
	for($i = 0; $i < @$_SESSION['nmid']; $i++) {
		if(isset($_POST['mid'][$i])) {
			if($count == 0) {
				$where = $where . " AND (Notifications.mid=" . $_POST['mid'][$i] . " ";
			} else {
				$where = $where . " OR Notifications.mid=" . $_POST['mid'][$i] . " ";
			}
			$count++;
		}
	}	
	if($count != 0) {
		$where = $where . ") ";
	}
	
	$n = 0;
	if(isset($_POST['option5']) || isset($_POST['option6'])) {
		$n = 1;
		$where = $where . "AND (";
	}
	if(isset($_POST['option5'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " status = 1 ";
		$n++;
	}  
	if(isset($_POST['option6'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " status = 2 ";
		$n++;
	}  
	if($n>0) {
		$where = $where . ") ";
	}
	
	$n = 0;
	if(isset($_POST['option7']) || isset($_POST['option8']) || isset($_SESSION['opt6'])) {
		$n = 1;
		$where = $where . "AND (";
	}
	if(isset($_POST['option7']) || isset($_SESSION['opt6'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " astatus = 1 ";
		$n++;
	}  
	if(isset($_POST['option8'])) {
		if($n > 1) {
			$where = $where . " OR ";
		}
		$where = $where . " astatus = 2 ";
		$n++;
	}  
	if($n>0) {
		$where = $where . ") ";
	}
	
	if(!isset($_POST['option5']) && !isset($_POST['option6']) && !isset($_POST['option7']) && !isset($_POST['option8']) && !isset($_SESSION['opt6'])){
		$where = $where . " AND status = 10 ";
	}
	
	if(@$_POST['type'] == 0 || isset($_SESSION['opt6'])) {
	} else {
		$where = $where . " AND Notifications.ntype = " . $_POST['type'] . " ";
	}
	
	if(isset($_SESSION['opt6'])){
		unset($_SESSION['opt6']);
	}

	
	
?>

<?php

	$query = "SELECT * FROM Notifications, NotificationDescription, Mothers 
						LEFT JOIN MotherInfo ON (Mothers.mid = MotherInfo.mid)
	          WHERE 
						  Notifications.ntype = NotificationDescription.ntype AND 
							Mothers.mid = Notifications.mid AND status != 8 AND $where 
						ORDER BY 
							Notifications.mid ASC, astatus ASC, NotificationIssued DESC, status ASC;";
	$result=mysql_query($query);
	
	

	echo "<div id='container'>";
  echo "<div class='tabs'>";
	echo "<ul class='menu'>";
	echo "<li id='breastfeeding' class='active'><a href='#results'>Results</a></li>";
	echo "</ul>";
	echo "<div id='results'>";
	if(mysql_num_rows($result) == 0) {
	echo "<br /><h3>"._("No notifications entries for date range").": " . modDate2($_SESSION['dateFrom']) . " "._("to")." " . modDate2($_SESSION['dateTo'])." "._("for the selected users")."</h3>";
	} else {

	echo "<h1><img src='image/notification.gif' alt=''/> "._("Notifications")."</h1>";
	echo "<ul>";
	echo "<table id='breast'>";
	echo "<thead>";
	echo "<tr>";
	echo "<td>Name</td>";
	echo "<td>Email</td>";
	echo "<td>Issue Date</td>";
	echo "<td>Problem</td>";
	echo "<td>Read by User</td>";
	echo "<td>Read by Admin</td>";
	echo "<td>Mark As Read</td>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	
	$co = 0;
	$m = 0;
	$c = 0;
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
	
		
		echo "<tr BGCOLOR='" . $bgcolfin . "'>";
		echo "<td>" . $row['Name'] . "</td>";
		echo "<td>" . $row['email'] . "</td>";
		echo "<td>" . getDateTime($row['NotificationIssued']) . "</td>";
		echo "<td>" . $row['Problem'] . "</td>";
		if($row['status'] == 1) {
			$st = "No";
		} else {
			$st = "Yes";
		}
		echo "<td>" . $st . "</td>";
		if($row['astatus'] == 1) {
			$ast = "No";
			$mod = "<input type='hidden' name='notnid[" . $co . "]' value='" . 
				$row['nid'] . "' ><input type='hidden' name='notmid[" . $co . "]' value='" . 
				$row['mid'] . "' ><input type='submit' name='sub[". $co . "]'/>";
		} else {
			$ast = "Yes";
			$mod= "--";
		}
		echo "<td>" . $ast . "</td>";
		echo "<td>" . $mod . "</td>";
		echo "</tr>";
		
		$co++;
		
	} 

	echo "</tbody>";
	echo "</table>";
	echo "<ul>";
	}
	echo "</div>";
	echo "</div>";
	echo "</div>";
		
	$_SESSION['notamount'] = @$co;

?>
</form>

<script type="text/javascript" src="js/tabs.js"></script>

<input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />

</div>
</div>
</div>


<?php page_footer(); ?>
</div>

</div>
<script type="text/javascript"><!--
$(function( ) {
  $('.datepicker').datepicker( );
});
</script>
</body>
</html>
