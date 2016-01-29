<?php

require_once('../includes/general.php');
require_once('../includes/db.php');

include_once('access_control.php');

if (loggedIn(false)) {
  db_connect();
  header('Content-type: application/json');
  $jsonp = false;
  if (isset($_REQUEST['callback'])) {
    $jsonp = true;
  }
  if ($jsonp) {
    echo $_REQUEST['callback']."(";
  }
  if (isset($_SESSION['sid'])) {
    echo json_encode(getMotherInfo());

  } else if (isset($_SESSION['mid'])) {
    echo json_encode(getProfile());

  } else {
    echo "{}";
  }
  if ($jsonp) {
    echo ")";
  }
}

?>
