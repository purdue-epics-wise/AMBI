<?php 
include_once("includes/general.php");
initialize();
loggedIn();

?>

<head>
<?php head_tag("LACTOR - "._("Graceland University Research Participant Consent Form")); ?>
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
<h2 style='text-align: center'><?php echo _("Informed Consent (For Mothers)<br>Graceland University School of Nursing") ?></h2>
<p style='text-align: center'>

<h3>What is the purpose of this study?</h3>
<p>The purpose of this study is to explore the lactation consultants&rsquo; perceptions and experiences with the interactive web-based breastfeeding monitoring system (LACTOR). To help mothers breastfeed longer without giving food other than breast milk, our research team developed an interactive online breastfeeding monitoring system where the mothers could enter their breastfeeding data and a lactation consultant monitor their data and intervene in case of breastfeeding problems. The goal was to keep the mothers and lacation consultants (LC) connected and detect early breasfeeding problems. The system was tested among mothers and proved to be useful in improving breastfeeding outcomes.</p>
<p>Data collected from this study will help in identifying strategies to maintain the communication between the mothers and lactation consultants and determine the target mother/baby population who could benefit from the system.</p>


<h3>What will I do if I choose to be in this study?</h3>
<p>You will be given access to the system and your lactation consultant will ask you to enter your age, educational level, residency, breastfeeding data such as how many times did you breastfeed your baby per day, number of wet and dirty diapers, breastfeeding problems, any feeding supplementation given, pumping, and any health problems with your baby. The lactation consultant will explain to you how to use the system and you will be given a handout that also explains how to use the system. Please note that the use of the system is not part of the standard care you receive. The use of the LACTOR system is voluntary and is used for research purposes only.</p>

<h3>How long will I be in the study?</h3>
<p>You will have access to the interactive web-based breastfeeding monitoring system to communicate with your lactation consultant for two months. You will enter your breastfeeding data as long as your lactation consultant would ask you, however the system proved to be useful to use during the first month after giving birth during the time of establishing breastfeeding.</p>

<h3>Are there any potential benefits?</h3>
<p>There is no direct benefit from using the system. You might benefit from using the system by monitoring your breastfeeding pattern, early detect any breastfeeding problems and find the strategies to solve it besides keeping in touch with your lactation consultant. The system also might contribute to improving breastfeeding outcomes especially continuation and exclusivity.</p>

<h3>What are the possible risks or discomforts?</h3>
<p>Data entry might be overwhelming to mothers, but you can enter your data at your convenience. Also, your partner or significant other could help in data entry. The system is also available through the internet via your computer, mobile web or via the mobile phone app on the Google Play and Apple App stores.</p>

<h3>Confidentiality</h3>
<p>The research study records may be reviewed by departments at Purdue University responsible for regulatory and research oversight and Dr. Rojjanasrirat, a researcher at Graceland University School of Nursing. All access to the database will be by means of usernames and passwords. Records will be kept for 3 years and may be used for future studies. Your names will not be used. There will be a single source for identifying the subject with the IDs. That source will be kept in a separate locked file cabinet at the PI office and online on a secure server which is password protected. Mothers and lactation consultants' data entries will be stored on a secure server at Bindley Bioscience Center at Purdue University where the Interactive Breastfeeding Monitoring System (LACTOR) is hosted.</p>
<p>You will not be identified in the reporting of results; only subject numbers will be used. Your individual identity will remain confidential. Your participation in the study indicates your understanding of the study and agreement to participate to the best of your ability. Please ask the researcher any questions you mave have. If you choose to particiapte, indicate that you agree and click &ldquo;Continue&rdquo;. If you choose not to participate, please close this browser tab.</p>
<p>If you have any questions about your rights while taking part in the study or have any concerns about the research participation, please call the Human Subject Research Committee office at (816) 423-4720, email hsrc@graceland.edu, or write to:</p>

<p>Human Subject Research Committee<br>
Graceland University<br>
1401 W Truman Road<br>
Independence, MO 64050</p>

<p>Primary Investigator:<br>
Wilaiporn Rojjanasrirat<br>
rojjanas@graceland.edu<br>
(903) 709-2866</p>

<form action='post/gu_consent_form.php' method='post'>
<label><input type='checkbox' name='agree' value='1'>I understand and agree to the terms stated above</label>
<br>
<button type='submit'>Continue</button>
</form>

</body>
</html>
