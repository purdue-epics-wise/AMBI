<!DOCTYPE html>
<?php

session_start();

include_once("../includes/mail.include.php");
include_once("../includes/general.php");

if(!isset($_SESSION['mid'])) {
	$_SESSION['LoginMessage'] = "Session expired.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
}

include_once("../includes/db.php");
$conn = db_connect( );
if (!$conn) {
	die('Could not connect');
}

mysql_select_db("Breast1", $conn);
$_SESSION['db_con'] = $conn;

if(!isset($_SESSION['EntryDisplay'])) 
	$_SESSION['EntryDisplay'] = 0;


$error_message = "";
$success_message = "";
$active_tab = "breastfeeding";
$hour = isset($_POST[ 'entryhour' ]) ? $_POST[ 'entryhour' ] : date( 'g' );
$minute = isset( $_POST[ 'entryminute' ]) ? $_POST[ 'entryminute' ] : date( 'i' );
$minute -= $minute % 5; // round down the the closest 5 minute increment.
$ampm = isset( $_POST[ 'entryam' ]) ? ($_POST[ 'entryam' ] == "01" ? "AM" : "PM") : date( 'A' );
$today = "SELECTED";
$yesterday = "";
if(@$_POST['which'] == "00") {
	$today = "";
	$yesterday = "SELECTED";
}


