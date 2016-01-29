<?php

session_start();

include_once("../includes/constants.php");
include_once("../includes/general.php");
include_once("../includes/db.php");

db_connect();

if ( isset( $_POST['user'] )) {
  $_SESSION['email'] = mysql_real_escape_string(trim($_POST['user']));
}
if ( isset($_POST['pass'])) {
  $_SESSION['legacy_password'] = md5($_POST['pass']);
  $_SESSION['password'] = hash( "sha256", $_POST['pass'].SALT );
}

$result = credentials();
if(!$result) {
	$_SESSION['LoginMessage'] = "Invalid email and/or password.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
} 
//we scan results, mark if there is one
while($row = mysql_fetch_array($result))
{
  	//save the mid
  	$_SESSION['mid'] = $row['mid'];
  	$loginstep = $row['loginstep'];
  	$_SESSION['loginstep'] = $loginstep;
}
  
if($_SESSION['loginstep'] == 11) {
	$_SESSION['LoginMessage'] = "User disabled.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
}

header('Location: ./m.add_entry.php');
exit;

?>
