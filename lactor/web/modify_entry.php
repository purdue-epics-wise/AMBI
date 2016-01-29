

<?php 
include_once("includes/general.php");
include_once("includes/db.php");
include_once("includes/constants.php");


initialize();
loggedIn();
db_connect();

loadVocabulary();

if(isset($_POST['entryid'])) $_SESSION['modid'] = $_POST['entryid'];
if(isset($_POST['direction'])) $_SESSION['direction'] = $_POST['direction'];

?>

<head>
<?php head_tag("LACTOR - "._("Modify Entry")); ?>
</head>

<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php page_menu(PAGE_MODIFY_ENTRY); ?>



<div id="pagecontent">
<div id="registercontent">

<?php if(isset($_SESSION['s_mid'])) {
  $_SESSION['Smessage']="Logged in as scientist.";$_SESSION['Stype']=2;$_SESSION['Sdetail']="";
  displayMessage('Smessage', 'Sdetail', 'Stype');
} ?>

<?php 
  if(isset($_SESSION['writetype'])) $_SESSION['writetype'] = 3;
  displayMessage('writemessage','writedetails', 'writetype'); 
?>

<div id="container">
<form  action="post/modify_entry.post.php" onsubmit="return checkForm()" 
  method="post" class="long">
<div class="tabs">
<ul class="menu">
<li><a href="#modify"><?php echo _("Modify Entry"); ?></a></li>
</ul>
<div id="modify">
<br />
<?php


if(isset($_POST['breast'])) {
  $entrytype = ENTRYTYPE_BREASTFEEDING;
} else if(isset($_POST['pumping'])) {
  $entrytype = ENTRYTYPE_PUMPING;
} else if(isset($_POST['sup'])) {
  $entrytype = ENTRYTYPE_SUPPLEMENT;
} else if(isset($_POST['out'])) {
  $entrytype = ENTRYTYPE_OUTPUT;
} else if(isset($_POST['morb'])) {
  $entrytype = ENTRYTYPE_HEALTH;
} else {
  go("view_diary.php");
}


//adding date input controls
$query = sprintf( 
  "SELECT * FROM Diary WHERE mid=%d AND EntryID=%d AND EntryType=%d", 
  $_SESSION['mid'],$_POST['entryid'], $entrytype );

$result = mysql_query( $query ) or die(_("Entry Not Found"));
$row = mysql_fetch_assoc( $result );
$entrydate = new DateTime( $row['EntryDate']);

$hour = $entrydate->format( 'g' );
$minute = $entrydate->format( 'i' );
$ampm = $entrydate->format( 'A' );
$today = "selected";
$yesterday = "";
if($entrydate->getTimestamp( ) < strtotime("today")) {
  $today = "";
  $yesterday = "selected";
}

echo "Time:   <select name='hour'>";
for ( $i=1; $i <= 12; $i++ ) {
  echo "<option " . (( $hour ==  $i ) ? "selected" : "" ) . 
    " value='$i'>$i</option>";
}
echo "</select>:";

echo "<select name='minute'>";
for ( $i=0; $i <= 60; $i+=5 ) {
  echo "<option " . (( $minute == $i ) ? "selected" : "" ) . 
    " value='$i'>" . (( $i < 10 ) ? "0" : "" ) . "$i</option>";
}
echo "</select> ";

echo "<select name='ampm'>";
echo "<option " . ($ampm == 'AM' ? 'selected' : '') . " value='AM'>"._("AM")."</option>";
echo "<option " . ($ampm == 'PM' ? 'selected' : '') . " value='PM'>"._("PM")."</option>";
echo "</select> ";




echo "     "._("Day").": <select name='day'>";
echo "<option " . $today . " value='today'>"._("Today")."</option>";
echo "<option " . $yesterday . " value='yesterday'>"._("Yesterday")."</option>";
echo "</select> ";

echo "<br />";

//end date input controls

