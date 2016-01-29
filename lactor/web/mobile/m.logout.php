<?php

session_start();

session_destroy();

header("Location: ./m.login.php");
exit;

?>
