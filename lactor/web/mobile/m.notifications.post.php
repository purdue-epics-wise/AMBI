<?php

session_start();

include_once("./mobile.include.php");

if(!isset($_SESSION['mid'])) {
	$_SESSION['LoginMessage'] = "Session expired.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
}

if(!isset($_POST['MarkRead'])) {
	header('Location: ./m.add_entry.php');
	exit;
}

include_once("../includes/db.php");
$conn = db_connect( );
if (!$conn) {
	die('Could not connect');
}

mysql_select_db("Breast1", $conn);
$_SESSION['db_con'] = $conn;

$query = "UPDATE Notifications SET status = 2 WHERE  mid = " . $_SESSION['mid'] . " AND nid = " . $_POST['MarkRead'] . " AND status = 1;";
$result = mysql_query($query);
if (!$result){
  error_log(mysql_error( ));
}

header('Location: ./m.notifications.php');
exit;

?>