if(isset($_POST['breast'])) {
  $query = sprintf("SELECT * FROM Diary d,BreastfeedEntry b WHERE 
                   d.EntryType=%d AND d.mid=%d AND b.EntryId = d.entryId AND 
                   b.EntryId=%d", 
                   ENTRYTYPE_BREASTFEEDING,$_SESSION['mid'], $_POST['entryid']);
  $result = mysql_query($query);
  if (!$result) {
   error_log(mysql_error( ));
  }
  $row = mysql_fetch_assoc($result);
  echo "<pre>"._("Breastfeeding duration:").
    "         <select class='standardselect' name='duration'>";
  echo selectControlledVocabulary('duration', $row['BreastfeedingDuration']);;
  echo "</select></pre>";

  echo "<pre>"._("Side:").
    "                           <select id='standardselect' name='side'>";
  echo  selectControlledVocabulary('Side', $row['Side']);
  echo "</select></pre>";

  echo "<pre>"._("Latching").
    ":                       <select class='standardselect' name='latching'>";
  echo selectControlledVocabulary('latching', $row[ 'Latching' ]);
  echo "</select></pre>";

  echo "<pre>"._("Infant alertness:").
    "               <select class='standardselect' name='infant_state'>";
  echo selectControlledVocabulary('infant-state', $row['InfantState']);
  echo "</select></pre>";

  echo "<pre>"._("Maternal breastfeeding problems:").
    "<select class='standardselect' name='maternal_problems'>";
  echo selectControlledVocabulary('maternal-problems', $row['MaternalProblems']);
  echo "</select></pre>";

  echo sprintf("<input name='entryid' value='%s' type='hidden' />",
    $_POST['entryid']);

  if($_SESSION['direction'] == 'view') {
    echo "<input name='direction' value='view' type='hidden' />";
  } else {
    echo "<input name='direction' value='add' type='hidden' />";
  }
  echo "<input type='submit' id='submit' value='"._("Modify Entry").
    "' name='breast' />";
  echo "<input type='submit' id='submit' value='"._("Cancel").
    "' name='cancel1' />";
}

if(isset($_POST['pumping'])) {
  $query = sprintf("SELECT * FROM Diary d,BreastfeedEntry b WHERE 
                   d.EntryType=%d AND d.mid=%d AND b.EntryId = d.entryId AND 
                   b.EntryId=%d", 
                   ENTRYTYPE_PUMPING,$_SESSION['mid'], $_POST['entryid']);
  $result = mysql_query($query);
  if (!$result) {
   error_log(mysql_error( ));
  }
  $row = mysql_fetch_assoc($result);
  echo "<pre>"._("Duration").":             ".
    "<select class='standardselect' name='duration'>";
  echo selectControlledVocabulary('duration', $row['BreastfeedingDuration']);;
  echo "</select></pre>";

  echo "<pre>"._("Method").":               ".
    "<select class='standardselect' name='pumping_method'>";
  echo selectControlledVocabulary('pumping-method', $row[ 'PumpingMethod' ]);
  echo "</select></pre>";

  echo "<pre>"._("Amount").":               ".
    "<select class='standardselect' name='pumping_amount'>";
  echo selectControlledVocabulary('TotalAmount', $row['PumpingAmount']);
  echo "</select></pre>";

  echo sprintf("<input name='entryid' value='%s' type='hidden' />",
    $_POST['entryid']);

  if($_SESSION['direction'] == 'view') {
    echo "<input name='direction' value='view' type='hidden' />";
  } else {
    echo "<input name='direction' value='add' type='hidden' />";
  }
  echo "<input type='submit' id='submit' value='"._("Modify Entry").
    "' name='pumping' />";
  echo "<input type='submit' id='submit' value='"._("Cancel").
    "' name='cancel1' />";
}


if(isset($_POST['sup'])) {
  $query = sprintf( "SELECT * FROM Diary d,SupplementEntry s WHERE 
                     d.EntryType=%d AND d.mid=%d AND d.EntryId = s.EntryId AND 
                     s.EntryId=%d",
                     ENTRYTYPE_SUPPLEMENT, $_SESSION['mid'], $_POST['entryid']);
  echo "<br /><br />".$query; // DEBUG
  $result = mysql_query($query);
  if (!$result) {
   error_log(mysql_error( ));
  }
  $row = mysql_fetch_assoc($result);

  echo "<h1><img src='image/supplement.gif' alt=''/>Supplement Entry</h1>";
  echo "<ul>";

  echo "<pre>"._("Type").":                   ".
    "<select id='standardselect' name='sup-type'>";
  echo selectControlledVocabulary("sup-type", $row['SupType']) ;
  echo "</select></pre>";
  echo "<pre>"._("Method").":                 ".
    "<select id='standardselect' name='sup-method'>";
  echo selectControlledVocabulary("sup-method", $row['SupMethod']);
  echo "</select></pre>";   
  echo "<pre>"._("Frequency").":  ".
    "<select id='standardselect' name='NumberTimes'>";
  echo selectControlledVocabulary("NumberTimes", $row['NumberTimes']) ;
  echo "</select></pre>";
  echo "<pre>"._("Total Amount Today").":     ".
    "<select id='standardselect' name='TotalAmount'>";
  echo selectControlledVocabulary("TotalAmount", $row['TotalAmount']) ;
  echo "</select></pre>";
  echo "<input name='entryid' value='".$_SESSION['modid'].
    "' type='hidden' />";
  if($_SESSION['direction'] == 'view') {
    echo "<input name='direction' value='view' type='hidden' />";
  } else {
    echo "<input name='direction' value='add' type='hidden' />";
  }
  echo "<input type='submit' id='submit' value='"._("Modify Entry").
    "' name='sup' />";
  echo "<input type='submit' id='submit' value='"._("Cancel").
    "' name='cancel2' />";
}  