if ( count( $_POST )) {
	$dateString = sprintf( "%s %d:%s%d %s", ($_POST['which'] == "01" ? "Today" : "Yesterday"), $hour, ($minute < 10 ? "0" : ""), $minute, $ampm);
	$entryDate = date_create( $dateString )->format( "Y-m-d H:i" );
	// =================================== BREASTFEEDING FORM ================================================
  if ( $_POST[ 'duration_left' ] || $_POST[ 'duration_right' ] || 
       $_POST[ 'latching' ] || $_POST[ 'maternal_problems' ] ) {

		if ( !$_POST[ 'duration_left' ] && !$_POST[ 'duration_right' ] ){
			$error_message .= _("Please enter breastfeeding duration");
		} 
		if ( !$_POST[ 'latching' ] ) {
			$error_message .= _("Please enter breastfeeding latching");
		} 
		if ( !$_POST[ 'infant_state' ] ) {
			$error_message .= _("Please enter breastfeeding infant alertness");
		} 
		if ( !$_POST[ 'maternal_problems' ] ) {
			$error_message .= _("Please enter breastfeeding maternal problems");
		} 
		if ( empty( $error_message )) { 
			// insert the entry into the database
			if ( !$_POST['duration_right'] ) {
				$duration = $_POST[ 'duration_left' ];
				$side = 1; // left
			} elseif ( !$_POST['duration_left'] ) {
				$duration = $_POST[ 'duration_right' ];
				$side = 2; // right
			} else {
				// combine the 2 durations to determine the entry, using the best guess (ugly)
				$duration_time = 0;
				switch( $_POST['duration_left'] ) {
					case 1:
						$duration_time += 1.5;
						break;
					case 2:
						$duration_time += 3.5;
						break;
					case 3:
						$duration_time += 7.5;
						break;
					case 4:
						$duration_time += 13;
						break;
					case 5:
						$duration_time += 15;
				}
				switch( $_POST['duration_right'] ) {
					case 1:
						$duration_time += 1.5;
						break;
					case 2:
						$duration_time += 3.5;
						break;
					case 3:
						$duration_time += 7.5;
						break;
					case 4:
						$duration_time += 13;
						break;
					case 5:
						$duration_time += 15;
				}
				if ( $duration_time >= 15 ) {
					$duration = 5;
				} elseif ( $duration_time >= 11 ){
					$duration = 4;
				} elseif ( $duration_time >= 5 ){
					$duration = 3;
				} elseif ( $duration_time >= 3 ){
					$duration = 2;
				} else {
					$duration = 1;
				}
				$side = 3; //both
			}
			if ( addBreastFeedEntry( $_SESSION['mid'], $duration, $_POST['latching'], $_POST['infant_state'],
			                         $_POST['maternal_problems'], $side, $entryDate )) {
				$success_message = "Breastfeeding entry added successfully";
				unset( $_POST, $hour, $minute, $ampm );
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}
		}
	}

	// ==================================== PUMPING FORM ====================================================
	elseif( $_POST['pumping_method'] || $_POST['pumping_duration'] ||
		      $_POST['pumping_amount'] ) {
		$active_tab = "pumping";
		if ( !$_POST['pumping_method'] ) {
			$error_message .= _("Please enter pumping method");
		}
		if ( !$_POST['pumping_duration'] ) {
			$error_message .= _("Please enter pumping duration");
		} 
		if ( !$_POST['pumping_amount'] ) {
			$error_message .= _("Please enter pumping amount");
		} 
		if ( empty( $error_message )) { 
			// insert the entry into the database
			if ( addPumpingEntry( $_SESSION['mid'], $_POST['pumping_method'], $_POST['pumping_duration'], 
			                      $_POST['pumping_amount'], $entryDate)) {
				$success_message = "Pumping entry added successfully";
				unset( $_POST, $hour, $minute, $ampm );
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}
		}
	}
	// =================================== SUPPLEMENT FORM ==================================================
	elseif( $_POST['sup_type'] || $_POST['sup_method'] || $_POST['TotalAmount'] ||
		      $_POST['NumberTimes'] ) {
		$active_tab = "supplement";
		if ( !$_POST['sup_type'] ) {
			$error_message .= _("Please enter supplement type");
		} 
		if ( !$_POST['sup_method'] ) {
			$error_message .= _("Please enter supplement method");
		} 
		if ( !$_POST['TotalAmount'] ) {
			$error_message .= _("Please enter total amount");
		} 
		if ( !$_POST['NumberTimes'] ) {
			$error_message .= _("Please enter frequency");
		} 
		if ( empty( $error_message )) { 
			// insert the entry into the database
			if ( addSupplementEntry( $_SESSION['mid'], $_POST['sup_type'], $_POST['sup_method'],
			                         $_POST['TotalAmount'], $_POST['NumberTimes'], $entryDate)) {
				$success_message = "Supplement entry added successfully";
				unset( $_POST, $hour, $minute, $ampm );
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}

		}
	}
	// ==================================== OUTPUT FORM =====================================================
	elseif( $_POST['out_u_color'] || $_POST['out_u_saturation'] ||
		      $_POST['out_s_color'] || $_POST['out_s_consistency'] ) {
		$active_tab = "output";
		if ( !$_POST['NumberDiapers'] ) {
			$error_message .= _("Please enter number of diapers");
		} 
		if ( $_POST['out_u_color'] || $_POST['out_u_saturation'] ) {
			if ( !$_POST['out_u_color'] ) {
				$error_message .= _("Please enter urine color");
			} 
			if ( !$_POST['out_u_saturation'] ) {
				$error_message .= _("Please enter urine saturation");
			} 
		} elseif ( $_POST['out_s_color'] || $_POST['out_s_consistency'] ) {
			if ( !$_POST['out_s_color'] ) {
				$error_message .= _("Please enter stool color");
			} 
			if ( !$_POST['out_s_consistency'] ) {
				$error_message .= _("Please enter stool consistency");
			} 
		}
		if(!$_POST['out_u_color'] && !$_POST['out_u_saturation'] && !$_POST["out_s_color"] && !$_POST["out_s_consistency"] ){
			$error_message .= "Please enter output color and saturation or consitency";
		}
		if ( empty( $error_message )) { 
			// insert the entry into the database.
			if ( addOutputEntry( $_SESSION['mid'], $_POST['out_u_color'], $_POST['out_u_color'],
			                $_POST['out_s_color'], $_POST['out_s_consistency'],
											$_POST['NumberDiapers'], $entryDate )) {
				$success_message = "Output entry added successfully";
				unset( $_POST, $hour, $minute, $ampm );
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}
		}
	}
	// ================================= HEALTH ISSUES FORM =================================================
	elseif( $_POST['morb_type'] ){
		$active_tab = "morbidity";
		// insert the entry into the database.
		if ( addMorbidityEntry( $_SESSION['mid'], $_POST['morb_type'], $entryDate )) {
			$success_message = _("Health issue entry added successfully");
			unset( $_POST, $hour, $minute, $ampm );
		} else {
			$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
		}
	}
}
if ( !isset( $hour ))
	$hour = date( 'g' );
