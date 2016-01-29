<?php
if (!session_id())
	session_start();

include_once("constants.php");
include_once("mail.include.php");
include_once("gettext.inc");

if (isset($_GET['lang'])) {
  // set a cookie to expire in 30 days.
  setcookie('lang', $_GET['lang'], time() + 2592000, '/', 'lactor.org', true);
  $_COOKIE['lang'] = $_GET['lang'];
}

if (@$_COOKIE['lang'] == 'es') {
  $locale = "es_ES";
  T_setlocale(LC_MESSAGES, $locale);
  $domain = "lactor";
  bindtextdomain($domain, WEB_ROOT."/includes/locale");
  textdomain($domain);
}


function initialize() {
	
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
}

function head_standard($title) {
	echo "<head>\n";
	echo "<title>" . $title . "</title>\n";
	echo "<link rel='stylesheet' href='".HTTP_ROOT."/css/base.css' type='text/css' media='all' />\n";
	echo "<link rel='stylesheet' href='".HTTP_ROOT."/css/jquery-ui-1.8.23.flick-theme.css' type='text/css' media='all' />\n";
  echo "<!--[if IE]><link rel='stylesheet' href='".HTTP_ROOT."/css/ie.css' type='text/css' media='all' /><![endif]-->";
  echo "<link rel='apple-touch-icon' href='".HTTP_ROOT."/touch-icon.png' />\n";
  // Google web fonts
  echo "<link href='//fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>";

	echo "</head>\n";
}

function head_tag($title) {
	echo "<title>" . $title . "</title>\n";
  echo "<base href='".HTTP_ROOT."/'>";
	echo "<link rel='stylesheet' href='".HTTP_ROOT."/css/base.css' type='text/css' media='all' />\n";
	echo "<link rel='stylesheet' href='".HTTP_ROOT."/css/jquery-ui-1.8.23.flick-theme.css' type='text/css' media='all' />\n";
  echo "<!--[if IE]><link rel='stylesheet' href='".HTTP_ROOT."/css/ie.css' type='text/css' media='all' /><![endif]-->";
  echo "<link rel='apple-touch-icon' href='".HTTP_ROOT."/touch-icon.png' />\n";
  // Google web fonts
  echo "<link href='//fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>";

	echo "<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js'></script>\n";
	echo "<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js'></script>\n";
	echo "<script type='text/javascript' src='".HTTP_ROOT."/js/jqplus.min.js'></script>\n";
	echo "<script type='text/javascript' src='".HTTP_ROOT."/js/lactor.js'></script>\n";
  echo "<script type='text/javascript'>";
  echo "$(function( ) { $('ul.lavaLampNoImage li').click( function( ) { var a = $(this).find('a'); if (a.attr('id') != 'linkself') window.location = a.attr('href'); })});";
	echo "</script>";
	echo "</head>\n";
}


function page_header() {
    //Page Header
    echo "<div id='header'>\n";
    echo "<img alt='Welcome' src='".HTTP_ROOT."/image/"._("logo.png")."' />\n";
    echo "</div>\n";
//    echo "<!--[if IE lte 8]";
//    echo "<div style='background-color: #8f8; border:1px solid black; padding:5px;'>Support for Internet Explorer 8 and lower will be discontinued on April 8, 2014. We recommend you upgrade to the latest version of Internet Explorer or choose an alternative browser. <a href='deprecation.php'>More info</a></div>";
//    echo "<![endif]-->";
//    echo "<div style='background-color: #8f8; border:1px solid black; padding:5px;'>Lactor will be unavailable Wed. May 21 at 5PM until Thurs. May 22 at 8AM for scheduled maintenance. We apologize for any inconvenience.</div>";
}

