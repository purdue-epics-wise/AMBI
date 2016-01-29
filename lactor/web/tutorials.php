<?php 
include_once("includes/general.php");
include_once("includes/db.php");


initialize();
loggedIn();
db_connect();


loadVocabulary();

?>

<head>
<?php head_tag("LACTOR - "._("Tutorials")); ?>
<link rel="stylesheet" href="css/profile.css" type="text/css" media="all" />
</head>

<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php page_menu(PAGE_TUTORIALS); ?>

<div id="pagecontent">
<div id="registercontent">


<?php if(isset($_SESSION['s_mid'])) {
	$_SESSION['Smessage']="Logged in as scientist.";$_SESSION['Stype']=2;$_SESSION['Sdetail']="";
	displayMessage('Smessage', 'Sdetail', 'Stype');
} ?>
<?php displayNotification(); ?>

<div id="container">
<div class="tabs">
  <ul class="menu">
  <li><a href="#intro"><?php echo _("Introduction") ?></a></li>
  <li><a href="#notifications"><?php echo _("Notifications") ?></a></li>
  </ul>
  <span class="clear"></span>

  <div id="intro" class="content breastfeeding">
  <h1><?php echo _("Introduction"); ?></h1>
  <iframe src="//www.youtube.com/embed/LtnIxSWxckU" width="560" height="315"></iframe>
  </div>

  <div id="notifications" class="content supplement">
  <h1><?php echo _("Handling Notifications") ?></h1>
  <iframe src="//www.youtube.com/embed/Yj9QCO2ec8E" width="560" height="315"></iframe>
  </div>

  <input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />

  </div>
</div>
</div>
</div>


<?php page_footer(); ?>
</div>

</div>
</body>
</html>
	
	
