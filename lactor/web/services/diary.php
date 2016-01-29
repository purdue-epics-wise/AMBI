<?php

require("../includes/general.php");
loggedIn(false) or die();

require("../includes/db.php");
db_connect();

include_once('access_control.php');

$from = urldecode($_REQUEST['from']);
$to = urldecode($_REQUEST['to']);

if ($_REQUEST['type'] == 'breastfeeding') {
  $response = getBreastfeedingEntries($_SESSION['mid'], $from, $to);
}

if ($_REQUEST['type'] == 'pumping') {
  $response = getPumpingEntries($_SESSION['mid'], $from, $to);
}

if ($_REQUEST['type'] == 'supplement') {
  $response = getSupplementEntries($_SESSION['mid'], $from, $to);
}

if ($_REQUEST['type'] == 'output') {
  $response = getOutputEntries($_SESSION['mid'], $from, $to);
}


if ($_REQUEST['type'] == 'weight') {
  $response = getWeightEntries($_SESSION['mid'], $from, $to);
}

if ($_REQUEST['type'] == 'health') {
  $response = getHealthEntries($_SESSION['mid'], $from, $to);
}

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
$response = json_encode($response);
if ($jsonp)
  $response = $_REQUEST['callback'].'('.$response.')';
echo $response;

?>
