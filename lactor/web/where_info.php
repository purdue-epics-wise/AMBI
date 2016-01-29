<?php 
include_once("includes/general.php");
initialize();

?>

<head>
<?php head_tag("LACTOR - "._("Login Information")); ?>
<link rel="stylesheet" href="css/login.css" type="text/css" media="all" />
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">

<p> <b><?php echo _("Welcome!") ?></b> </p>
<p> <?php echo _("We are very excited that you have decided to join this experiment. We hope that we can help you secure your baby's current and future health by using our monitoring system.") ?> </p>
<p> <?php echo _("You should have an email from LACTOR, with the subject New Mother User. If you do not find it, look for it in the junk mail, if it is not there, contact us by clicking the link at the bottom.") ?> </p>
<p> <?php echo _("In this email you will find your temporary password. You will be asked to change it as soon as you log in.") ?> </p>
<p> <?php echo _("Again, we would like to thank you for using our system. We hope you enjoy it.") ?> </p>
<br />

   
</div>

<br/>

<br />


<?php page_footer(); ?>
</div>
</body>
</html>
