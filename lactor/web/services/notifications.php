<?php

include_once("../includes/general.php");
include_once("../includes/constants.php");
include_once("../includes/db.php");

include_once('access_control.php');

db_connect();

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

  if (isset($_REQUEST['count'])) {
    
  } else if (isset($_REQUEST['markRead'])) {
    $result = markNotificationRead((int)$_REQUEST['markRead'], $_SESSION['mid']); 
    if ($jsonp)
      echo "$callback(";
    if ($result) {
      echo json_encode(
        array('message' => "Your notification was successfully updated."));
    } else {
      json_encode(
        array('error' => "Your notification update failed."));
    }
    if ($jsonp)
      echo ")";
  } else {
    $notificationStatus = 
        isset($_REQUEST['status']) ? 
            (int)$_REQUEST['status'] : NOTIFICATION_STATUS_CURRENT;
        
    $notificationType = 
        isset($_REQUEST['type']) ? (int)$_REQUEST['type'] : 0;

    $notifications = getNotifications($_SESSION['mid'], $notificationStatus,
                                      $notificationType);
    if ($jsonp)
      echo "$callback(";
    echo json_encode($notifications);
    if ($jsonp)
      echo ")";
  }
}
?>