if(isset($_POST['out'])) {
  $query = sprintf( "SELECT * FROM Diary d, OutputEntry o WHERE 
            d.EntryType=%d AND d.EntryId = o.EntryId 
            AND o.EntryId=%d", ENTRYTYPE_HEALTH, $_POST['entryid']);

  $result = mysql_query($query);
  if (!$result) {
   error_log(mysql_error( ));
  }
  $row = mysql_fetch_array($result);

  echo "<h1><img src='image/output.gif' alt=''/>Output Entry</h1>";
  echo "<ul>";
  echo "<pre>"._("Number of Diapers").":    ".
       "<select id='standardselect' name='NumberDiapers'>";
  echo selectControlledVocabulary("NumberDiapers", $row['NumberDiapers']);
  echo "</select></pre>";     
  echo "<br />";
  echo "<pre>"._("Urine Color").":          ".
       "<select id='standardselect' name='out-u-color'>";
  echo selectControlledVocabulary("out-u-color", $row['UrineColor']) ;
  echo "</select></pre>";
  echo "<pre>"._("Urine Saturation").":     ".
       "<select id='standardselect' name='out-u-saturation'>";
  echo selectControlledVocabulary("out-u-saturation", $row['UrineSaturation']);
  echo "</select></pre>";     
  echo "<br />";
  echo "<pre>"._("Stool Color").":          ".
       "<select id='standardselect' name='out-s-color'>";
  echo selectControlledVocabulary("out-s-color", $row['StoolColor']) ;
  echo "</select></pre>";
  echo "<pre>"._("Stool Consistency").":    ".
       "<select id='standardselect' name='out-s-consistency'>";
  echo selectControlledVocabulary("out-s-consistency", $row['StoolConsistency']);
  echo "</select></pre>"; 
  echo "<input name='entryid' value='".$_POST['entryid']."' type='hidden' />";
  if($_SESSION['direction'] == 'view') {
    echo "<input name='direction' value='view' type='hidden' />";
  } else {
    echo "<input name='direction' value='add' type='hidden' />";
  }
  echo "<input type='submit' id='submit' value='"._("Modify Entry")."' name='out' />";
  echo "<input type='submit' id='submit' value='"._("Cancel")."' name='cancel3' />";
}  

if(isset($_POST['morb'])) {
  $query = "SELECT * FROM Diary d, MorbidityEntry m WHERE 
            EntryType=".ENTRYTYPE_HEALTH." AND d.EntryId = m.EntryId AND 
            m.EntryId=".$_POST['entryid'];

  $result = mysql_query($query);
  if (!$result) {
   error_log(mysql_error( ));
  }
  $row = mysql_fetch_array($result);

  echo "<h1><img src='image/morbidity.gif' alt=''/>"._("Health Issue Entry")."</h1>";
  echo "<ul>";

  echo "<pre>"._("Type").":         <select id='standardselect' name='morb-type'>";
  echo selectControlledVocabulary("morb-type", $row['Type']) ;
  echo "</select></pre>";    
  echo "<input name='entryid' value='".$_POST['entryid']."' type='hidden' />";
  if($_SESSION['direction'] == 'view') {
    echo "<input name='direction' value='view' type='hidden' />";
  } else {
    echo "<input name='direction' value='add' type='hidden' />";
  }
  echo "<input type='submit' id='submit' value='"._("Modify Entry")."' name='morb'/>";
  echo "<input type='submit' id='submit' value='"._("Cancel")."' name='cancel4' />";
}  
?>



</div>
</div>
</form>
</div>


</div>
</div>


<?php page_footer(); ?>

</div>
</body>
</html>
