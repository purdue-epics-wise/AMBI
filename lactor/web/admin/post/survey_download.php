<?php

include_once('../../includes/db.php');
include_once('../../includes/general.php');
include_once('../../includes/constants.php');

session_start( );
db_connect();

function getResult($input){
  switch($input)
  {
    case 1:
      return _("Strongly Disagree");
    case 2:
      return _("Disagree");
    case 3:
      return _("Neutral");
    case 4:
      return _("Agree");
    case 5:
      return _("Strongly Agree");
    default:
      return _("Not answered");
  }
}

function getResult2($input){
  switch($input)
  {
    case -2:
      return _("Strongly Disagree");
    case -1:
      return _("Disagree");
    case 0:
      return _("Neutral");
    case 1:
      return _("Agree");
    case 2:
      return _("Strongly Agree");
    default:
      return _("Not answered");
  }
}
      
function surveyTable( $action ) {
  switch( $action ) {
    case ACTION_SYSTEM_FEEDBACK:
      return "SystemFeedback";
    case ACTION_SYSTEM_PERCEPTION:
      return "SystemPerception";
    case ACTION_BREASTFEEDING_FOLLOWUP:
      return "Breastfeeding_Followup";
    case ACTION_SELF_EFFICACY:
      return "Self_Efficacy_Survey";
    case ACTION_BREASTFEEDING_EVALUATION:
      return "Breastfeeding_Evaluation";
    case ACTION_POSTNATAL_DEPRESSION:
      return "Postnatal_Depression";
    default:
      return "";
  }
}

function surveyName($action) {
  switch( $action ) {
    case ACTION_SYSTEM_FEEDBACK:
      return _("System Feedback");
    case ACTION_SYSTEM_PERCEPTION:
      return _("System Perception");
    case ACTION_BREASTFEEDING_FOLLOWUP:
      return _("Breastfeeding Followup");
    case ACTION_SELF_EFFICACY:
      return _("Self Efficacy");
    case ACTION_BREASTFEEDING_EVALUATION:
      return _("Breastfeeding Evaluation");
    case ACTION_POSTNATAL_DEPRESSION:
      return _("Postnatal Depression");
    default:
      return "";
  }
}

if (!can_access_mother((int)$_POST['mid'])) {
  header("HTTP/1.0 403 Forbidden");
  die( "<h1>Forbidden</h1>" );
}
$survey= $_POST["survey"];
$query = "SELECT * FROM ". surveyTable($survey)." WHERE mid in ( %s );";
if (isset($_POST['downloadAll'])) {
  if ($_SESSION['admin'] == SUPER_ADMIN) {
    $query = sprintf( $query, "SELECT M.mid FROM Mothers M");
  } else if ($_SESSION['admin'] == HOSPITAL_ADMIN) {
    $query = sprintf( $query, "SELECT M.mid FROM Mothers M where M.hospital_id = ".$_SESSION['hospital_id']);
  } else {
    $query = sprintf( $query, "SELECT M.mid FROM Mothers M,Mothers_Scientists MS where M.mid=MS.mid AND MS.sid=" .$_SESSION['sid']);
  }
} else  {
  $query = sprintf($query, (int)@$_POST['mid']);
}
$result = mysql_query($query);
if (!$result) {
  error_log( "No results found - ".$query );
  die( "Unable to locate results for user " + @$_POST['mid']);
}

header(sprintf('Content-Disposition: attachment; filename="%s.csv"', surveyName($survey)));
header('Content-Type: text/plain');
//header('Content-Type: text/csv');

