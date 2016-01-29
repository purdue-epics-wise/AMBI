<?php
include_once("../../includes/constants.php");
include_once("../../includes/general.php");
require_once("../../includes/db.php");

loggedIn();
db_connect();

$query = "UPDATE Scientists SET name='".mysql_real_escape_string($_POST["name"]).
         "' WHERE sid=".$_SESSION['sid'];
mysql_query($query);
go("../profile.php");

?>
