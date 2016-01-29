<!DOCTYPE html>
<?php

session_start();

?>
<html>
<head>
  <title> Lactor Mobile - <?php echo _("Notifications") ?> </title>
  <?php include('head.php'); ?>
</head>
<body>
<div data-role='page'>
<div data-role='header' id="header">
	<img id='header-logo' alt="Welcome" src="./logo.png" />
</div>
<div data-role='content'>
<h1>Password Reset</h1>
<?php 
if(isset($_SESSION['ResetType']) && $_SESSION['ResetType'] == 3) {
	echo "<div id=\"errorcontent\"><b>" . $_SESSION['ResetMessage'] . "</b></div>";
	unset($_SESSION['ResetType']);
	unset($_SESSION['ResetMessage']);
}
?>
</div>
<div id="loginform">
<form name="login" method="post" action="m.reset_pass.post.php">
<b><?php echo _("Email") ?>:</b>
<input name="email" type="email" />
<input name="enter" type="submit" value="Request password reset" />
</form>

<div id='loginLinks'>
  <a href="./m.login.php"><?php echo _("Back to login page") ?></a>
</div>
<?php include('footer.php'); ?>
</div>
</div>
</body>
</html>
