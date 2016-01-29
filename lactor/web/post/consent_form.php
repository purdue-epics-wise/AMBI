<?php

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();

db_connect();

if (isset($_POST['agree'])) {

  // Remove the required action of completing this survey.
  $query = "UPDATE Mothers 
            SET actions_required = (actions_required & ~".ACTION_CONSENT."),
                actions_completed = (actions_completed | ".ACTION_CONSENT.")
           WHERE mid = " . $_SESSION['mid'] .  ";";
  $result = mysql_query($query);
  if ( !$result )
    error_log( mysql_error( ));

  go("login.post.php");

} else {

  $_SESSION["ErrorMessage"] = "You must agree to the terms before continuing.";
  $_SESSION["ErrorType"] = 3;
  go("../consent_form.php");
}
?>
