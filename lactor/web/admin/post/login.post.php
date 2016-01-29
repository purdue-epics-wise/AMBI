<?php 

include_once("../../includes/general.php");
include_once("../../includes/db.php");

//connect to db
db_connect();
//save email and encoded password as session variables

if ( isset( $_POST['user'] )) {
  $_SESSION['email'] = mysql_real_escape_string(trim($_POST['user']));
}
if ( isset($_POST['pass'])) {
  $_SESSION['legacy_password'] = md5($_POST['pass']);
  $_SESSION['password'] = hash( "sha256", $_POST['pass'].SALT );
}

$_SESSION['first'] = 1;

$result = admin_credentials( );

//we scan results, mark if there is one
if ($result) {
  while($row = mysql_fetch_array($result)) {
    $_SESSION['sid'] = $row['sid'];
    $_SESSION['loginstep'] = $row['loginstep'];
    $_SESSION['admin'] = $row[ 'admin' ];
    $_SESSION['hospital_id'] = $row[ 'hospital_id' ];
  }
}
  
if ( !$result || !loggedIn(false) ) {
  error_log( 'Failed login attempt - username '.$_SESSION['email'].
             ' password '.$_SESSION['password'] );
  error_log( mysql_error( ));
	$_SESSION['LoginMessage'] = "Invalid username and/or password.";
	$_SESSION['LoginDetails'] = "You have entered an incorrect username and/or password. If this problem persists, please contact us or reset your password.";
	$_SESSION['LoginType'] = 3;
	go('../login.php');

}


//if there is a record of matching user name and password
if($_SESSION['loginstep'] == 1) {
	go("../change_pass.php");
} else {
	go("../main.php");
}

?>