function page_menu($current) {

	if($current == PAGE_MODIFY_ENTRY) {
		echo "<ul class='lavaLampNoImage' id='2'>";
		echo "<li class='current'><a " . "id='linkself'" . " >" . "Modify Entry" . "</a></li>\n";
		echo "</ul>";
		return;
	}

	
  $option = array( 1=>
    "href='add_entry.php'",
    "href='notifications.php'",
    "href='inbox.php'",
    "href='view_diary.php'",
    "href='tutorials.php'",
    "href='profile.php'",
    "href='education.php'",
    "href='logout.php'"
  );
  $option[$current]="id='linkself'";
  $onthis = array_fill(1, 8, "");
  $onthis[$current]=" class='current' ";
  
  processNotifications();
  $notification_count;
  if ($_SESSION['num_not'] != 0) {
    $notification_count = " (" . $_SESSION['num_not'] . ")"; 
  } else {
    $notification_count = "";
  }
	
	echo "<ul class='lavaLampNoImage' id='2'>";
	echo "<li" . $onthis[1] . "><a " . $option[1] . " >"._("Add Entry")."</a></li>\n";
	echo "<li" . $onthis[2] . "><a " . $option[2] . " >"._("Notifications")."$notification_count</a></li>\n";
	echo "<li" . $onthis[3] . "><a class='inbox' " . $option[3] . " >"._("Inbox")."</a></li>\n";
	echo "<li" . $onthis[4] . "><a " . $option[4] . " >"._("View Diary")."</a></li>\n";
	echo "<li" . $onthis[5] . "><a " . $option[5] . " >"._("Tutorials")."</a></li>\n";
	echo "<li" . $onthis[6] . "><a " . $option[6] . " >"._("Profile")."</a></li>\n";
	echo "<li" . $onthis[7] . "><a " . $option[7] . " >"._("Educational Materials")."</a></li>\n";
	echo "<li" . $onthis[8] . " id='logout'><a " . $option[8] . ">"._("Logout")."</a></li>\n";
  echo "<li style='visibility:hidden;' class='clear'></li>";
	echo "</ul>";
}


function admin_menu($current) {

  $option = array(
    'href="admin/main.php"',
    'href="admin/display_data.php"',
    'href="admin/proxy.php"',
    'href="admin/user_accounts.php"',
    'href="admin/child_info.php"',
    'href="admin/logout.php"',
    'href="admin/notifications.php"',
    'href="admin/inbox.php"',
    'href="admin/profile.php"'
  );
  $option[$current]="id=\"linkself\"";

  $onthis = array_fill(0, 9, "");
  $onthis[$current]=" class=\"current\" ";
    

	
	echo "<ul class=\"lavaLampNoImage\" id=\"2\">";
	echo "<li" . $onthis[0] . "><a " . $option[0] . " >" . _("Dashboard") . "</a></li>\n";
	echo "<li" . $onthis[6] . "><a " . $option[6] . " >" . _("Notifications") . @$n . "</a></li>\n";
	echo "<li" . $onthis[1] . "><a " . $option[1] . " >" . _("Display Data") . "</a></li>\n";
	//echo "<li" . $onthis[2] . "><a " . $option[2] . " >" . _("Log In As Mother") . "</a></li>\n";
	echo "<li" . $onthis[7] . "><a class='inbox' " . $option[7] . " >" . _("Inbox") . "</a></li>\n";
	echo "<li" . $onthis[3] . "><a " . $option[3] . " >" . _("Accounts") . "</a></li>\n";
	echo "<li" . $onthis[8] . "><a " . $option[8] . " >" . _("Profile") . "</a></li>\n";
	//echo "<li" . $onthis[4] . "><a " . $option[4] . " >" . _("Child Information") . "</a></li>\n";
	echo "<li" . $onthis[5] . " id='logout'><a " . $option[5] . " >" . _("Logout") . "</a></li>\n";
  echo "<li style='visibility:hidden;' class='clear'></li>";
	echo "</ul>";

}

function languageSwitcher() {
  $script_name = $_SERVER['SCRIPT_NAME'];
  // stupid hack for dev server
  $script_name = str_replace('/lactor-devel/', '/devel/', $script_name);
    if (@$_COOKIE['lang'] == 'es')
      return "<a href='$script_name?lang=en'>Do you prefer english?</a>";
    return "<a href='$script_name?lang=es'>¿Prefiere Español?</a>";
}

