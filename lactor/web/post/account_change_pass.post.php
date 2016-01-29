<?php 

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();

$_SESSION['ProfileDisplay'] = 1;

if($_POST['newpass'] != $_POST['rnewpass']) {
	$_SESSION['ChangePassMessage'] = "Password change failed.";
	$_SESSION['ChangePassDetails'] = "Passwords do not match.";
	go("../profile.php");
}
else if(strlen($_POST['newpass']) < 6) {
	$_SESSION['ChangePassMessage'] = "Password change failed.";
	$_SESSION['ChangePassDetails'] = "Passwords needs to be at least 6 characters long.";
	go("../profile.php");
}
else {
	db_connect();

	//update
	$query = "UPDATE Mothers SET password = '" . mysql_real_escape_string(md5($_POST['newpass'])) . "' WHERE  mid = " . $_SESSION['mid'] . ";";
	$result = mysql_query($query);
	$query="UPDATE Mothers SET loginstep = 3 WHERE mid = " . $_SESSION['mid'] . ";";
	$_SESSION['ChangePassMessage'] = "Password change successful.";
	$_SESSION['ChangePassDetails'] = "Password has been recorded. Please use this password from now on.";
	go("../profile.php");

}

?>
