<?php 
include_once("../includes/general.php");
initialize();

?>

<head>
<?php head_tag("Admin LACTOR - "._("Login")); ?>
<link rel="stylesheet" href="css/base.css" type="text/css" media="all" />
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('LoginMessage','LoginDetails', 'LoginType'); ?> 

<div id="container">
<div class="tabs">
  <ul class="menu">
  <li class="active"><a href="#login"><?php echo _("Login") ?></a></li>
  </ul>
  <div id="login">
  <h1><img src="image/lock.gif" alt =""/> <?php echo _("Credentials") ?></h1>
  <div id="standardinput">
  <form id="standardform" action="admin/post/login.post.php" method="post">
  <table border='0'><tbody>
  <tr><td><?php echo _("Email") ?>:</td><td><input id="standardtextform" type="text" name="user" /><br /></td></tr>
  <tr><td><?php echo _("Password") ?>:</td><td><input id="standardtextform" type="password" name="pass" /><br/> <br/></td></tr>
  </tbody></table>
  <input id="standardsubmit" type="submit" value="<?php echo _("Log In") ?>" />
  <span style="margin-left:100px;">
  <?php 
    if (@$_SESSION['lang'] == 'es') {
      echo "<a href='admin/post/set_language.php?lang=en'>Do you prefer english?</a>";
    } else {
      echo "<a href='admin/post/set_language.php?lang=es'>¿Prefiere Español?</a>";
    }
  ?>
  </span>
  </div>
  </form>
  </div>   
</div>
</div>

<p><a href="admin/reset_pass.php"><?php echo _("Forgot your password?") ?></a></p>
<br /><br />
<br/>
<br />
</div>

<?php page_footer(); ?>
</div>
</body>
</html>
