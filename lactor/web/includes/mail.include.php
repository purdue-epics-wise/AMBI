<?php

if (!session_id( ))
	session_start();

require_once('environment.php');

function mailx( $to, $subject, $message, $headers="" ) {
  $message = escapeshellarg( "Subject: $subject\nFrom: \"LACTOR\" <".WEB_SUPPORT_EMAIL.">\n$headers\n\n$message" );
  exec( "echo $message | sendmail -f 'tmcgrew@purdue.edu' ".
    escapeshellarg( $to ));
}

function generatePassMail($to, $sub, $reset_password, $body) {
	$message=parsePassHTML($body, $reset_password);
	$headers="Content-type: text/html; charset=iso-8859-1" . "\n";
	
	mailx($to, $sub, $message, $headers);

}

function parsePassHTML($body, $pass) {
  return mailTemplate( "Your Lactor Password",
                       "Your password is: $pass<br /><br />$body" );
}

function generateNotMail($sub, $address, $body) {
	$message=parseNotHTML($body);
	$to=$address;
	$headers="Content-type: text/html; charset=iso-8859-1" . "\n";
	
	error_log( "Sending mail to $to about $sub" );
	mailx($to, $sub, $message, $headers);
}

function parseNotHTML($body) {
  return mailTemplate( "Breastfeeding Diary", $body );
}

function mailTemplate( $heading, $body ) {
  $htmlBody  =  "<!DOCTYPE html>";
  $htmlBody .=  "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">";

  $htmlBody .=  "<head>";
  $htmlBody .=  "<style type='text/css'>";
  $htmlBody .=  file_get_contents(WEB_ROOT.'/css/email.css');
  $htmlBody .=  "</style>";
  $htmlBody .=  "</head>";

  $htmlBody .=  "<body>";
  $htmlBody .=  "<div id='body'>";
  $htmlBody .=  "<div id=\"maincontainer\">";
  $htmlBody .=  "<div id=\"header\">";
  $htmlBody .=  "<div id='logo'></div>";
  $htmlBody .=  "</div>";
  $htmlBody .=  "<div id='content'>";
  $htmlBody .= "<h1>$heading</h1>";
  $htmlBody .=  "<div>$body</div>";
  $htmlBody .=  "<div id=\"footer\">";
  $htmlBody .=  "<p></p>";
  $htmlBody .=  "<p>"._("The project is supported by a grants from")." ";
  $htmlBody .=  "<a href='http://ilca.org'>The International Lactation Consultant Association</a>, ";
  $htmlBody .=  "the <a href='http://www.indianactsi.org/'>Indiana CTSI</a>, ";
  $htmlBody .=  _("and by")." <a href='http://www.purdue.edu/bbc/'>Purdue University's Bindley Bioscience Center.</a></p>";
  $htmlBody .=  "</div>";
  $htmlBody .=  "</div>";
  $htmlBody .=  "</div>";
  $htmlBody .=  "</div>";
  $htmlBody .=  "</div>";
  $htmlBody .=  "</body>";
  $htmlBody .=  "</html>";

  return $htmlBody;

}


?>
