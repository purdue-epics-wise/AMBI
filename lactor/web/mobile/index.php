<?php 

include_once('../includes/general.php');

if (loggedIn(false)) {
  go('m.add_entry.php');
}

?><!DOCTYPE html>
<html>
<head>
  <title><?php echo _("Lactor Mobile") ?></title>
  <?php include('head.php'); ?>
</head>
<body>
<div data-role='page'>
<div data-role='header' id="header">
	<img id='header-logo' alt="Welcome" src="./<?php echo _("logo.png") ?>" />
</div>
<div data-role='content'>
<?php 
if(isset($_SESSION['LoginType']) && $_SESSION['LoginType'] == 3) {
	echo "<div id=\"errorcontent\"><b>" . $_SESSION['LoginMessage'] . "</b></div>";
	unset($_SESSION['LoginType']);
	unset($_SESSION['LoginMessage']);
} else if(isset($_SESSION['LoginType']) && $_SESSION['LoginType'] == 1){
	echo "<div id=\"goodcontent\"><b>" . $_SESSION['LoginMessage'] . "</b></div>";
	unset($_SESSION['LoginType']);
	unset($_SESSION['LoginMessage']);
}
?>
</div>
<div id="loginform">
<form name="login" method="post" action="m.login.post.php">
<b><?php echo _("Email") ?>:</b>
<input name="user" type="email" />
<b><?php echo _("Password") ?>:</b>
<input name="pass" type="password" />
<input name="enter" type="submit" value='<?php echo _("Log in") ?>' />
</form>
</div>

<div id='loginLinks'>
  <a href="./m.reset_pass.php"><?php echo _("Reset Password") ?></a> | 
  <a rel='external' href="./m.full_version.php"><?php echo _("Desktop Version") ?></a> |
  <?php 
    if (@$_SESSION['lang'] == 'es') {
      echo "<a href='?lang=en'>Do you prefer english?</a>";
    } else {
      echo "<a href='?lang=es'>¿Prefiere Español?</a>";
    }
  ?>
</div>
<?php include('footer.php'); ?>
</div>
</div>
</body>
</html>
