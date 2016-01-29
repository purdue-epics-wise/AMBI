<?php

include_once("../includes/general.php");
include_once("../includes/db.php");

loggedIn();
db_connect();
loadVocabulary();

 function add_science_entry( $entryid, $entrytype ) {
    $record = sprintf(
      "INSERT INTO ScienceWrite VALUES( %d, NOW(), %d, 2, %d, %d)",
      $_SESSION['s_mid'], $_SESSION['mid'], $entrytype, $entryid);
    $result = mysql_query($record);
   if (!$result) {
     error_log(mysql_error( ));
     return false;
   }
   return true;
 }

 function update_diary( $time, $entryid, $type ) {
   $query = sprintf( 
     "UPDATE Diary SET EntryDate='%s' WHERE EntryId=%d AND EntryType=%s",
     date( "Y-m-d G:i:s", strtotime( $time  )), $entryid, $type);
   $result = mysql_query( $query );
   if (!$result) {
     error_log(mysql_error( ));
     return false;
   }
   return true;
 }

$_SESSION['modid'] = $_POST['entryid'];

if(isset($_POST['cancel1'])) {
  $_SESSION['writemessage'] = _("Breastfeeding entry not modified.");
  $_SESSION['writetype'] = 6;
  unset($_SESSION['breast']);
  if($_POST['direction'] == "view") {
    $_SESSION['ViewDisplay'] = 1;
    go("../view_diary.php");
  } else {
    $_SESSION['EntryDisplay'] = 1;
    go("../add_entry.php");    
  }
}
if(isset($_POST['cancel2'])) {
  $_SESSION['writemessage'] = _("Supplement entry not modified.");
  $_SESSION['writetype'] = 6;
  unset($_SESSION['sup']);
  if($_POST['direction'] == "view") {
    $_SESSION['ViewDisplay'] = 2;
    go("../view_diary.php");
  } else {
    $_SESSION['EntryDisplay'] = 2;
    go("../add_entry.php");    
  }
}
if(isset($_POST['cancel3'])) {
  $_SESSION['writemessage'] = _("Output entry not modified.");
  $_SESSION['writetype'] = 6;
  unset($_SESSION['out']);
  if($_POST['direction'] == "view") {
    $_SESSION['ViewDisplay'] = 3;
    go("../view_diary.php");
  } else {
    $_SESSION['EntryDisplay'] = 3;
    go("../add_entry.php");    
  }
}
if(isset($_POST['cancel4'])) {
  $_SESSION['writemessage'] = _("Health Issue entry not modified.");
  $_SESSION['writetype'] = 6;
  unset($_SESSION['morb']);
  if($_POST['direction'] == "view") {
    $_SESSION['ViewDisplay'] = 4;
    go("../view_diary.php");
  } else {
    $_SESSION['EntryDisplay'] = 4;
    go("../add_entry.php");  
  }
}


// Added to enable update of date


// end

$error_message = "";
$success_message = "";

