<?php

require_once('../includes/general.php');
require_once('../includes/db.php');

include_once('access_control.php');

if (loggedIn(false)) {
  db_connect();
  if (isset($_SESSION['mid'])) {
    header('Content-type: application/json');
    echo json_encode(getScientistInfo($_SESSION['mid']));
  }
}

?>
