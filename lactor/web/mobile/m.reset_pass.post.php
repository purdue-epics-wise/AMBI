<?php

session_start();

include_once("../includes/general.php");

$conn = mysql_connect('localhost', 'lactor', 'breast1010');
if (!$conn) {
	die('Could not connect');
}

mysql_select_db("Breast1", $conn);
$_SESSION['db_con'] = $conn;

$_SESSION['reset_email'] = mysql_real_escape_string($_POST['email']);

if(trim($_POST["email"]) == "") {
	$_SESSION['ResetMessage'] = "You must enter an email.\n";
	$_SESSION['ResetType'] = 3;
	header('Location: ./m.reset_pass.php');
	exit;
}

$newpassdisplay = genRandomString();
$newpass = mysql_real_escape_string(md5($newpassdisplay));


//update
$query = "SELECT * FROM Mothers WHERE email = '" . $_SESSION['reset_email'] . "';";
$result = mysql_query($query);
$ls = "2";
$row=mysql_fetch_array($result);
if($row['loginstep'] == 1) {
	$ls = "1";
}
$query = "UPDATE Mothers SET password = '" . $newpass . "', loginstep = " . $ls . " WHERE  email = '" . $_SESSION['reset_email'] . "';";
$result = mysql_query($query);

$mail = generatePassMail($_SESSION['reset_email'], "Password Reset", $newpassdisplay, "You will be prompted to change the password as soon as you log in. If you are not, you can change it by going to the profile tab, and selecting the Change Password option.");

$_SESSION['LoginMessage'] = "Password resetted.";
$_SESSION['LoginType'] = 1;
header('Location: ./m.login.php');
exit;

?> 
