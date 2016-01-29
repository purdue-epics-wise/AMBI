<?php

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();
db_connect();
loadVocabulary();

//update
$query = "UPDATE Notifications SET status = 2 WHERE  mid = ".$_SESSION['mid']. 
         " AND nid = ".$_POST['MarkRead']." AND status = 1;";
$result = mysql_query($query);
if (!$result)
  error_log(mysql_error());


go('../notifications.php');

?> 