function page_footer() {
    //Page Footer
    echo "<div id='footer'>\n";
    echo "<p></p>";
      
    echo "<p>";
    echo languageSwitcher();
    if(isset($_SESSION['mid'])) {
      echo " | <a href='mailto:LACTOR <".WEB_SUPPORT_EMAIL.">?subject=LACTOR Web Issue for Mother " . $_SESSION['mid'] . "'>"._("Contact Web Support")."</a>";
    }
    else {
      echo " | <a href='mailto:LACTOR <".TECH_SUPPORT_EMAIL.">?subject=LACTOR Web Issue'>"._("Contact Web Support")."</a>";
    }
    echo " | <a href='security_info.php'>"._("View Security/Privacy Information")."</a></p>";    

    echo "<p>"._("This project is supported by grants from")." ";
    echo "<a href='http://ilca.org'>"._("The International Lactation Consultant Association")."</a>, ";
    echo _("the")." <a href='http://www.indianactsi.org/'>"._("Indiana CTSI")."</a>, ";
    echo _("and by")." <a href='http://www.purdue.edu/bbc/'>"._("Purdue University's Bindley Bioscience Center").".</a></p>";
    echo "</div>\n";
}

function displayMessage($message, $details='', $type='') {
	if(!isset($_SESSION[$type])) {
		if(isset($_SESSION[$message])) {
			echo "<div id='standardmessage' class='standardmessageclass'>\n";
			echo $_SESSION[$message];
			if (isset($_SESSION[$details]) && strlen($_SESSION[$details])) {
        echo "<a href='#' class='showLink' onclick='showHide('standarddetail');return false;'>"._("Full message").".</a>";
        echo "<a href='#' class='hideLink' onclick='showHide('standardmessage');return false;'>"._("Close").".</a>";
        echo "<div id='standarddetail' class='more'>";
        echo $_SESSION[$details];
        echo "</div>"; 
      }
			echo "</div>\n";
			echo "<br />\n";
        unset($_SESSION[$details]);
			unset($_SESSION[$message]);
		} else {
			echo "<br />";
		}
	} else {
		//all good
		if($_SESSION[$type] == 1) {
			echo "<div class='successMessage'>"; 
			echo $_SESSION[$message];
//			echo "<br />";
//			echo "" . $_SESSION[$details] . "";
			echo "</div>";
			echo "<br />";
		} else if($_SESSION[$type] == 2) { //attention
			echo "<div id='warnmessage'>"; 
			echo $_SESSION[$message];
//			echo "<br />";
//			echo "" . $_SESSION[$details] . "";
			echo "</div>";
			echo "<br />";			
		} else if($_SESSION[$type] == 3) { //error
			echo "<div id='errormessage'>"; 
			echo $_SESSION[$message];
//			echo "<br />";
//			echo "" . $_SESSION[$details] . "";
			echo "</div>";
		}
		unset($_SESSION[$message]);
		unset($_SESSION[$details]);
		unset($_SESSION[$type]);
		
	}
}

function displayNotification() {
	include_once("includes/mail.include.php");
	processNotifications( );

  $id = isset($_SESSION['sid']) ? $_SESSION['sid'] : $_SESSION['mid'];
  $scientist = isset($_SESSION['sid']);

  // find the number of unread notifications
  $notifications = getNotificationCount($id, $scientist);
  // find the number of unread messages;
  $unreadMessages = getMessageCount($id, $scientist)['unread'];
	
	if($notifications > 0 || $unreadMessages > 0) {
		echo "<div id='warnmessage'>\n";
    if ($notifications > 0) {
      echo ""._("Attention: You have")." $notifications "._("new notifications").
          ". <a href='notifications.php'> "._("View")."</a>.<br>";
    }
    if ($unreadMessages > 0) {
      echo ""._("Attention: You have")." $unreadMessages "._("new messages").
          ". <a href='inbox.php'> "._("View")."</a>.<br>";
    }
		echo "</div>\n";
		echo "<br />\n";
	}
	
	$query = "UPDATE Notifications SET status = 1 WHERE status = 0 AND mid = " . $_SESSION['mid'] . ";";
	$result = mysql_query($query);
}