if(isset($_POST['breast'])) {  

  $_SESSION['writetype'] = 5;
  $_SESSION['writecontent'] = ""; 
  if($_POST['duration'] == 0 ) {
    $error_mesage .= _("Please enter breastfeeding duration")."<br/>";
  } 
  if($_POST['side'] == 0) {
    $error_message .= _("Please enter latching information.")."<br/>";
  }
  if($_POST['latching'] == 0) {
    $error_message .= _("Breastfeeding latching must be entered.")."<br/>";
  }
  if($_POST['infant_state'] == 0) {
    $error_message .= _("Infant alertness must be entered.")."<br/>";
  }
  if($_POST['maternal_problems'] == 0) {
    $error_message .= _("Maternal problems must be entered.")."<br/>";
  } 
  
  if ( $error_message ) {
    $error_message .= _("Breastfeeding entry not modified.");
    $_SESSION['writemessage'] = $error_message;
    go("../modify_entry.php");
  }

  begin_transaction( );
  
  if(isset($_SESSION['s_mid'])) {
    // add a log entry
    if(!add_science_entry($_POST['entryid'], ENTRYTYPE_BREASTFEEDING)) {
      $_SESSION['writemessage'] = 
        _("An error occurred when attempting to log your entry<br />").
        _("Breastfeeding entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
    }
  }

  // update the diary entry
  if ( !update_diary( sprintf( "%s %d:%02d %s",$_POST['day'], $_POST['hour'], 
                               $_POST['minute'], $_POST['ampm']),
                      $_POST['entryid'], ENTRYTYPE_BREASTFEEDING )) {
        _("An error occurred when attempting to update your entry<br />").
        _("Output entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
  }
  
  $duration = mysql_real_escape_string($_POST['duration']);
  $latching = mysql_real_escape_string($_POST["latching"]);
  $infantstate = mysql_real_escape_string($_POST["infant_state"]);
  $maternalproblems = mysql_real_escape_string($_POST["maternal_problems"]);
  
  $query="UPDATE BreastfeedEntry SET BreastfeedingDuration=$duration, ".
         "Latching=$latching, InfantState=$infantstate, ".
         "MaternalProblems=$maternalproblems, ".
         "Side=". mysql_real_escape_string($_POST['side']).
         " WHERE EntryId=".mysql_real_escape_string($_SESSION['modid']).";";
  if ( !mysql_query($query)) {
    $error_message .= _("An error occurred while trying to update the entry")."<br />";
    $error_message .= _("Breastfeeding entry not modified.");
    $_SESSION['writemessage'] = $error_message;
    error_log( mysql_error( ));
    rollback_transaction( );
    go("../modify_entry.php");
  } else {
    $_SESSION['writemessage'] = _("Breastfeeding entry updated.");
    commit_transaction( );
    go("../view_diary.php#breastfeeding");
  }

} 

if(isset($_POST['pumping'])) {  

  $_SESSION['writetype'] = 5;
  $_SESSION['writecontent'] = ""; 
  if($_POST['duration'] == 0 ) {
    $error_mesage .= _("Please enter pumping duration")."<br/>";
  } 
  if($_POST['pumping_method'] == 0) {
    $error_message .= _("Please enter pumping method.")."<br/>";
  }
  if($_POST['pumping_amount'] == 0) {
    $error_message .= _("Please enter pumping amount.")."<br/>";
  }
  if ( $error_message ) {
    $error_message .= _("Pumping entry not modified.");
    $_SESSION['writemessage'] = $error_message;
    go("../modify_entry.php");
  }

  begin_transaction( );
  
  if(isset($_SESSION['s_mid'])) {
    // add a log entry
    if(!add_science_entry($_POST['entryid'], ENTRYTYPE_BREASTFEEDING)) {
      $_SESSION['writemessage'] = 
        _("An error occurred when attempting to log your entry<br />").
        _("Breastfeeding entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
    }
  }

  // update the diary entry
  if ( !update_diary( sprintf( "%s %d:%02d %s",$_POST['day'], $_POST['hour'], 
                               $_POST['minute'], $_POST['ampm']),
                      $_POST['entryid'], ENTRYTYPE_BREASTFEEDING )) {
        _("An error occurred when attempting to update your entry<br />").
        _("Output entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
  }
  
  $duration = mysql_real_escape_string($_POST['duration']);
  $method = mysql_real_escape_string($_POST["pumping_method"]);
  $amount = mysql_real_escape_string($_POST["pumping_amount"]);
  
  $query="UPDATE BreastfeedEntry SET BreastfeedingDuration=$duration, ".
         "PumpingMethod=$method, PumpingAmount=$amount ".
         " WHERE EntryId=".mysql_real_escape_string($_SESSION['modid']).";";
  if ( !mysql_query($query)) {
    $error_message .= _("An error occurred while trying to update the entry")."<br />";
    $error_message .= _("Pumping entry not modified.");
    $_SESSION['writemessage'] = $error_message;
    error_log( mysql_error( ));
    rollback_transaction( );
    go("../modify_entry.php");
  } else {
    $_SESSION['writemessage'] = _("Pumping entry updated.");
    commit_transaction( );
    go("../view_diary.php#pumping");
  }
} 


if(isset($_POST['sup'])) {  

  if($_POST["sup-type"] == 0 || $_POST["sup-method"] == 0 || $_POST['TotalAmount'] == 0 || $_POST['NumberTimes'] == 0) {
  $_SESSION['writetype'] = 5;
  $_SESSION['writemessage'] = "Supplement entry not modified.";
  $_SESSION['writecontent'] = "";
  if($_POST["sup-type"] == 0) {
    $_SESSION['writecontent'] .= "Supplement type must be entered.<br/>";
  } 
  if($_POST["sup-method"] == 0) {
    $_SESSION['writecontent'] .= "Supplement method must be entered.<br/>";
  }
  if($_POST["TotalAmount"] == 0) {
    $_SESSION['writecontent'] .= "Supplement amount must be entered.<br/>";
  }
  if($_POST["NumberTimes"] == 0) {
    $_SESSION['writecontent'] .= "Supplement times must be entered.<br/>";
  }
  

  go("../modify_entry.php");

  }
  
  $suptype=$_POST['sup-type'];
  $supmethod=$_POST["sup-method"];

  begin_transaction( );
  
  if(isset($_SESSION['s_mid'])) {
    if(!add_science_entry($_POST['entryid'], ENTRTYPE_SUPPLEMENT)) {
      $_SESSION['writemessage'] = 
        _("An error occurred when attempting to log your entry<br />").
        _("Supplement entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
    }
  }  

  // update the diary entry
  if ( !update_diary( sprintf( "%s %d:%02d %s",$_POST['day'], $_POST['hour'], 
                               $_POST['minute'], $_POST['ampm']),
                      $_POST['entryid'], ENTRYTYPE_SUPPLEMENT )) {
        _("An error occurred when attempting to update your entry<br />").
        _("Output entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
  }
  
  $query="Update SupplementEntry SET SupType=$suptype, SupMethod=$supmethod, 
          TotalAmount=".mysql_real_escape_string($_POST['TotalAmount']).
          ", NumberTimes=".mysql_real_escape_string($_POST['NumberTimes']).
          " WHERE EntryId=".mysql_real_escape_string($_POST['entryid']).";";
  if ( !mysql_query($query)) {
    $error_message .= _("An error occurred while trying to update the entry")."<br />";
    $error_message .= _("Breastfeeding entry not modified.");
    $_SESSION['writemessage'] = $error_message;
    error_log( mysql_error( ));
    rollback_transaction( );
    go("../modify_entry.php");
  } else {
    $_SESSION['writemessage'] = _("Supplement entry updated.");
    commit_transaction( );
    go("../view_diary.php#supplement");
  }
  
} 

if(isset($_POST['out'])) {  

  if($_POST["out-u-color"] != 0 && $_POST["out-u-saturation"] == 0 || 
     $_POST["out-u-color"] == 0 && $_POST["out-u-saturation"] != 0) {
    $_SESSION['writetype'] = 5;
    $_SESSION['writemessage'] = "Output entry not modified.";
    $_SESSION['writecontent'] = "Urine color and saturation must be entered ".
                                "if modifying an urine entry.<br />";
    if($_POST['NumberDiapers'] == 0) {
      $_SESSION['writecontent'] .= "Number of diapers must be entered for ".
                                   "every output entry.";
    }
    go("../modify_entry.php");
  }
  if($_POST["out-s-color"] != 0 && $_POST["out-s-consistency"] == 0 || 
     $_POST["out-s-color"] == 0 && $_POST["out-s-consistency"] != 0) {
    $_SESSION['writetype'] = 5;
    $_SESSION['writemessage'] = "Output entry not modified.";
    $_SESSION['writecontent'] = "Stool color and consistency must be entered ".
                                "if adding a stool entry.<br />";
    if($_POST['NumberDiapers'] == 0) {
      $_SESSION['writecontent'] .= "Number of diapers must be entered for ".
                                   "every output entry.";
    }
    go("../modify_entry.php");
  }
  if($_POST['NumberDiapers'] == 0) {
    $_SESSION['writetype'] = 5;
    $_SESSION['writemessage'] = "Output entry not modified.";
    $_SESSION['writecontent'] .= "Number of diapers must be entered for ".
                                 "every output entry.";
    go("../modify_entry.php");
  }
  if($_POST['NumberDiapers'] != 0 && $_POST["out-s-color"] == 0 && 
     $_POST["out-s-consistency"] == 0 && $_POST["out-u-color"] == 0 && 
     $_POST["out-u-saturation"] == 0) {
    $_SESSION['writetype'] = 5;
    $_SESSION['writemessage'] = "Output entry not added.";
    $_SESSION['writecontent'] .= "Must select one type of output.";
    go("../modify_entry.php");
  }
  
  $ucolor=$_POST['out-u-color'];
  $usat=$_POST["out-u-saturation"];
  $scolor=$_POST['out-s-color'];
  $scon=$_POST["out-s-consistency"];

  begin_transaction( );
  
  if(isset($_SESSION['s_mid'])) {
    if(!add_science_entry($_POST['entryid'], ENTRTYPE_SUPPLEMENT)) {
      $_SESSION['writemessage'] = 
        _("An error occurred when attempting to log your entry<br />").
        _("Output entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
    }
  }  
  
  if ( !update_diary( sprintf( "%s %d:%02d %s",$_POST['day'], $_POST['hour'], 
                               $_POST['minute'], $_POST['ampm']),
                      $_POST['entryid'], ENTRYTYPE_OUTPUT )) {
        _("An error occurred when attempting to upate your entry<br />").
        _("Output entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
  }
  
  
  $query="UPDATE OutputEntry SET UrineColor=$ucolor, UrineSaturation=$usat,".
         "StoolColor=$scolor, StoolConsistency=$scon, NumberDiapers=".
         $_POST['NumberDiapers']." WHERE EntryId =".$_POST['entryid'].";";
  if ( !mysql_query($query)) {
    $error_message .= _("An error occurred while trying to update the entry")."<br />";
    $error_message .= _("Breastfeeding entry not modified.");
    $_SESSION['writemessage'] = $error_message;
    error_log( mysql_error( ));
    rollback_transaction( );
    go("../modify_entry.php");
  } else {
    $_SESSION['writemessage'] = _("Output entry updated.");
    commit_transaction( );
    go("../view_diary.php#output");
  }
} 

if(isset($_POST['morb'])) {  
  if($_POST["morb-type"] == 0) {
    $_SESSION['writetype'] = 5;
    $_SESSION['writemessage'] = "Health Issue entry not modified.";
    $_SESSION['writecontent'] = "Health Issue type must be entered. <br />";

    go("../modify_entry.php");
  }

  begin_transaction( );

  if(isset($_SESSION['s_mid'])) {
    if(!add_science_entry($_POST['entryid'], ENTRTYPE_SUPPLEMENT)) {
      $_SESSION['writemessage'] = 
        _("An error occurred when attempting to log your entry<br />").
        _("Output entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
    }
  }  
  
  if ( !update_diary( sprintf( "%s %d:%02d %s",$_POST['day'], $_POST['hour'], 
                               $_POST['minute'], $_POST['ampm']),
                      $_POST['entryid'], ENTRYTYPE_HEALTH )) {
        _("An error occurred when attempting to update your entry<br />").
        _("Output entry not modified.");
      rollback_transaction( );
      go("../modify_entry.php");
  }
  
  $morbtype=$_POST['morb-type'];
  $query="Update MorbidityEntry SET Type=$morbtype WHERE EntryId=".
         $_POST['entryid'] . ";";
  mysql_query($query);
  if ( !mysql_query($query)) {
    $error_message .= _("An error occurred while trying to update the entry")."<br />";
    $error_message .= _("Health Issue entry not modified.");
    $_SESSION['writemessage'] = $error_message;
    error_log( mysql_error( ));
    rollback_transaction( );
    go("../modify_entry.php");
  } else {
    $_SESSION['writemessage'] = _("Health issue entry updated.");
    commit_transaction( );
    go("../view_diary.php#health");
  }
  
} 

?>
