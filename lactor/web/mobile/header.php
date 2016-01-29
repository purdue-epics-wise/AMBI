<div data-role='header' id="header">
	<img id='header-logo' alt="Welcome" src="./logo.png" />
  <div id='menu' onclick="$(this).toggleClass('opened')">
    <div></div>
    <div></div>
    <div></div>
    <ul id='menu-content'>
	    <li><a href="m.add_entry.php"><?php echo _("Add Entry") ?></a></li>
	    <li><a href="view_diary.php"><?php echo _("View Diary") ?></a></li>
	    <li><a href='m.notifications.php'><?php echo _("Notifications") ?></a></li>
	    <li><a href='tutorials.php'><?php echo _("Tutorials") ?></a></li>
	    <li><a href='change_pass.php'><?php echo _("Change Password") ?></a></li>
      <li><a rel='external' href="./m.full_version.php"><?php echo _("Desktop Version") ?></a></li>
      <li><a  href="./m.logout.php"><?php echo _("Log Out") ?></a></li>
    </ul>
  </div>
</div>
<?php
include_once("../includes/db.php");
if (!isset($conn)) {
  $conn = db_connect( );
}
if (!session_id())
  session_start();
$query = "SELECT * FROM Notifications WHERE mid = " . $_SESSION['mid'] . ";";
$counter = 0;
$result = mysql_query($query);
if (!$result) {
  error_log(mysql_error( ));
} else {
  while($row = mysql_fetch_array($result)) {
    if($row['status'] != 2 && $row['status'] != 8) {
      $counter = $counter + 1;
    }
  } 
}

if($counter != 0) {
	echo "<ul id='warncontent' data-role='listview'>\n";
  printf( "<li><a href='m.notifications.php'>You have %d new notification%s.</a></li>", $counter, ($counter != 1) ? "s": "");
	echo "</ul>\n";
}

$query = "UPDATE Notifications SET status = 1 WHERE status = 0 AND mid = " . $_SESSION['mid'] . ";";
mysql_query($query);

if ( !empty( $error_message )) {
	echo "<div class='errorMessage'>$error_message</div>";
}
if ( !empty( $success_message )) {
	echo "<div class='successMessage'>$success_message</div>";
}
?>