function processNotifications( $mid=null ) {
  if ( $mid == null )
    $mid = $_SESSION['mid'];
  if ( isset($_SESSION['email']) ) {
    $email = $_SESSION['email'];
  } else {
    $query = "SELECT email FROM Mothers where mid=$mid";
    $row = mysql_fetch_assoc(mysql_query( $query ));
    $email = $row['email'];
  }
	$query = "SELECT * FROM Notifications WHERE mid = $mid;";
	$counter = 0;
	$result = mysql_query($query);
  if ( !$result ) {
    error_log( mysql_error( ));
  }
	
	while($row = mysql_fetch_array($result)) {
		if($row['status'] != 2 && $row['status'] != 8) {
			$counter = $counter + 1;
		}
		if($row['status'] == 0) {
			$title = notificationTitle($row['ntype']);
			$body  = notificationText( $row['ntype']);
			$mail = generateNotMail($title, $email, $body);
			
      // determine this mother's consultant and generate an email.
      $consultant_query = "SELECT S.email FROM Mothers M LEFT JOIN Mothers_Scientists MS ON MS.mid=M.mid LEFT JOIN Scientists S ON S.sid=MS.sid WHERE M.mid=$mid;";
      $consultant_result = mysql_query( $consultant_query );
      if ( !$consultant_result ) {
        error_log(mysql_error( ));
      } else if (!mysql_num_rows($consultant_result)) {
        error_log("Unable to determine consultant for Mother $email");
      } else {
        $title="Notification issued to $email.";
        if($row['ntype'] == 1) {
          $body = "Latching ";			
        }
        else if($row['ntype'] == 2) {
          $body = "Sleepy ";			
        }			
        $body = "<h2>Notification issued to mother $email</h2><br />".$body;
        $consultant_row = mysql_fetch_assoc($consultant_result);
        $mail = generateNotMail($title,$consultant_row['email'], $body);
      }
		}
	} 
	$_SESSION['num_not'] = $counter;
	
	$query = "UPDATE Notifications SET status = 1 WHERE status = 0 AND mid = $mid;";
	$result = mysql_query($query);
}

function go($direction) {
	mysql_close($_SESSION['db_con']);
	header('Location: ' . $direction . '');
	exit;
}

