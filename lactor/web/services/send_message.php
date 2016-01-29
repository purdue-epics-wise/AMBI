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

  if (isset($_REQUEST['message'])) {
    db_connect();
    if (isset($_SESSION['mid'])) {
      // A mother is sending a message to her consultant.
      $sender_id = $_SESSION['mid'];
      $sid_query = "SELECT sid FROM Mothers_Scientists where mid=$sender_id";
      $result = mysql_query($sid_query);
      if (!$result) {
        error_log(mysql_error());
      } 
      $row = mysql_fetch_row($result);
      $recipient_id = $row[0];
      $meta = INBOX_SENDER_MOTHER | INBOX_RECIPIENT_SCIENTIST | INBOX_MESSAGE_UNREAD;

    } else if (isset($_SESSION['sid'])) {
      // A consultant is sending a message to one of the mothers.
      $sender_id = $_SESSION['sid'];
      $recipient_id = (int)$_REQUEST['mailto']; // cast to an int to avoid sql exploits
      if (!can_access_mother($recipient_id)) {
        header('Content-type: application/json');
        die('{ "error": "You are not authorized to send mail to this mother." }');
      }
      $meta = INBOX_SENDER_SCIENTIST | INBOX_RECIPIENT_MOTHER | INBOX_MESSAGE_UNREAD;
    }
    $query = "INSERT INTO Inbox 
              (`message`, `messageDate`, `senderId`, `recipientId`, `metadata`)
              VALUES
              ('".mysql_real_escape_string(urldecode($_REQUEST['message'])).
              "', NOW(), $sender_id, $recipient_id, $meta);";
    $result = mysql_query($query);
    if (!$result) {
      error_log(mysql_error());
    } else {
      header('Content-type: application/json');
      $response = array(
          "message" => "Your message was sent",
          "timestamp" => date("U"),
          "id" => mysql_insert_id(),
          "content" => urldecode($_REQUEST['message']),
          "sent" => true
      );
      if ($jsonp)
        echo "$callback(";
      echo json_encode($response);
      if ($jsonp)
        echo ")";
    }
  }
}

?>