echo '"'.surveyName($survey).' '._("Survey Results").'",';
if($survey==ACTION_SYSTEM_FEEDBACK) {
  echo '"'._("I think that I would like to use this system frequently").'",';
  echo '"'._("I found the system unnecessarily complex").'",';
  echo '"'._("I thought the system was easy to use").'",';
  echo '"'._("I think that I would need the support of a technical person to use this system").'",';
  echo '"'._("I found the various functions in this system were well integrated").'",';
  echo '"'._("I thought there was too much inconsistency in this system").'",';
  echo '"'._("I would imagine that most people would learn to use this system quickly").'",';
  echo '"'._("I found the system very cumbersome to use").'",';
  echo '"'._("I felt very confident using the system").'",';
  echo '"'._("I needed to learn a lot of the things before I could get going with this system")."\"\n";

} else if($survey==ACTION_SYSTEM_PERCEPTION) {
  echo '"'._("Was the web-based breastfeeding monitoring helpful in recognizing your baby's breastfeeding problems?").' ';
  echo '"'._("How did it help?").'",';
  echo '"'._("Was the data entry time consuming? Do you have any suggestions?").'",';
  echo '"'._("Was the data entry a burden for you or overwhelming? Do you have any suggestions?").'",';
  echo '"'._("How did the web-based monitoring help in overcoming your baby's health problems?").'",';
  echo '"'._("Do you think the system helped you to breastfeed longer?").'",';
  echo '"'._("Do you think the monitoring helped you to decrease supplementation with any substance other than breast milk?").'",';
  echo '"'._("Would you recommend this web-based monitoring for a friend? Why and why not?")."\"\n";

} else if ($survey == ACTION_BREASTFEEDING_FOLLOWUP ) {
  echo '"'._("Visit").'","' . $row['q1'] . "\"\n";
  echo '"'._("How long are you planning to breastfeed your baby?").'",';
  echo '"'._("How many times do you breastfeed your baby per day?").'",';
  echo '"'._("Do you give any other substances (supplementation) for your baby?").'",';
  echo '"'._("If yes, what kind of supplement do you give?").'",';
  echo '"'._("How often do you give a supplemental feeding per day?").'",';
  echo '"'._("Causes of supplemental feeding").'",';
  echo '"'._("Pattern of breastfeeding (How did you breastfeed your baby in the last 7 days)").'",';
  echo '"'._("Breastfeeding/baby Problems")."\"\n";

} else if ($survey == ACTION_SELF_EFFICACY) {
  echo '"'._("I can always determine that my baby is getting enough milk.").'",';
  echo '"'._("I can always successfully cope with breastfeeding like I have with other challenging tasks.").'",';
  echo '"'._("I can always breastfeed my baby without first using formula as a supplement.").'",';
  echo '"'._("I can always ensure that my baby is properly latched on for the whole feeding.").'",';
  echo '"'._("I can always manage the breastfeeding situation to my satisfaction.").'",';
  echo '"'._("I can always manage to breastfeed even if my baby is crying.").'",';
  echo '"'._("I can always keep wanting to breastfeed").'",';
  echo '"'._("I can always comfortably breastfeed with my family members present.").'",';
  echo '"'._("I can always be satisfied with my breastfeeding experience.").'",';
  echo '"'._("I can always deal with the fact that breastfeeding can be time consuming.").'",';
  echo '"'._("I can always finish feeding my baby on one breast before switching to the other breast.").'",';
  echo '"'._("I can always continue to breastfeed my baby for every feeding.").'",';
  echo '"'._("I can always manage to keep up with my baby's breastfeeding demands.").'",';
  echo '"'._("I can always tell when my baby is finished breastfeeding.")."\"\n";

} else if ($survey == ACTION_BREASTFEEDING_EVALUATION) {
  echo '"'._("With breastfeeding I felt a sense of inner contentment.").'",';
  echo '"'._("Breastfeeding was a special time with my baby.").'",';
  echo '"'._("My baby wasn't interested in breastfeeding.").'",';
  echo '"'._("My baby loved to nurse.").'",';
  echo '"'._("It was a burden being my baby's main source of food.").'",';
  echo '"'._("I felt extremely close to my baby when I breastfed.").'",';
  echo '"'._("My baby was an eager breastfeeder.").'",';
  echo '"'._("Breastfeeding was physically draining.").'",';
  echo '"'._("It was important to me to be able to nurse.").'",';
  echo '"'._("While breastfeeding, my baby's growth was excellent.").'",';
  echo '"'._("My baby and I worked together to make breastfeeding go smoothly.").'",';
  echo '"'._("Breastfeeding was a very nurturing, maternal experience.").'",';
  echo '"'._("While breastfeeding, I felt self-concious about my body.").'",';
  echo '"'._("With breastfeeding, I felt too tied down all the time.").'",';
  echo '"'._("While breastfeeding, I worried about my baby gaining enough weight.").'",';
  echo '"'._("Breastfeeding was soothing when my baby was upset or crying.").'",';
  echo '"'._("Breastfeeding was like a high of sorts.").'",';
  echo '"'._("The fact that I could produce the food to feed my own baby was very satisfying.").'",';
  echo '"'._("In the beginning, my baby had trouble breastfeeding.").'",';
  echo '"'._("Breastfeeding made me feel like a good mother.").'",';
  echo '"'._("I really enjoyed nursing.").'",';
  echo '"'._("While breastfeeding, I was anxious to have my body back.").'",';
  echo '"'._("Breastfeeding made me feel more confident as a mother.").'",';
  echo '"'._("My baby gained weight really well with breastmilk.").'",';
  echo '"'._("Breastfeeding made me feel more confident as a mother.").'",';
  echo '"'._("I could easily fit my baby's breastfeeding with my other activities.").'",';
  echo '"'._("Breastfeeding made me feel like a cow.").'",';
  echo '"'._("My baby did not relax while nursing.").'",';
  echo '"'._("Breastfeeding was emotionally draining.").'",';
  echo '"'._("Breastfeeding felt wonderful to me.")."\"\n";

} else if ($survey == ACTION_POSTNATAL_DEPRESSION) {
  echo '"'._("I have been able to laugh and see the funny side of things").'","';
  echo '"'._("I have looked forward with enjoyment to things").'","';
  echo '"'._("I have blamed myself unnecessarily when things went wrong").'","';
  echo '"'._("I have been anxious or worried for no good reason").'","';
  echo '"'._("I have felt scared of panicky for no very good reason").'","';
  echo '"'._("Things have been geting on top of me").'","';
  echo '"'._("I have been so unhappy that I have had difficulty sleeping").'","';
  echo '"'._("I have felt sad or miserable").'","';
  echo '"'._("I have been so unhappy that I have been crying").'","';
  echo '"'._("The thought of harming myself has occurred to me")."\"\n";
}

