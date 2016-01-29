<?php

include_once("../includes/general.php");
include_once("../includes/db.php");

include_once('access_control.php');

db_connect();

if (!loggedIn(false)) {
  header('Content-Type: text/html');
  http_response_code(403);
  die("<h1>Forbidden</h1>");
}

$error_message = "";
$success_message = "";
$active_tab = "breastfeeding";
$hour = isset($_REQUEST[ 'entryhour' ]) ? $_REQUEST[ 'entryhour' ] : date( 'g' );
$minute = isset( $_REQUEST[ 'entryminute' ]) ? $_REQUEST[ 'entryminute' ] : date( 'i' );
$minute -= $minute % 5; // round down the the closest 5 minute increment.
$ampm = isset( $_REQUEST[ 'entryam' ]) ? ($_REQUEST[ 'entryam' ] == "01" ? "AM" : "PM") : date( 'A' );
$today = "SELECTED";
$yesterday = "";

if ( count( $_REQUEST )) {
	$dateString = sprintf( "%s %d:%s%d %s", ($_REQUEST['which'] == "01" ? "Today" : "Yesterday"), $hour, ($minute < 10 ? "0" : ""), $minute, $ampm);
	$entryDate = date_create( $dateString )->format( "Y-m-d H:i" );
  error_log($dateString);
  error_log($entryDate);
	// =================================== BREASTFEEDING FORM ================================================
	if( @$_REQUEST['breast'] ){

		if ( !$_REQUEST[ 'duration_left' ] && !$_REQUEST[ 'duration_right' ] ){
			$error_message .= _("Breastfeeding duration must be at least 1 minute.")."<br />";
		} 
		if ( !$_REQUEST[ 'latching' ] ) {
			$error_message .= _("Please enter latching information.")."<br />";
		} 
		if ( !$_REQUEST[ 'infant_state' ] ) {
			$error_message .= _("Please enter infant alertness.")."<br />";
		} 
		if ( !$_REQUEST[ 'maternal_problems' ] ) {
			$error_message .= _("Please enter breastfeeding maternal problems.")."<br />";
		} 
		if ( empty( $error_message )) { 
			// insert the entry into the database
			if ( !$_REQUEST['duration_right'] ) {
				$duration = $_REQUEST[ 'duration_left' ];
				$side = 1; // left
			} elseif ( !$_REQUEST['duration_left'] ) {
				$duration = $_REQUEST[ 'duration_right' ];
				$side = 2; // right
			} else {
				// combine the 2 durations to determine the entry, using the best guess (ugly)
				$duration_time = 0;
				switch( $_REQUEST['duration_left'] ) {
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
				switch( $_REQUEST['duration_right'] ) {
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
			if ( addBreastFeedEntry( $_SESSION['mid'], $duration, $_REQUEST['latching'], $_REQUEST['infant_state'],
			                         $_REQUEST['maternal_problems'], $side, $entryDate )) {
				$success_message = _("Breastfeeding entry added successfully");
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}
		}
	}

	// ==================================== PUMPING FORM ====================================================
	elseif( @$_REQUEST['pump'] ){
		$active_tab = "pumping";
		if ( !$_REQUEST['pumping_method'] ) {
			$error_message .= _("Please enter pumping method")."<br />";
		}
		if ( !$_REQUEST['pumping_duration'] ) {
			$error_message .= _("Please enter pumping duration")."<br />";
		} 
		if ( !$_REQUEST['pumping_amount'] ) {
			$error_message .= _("Please enter pumping amount")."<br />";
		} 
		if ( empty( $error_message )) { 
			// insert the entry into the database
			if ( addPumpingEntry( $_SESSION['mid'], $_REQUEST['pumping_method'], $_REQUEST['pumping_duration'], 
			                      $_REQUEST['pumping_amount'], $entryDate)) {
				$success_message = _("Pumping entry added successfully");
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}
		}
	}
	// =================================== SUPPLEMENT FORM ==================================================
	elseif( @$_REQUEST['sup'] ){
		$active_tab = "supplement";
		if ( !$_REQUEST['sup_type'] ) {
			$error_message .= _("Please enter supplement type")."<br />";
		} 
		if ( !$_REQUEST['sup_method'] ) {
			$error_message .= _("Please enter supplement method")."<br />";
		} 
		if ( !$_REQUEST['TotalAmount'] ) {
			$error_message .= _("Please enter total amount")."<br />";
		} 
		if ( !$_REQUEST['NumberTimes'] ) {
			$error_message .= _("Please enter frequency")."<br />";
		} 
		if ( empty( $error_message )) { 
			// insert the entry into the database
			if ( addSupplementEntry( $_SESSION['mid'], $_REQUEST['sup_type'], $_REQUEST['sup_method'],
			                         $_REQUEST['TotalAmount'], $_REQUEST['NumberTimes'], $entryDate)) {
				$success_message = _("Supplement entry added successfully");
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}

		}
	}
	// ==================================== OUTPUT FORM =====================================================
	elseif( @$_REQUEST['out'] ){
		$active_tab = "output";
		if ( !$_REQUEST['NumberDiapers'] ) {
			$error_message .= _("Please enter number of diapers")."<br />";
		} 
		if ( $_REQUEST['out_u_color'] || $_REQUEST['out_u_saturation'] ) {
			if ( !$_REQUEST['out_u_color'] ) {
				$error_message .= _("Please enter urine color")."<br />";
			} 
			if ( !$_REQUEST['out_u_saturation'] ) {
				$error_message .= _("Please enter urine saturation")."<br />";
			} 
		} elseif ( $_REQUEST['out_s_color'] || $_REQUEST['out_s_consistency'] ) {
			if ( !$_REQUEST['out_s_color'] ) {
				$error_message .= _("Please enter stool color")."<br />";
			} 
			if ( !$_REQUEST['out_s_consistency'] ) {
				$error_message .= _("Please enter stool consistency")."<br />";
			} 
		}
		if(!$_REQUEST['out_u_color'] && !$_REQUEST['out_u_saturation'] && !$_REQUEST["out_s_color"] && !$_REQUEST["out_s_consistency"] ){
			$error_message .= "Please enter output color and saturation or consitency<br />";
		}
		if ( empty( $error_message )) { 
			// insert the entry into the database.
			if ( addOutputEntry( $_SESSION['mid'], $_REQUEST['out_u_color'], $_REQUEST['out_u_saturation'],
			                $_REQUEST['out_s_color'], $_REQUEST['out_s_consistency'],
											$_REQUEST['NumberDiapers'], $entryDate )) {
				$success_message = _("Output entry added successfully");
			} else {
				$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
			}
		}
	}
	// ================================= INFANT WEIGHT FORM =================================================
	elseif( @$_REQUEST['weight'] ){
		$active_tab = "health";
		// insert the entry into the database.
    $ounces = $_REQUEST['weight-lbs'] * 16 + $_REQUEST['weight-oz'];
		if ( addWeightEntry( $_SESSION['mid'], $ounces, $entryDate )) {
			$success_message = _("Infant weight entry added successfully");
		} else {
			$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
		}
	}
	// ================================= HEALTH ISSUES FORM =================================================
	elseif( @$_REQUEST['morb'] ){
		$active_tab = "health";
		// insert the entry into the database.
		if ( addMorbidityEntry( $_SESSION['mid'], $_REQUEST['morb_type'], $entryDate )) {
			$success_message = _("Health issue entry added successfully");
		} else {
			$error_message = _("An error ocurred while processing your submission. Please contact the site administrator.");
		}
	}
  if (isset($_REQUEST['callback'])) {
    $callback = $_REQUEST['callback'];
    // jsonp request
    $jsonp = true;
    header('Content-Type: text/javascript');
  } else {
    $callback = false;
    $jsonp = false;
    header('Content-Type: application/json');
  }
  processNotifications();
  $reply = Array();
  if ($error_message) {
    $reply['error'] = $error_message;
  }
  if ($success_message) {
    $reply['message'] = $success_message;
  }
  $response = json_encode($reply);
  if ($jsonp)
    $response = $_REQUEST['callback'].'('.$response.')';
  echo $response;

}
?>
