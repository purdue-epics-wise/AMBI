<?php
if ( !session_id())
	session_start();


require_once('class.phpmailer.php');

function generatePassMail($sub, $address, $reset_password, $body) {
	if(strstr($sub, "Scientist")) {
		$body = $body . "<br/><br/><a href=\"https://www.lactor.org/admin\">Log in here</a>.<br />"; 
	} else {
		$body = $body . "<br/><br/><a href=\"https://www.lactor.org/\">Log in here</a>.<br />"; 
	}
	$message=parsePassHTML($body, $reset_password);
	$to=$address;
	$headers="Content-type: text/html; charset=iso-8859-1" . "\n";
	$headers.="From: LACTOR " . "\n";
	
	mail($to, $sub, $message, $headers);

}


function parsePassHTML($body, $pass) {
$htmlBody = "";
$htmlBody = $htmlBody .  "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
$htmlBody = $htmlBody .  "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n";
$htmlBody = $htmlBody .  "	\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
$htmlBody = $htmlBody .  "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n";

$htmlBody = $htmlBody .  "<head>\n";
$htmlBody = $htmlBody .  "<style type=\"text/css\">\n";
$htmlBody = $htmlBody .  "*{";  
$htmlBody = $htmlBody .  "margin-top: 0px;";
$htmlBody = $htmlBody .  "margin-bottom: 0px;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "body{";
$htmlBody = $htmlBody .  "background-color: #b9dcff;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#maincontainer{";
$htmlBody = $htmlBody .  "background-color: White;";
$htmlBody = $htmlBody .  "border: solid 1pt black;  ";   
$htmlBody = $htmlBody .  "margin:auto;  ";
$htmlBody = $htmlBody .  "width: 1000px; ";
$htmlBody = $htmlBody .  "overflow:auto;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#header{";
$htmlBody = $htmlBody .  "font-size: 42pt;";
$htmlBody = $htmlBody .  "font-family: Arial,Helvetica,sans-serif;";
$htmlBody = $htmlBody .  "font-style: italic;";
$htmlBody = $htmlBody .  "font-weight:bolder;";
$htmlBody = $htmlBody .  "color: White;";
$htmlBody = $htmlBody .  "background-color: #0070df;   "; 
$htmlBody = $htmlBody .  "}";


$htmlBody = $htmlBody .  "#menu{  ";
$htmlBody = $htmlBody .  "background-color: #d7d7d7;";
$htmlBody = $htmlBody .  "line-height: 50px;   ";
$htmlBody = $htmlBody .  "font-family: Tahoma;";
$htmlBody = $htmlBody .  "font-size: 20px;";
$htmlBody = $htmlBody .  "font-weight:bold;";
$htmlBody = $htmlBody .  "text-align: center; ";
$htmlBody = $htmlBody .  "border-bottom: dotted 1px;";
$htmlBody = $htmlBody .  "}";


$htmlBody = $htmlBody .  "a{";
$htmlBody = $htmlBody .  "text-decoration: none;  ";
$htmlBody = $htmlBody .  "color: #004891;";
$htmlBody = $htmlBody .  "}";
$htmlBody = $htmlBody .  "a:hover {   ";
$htmlBody = $htmlBody .  "color: #000040; ";
$htmlBody = $htmlBody .  "text-decoration: underline;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#pagecontent{";
$htmlBody = $htmlBody .  "font-family: Verdana;  ";
$htmlBody = $htmlBody .  "font-size: 10pt;  ";
$htmlBody = $htmlBody .  "line-height: 20px;  ";
$htmlBody = $htmlBody .  "padding-top: 40px;";
$htmlBody = $htmlBody .  "padding-left: 20px;  ";
$htmlBody = $htmlBody .  "padding-right: 20px; ";
$htmlBody = $htmlBody .  "overflow:auto;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "img	{  ";
$htmlBody = $htmlBody .  "	border: 0px;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#footer{    ";
$htmlBody = $htmlBody .  "margin-top: 20px;";
$htmlBody = $htmlBody .  "line-height: 20px;";
$htmlBody = $htmlBody .  "text-align: center; "; 
$htmlBody = $htmlBody .  "padding: 10px;  ";
$htmlBody = $htmlBody .  "border-top: solid 1px;";
$htmlBody = $htmlBody .  "}";


$htmlBody = $htmlBody .  "#standardcontent{";
$htmlBody = $htmlBody .  "width:600px;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "</style>";
$htmlBody = $htmlBody .  "</head>";

$htmlBody = $htmlBody .  "<body>";
$htmlBody = $htmlBody .  "<div id=\"maincontainer\">";
$htmlBody = $htmlBody .  "<div id=\"header\">\n";
$htmlBody = $htmlBody .  "<img alt=\"\" src=\"https://www.lactor.org/image/logo.gif\" />Breastfeeding Diary\n";
$htmlBody = $htmlBody .  "</div>\n";

$htmlBody = $htmlBody .  "<div id=\"pagecontent\">";
$htmlBody = $htmlBody .  "<div id=\"standardcontent\">";

$htmlBody = $htmlBody .  "Your password is:   " . $pass . "<br/><br/>";

$htmlBody = $htmlBody .  $body;



    
$htmlBody = $htmlBody .  "</div> ";    
$htmlBody = $htmlBody .  "</div>";


$htmlBody = $htmlBody .  "<div id=\"footer\">\n";
$htmlBody = $htmlBody .  "<p></p>";
$htmlBody = $htmlBody .  "<p>The project is supported by the grant from the International Lactation Consultant Association (ilca.org) and by the Cyber Center.</p>\n";
$htmlBody = $htmlBody .  "</div>\n";

$htmlBody = $htmlBody .  "</div>";
$htmlBody = $htmlBody .  "</body>";
$htmlBody = $htmlBody .  "</html>";

return $htmlBody;

}

function generateNotMail($sub, $address, $body) {
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug  = 0;
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "tls";
	$mail->Host       = "smtp.purdue.edu"; // sets the SMTP server
	$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "gmartine"; // SMTP account username
	$mail->Password   = "pi016537.";        // SMTP account password
	$mail->SetFrom(WEB_SUPPORT_EMAIL, 'BreastfeedingMonitor - pCare');
	$mail->Subject  = $sub;
	$mail->AddAddress($address, "pCare");
	$mail->IsHTML(true);
	$mail->Body             = parseNotHTML($body);
	
	return $mail;

}

function parseNotHTML($body) {
$htmlBody = "";
$htmlBody = $htmlBody .  "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
$htmlBody = $htmlBody .  "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n";
$htmlBody = $htmlBody .  "	\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
$htmlBody = $htmlBody .  "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n";

$htmlBody = $htmlBody .  "<head>\n";
$htmlBody = $htmlBody .  "<style type=\"text/css\">\n";
$htmlBody = $htmlBody .  "*{";  
$htmlBody = $htmlBody .  "margin-top: 0px;";
$htmlBody = $htmlBody .  "margin-bottom: 0px;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "body{";
$htmlBody = $htmlBody .  "background-color: #b9dcff;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#maincontainer{";
$htmlBody = $htmlBody .  "background-color: White;";
$htmlBody = $htmlBody .  "border: solid 1pt black;  ";   
$htmlBody = $htmlBody .  "margin:auto;  ";
$htmlBody = $htmlBody .  "width: 1000px; ";
$htmlBody = $htmlBody .  "overflow:auto;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#header{";
$htmlBody = $htmlBody .  "font-size: 42pt;";
$htmlBody = $htmlBody .  "font-family: Arial,Helvetica,sans-serif;";
$htmlBody = $htmlBody .  "font-style: italic;";
$htmlBody = $htmlBody .  "font-weight:bolder;";
$htmlBody = $htmlBody .  "color: White;";
$htmlBody = $htmlBody .  "background-color: #0070df;   "; 
$htmlBody = $htmlBody .  "}";


$htmlBody = $htmlBody .  "#menu{  ";
$htmlBody = $htmlBody .  "background-color: #d7d7d7;";
$htmlBody = $htmlBody .  "line-height: 50px;   ";
$htmlBody = $htmlBody .  "font-family: Tahoma;";
$htmlBody = $htmlBody .  "font-size: 20px;";
$htmlBody = $htmlBody .  "font-weight:bold;";
$htmlBody = $htmlBody .  "text-align: center; ";
$htmlBody = $htmlBody .  "border-bottom: dotted 1px;";
$htmlBody = $htmlBody .  "}";


$htmlBody = $htmlBody .  "a{";
$htmlBody = $htmlBody .  "text-decoration: none;  ";
$htmlBody = $htmlBody .  "color: #004891;";
$htmlBody = $htmlBody .  "}";
$htmlBody = $htmlBody .  "a:hover {   ";
$htmlBody = $htmlBody .  "color: #000040; ";
$htmlBody = $htmlBody .  "text-decoration: underline;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#pagecontent{";
$htmlBody = $htmlBody .  "font-family: Verdana;  ";
$htmlBody = $htmlBody .  "font-size: 10pt;  ";
$htmlBody = $htmlBody .  "line-height: 20px;  ";
$htmlBody = $htmlBody .  "padding-top: 40px;";
$htmlBody = $htmlBody .  "padding-left: 20px;  ";
$htmlBody = $htmlBody .  "padding-right: 20px; ";
$htmlBody = $htmlBody .  "overflow:auto;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "img	{  ";
$htmlBody = $htmlBody .  "	border: 0px;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "#footer{    ";
$htmlBody = $htmlBody .  "margin-top: 20px;";
$htmlBody = $htmlBody .  "line-height: 20px;";
$htmlBody = $htmlBody .  "text-align: center; "; 
$htmlBody = $htmlBody .  "padding: 10px;  ";
$htmlBody = $htmlBody .  "border-top: solid 1px;";
$htmlBody = $htmlBody .  "}";


$htmlBody = $htmlBody .  "#standardcontent{";
$htmlBody = $htmlBody .  "width:600px;";
$htmlBody = $htmlBody .  "}";

$htmlBody = $htmlBody .  "</style>";
$htmlBody = $htmlBody .  "</head>";

$htmlBody = $htmlBody .  "<body>";
$htmlBody = $htmlBody .  "<div id=\"maincontainer\">";
$htmlBody = $htmlBody .  "<div id=\"header\">\n";
$htmlBody = $htmlBody .  "<img alt=\"\" src=\"https://www.lactor.org/image/logo.gif\" />Breastfeeding Diary\n";
$htmlBody = $htmlBody .  "</div>\n";

$htmlBody = $htmlBody .  "<div id=\"pagecontent\">";
$htmlBody = $htmlBody .  "<div id=\"standardcontent\">";

$htmlBody = $htmlBody .  $body;


    
$htmlBody = $htmlBody .  "</div> ";    
$htmlBody = $htmlBody .  "</div>";


$htmlBody = $htmlBody .  "<div id=\"footer\">\n";
$htmlBody = $htmlBody .  "<p></p>";
$htmlBody = $htmlBody .  "<p>The project is supported by the grant from the International Lactation Consultant Association (ilca.org) and by the Cyber Center.</p>\n";
$htmlBody = $htmlBody .  "</div>\n";

$htmlBody = $htmlBody .  "</div>";
$htmlBody = $htmlBody .  "</body>";
$htmlBody = $htmlBody .  "</html>";

return $htmlBody;

}


?>
