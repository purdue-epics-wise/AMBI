<?php 

include_once('../includes/general.php');

?><!DOCTYPE html>
<html>
<head>
  <title>Lactor Mobile - Change Password</title>
  <?php include('head.php'); ?>
  <script type="text/javascript" src="../js/passwords.js"></script>
</head>
<body>
<div data-role='page'>
  <?php
  include('header.php');
  ?>
  <div data-role='content'>

  <h1><?php echo _("Change Password") ?></h1>
  <form id="standardform" name="changepassform" action="m.change_pass.post.php" method="post">
    <table border='0'><tbody>
      <tr><td><?php echo _("New Password") ?>:</td><td><input id="standardtextform" type="password" name="newpass" id='passwd' onkeyup="testPassword(this.value);" value=""/> <span id='passwordStrength'></span><br /></td></tr>
      <tr><td><?php echo _("Repeat New Password") ?>:</td><td><input id="standardtextform" type="password" name="rnewpass" /></td></tr>
    </tbody></table>
    <br/><br/>
    <input id="standardsubmit" type="submit" value="<?php echo _("Change Password") ?>"/>
  </form>

  <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>
