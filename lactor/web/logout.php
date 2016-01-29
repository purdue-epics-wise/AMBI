<?php
session_start();

include_once("includes/general.php");

if(isset($_SESSION['s_mid'])) {
	go("../admin/main.php");

} else {
	$_SESSION = array();
	
	session_destroy();
	
}


head_standard("LACTOR - Logout");

?> 

<body>
<div id="maincontainer">

<?php page_header(); ?>

<div id="pagecontent">
<div id="registercontent">
<h3><p><i><?php echo _("You have successfully logged out.") ?></i></p></h3>
<br />
<p><a href="login.php"><?php echo _("Log back in.") ?></a></p>
</div>
</div>

<!-- Page Footer -->
<?php page_footer(); ?>


</div>
</body>
</html>