if ( !isset( $minute )) {
	$minute = date( 'i' );
	$minute -= $minute % 5; // round down the the closest 5 minute increment.
}
if ( !isset( $ampm ))
	$ampm = date( 'A' );
	
?>
<html>
<head>
  <title> Lactor Mobile - <?php echo _("Add Entry") ?> </title>
  <?php include('head.php'); ?>
</head>

<?php
processNotifications( );
?>
<body>
<div data-role='page'>
<?php include('header.php'); ?>
<div data-role='content'>
<h1>Add an entry</h1>
<form class="long" name="addentry" method="post" action="./m.add_entry.php">
<h2><?php echo _("Time &amp; Day") ?></h2>
<div class="content time">
<?php
date_default_timezone_set("America/New_York");

echo "<div class='timeselect' data-role='fieldcontain'>";
echo "<fieldset class='timeselect' data-role='controlgroup' data-type='horizontal'>";
echo "<select name='entryhour'>";
for ( $i=1; $i <= 12; $i++ ) {
	echo "<option " . (( $hour ==  $i ) ? "selected" : "" ) . " value='$i'>$i</option>";
}
echo "</select>";

echo "<select name='entryminute'>";
for ( $i=0; $i <= 60; $i+=5 ) {
	echo "<option " . (( $minute == $i ) ? "selected" : "" ) . 
		" value='$i'>" . (( $i < 10 ) ? "0" : "" ) . "$i</option>";
}
echo "</select> ";

echo "<select name='entryam'>";
echo "<option " . ( $ampm == 'AM' ? 'selected' : '' ) . " value='01'>AM</option>";
echo "<option " . ( $ampm == 'PM' ? 'selected' : '' ) . " value='00'>PM</option>";
echo "</select> ";

echo "<select name='which'>";
echo "<option " . $today . " value='01'>"._("Today")."</option>";
echo "<option " . $yesterday . " value='00'>"._("Yesterday")."</option>";
echo "</select>";
echo "</fieldset>";
echo "</div>";
echo "<button type='submit' data-inline='true'>"._("Add entry")."</button>";
?>
</div>

