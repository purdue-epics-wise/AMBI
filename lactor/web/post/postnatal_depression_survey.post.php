<?php 

include_once("../includes/general.php");
include_once("../includes/db.php");

loggedIn();


if(!isset($_POST["q1"]) || !isset($_POST["q2"]) || !isset($_POST["q3"]) || 
   !isset($_POST["q4"]) || !isset($_POST["q5"]) || !isset($_POST["q6"]) || 
   !isset($_POST["q7"]) || !isset($_POST["q8"]) || !isset($_POST["q9"]) ||
   !isset($_POST["q10"])) {
	$_SESSION["SysMessage"] = "Please complete all the forms.";
	$_SESSION["SysDetails"] = "There is at least one question missing.";
	$_SESSION["SysType"] = 3;
  for ( $i=1; $i <= 10; $i++ ) {
    $_SESSION["q".$i] = $_POST['q'.$i];
  }
	go("../postnatal_depression_survey.php");
}

//connect to db
db_connect();
begin_transaction( );
$query = sprintf( "INSERT INTO Postnatal_Depression VALUES(%d,NOW(),'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');",
  $_SESSION['mid'],
  mysql_real_escape_string($_POST["q1"]),
  mysql_real_escape_string($_POST["q2"]),
  mysql_real_escape_string($_POST["q3"]),
  mysql_real_escape_string($_POST["q4"]),
  mysql_real_escape_string($_POST["q5"]),
  mysql_real_escape_string($_POST["q6"]),
  mysql_real_escape_string($_POST["q7"]),
  mysql_real_escape_string($_POST["q8"]),
  mysql_real_escape_string($_POST["q9"]),
  mysql_real_escape_string($_POST["q10"]));
error_log($query);
$result=mysql_query($query);
if (!$result) {
  rollback_transaction( );
  error_log(mysql_error());
}

$query = "UPDATE Mothers 
          SET actions_required = (actions_required & ~".ACTION_POSTNATAL_DEPRESSION."),
              actions_completed = (actions_completed | ".ACTION_POSTNATAL_DEPRESSION."),
              loginstep = 5
         WHERE mid = " . $_SESSION['mid'] .  ";";
$result = mysql_query($query);
if (!$result) {
  rollback_transaction( );
  error_log(mysql_error());
}

commit_transaction( );

$_SESSION["PerMessage"] = "Feedback entered correctly.";
$_SESSION["PerDetails"] = "";
$_SESSION["PerType"] = 1;
go("login.post.php");


?>
