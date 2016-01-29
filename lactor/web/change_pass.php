<?php 
include_once("includes/general.php");
initialize(); loggedIn(); ?>

<head>
<?php head_tag("LACTOR - Change Password"); ?>
<script type='text/javascript' src='js/passwords.js'></script>
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
  <?php displayMessage('ChangePassMessage','ChangePassDetails', 'ChangePassType'); ?> 


  <div id="container">
    <div class="tabs">
      <ul class="menu">
        <li><a href="#password"><?php echo _("Change Password") ?></a></li>
      </ul class="menu">
        <h1><img src="image/reset.gif" alt =""/><?php echo _("New Password") ?></h1>
        <div id="password">
          <form id="standardform" name="changepassform" action="post/change_pass.post.php" method="post">
            <pre><?php echo _("New Password") ?>:        <input id="standardtextform" type="password" name="newpass" id='passwd' onkeyup="testPassword(this.value);" value=""/> <span id='passwordStrength'></span><br /></pre>
            <pre><?php echo _("Repeat New Password") ?>: <input id="standardtextform" type="password" name="rnewpass" /><br/><br/></pre>
            <input id="standardsubmit" type="submit" value="<?php echo _("Change Password") ?>"/>
          </form>
        </div>
      </div>   
    </div>   
  </div>

  <?php page_footer(); ?>
</div>
</body>
</html>
