<?php 
include_once("../includes/general.php");
initialize();

?>

<head>
<?php head_tag("Admin LACTOR - "._("Notification Instructions")); ?>
<link rel="stylesheet" href="css/login.css" type="text/css" media="all" />
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<h2><?php echo _("Notification Instructions") ?></h2> <br/>

<h3> <?php echo _("Sleep Problem") ?>s </h3>
<p>1. <?php echo _("Wake the baby every 3 hours.") ?> </p>
<p>2. <?php echo _("Skin-to-skin care for 30 min.") ?> </p>
<p>3. <?php echo _("Provide jaw support during feeding.") ?> </p>
<p>4. <?php echo _("Pump after each feeding (every 3 hours).") ?> </p>

<br/>

<h3> <?php echo _("Latching Problems") ?> </h3>
<p>1. <?php echo _("Pump every 3 hours.") ?></p>
<p>2. <?php echo _("Contact local lactation consultant.") ?></p>
<p>3. <?php echo _("Provide jaw support during feeding.") ?> </p>


<br/><br/>
<p><a href="admin/notifications.php"><?php echo _("Back to notifications") ?></a></p>
<br/>
<br />
</div>

<?php page_footer(); ?>
</div>
</body>
</html>
