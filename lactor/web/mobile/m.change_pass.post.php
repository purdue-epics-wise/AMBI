<?php 

include_once("../includes/general.php");
include_once("../includes/constants.php");
include_once("../includes/db.include.php");

loggedIn();

if($_POST['newpass'] != $_POST['rnewpass']) {
	$_SESSION['ChangePassMessage'] = "Password change failed.";
	$_SESSION['ChangePassDetails'] = "Passwords do not match. Make sure the passwords on both fields match.";
	$_SESSION['ChangePassType'] = 3;
	go("change_pass.php");
}
else if(strlen($_POST['newpass']) < 6) {
	$_SESSION['ChangePassMessage'] = "Password change failed.";
	$_SESSION['ChangePassDetails'] = "The password needs to be at least 6 characters long.";
	$_SESSION['ChangePassType'] = 3;
	go("change_pass.php");
}
else {
	db_connect();

	//update
	$query = "UPDATE Mothers SET password = '" . hash("sha256",$_POST['newpass'].SALT) . "' WHERE  mid = " . $_SESSION['mid'] . ";";
	$result = mysql_query($query);

  $query = "UPDATE Mothers SET loginstep = 0 WHERE mid = " . $_SESSION['mid'] .  ";";
  $result = mysql_query($query);
  $_SESSION['ChangePassMessage'] = "Password change successful.";
  $_SESSION['ChangePassDetails'] = "Password has been recorded. Please use this password from now on.";
  $_SESSION['ChangePassType'] = 1;
  go("m.add_entry.php");

}

?>
