<?php 
include_once("../includes/general.php");
initialize();

?>

<head>
<?php head_tag("Admin LACTOR - "._("Reset Password")); ?>
<link rel="stylesheet" href="css/base.css" type="text/css" media="all" />
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('ResetMessage','ResetDetails', 'ResetType'); ?> 

  <div id="container">
    <div class="tabs">
      <ul class="menu">
        <li class="active"><a href="#reset"><?php echo _("Reset Password") ?></a></li>
      </ul>
      <div class="content breastfeeding" style="padding:1em;">
      <h1><img src="image/reset.gif" alt =""/> <?php echo _("Enter your email address") ?></h1>
      <div id="standardinput">
      <form id="standardform" action="admin/post/reset_pass.post.php" method="post">
      <pre><?php echo _("Email") ?>:    <input id="standardtextform" type="text" name="email" /><br /></pre>
      <input id="standardsubmit" type="submit" value="Reset" />
      </div>
      </form>
    </div>
    </div>   
  </div>

</div>

<?php page_footer(); ?>
</div>
</body>
</html>

