<?php 
include_once("../includes/general.php");
include_once("../includes/db.include.php");


initialize();
loggedIn();
db_connect();

loadVocabulary();

?>

<head>
<?php head_tag("Admin LACTOR - "._("Mother Log In")); ?>
</head>


<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php admin_menu(2); ?>

<div id="pagecontent">
<?php displayMessage('ProxyMessage','ProxyDetails', 'ProxyType'); ?> 

<div id="registercontent">

<div id="container">
<ul class="menu">
<li id="breastfeeding" class="active"><?php echo _("Mother's Portal") ?></li>
</ul>
<span class="clear"></span>


<div class="content breastfeeding">
<h1><?php echo _("Log In As") ?></h1>
<ul>
<form name="feedback" method="post" action="admin/post/proxy.post.php">
<pre><?php echo _("Mother") ?>:    <select name="mid"> 
		<?php 		
		$query = "select * from Mothers;";
		$result = mysql_query($query);
		
		while($row=mysql_fetch_array($result)) {
			echo "<option value=\"" . $row['mid'] . "\">" . $row['email'] . "</option>";
		} 
		?> </select>
</pre>
<br />
<pre>                  <input type="submit" name="issue" value="<?php echo _("Log In As Mother") ?>"></pre>
<br />
</form>
<ul>
</div>


<script type="text/javascript" src="js/tabs.js"></script>

<input type="hidden" id="mothersubmit" value="<?php echo _("Add Entry") ?>" name="breast" tabindex="12"  accesskey="u" />

</div>
</div>
</div>


<?php page_footer(); ?>
</div>

</div>
</body>
</html>
