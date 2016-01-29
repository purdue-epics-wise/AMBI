<?php

include_once('../includes/general.php');
include_once('../includes/db.php');

include_once('access_control.php');

db_connect();


if (isset($_REQUEST['callback'])) {
  $callback = $_REQUEST['callback'];
  // jsonp request
  $jsonp = true;
  header('Content-Type: text/javascript');
} else {
  $callback = false;
  $jsonp = false;
  header('Content-Type: application/json');
}

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
  authenticate($_REQUEST['username'], $_REQUEST['password']);
}

if (loggedIn(false)) {
  $response = '{"userid":"'.$_SESSION['mid'].'"}';
  if ($jsonp)
    $response = $_REQUEST['callback'].'('.$response.')';
  echo $response;
} else {
  header('Content-Type: text/html');
  http_response_code(403);
  echo("<h1>Forbidden</h1>");
}
exit();

?>
