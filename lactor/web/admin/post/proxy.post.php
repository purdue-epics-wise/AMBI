<?php

include_once("../../includes/general.php");


loggedIn();


$_SESSION['mid'] = $_POST['mid'];
$_SESSION['s_mid'] = 1;

header("Location: ../../mother1/add_entry.php");

?>
