<?php

include_once('../includes/environment.php');

session_start();

$_SESSION['mobile'] = 0;

header('Location: https://www.lactor.org'.HTTP_ROOT.'/');
exit;

?>
