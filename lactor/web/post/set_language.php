<?php

if (!session_id())
	session_start();

$_SESSION['lang'] = $_GET['lang'];

header('HTTP/1.0 302 Found');
header('Location: '.$_SERVER['HTTP_REFERER']);

?>
