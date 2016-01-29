<!DOCTYPE html>
<?php

session_start();
include_once('../includes/constants.php');
include_once('../includes/general.php');

if(!isset($_SESSION['mid'])) {
	$_SESSION['LoginMessage'] = "Session expired.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
}

include_once("./mobile.include.php");
include_once("../includes/db.php");
$conn = db_connect( );
if (!$conn) {
	die('Could not connect');
}

mysql_select_db("Breast1", $conn);
$_SESSION['db_con'] = $conn;

?>
<html>
<head>
  <title> Lactor Mobile - Notifications </title>
  <?php include('head.php'); ?>
</head>
<body>
<div data-role='page'>
<?php include('header.php'); ?>
<div data-role='content'>
<h1>Notifications</h1>
<?php
$query = "SELECT * FROM Notifications WHERE mid = " . $_SESSION['mid'] . " AND status = 1 ORDER BY NotificationIssued DESC;";
$result = mysql_query($query);
if (!$result) {
  error_log(mysql_error( ));
} else {
  if (!mysql_num_rows( $result )) {
    echo "<h3>"._("You have no unread notifications")."</h3>";
  }
  while($row = mysql_fetch_assoc($result))
  {
    echo "<b>Date & Time:</b> " . getDateTime($row['NotificationIssued']) . "    <br /><b>Problem:</b> ".notificationTitle($row['ntype'])."<br />";
    echo notificationText($row['ntype']);
    echo "<form method=\"post\" action=\"m.notifications.post.php\"><input type=\"hidden\" name=\"MarkRead\" value=\"" . $row['nid'] . "\"/><input value=\""._("Mark As Read")."\" type=\"submit\"></form><br/>";
  }
}

?>
<?php include('footer.php'); ?>
</div>
</div>
</body>
</html>
