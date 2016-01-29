<?php 

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();


if(!isset($_POST["q1"]) || !isset($_POST["q2"]) || !isset($_POST["q3"]) || !isset($_POST["q4"]) || !isset($_POST["q5"]) || !isset($_POST["q6"]) || !isset($_POST["q1"]) || !isset($_POST["q7"]) || !isset($_POST["q8"]) || !isset($_POST["q9"]) || !isset($_POST["q10"])) {
	$_SESSION["SysMessage"] = "Please complete all the forms.";
	$_SESSION["SysDetails"] = "There is at least one question missing.";
	$_SESSION["SysType"] = 3;
	go("../system_feedback.php");
}

//connect to db
db_connect();

$query = "INSERT INTO Breastfeed_Followup VALUES(" . $_SESSION['mid'] . ", NOW(), " . $_POST["q1"] . ", " . $_POST["q2"] . ", " . $_POST["q3"] . ", " . $_POST["q4"] . ", " . $_POST["q5"] . ", " . $_POST["q6"] . ", " . $_POST["q7"] . ", " . $_POST["q8"] . ", " . $_POST["q9"] . ", " . $_POST["q10"] . ");";
$result=mysql_query($query);
$query = "UPDATE Mothers SET loginstep = 5 WHERE mid = " . $_SESSION['mid'] .  ";";
$result = mysql_query($query);

$_SESSION["PerMessage"] = "Feedback entered correctly.";
$_SESSION["PerDetails"] = "";
$_SESSION["PerType"] = 1;
go("../system_perception.php");


?>
