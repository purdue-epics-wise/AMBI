<?php 

include_once("../../includes/general.php");
include_once("../../includes/db.php");

loggedIn();

if($_POST['newpass'] != $_POST['rnewpass']) {
	$_SESSION['ChangePassMessage'] = "Password change failed.";
	$_SESSION['ChangePassDetails'] = "Passwords do not match.";
	$_SESSION['ChangePassType'] = 3;
	go("../change_pass.php");
}
else if(strlen($_POST['newpass']) < 6) {
	$_SESSION['ChangePassMessage'] = "Password change failed.";
	$_SESSION['ChangePassDetails'] = "Passwords needs to be at least 6 characters long.";
	$_SESSION['ChangePassType'] = 3;
	go("../change_pass.php");
}
else {
	db_connect();

	//update
	$query = "UPDATE Scientists SET loginstep = 0, password = '" . mysql_real_escape_string(md5($_POST['newpass'])) . "' WHERE  sid = " . $_SESSION['sid'] . ";";
	$result = mysql_query($query);

	go("../main.php");
}

?>