function genRandomString() {
    $length = 8;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRST";
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

define('TODAY', strtotime('today'));
define('YESTERDAY', strtotime('yesterday'));
define('LAST_WEEK', strtotime('-1 week 12:00:00AM') + 86400);

function formatDate($timestamp) {
   if ($timestamp > TODAY) { // less than 1 day old
    $date = _("Today");
   } else if ($timestamp > YESTERDAY) { // less than 2 days old
    $date = _("Yesterday");
   } else if ($timestamp > LAST_WEEK) { // less than a week old
    $date = date("l", $timestamp); // the full day of the week
   } else {
    $date = date("F d,", $timestamp); // the full date
   }
   return $date . date(" g:i a", $timestamp);
 }


function modDate($d) {
	$newDate = substr($d, 6, 4) . "-";
	$newDate = $newDate . substr($d, 0, 2) . "-";
	$newDate = $newDate . substr($d, 3, 2);
    return $newDate;
}

function modDate2($d) {
	$newDate = substr($d, 5, 2) . "/";
	$newDate = $newDate . substr($d, 8, 2) . "/";
	$newDate = $newDate . substr($d, 0, 4);
    return $newDate;
}

function getDateTime($d) {
	$newDate = substr($d, 5, 2) . "/";
	$newDate = $newDate . substr($d, 8, 2) . "/";
	$newDate = $newDate . substr($d, 0, 4) . " ";
	$h = substr($d, 11, 2); $am;
	if($h < 12) {$am = "AM"; if ($h == 0) { $h = 12; } }
	else {$am = "PM"; if ($h != 12) { $h = $h - 12; } }
	$newDate = $newDate . $h . ":";
	$newDate = $newDate . substr($d, 14, 2) . " ";
	$newDate = $newDate . $am;
	return $newDate;
}

function getTime($d) {
	$newDate = substr($d, 11, 2); 
	$newDate = $newDate . " : " . substr($d, 14, 2);
	return $newDate;
}

function passed($d1) {
	if(strtotime($d1) >= mktime(0, 0, 0, date("m") , date("d") - 1, date("Y"))) {
		return 0;
	}
	else {
		return 1;
	} 
	$edatetime = strtotime($d1);
	$ndatetime = time();
	
	$tdate = 48 * 60 * 60;

	if(($ndatetime - $edatetime) < $tdate) {
		return 0;
	}
	else {
		return 1;
	} 
}

function loggedIn($forward = true) {
	if(!isset($_SESSION['mid']) && !isset($_SESSION['sid'])) {
    if ($forward) {
      $_SESSION['LoginMessage'] = "You need to log in before you can navigate this site.";
      $_SESSION['LoginType'] = 3;
      go('login.php');
    } else {
      return false;
    }
	}
  return true;
}

function notificationText( $type ) {
  switch( $type ) {
    case NOTIFICATION_LATCHING: 
      return _("You have recently experienced difficulty with your baby latching. Please do the following").": <br /><ol>".
        "<li>"._("Skin-to-skin contact (remove all clothing from the baby -except diaper-, place the baby on your bare chest. The baby will crawl to the breast and latch).")."</li>".
        "<li>"._("Pump every 3 hours if baby does not latch.")."</li>".
        "<li>"._("Contact local lactation consultant for help.")."</li>".
        "</ol>";
    case NOTIFICATION_SLEEPY:
      return _("Your baby has been difficult to wake and breastfeed. Please do the following").": <br /><ol>".
        "<li>"._("Skin-to-skin contact (remove all clothing from baby except diaper, place the baby on your bare chest. The baby will crawl to the breast and latch)")."</li>".
        "<li>"._("Pump after each feeding if baby did not get enough.")."</li>".
        "<li>"._("Contact the primary care provider if continues (baby should not go for more than 5 hours without feeding for the first 5 weeks)")."</li>".
        "</ol>";
    case NOTIFICATION_ENGORGEMENT:
      return _("You have recently experienced breast engorgement. Please try the following").":<br /><ol>".
        "<li>"._("Manually express or pump enough to soften your breast before putting your baby to the breast").".</li>".
        "<li>"._("Breastfeed your baby more often.")."</li>".
        "<li>"._("Make sure that your baby is latching on and feeding well.")."</li>".
        "<li>"._("Apply cold packs to the breasts after feeding to help reduce swelling, warmth, and pain.")."</li>".
        "</ol>";
    case NOTIFICATION_JAUNDICE:
      return _("You have reported that your baby having a yellowish discoloration of the skin. Please do the following").":<br/><ol>".
        "<li>"._("Contact your primary care provider.")."</li>".
        "<li>"._("Breastfeed the baby more frequently.")."</li>".
        "<li>"._("Make sure that the baby is latching correctly.")."</li>".
        "<li>"._("Report any decrease in wet and dirty diapers.")."</li>".
        "</ol>";
    case NOTIFICATION_SORE_NIPPLE:
      return _("You have recently experienced sore nipples. Please try the following").":<br /><ol>".
        "<li>"._("Make sure that your baby is latched on well, so that baby opens mouth wide.")."</li>".
        "<li>"._("Continue breast-feeding even if nipples are sore or cracked. Try to feed the baby from the less painful breast first")."</li>".
        "<li>"._("Detach your baby carefully off of the breast when feeding is finished")."</li>".
        "<li>"._("Breastfeed often (Every 1&frac12; to 3 hours for 8 to 12 feedings a day) to reduce engorgement.")."</li>".
        "<li>"._("Vary nursing positions, so the baby's positions on the breast are changed.")."</li>".
        "<li>"._("Express colostrum or milk onto your nipples before and after feedings.")."</li>".
        "<li>"._("After feeding, allow the nipples to air dry.")."</li>".
        "</ol>";
    case NOTIFICATION_INSUFFICIENT_BREASTFEEDING:
      return _("Your records showed that you breastfed your baby less than 6 times last 24 hours. Please note the following").":<br /><ol>".
        "<li>"._("Ideal breastfeeding during the first month is from 8 to 12 times per day. Feeding your baby less than 6 times per day will decrease your milk supply.")."</li>".
        "<li>"._("Try to breastfeed your baby as much as you can and avoid any supplementation.")."</li>".
        "<li>"._("Contact your lactation consultant if you have any question.")."</li>".
        "</ol>";
    case NOTIFICATION_NIPPLE_SHIELD:
      return _("Your records showed that you are using nipple shields. Please note the following")."<br /><ol>".
        "<li>"._("Nipple shields should in general be considered a short-term solution and should be used under the guidance of a Lactation Consultant")."</li>".
        "<li>"._("Work with you lactation consultant to solve the problem behind using nipple shields.")."</li>".
        "<li>"._("Make sure that your baby is getting enough milk and gaining weight")."</li>".
        "</ol>";
        "</ol>";
    case NOTIFICATION_SUFFICIENT_BREASTFEEDING:
      return _("You did a great job of entering your breastfeeding data entries today! Keep up the great work!").":<br /><ol>".
        "<li>"._("Effective breastfeeding is from 8 to 12 times per day.")."</li>".
        "</ol>";
    case NOTIFICATION_SUFFICIENT_OUTPUT:
      return _("You did a great job of entering your baby's output data today! Keep doing what you're doing!")."<br /><br />";
    case NOTIFICATION_SUFFICIENT_READING:
      return _("You did a great job of reading your notifications today! Keep it up!")."<br /><br />";
    default:
      return "";
  }
}

function notificationTitle( $type ) {
  switch( $type ){
    case NOTIFICATION_LATCHING: 
      return _("Latching");
    case NOTIFICATION_SLEEPY:
      return _("Sleepy");
    case NOTIFICATION_ENGORGEMENT:
      return _("Engorgement");
    case NOTIFICATION_JAUNDICE:
      return _("Jaundice");
    case NOTIFICATION_SORE_NIPPLE:
      return _("Sore nipple");
    case NOTIFICATION_INSUFFICIENT_BREASTFEEDING:
      return _("Insufficient Breastfeeding");
    case NOTIFICATION_NIPPLE_SHIELD:
      return _("Nipple shield");
    case NOTIFICATION_SUFFICIENT_BREASTFEEDING:
      return _("Great job!");
    case NOTIFICATION_SUFFICIENT_OUTPUT:
      return _("Great job!");
    case NOTIFICATION_SUFFICIENT_READING:
      return _("Great job!");
    default:
      return "";
  }
}

!defined("OPTIONS_TYPE_RADIO") or define("OPTIONS_TYPE_RADIO", 1);
!defined("OPTIONS_TYPE_CHECKBOX") or define("OPTIONS_TYPE_CHECKBOX", 2);
!defined("OPTIONS_TYPE_SELECT") or define("OPTIONS_TYPE_SELECT", 3);

function createOptions($name, $options, $default=false, 
                       $optionsType=OPTIONS_TYPE_SELECT, $class="") {
  $returnvalue = "";
  if ($optionsType === OPTIONS_TYPE_SELECT) {
      $returnvalue .= "<select name='$name' class='$class'>";
  }
  foreach ($options as $k=>$v) {
    switch ($optionsType) {
      case OPTIONS_TYPE_SELECT:
        $returnvalue .= "<option value='$k'>$v</option>";
        break;

      case OPTIONS_TYPE_CHECKBOX:
        $returnvalue .= "<label><input type='checkbox' ";
        if ($k == $default) {
          $returnvalue .= "selected ";
        }
        $returnvalue .= "name='$name' value='$k' class='$class'>$v</label>";
        if (in_array($k, $default)) {
          $returnvalue .= "selected ";
        }
        break;

      case OPTIONS_TYPE_CHECKBOX:
        $returnvalue .= "<label><input type='radio' ";
        $returnvalue .= "name='$name' value='$k' class='$class'>$v</label>";
        break;

      default:
        break;
    }
  }
  if ($optionsType === OPTIONS_TYPE_SELECT) {
      $returnvalue .= "</select>";
  }
  return $returnvalue;
}

?>
