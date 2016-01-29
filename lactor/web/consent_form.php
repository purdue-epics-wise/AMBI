<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - "._("Research Participant Consent Form")); ?>
<style type='text/css'>
#errormessage {
	background: #FFEBE8;
	border-style: solid;
	border-width: 1px;
	border-color:  #DD3C10;
	padding: 5px;
	width: 450px; 
	overflow:auto;
}

h3 {
  text-decoration: underline;
}
</style>
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">
<?php displayMessage('ErrorMessage', '', 'ErrorType'); ?> 

<div id="container">
<span class="clear"></span>
<div class="content breastfeeding" width="500px">
<div id="standardinput">
<h2 style='text-align: center'><?php echo _("Research Participant Consent Form") ?></h2>
<p style='text-align: center'>
Interactive Web-based Breastfeeding Monitoring: Lactation Consultants<br>
Perceptions<br>
Azza Ahmed<br>
School of Nursing<br>
Purdue University<br></p>

<h3>What is the purpose of this study?</h3>
<p>The purpose of this study is to explore The lactation consultants&rsquo; perceptions and experiences with the interactive web-based breastfeeding monitoring system.</p>
<p>To help mothers breastfeed longer without giving food other than breast milk, our research team developed an online breastfeeding monitoring system where the mothers could enter their breastfeeding data and a lactation consultant will monitor their data and intervene in case of breastfeeding problems.
The goal was to keep the mothers and lactation consultants (LC) connected and detect early breastfeeding problems.
The system was tested among mothers and proved to be useful in improving breastfeeding outcomes.</p>

<p>Data collected from this study will help in identifying strategies to maintain the communication between the mothers and lactation consultants and determine the target mother/baby population who could benefit from the system</p>

<h3>What will I do if I choose to be in this study?</h3>
<p>You will be given access to the system and your lactation consultant will ask you to enter your age, educational level, residency, breastfeeding data such as how many times did you breastfeed per day, number of wet and dirty diapers, breastfeeding problems, any supplementation given, pumping and any health problems with your baby.
The lactation consultant will explain to you how to use the system and you will be given a handout taht also explains how to use the system. Pleast note that the use of the system is not a part of the standard care you receive.</p>

<h3>How long will I be in the study?</h3>
<p>You will enter your breastfeeding data as long as your lactation consultant would ask you, however the system proved to be useful to use during the forst month after giving birth during the time of establishing breastfeeding.</p>

<h3>What are the possible risks or discomforts?</h3>
<p>Data entry might be overwhelming to the mothers, but you cna enter your data according to your time convenience.
Also your partners or significant others could help in the data entry.
The system is also available through regular internet, mobile version and Android and iOS apps, so they could use the available device they have (PC, laptop, or smart phones).</p>

<h3>Are there any potential benefits?</h3>
<p>There is no direct benefit from using the system. You could benefit from the system by monitoring your breastfeeding pattern, early detect any breastfeeding problems and find the strategies to solve it besides keeping in touch with your lactation consultant.
The system could contribute in improving breastfeeding outcomes, especially continuation and exclusivitiy</p>

<h3>Will information about me and my participation be kept confidential?</h3>
<p>the research study records may be reviewed by departments at Purdue University responsible for regulatory and research oversight and the School of Nursing.
All accesses to the database will be by means of usernames and passwords. Records will be kept for 3 years and could be used for future studies.
Your name will not be used. 
There will be a single source for identifying subjects with their IDs.
That source will be kept in a separate locked file cabinet at the PI office and on an online secure server. Mothers&rsquo; data entries will be stored on a secure server at Bindley Bioscence Center at Purdue University where the INteractive Breastfeeding Monitoring system (LACTOR) is hosted.</p>

<h3>What are my rights if I take part in this study?</h3>
<p>Your participation in this study is voluntary.
You may choose not to participate or, if you agree to participate, you can withdraw your participation at any time without penalty or loss of benefits to which you are otherwise entitled.
Participation in the study will not affect the standard of care you receive.</p>

<h3>Who can I contact if I have questions about the study?</h3>
<p>If you have questions, comments or concerns about this research project, please contact Azza Ahmed at (765) 494-1272 or e-mail ahmedah@purdue.edu</p>
<p> If you have questions about your rights while taking part in the study or have concerns about the treatment of research participants, please call the Human Reserach Protection Program at (765) 494-5492, email irb@purdue.edu, or write to:</p>
<p>Human Research Protection Program - Purdue University<br>
Ernest C. Young Hall, Room 1032<br>
155 S. Grant St,<br>
West Lafayette, IN, 47907-2114 </p>

<form action='post/consent_form.php' method='post'>
<label><input type='checkbox' name='agree' value='1'>I understand and agree to the terms stated above</label>
<br>
<button type='submit'>Continue</button>
</form>

</body>
</html>
