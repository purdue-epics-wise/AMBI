<?php
//TODO: unexistant user.

include_once("../includes/general.php");
include_once("../includes/db.include.php");
include_once("../includes/mail.include.php");

db_connect();

$_SESSION['reset_email'] = mysql_real_escape_string($_POST['email']);

if(trim(@$_POST["email"]) == "") {
	$_SESSION['ResetMessage'] = "You must enter an email address.\n";
	$_SESSION['ResetDetails'] = $_SESSION['ResetDetails'] . "Enter your email address so we can send you your password.<br />";
	$_SESSION['ResetType'] = 3;
	go("../reset_pass.php");
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

$mail = generatePassMail( $_SESSION['reset_email'], "Password Reset", $newpassdisplay, "You will be prompted to change the password as soon as you log in. If you are not, you can change it by going to the profile tab, and selecting the Change Password option.");

$_SESSION['LoginMessage'] = "Password reset successful for " . $_POST["email"] . ".";
$_SESSION['LoginDetails'] = "An email was sent to " . $_SESSION['reset_email'] . " with a new password. You will be asked to change the password upon your first login.";
$_SESSION['LoginType'] = 1;

go("../login.php");

?> 
