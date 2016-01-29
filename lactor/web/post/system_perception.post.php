<?php 

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();


if(trim($_POST["q1"]) == "" || trim($_POST["q2"]) == "" || 
   /* trim($_POST["q3"]) == "" || */ trim($_POST["q4"]) == "" ||
   trim($_POST["q5"]) == "" || trim($_POST["q6"]) == "" || 
   trim($_POST["q7"]) == "") {
	$_SESSION["PerMessage"] = "Please complete all the forms.";
	$_SESSION["PerDetails"] = "There is at least one question missing.";
	$_SESSION["PerType"] = 3;
	$_SESSION["q1"] = $_POST["q1"];
	$_SESSION["q2"] = $_POST["q2"];
//	$_SESSION["q3"] = $_POST["q3"];
	$_SESSION["q4"] = $_POST["q4"];
	$_SESSION["q5"] = $_POST["q5"];
	$_SESSION["q6"] = $_POST["q6"];
	$_SESSION["q7"] = $_POST["q7"];
	
	go("../system_perception.php");
}

//connect to db
db_connect();

$query = "INSERT INTO SystemPerception VALUES(" . $_SESSION['mid'] . ", NOW(), '" . mysql_real_escape_string($_POST["q1"]) . "', '" . mysql_real_escape_string($_POST["q2"]) . "', '" . mysql_real_escape_string($_POST["q3"]) . "', '" . mysql_real_escape_string($_POST["q4"]) . "', '" . mysql_real_escape_string($_POST["q5"]) . "', '" . mysql_real_escape_string($_POST["q6"]) . "', '" . mysql_real_escape_string($_POST["q7"]) . "');";
$result=mysql_query($query);
if (!$result)
  error_log( mysql_error( ));
mysql_free_result( $result );

$query = "UPDATE Mothers 
          SET actions_required = (actions_required & ~".ACTION_SYSTEM_PERCEPTION."),
              actions_completed = (actions_completed | ".ACTION_SYSTEM_PERCEPTION."),
              loginstep = 5
         WHERE mid = " . $_SESSION['mid'] .  ";";
$result = mysql_query($query);
if (!$result)
  error_log( mysql_error( ));

$_SESSION["LoginMessage"] = "Feedback entered correctly.";
$_SESSION["LoginDetails"] = "";
$_SESSION["LoginType"] = 1;
go("login.post.php");


?>
