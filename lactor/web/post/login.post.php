<?php 

include_once("../includes/constants.php");
include_once("../includes/general.php");
include_once("../includes/db.php");

//connect to db
db_connect();

defined('SALT') or 
  die( 'Unable to continue; SALT constant not defined in environment');

if ( isset( $_POST['user'] )) {
  $_SESSION['email'] = mysql_real_escape_string(trim($_POST['user']));
}
if ( isset($_POST['pass'])) {
  $_SESSION['legacy_password'] = md5($_POST['pass']);
  $_SESSION['password'] = hash( "sha256", $_POST['pass'].SALT );
}

//Query for retriving user name and password inputed
$result = credentials();
if ( !$result ) {
  error_log('Failed login attempt - username '.$_SESSION['email']);
  error_log( mysql_error( ));
}

//we scan results, mark if there is one
if($result && $row = mysql_fetch_array($result))
{
  //save the mid
  $_SESSION['mid'] = $row['mid'];
  $loginstep = $row['loginstep'];
  $requiredActions = $row['actions_required'];
  $_SESSION['loginstep'] = $loginstep;
}

//if there is a record of matching user name and password
if(loggedIn(false)) {
	//check if it is the user's first time
	if($loginstep == 1 || $loginstep == 2 ||
           ($requiredActions & ACTION_RESET_PASSWORD ) != 0) { 
		go("../change_pass.php");
	} else if ($loginstep == 3 || 
           ($requiredActions & ACTION_MOTHER_INFORMATION) != 0) { 
		go("../mother_info.php");
	} else if($loginstep == 4 || 
           ($requiredActions & ACTION_SYSTEM_FEEDBACK ) != 0) { 
		go("../system_feedback.php");
  } else if (($requiredActions & ACTION_SYSTEM_PERCEPTION ) != 0) { 
		go("../system_perception.php");
  } else if (($requiredActions & ACTION_BREASTFEEDING_FOLLOWUP) != 0 ) {
		go("../breastfeeding_followup.php");
  } else if (($requiredActions & ACTION_SELF_EFFICACY) != 0 ) {
		go("../self-efficacy_survey.php");
  } else if (($requiredActions & ACTION_BREASTFEEDING_EVALUATION) != 0 ) {
		go("../breastfeeding_evaluation.php");
  } else if (($requiredActions & ACTION_POSTNATAL_DEPRESSION) != 0 ) {
		go("../postnatal_depression_survey.php");
  } else if (($requiredActions & ACTION_CONSENT) != 0 ) {
		go("../consent_form.php");
  } else if (($requiredActions & ACTION_GU_CONSENT) != 0 ) {
		go("../gu_consent_form.php");
	} else if($loginstep == 11) {
		$_SESSION['LoginType'] = 3;
		$_SESSION['LoginMessage'] = "User disabled.";
		$_SESSION['LoginDetails'] = "You have requested to no longer partake in this experiment.";		
  }
  go("../add_entry.php");

} else { //if there isnt, go back to the login page and inform the user of incorrect input
	$_SESSION['LoginMessage'] = "Invalid username and/or password.";
	$_SESSION['LoginDetails'] = "The username and/or password you entered does not match our records. If this problem persists, contact us or <a href=\"reset_pass.php\">reset your password</a>.";
	$_SESSION['LoginType'] = 3;
	go('../login.php');
} 

?>
