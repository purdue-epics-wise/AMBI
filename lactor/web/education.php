<?php 
include_once("includes/general.php");
include_once("includes/db.php");


initialize();
loggedIn();
db_connect();


loadVocabulary();

?>

<head>
<?php head_tag("LACTOR - Tutorials"); ?>
<link rel="stylesheet" href="css/profile.css" type="text/css" media="all" />
</head>

<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php page_menu(PAGE_EDUCATIONAL_MATERIALS); ?>

<div id="pagecontent">
<div id="registercontent">


<?php if(isset($_SESSION['s_mid'])) {
	$_SESSION['Smessage']=_("Logged in as scientist.");
  $_SESSION['Stype']=2;
  $_SESSION['Sdetail']="";
	displayMessage('Smessage', 'Sdetail', 'Stype');
} ?>
<?php displayNotification(); ?>

<div id="container">
</ul>
<span class="clear"></span>

<div>
<h1><?php echo _("Educational Materials") ?></h1><br />
	<ul style="font-size: 1.2em;font-weight:bold;">
		<li>
		 <a href="education/<?php echo _("Latching.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("Latching") ?></a>
		</li>
		<li>
			<a href="education/<?php echo _("Managing_your_milk_supply.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("Managing your milk supply") ?></a>
		</li>
		<li>
			<a href="education/<?php echo _("Milk_expression_and_pumping.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("Milk expression and pumping") ?></a>
		</li>
		<li>
			<a href="education/<?php echo _("My_Baby_is_Jaundiced.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("My baby is Jaundiced") ?></a>
		</li>
		<li>
			<a href="education/<?php echo _("Tips_for_breastfeeding_your_premature_baby.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("Tips for breastfeeding your premature baby") ?></a>
		</li>
		<li>
			<a href="education/<?php echo _("When_your_baby_is_born_a_little_early.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("When your baby is born a little early") ?></a>
		</li>
		<li>
			<a href="education/<?php echo _("feeding_cues.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("Feeding Cues") ?></a>
		</li>
		<li>
			<a href="education/<?php echo _("Breastfeeding_and_returning_to_work.pdf") ?>"><img src='image/pdficon_small.gif' /> <?php echo _("Breastfeeing and returning to work") ?></a>
		</li>
	</ul>
</div>

<input type="hidden" id="mothersubmit" value="<?php echo _("Add Entry") ?>" name="breast" tabindex="12"  accesskey="u" />

</div>
</div>
</div>


<?php page_footer(); ?>
</div>

</div>
</body>
</html>
	
	
