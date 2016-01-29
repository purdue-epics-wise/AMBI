<?php

require_once('../includes/general.php');
require_once('../includes/db.php');

include_once('access_control.php');

if (loggedIn(false)) {
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

  db_connect();
  $id = isset($_SESSION['sid']) ? $_SESSION['sid'] : $_SESSION['mid'];
  $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'unread';
  $scientist = isset($_SESSION['sid']);
  header('Content-type: application/json');
  if (isset($_REQUEST['markRead'])) {
    if (markMessageRead(mysql_real_escape_string($_REQUEST['markRead']), $id)) {
      $response = '{ "success": "Message marked as read." }';
    } else {
      $response = '{ "error": "Message status change failed." }';
    }
  } else if (isset($_REQUEST['count'])) {
    $count = getMessageCount($id, $scientist);
    $response = json_encode(array( 'count' => $count));
  } else {
    if ($scientist) {
      $messages = getScientistMessages($id);
    } else {
      $messages = getMotherMessages($id);
    }
    $count = getMessageCount($id, $scientist);
    $response = json_encode(array( 'messages' => $messages, 'count' => $count));
  }
  if ($jsonp)
    echo "$callback(";
  echo $response;
  if ($jsonp)
    echo ")";
}

?>
