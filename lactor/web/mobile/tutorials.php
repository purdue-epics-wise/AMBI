<?php 

include_once('../includes/general.php');

?><!DOCTYPE html>
<html>
<head>
  <title> Lactor Mobile - Add Entry </title>
  <?php include('head.php'); ?>
</head>
<body>
<div data-role='page'>
  <?php
  include('header.php');
  ?>
  <div data-role='content'>
    <h1><?php echo _("Desktop site tutorials") ?></h1>
    <div id="entries" data-role="collapsible">
      <h1><?php echo _("Introduction"); ?></h1>
      <iframe src="//www.youtube.com/embed/LtnIxSWxckU" width="560" height="315"></iframe>
    </div>

    <div id="notifications" data-role="collapsible">
      <h1><?php echo _("Handling Notifications") ?></h1>
      <iframe src="//www.youtube.com/embed/Yj9QCO2ec8E" width="560" height="315"></iframe>
    </div>

    <?php include('footer.php'); ?>
    </div>
  </div>
</div>
</body>
</html>
