<?php 

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();


if(!isset($_POST["q1"]) || !isset($_POST["q2"]) || !isset($_POST["q3"]) || 
   !isset($_POST["q4"]) || !isset($_POST["q5"]) || !isset($_POST["q6"]) || 
   !isset($_POST["q7"]) || !isset($_POST["q7"]) || !isset($_POST["q8"]) || 
   !isset($_POST["q8"]) || !isset($_POST["q9"]) || !isset($_POST["q10"]) || 
   !isset($_POST["q11"]) || !isset($_POST["q12"]) || !isset($_POST["q13"]) || 
   !isset($_POST["q14"])) {
	$_SESSION["SysMessage"] = "Please complete all the forms.";
	$_SESSION["SysDetails"] = "There is at least one question missing.";
	$_SESSION["SysType"] = 3;
	go("../self-efficacy_survey.php");
}

//connect to db
db_connect();

$query = sprintf( "INSERT INTO Self_Efficacy_Survey VALUES( %d,NOW(),%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d )", 
  $_SESSION['mid'],
  $_POST["q1" ], $_POST["q2" ], $_POST["q3" ],
  $_POST["q4" ], $_POST["q5" ], $_POST["q6" ],
  $_POST["q7" ], $_POST["q8" ], $_POST["q9" ],
  $_POST["q10"], $_POST["q11"], $_POST["q12"],
  $_POST["q13"], $_POST["q14"] );
$result=mysql_query($query);
if (!$result)
  error_log(mysql_error());

$query = "UPDATE Mothers 
          SET actions_required = (actions_required & ~".ACTION_SELF_EFFICACY."),
              actions_completed = (actions_completed | ".ACTION_SELF_EFFICACY."),
              loginstep = 5
         WHERE mid = " . $_SESSION['mid'] .  ";";
$result = mysql_query($query);
if (!$result)
  error_log(mysql_error());

$_SESSION["PerMessage"] = "Feedback entered correctly.";
$_SESSION["PerDetails"] = "";
$_SESSION["PerType"] = 1;
go("login.post.php");


?>
