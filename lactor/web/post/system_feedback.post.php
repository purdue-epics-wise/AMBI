<?php 

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();


if(!isset($_POST["group1"]) || !isset($_POST["group2"]) || 
   !isset($_POST["group3"]) || !isset($_POST["group4"]) || 
   !isset($_POST["group5"]) || !isset($_POST["group6"]) || 
   !isset($_POST["group1"]) || !isset($_POST["group7"]) || 
   !isset($_POST["group8"]) || !isset($_POST["group9"]) || 
   !isset($_POST["group10"])) {
	$_SESSION["SysMessage"] = "Please complete all the forms.";
	$_SESSION["SysDetails"] = "There is at least one question missing.";
	$_SESSION["SysType"] = 3;
	go("../system_feedback.php");
}

//connect to db
db_connect();

$query = "INSERT INTO SystemFeedback VALUES(" . $_SESSION['mid'] . ", NOW(), " . $_POST["group1"] . ", " . $_POST["group2"] . ", " . $_POST["group3"] . ", " . $_POST["group4"] . ", " . $_POST["group5"] . ", " . $_POST["group6"] . ", " . $_POST["group7"] . ", " . $_POST["group8"] . ", " . $_POST["group9"] . ", " . $_POST["group10"] . ");";
$result=mysql_query($query);
if (!$result)
  error_log( mysql_error( ));

$query = "UPDATE Mothers 
          SET actions_required = (actions_required & ~".ACTION_SYSTEM_FEEDBACK."),
              actions_completed = (actions_completed | ".ACTION_SYSTEM_FEEDBACK."),
              loginstep = 5
         WHERE mid = " . $_SESSION['mid'] .  ";";
$result = mysql_query($query);
if (!$result)
  error_log( mysql_error( ));

$_SESSION["PerMessage"] = "Feedback entered correctly.";
$_SESSION["PerDetails"] = "";
$_SESSION["PerType"] = 1;
go("login.post.php");


?>
