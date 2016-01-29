<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - "._("System Usability Scale")); ?>
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('SysMessage','SysDetails', 'SysType'); ?> 

<p><?php echo _("Please fill out all the fields.") ?></p>

<div id="container">
<ul class="menu">
<li id="breastfeeding" class="active"><?php echo _("System Feedback") ?></li>
</ul>
<span class="clear"></span>
<div class="content breastfeeding" width="500px">
<div id="standardinput">
<form id="standardform" action="post/system_feedback.post.php" method="post">
<pre>                                                                              Strongly               Strongly</pre>
<pre>                                                                              disagree                agree  </pre>
<pre><?php echo _("I think that I would like to use this system frequently") ?>:                          <input type="radio" name="group1" value="1">1 <input type="radio" name="group1" value="2">2 <input type="radio" name="group1" value="3">3 <input type="radio" name="group1" value="4">4 <input type="radio" name="group1" value="5">5 </pre><br/>
<pre><?php echo _("I found the system unnecessarily complex") ?>:                                         <input type="radio" name="group2" value="1">1 <input type="radio" name="group2" value="2">2 <input type="radio" name="group2" value="3">3 <input type="radio" name="group2" value="4">4 <input type="radio" name="group2" value="5">5 </pre><br/>
<pre><?php echo _("I thought the system was easy to use") ?>:                                             <input type="radio" name="group3" value="1">1 <input type="radio" name="group3" value="2">2 <input type="radio" name="group3" value="3">3 <input type="radio" name="group3" value="4">4 <input type="radio" name="group3" value="5">5 </pre><br/>
<pre><?php echo _("I think that I would need the support of a technical person to use this system") ?>:   <input type="radio" name="group4" value="1">1 <input type="radio" name="group4" value="2">2 <input type="radio" name="group4" value="3">3 <input type="radio" name="group4" value="4">4 <input type="radio" name="group4" value="5">5 </pre><br/>
<pre><?php echo _("I found the various functions in this system were well integrated") ?>:                <input type="radio" name="group5" value="1">1 <input type="radio" name="group5" value="2">2 <input type="radio" name="group5" value="3">3 <input type="radio" name="group5" value="4">4 <input type="radio" name="group5" value="5">5 </pre><br/>
<pre><?php echo _("I thought there was too much inconsistency in this system") ?>:                        <input type="radio" name="group6" value="1">1 <input type="radio" name="group6" value="2">2 <input type="radio" name="group6" value="3">3 <input type="radio" name="group6" value="4">4 <input type="radio" name="group6" value="5">5 </pre><br/>
<pre><?php echo _("I would imagine that most people would learn to use this system quickly") ?>:          <input type="radio" name="group7" value="1">1 <input type="radio" name="group7" value="2">2 <input type="radio" name="group7" value="3">3 <input type="radio" name="group7" value="4">4 <input type="radio" name="group7" value="5">5 </pre><br/>
<pre><?php echo _("I found the system very cumbersome to use") ?>:                                        <input type="radio" name="group8" value="1">1 <input type="radio" name="group8" value="2">2 <input type="radio" name="group8" value="3">3 <input type="radio" name="group8" value="4">4 <input type="radio" name="group8" value="5">5 </pre><br/>
<pre><?php echo _("I felt very confident using the system") ?>:                                           <input type="radio" name="group9" value="1">1 <input type="radio" name="group9" value="2">2 <input type="radio" name="group9" value="3">3 <input type="radio" name="group9" value="4">4 <input type="radio" name="group9" value="5">5 </pre><br/>
<pre><?php echo _("I needed to learn a lot of the things before I could get going with this system") ?>:  <input type="radio" name="group10" value="1">1 <input type="radio" name="group10" value="2">2 <input type="radio" name="group10" value="3">3 <input type="radio" name="group10" value="4">4 <input type="radio" name="group10" value="5">5 </pre><br/>
<pre>                                                                                                <input type="submit" value="Next"/><pre>
</div>
</form>
</div>   
</div>
</div>

<?php page_footer(); ?>
</div>
</body>
</html>