<div data-role='collapsible-set'>
  <div data-role='collapsible'>
    <h3><?php echo _("Breastfeeding") ?></h3>
    <div>
      <span style='font-weight:bold'><?php echo _("Breastfeeding duration") ?>:</span>
      <div style='padding-left: 10px;'>
      <label for='duration_left'><?php echo _("Left side") ?>:</label>
      <select name='duration_left'>
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'>1-2 <?php echo _("minutes") ?></option>
        <option value='2'>3-4 <?php echo _("minutes") ?></option>
        <option value='3'>5-10 <?php echo _("minutes") ?></option>
        <option value='4'>11-15 <?php echo _("minutes") ?></option>
        <option value='5'><?php echo _("More than") ?> 15 <?php echo _("minutes") ?></option>
      </select>
      <label for='duration_right'>Right side:</label>
      <select name='duration_right'>
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'>1-2 <?php echo _("minutes") ?></option>
        <option value='2'>3-4 <?php echo _("minutes") ?></option>
        <option value='3'>5-10 <?php echo _("minutes") ?></option>
        <option value='4'>11-15 <?php echo _("minutes") ?></option>
        <option value='5'><?php echo _("More than") ?> 15 <?php echo _("minutes") ?></option>
      </select>
      </div>

      <label for='latching'><?php echo _("Latching") ?>:</label>
      <select name='latching'>
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Not at All") ?></option>
        <option value='2'><?php echo _("Slipping of the breast") ?></option>
        <option value='3'><?php echo _("Latch correctly") ?></option>
        <option value='4'><?php echo _("Latch with nipple shield") ?></option>
      </select>

      <label for='infant_state'><?php echo _("Infant alertness") ?>:</label>
      <select name="infant_state">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Difficult to awake") ?></option>
        <option value='2'><?php echo _("Drowsy") ?></option>
        <option value='3'><?php echo _("Quiet and alert") ?></option>
        <option value='4'><?php echo _("Active alert") ?></option>
        <option value='5'><?php echo _("Crying") ?></option>
      </select>

      <label for='maternal_problems'><?php echo _("Maternal breastfeeding problems") ?>:</label>
      <select name="maternal_problems">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Breast tissue is soft/no milk coming in") ?></option>
        <option value='2'><?php echo _("Sore nipple") ?></option>
        <option value='3'><?php echo _("Flat/inverted nipple") ?></option>
        <option value='4'><?php echo _("Engorgement") ?></option>
        <option value='5'><?php echo _("Mastitis") ?></option>
        <option value='6'><?php echo _("No problems") ?></option>
      </select>
    </div>
  </div>

  <div data-role='collapsible'>
    <h3><?php echo _("Pumping") ?></h3>
    <div>
      <label for='pumping_method'><?php echo _("Pumping method") ?>:</label>
      <select name="pumping_method" onchange="disableDropDown()">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Hand pump") ?></option>
        <option value='2'><?php echo _("Manual hand pump") ?></option>
        <option value='3'><?php echo _("Double electric pump") ?></option>
        <option value='4'><?php echo _("Not applicable") ?></option>
      </select>

      <label for='pumping_duration'><?php echo _("Pumping duration") ?>:</label>
      <select name="pumping_duration">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'>1-2 <?php echo _("minutes") ?></option>
        <option value='2'>3-4 <?php echo _("minutes") ?></option>
        <option value='3'>5-10 <?php echo _("minutes") ?></option>
        <option value='4'>11-15 <?php echo _("minutes") ?></option>
        <option value='5'><?php echo _("More than") ?> 15 <?php echo _("minutes") ?></option>
      </select>

      <label for='pumping_amount'><?php echo _("Pumping amount") ?>:</label>
      <select name="pumping_amount">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'>1 <?php echo _("ounces") ?></option>
        <option value='2'>2 <?php echo _("ounces") ?></option>
        <option value='3'>3 <?php echo _("ounces") ?></option>
        <option value='4'>4 <?php echo _("ounces") ?></option>
        <option value='5'>5 <?php echo _("ounces") ?></option>
        <option value='6'>6 <?php echo _("ounces") ?></option>
        <option value='7'>7 <?php echo _("ounces") ?></option>
        <option value='8'>8 <?php echo _("ounces") ?></option>
        <option value='9'>9 <?php echo _("ounces") ?></option>
        <option value='10'>10 <?php echo _("ounces") ?></option>
        <option value='11'><?php echo _("More than") ?> 10 <?php echo _("ounces") ?></option>
      </select>
    </div>
  </div>

  <div data-role='collapsible'>
    <h3><?php echo _("Supplement") ?></h3>
    <div>
      <label for='sup_type'><?php echo _("Type") ?>:</label>
      <select name="sup_type">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Expressed milk") ?></option>
        <option value='2'><?php echo _("Pasteurized human milk") ?></option>
        <option value='3'><?php echo _("Formula") ?></option>
      </select>

      <label for='sup_method'><?php echo _("Method") ?>:</label>
      <select name="sup_method">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Bottle") ?></option>
        <option value='2'><?php echo _("Cup") ?></option>
        <option value='3'><?php echo _("Supplemental Set") ?></option>
        <option value='4'><?php echo _("Spoon") ?></option>
      </select>

      <label for='NumberTimes'><?php echo _("Frequency") ?>:</label>
      <select name="NumberTimes">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'><?php echo _("More than") ?> 5</option>
      </select>

      <label for='TotalAmount'><?php echo _("Total Amount Today") ?>:</label>
      <select name="TotalAmount">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'>1 <?php echo _("ounces") ?></option>
        <option value='2'>2 <?php echo _("ounces") ?></option>
        <option value='3'>3 <?php echo _("ounces") ?></option>
        <option value='4'>4 <?php echo _("ounces") ?></option>
        <option value='5'>5 <?php echo _("ounces") ?></option>
        <option value='6'>6 <?php echo _("ounces") ?></option>
        <option value='7'>7 <?php echo _("ounces") ?></option>
        <option value='8'>8 <?php echo _("ounces") ?></option>
        <option value='9'>9 <?php echo _("ounces") ?></option>
        <option value='10'>10 <?php echo _("ounces") ?></option>
        <option value='11'><?php echo _("More than") ?> 10 <?php echo _("ounces") ?></option>
      </select>
    </div>
  </div>


  <div data-role='collapsible'>
    <h3><?php echo _("Output") ?></h3>
    <div>
      <div>NOTE: Please add different entries for different forms of output.</div>
      <label for='NumberDiapers'><?php echo _("Number of Diapers") ?>:</label>
      <select name="NumberDiapers">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>6</option>
        <option value='7'><?php echo _("More than") ?> 6</option>
      </select>

      <label for='out_u_color'><?php echo _("Urine Color") ?>:</label>
      <select name="out_u_color">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Amber Yellow") ?></option>
        <option value='2'><?php echo _("Dark Yellow") ?></option>
      </select>

      <label for='out_u_saturation'><?php echo _("Urine Saturation") ?>:</label>
      <select name="out_u_saturation">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Not wet at all") ?></option>
        <option value='2'><?php echo _("Slighly wet") ?></option>
        <option value='3'><?php echo _("Moderately wet") ?></option>
        <option value='4'><?php echo _("Heavily wet") ?></option>
      </select>

      <label for='out_s_color'><?php echo _("Stool Color") ?>:</label>
      <select name="out_s_color">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Black/tarry meconium") ?></option>
        <option value='2'><?php echo _("Black/green") ?></option>
        <option value='3'><?php echo _("Yellow") ?></option>
      </select>

      <label for='out_s_consistency'><?php echo _("Stool Consistency") ?>:</label>
      <select name="out_s_consistency">
        <option value='0'><?php echo _("Choose one") ?></option>
        <option value='1'><?php echo _("Loose and seedy") ?></option>
        <option value='2'><?php echo _("Formed") ?></option>
        <option value='3'><?php echo _("Watery") ?></option>
      </select>
    </div>
  </div>

  <div data-role='collapsible'>
    <h3><?php echo _("Health Issue") ?></h3>
    <p>
			<label for='morb_type'><?php echo _("Type") ?>:</label>
			<select name="morb_type">
          <option value='0'><?php echo _("Choose one") ?></option>
          <option value='1'><?php echo _("Jaundice") ?></option>
          <option value='2'><?php echo _("Decrease body temperature") ?></option>
          <option value='3'><?php echo _("Decrease in blood glucose") ?></option>
          <option value='4'><?php echo _("Difficult or trouble breathing") ?></option>
          <option value='5'><?php echo _("Infection") ?></option>
          <option value='6'><?php echo _("Dehydration") ?></option>
          <option value='7'><?php echo _("Weight Loss") ?></option>
			</select>
    </p>
  </div>
</form>
</div>
<?php include('footer.php'); ?>
</div>
</body>
</html>
