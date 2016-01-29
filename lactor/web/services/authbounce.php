<?php

include_once('../includes/general.php');
include_once('../includes/db.php');

db_connect();

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
  authenticate($_REQUEST['username'], $_REQUEST['password']);
}

if (loggedIn(false)) {
  header('Content-Type: text/html');
  http_response_code(200);
  echo("<html><head><script type='text/javascript'>
    history.go(-1);
    </script><body></body></html>");
} else {
  header('Content-Type: text/html');
  http_response_code(403);
  echo("<h1>Login Failed</h1>");
  echo("<button onclick='history.go(-1)'>&lArr; Back</button>");
}
exit();

?>