while( $row = mysql_fetch_assoc($result)) {

  // mid as the first column
  echo $row['mid'].",";
  
  // print out the survey results
  if($survey==ACTION_SYSTEM_FEEDBACK) {
    for( $i=1; $i <= 10; $i++ ) {
      if ($i > 1)
        echo ',';
      echo '"'.getResult($row['q'.$i]).'"';
    }
    echo "\n";

  } else if($survey==ACTION_SYSTEM_PERCEPTION) {
    for( $i=1; $i <= 7; $i++ ) {
      if ($i > 1)
        echo ',';
      echo '"'.$row['q'.$i].'"';
    }
    echo "\n";

  } else if ($survey == ACTION_BREASTFEEDING_FOLLOWUP ) {
    for( $i=1; $i <= 9; $i++ ) {
      if ($i > 1)
        echo ',';
      echo '"'.$row['q'.$i].'"';
    }
    echo "\n";

  } else if ($survey == ACTION_SELF_EFFICACY) {
    for( $i=1; $i <= 14; $i++ ) {
      if ($i > 1)
        echo ',';
      echo '"'.getResult2($row['q'.$i]).'"';
    }
    echo "\n";

  } else if ($survey == ACTION_BREASTFEEDING_EVALUATION) {
    for( $i=1; $i <= 30; $i++ ) {
      if ($i > 1)
        echo ',';
      echo '"'.getResult2($row['q'.$i]).'"';
    }
    echo "\n";
  } else if ($survey == ACTION_POSTNATAL_DEPRESSION) {
    echo '"';
    switch ( $row['q1'] ) {
      case 1:
        echo _("As much as I always could"); break;
      case 2:
        echo _("Not quite so much now"); break;
      case 3:
        echo _("Definitely not so much now"); break;
      case 4:
        echo _("Not at all"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q2'] ) {
      case 1:
        echo _("As much as I ever did"); break;
      case 2:
        echo _("Rather less than I used to"); break;
      case 3:
        echo _("Definitely less than I used to"); break;
      case 4:
        echo _("Hardly at all"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q3'] ) {
      case 1:
        echo _("Yes, most of the time"); break;
      case 2:
        echo _("Yes, some of the time"); break;
      case 3:
        echo _("Not very often"); break;
      case 4:
        echo _("No, never"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q4'] ) {
      case 1:
        echo _("No, not at all"); break;
      case 2:
        echo _("Hardly ever"); break;
      case 3:
        echo _("Yes, sometimes"); break;
      case 4:
        echo _("Yes, very often"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q5'] ) {
      case 1:
        echo _("Yes, quite a lot"); break;
      case 2:
        echo _("Yes, sometimes"); break;
      case 3:
        echo _("No, not much"); break;
      case 4:
        echo _("No, not at all"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q6'] ) {
      case 1:
        echo _("Yes, most of the time I haven't been able"); break;
      case 2:
        echo _("Yes, sometimes I haven't been coping as well"); break;
      case 3:
        echo _("No, most of the time I have coped quite well"); break;
      case 4:
        echo _("No, I have been coping as well as ever"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q7'] ) {
      case 1:
        echo _("Yes, most of the time"); break;
      case 2:
        echo _("Yes, sometimes"); break;
      case 3:
        echo _("Not very often"); break;
      case 4:
        echo _("No, not at all"); break;
      default:
        break;
    }
    echo '","';
    switch ($row['q8']) {
      case 1:
        echo _("Yes, all of the time"); break;
      case 2:
        echo _("Yes, most of the time"); break;
      case 3:
        echo _("Not very often"); break;
      case 4:
        echo _("No, not at all"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q9'] ) {
      case 1:
        echo _("Yes, most of the time"); break;
      case 2:
        echo _("Yes, quite often"); break;
      case 3:
        echo _("Only occasionally"); break;
      case 4:
        echo _("No, never"); break;
      default:
        break;
    }
    echo '","';
    switch ( $row['q10'] ) {
      case 1:
        echo _("Yes, quite often"); break;
      case 2:
        echo _("Sometimes"); break;
      case 3:
        echo _("Hardly ever"); break;
      case 4:
        echo _("Never"); break;
      default:
        break;
    }
    echo "\"\n";
  }

}
