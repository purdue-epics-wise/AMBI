<?php
session_start();

include_once("includes/general.php");

include_once('access_control.php');

$_SESSION = array();
	
session_destroy();

if (isset($_REQUEST['callback'])) {
  $callback = $_REQUEST['callback'];
  // jsonp request
  header('Content-Type: text/javascript');
  echo $_REQUEST['callback'].'({ "message":"Successfully logged out" })';
} else {
  $callback = false;
  header('Content-Type: application/json');
  echo '{ "message":"Successfully logged out" }';
}
	

?>
