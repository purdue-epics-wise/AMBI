<?php 
/*
* Last updated by Andrew Huff (huffa@purdue.edu) on 2/8/2011
*/
include_once("../includes/general.php");
include_once("../includes/db.php");


initialize();
loggedIn();
db_connect();

loadVocabulary();

if(isset($_SESSION['downloadSurvey']) && $_SESSION['downloadSurvey']=="TRUE")
{
  
    $surveyType= $_SESSION['surveyT'];
    $_SESSION['downloadSurvey'] = "FALSE";
    $query = "SELECT * FROM ". $surveyType ." WHERE mid = " . $_SESSION['q_mid'] . " ;";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);


    if(!$row) //check if the user has filled a survey

    {  
      $filename= $surveyType . ".csv";
      $filedir="download/" . $filename;
      header('Content-type: application/csv');
      header("Content-disposition: attachment; filename=".$filename);
      ob_clean();
      flush();
      echo "\"No responses entered by the User\"";
      exit;      
    }
    else
    {

      $sql = "SELECT * FROM ControlledVocabulary WHERE Attribute ='" .$surveyType . "' ORDER BY NumValue";
            $sql_result = mysql_query($sql);
            $system_feedback_array = array();
            while($response = mysql_fetch_array($sql_result))
      {
        array_push($system_feedback_array,$response["TextValue"]);
      }
          
      $date = trim(date('m_d_Y', time()));  
      $filedir="Lactor_".$date.$surveyType.".csv";
      header('Content-type: application/csv');
      header("Content-disposition: attachment; filename=".$filedir);  
      ob_clean();
      flush();
      if($surveyType =="SystemFeedback")
      {
        echo "\"System Feeback Survey Results\"\n\n";
        /*echo $system_feedback_array[0] . "\n" . getResult($row['q1']) . "\n";
        echo $system_feedback_array[1] . "\n" . getResult($row['q2']) . "\n";
        echo $system_feedback_array[2] . "\n" . getResult($row['q3']) . "\n";
        echo $system_feedback_array[3] . "\n" . getResult($row['q4']) . "\n";
        echo $system_feedback_array[4] . "\n" . getResult($row['q5']) . "\n";
        echo $system_feedback_array[5] . "\n" . getResult($row['q6']) . "\n";
        echo $system_feedback_array[6] . "\n" . getResult($row['q7']) . "\n";
        echo $system_feedback_array[7] . "\n" . getResult($row['q8']) . "\n";
        echo $system_feedback_array[8] . "\n" . getResult($row['q9']) . "\n";
        echo $system_feedback_array[9] . "\n" . getResult($row['q10']) . "\n";*/

        for ($i = 0; $i < 10; $i++) 
        {
            writeSurveyForUser($system_feedback_array,$i,$row,1);
        }
      }
      else
      {
        echo "\"System Perception Survey Results\"\n\n";
        /*echo $system_feedback_array[0] . "\n" . $row['q1'] . "\n";
        echo $system_feedback_array[1] . "\n" . $row['q2'] . "\n";
        echo $system_feedback_array[2] . "\n" . $row['q3'] . "\n";
        echo $system_feedback_array[3] . "\n" . $row['q4'] . "\n";
        echo $system_feedback_array[4] . "\n" . $row['q5'] . "\n";
        echo $system_feedback_array[5] . "\n" . $row['q6'] . "\n";
        echo $system_feedback_array[6] . "\n" . $row['q7'] . "\n";*/

        for ($i = 0; $i < 7; $i++) 
        {
            writeSurveyForUser($system_feedback_array,$i,$row,0);
        }
      }
      exit;
    }
}
else if(isset($_SESSION['downloadAll']) && $_SESSION['downloadAll']=="TRUE")
{
  $_SESSION['downloadAll'] = "FALSE";
  $mothers = mysql_query('SELECT * FROM Mothers');
  $motherids= array();
  while($response = mysql_fetch_array($mothers))
  {
    array_push($motherids,$response["mid"]);
  }

  $date = trim(date('m_d_Y', time()));
  $filedir="Lactor_".$date."SurveyResults.csv";
  header('Content-type: application/csv');
  header("Content-disposition: attachment; filename=".$filedir);
  ob_clean();
  flush();

  $sql = "SELECT * FROM ControlledVocabulary WHERE Attribute ='SystemFeedback' ORDER BY NumValue";
  $sql_result = mysql_query($sql);
  $system_feedback_array = array();
  while($response = mysql_fetch_array($sql_result))
  {
    array_push($system_feedback_array,$response["TextValue"]);
  }

  $sql = "SELECT * FROM ControlledVocabulary WHERE Attribute ='SystemPerception' ORDER BY NumValue";
  $sql_result = mysql_query($sql);
  $system_perception_array = array();
  while($response = mysql_fetch_array($sql_result))
  {
    array_push($system_perception_array,$response["TextValue"]);
  }
  echo "\"System Feedback Survey Results\"\n\n\n";
  
  for ($i = 0; $i < 10; $i++) 
  {
      writeSystemFeedback($system_feedback_array,$motherids,$i);
  }
  echo "\"System Perception Survey Results\"\n\n\n";
  
  for($i =0;$i<7;$i++)
  {
    writeSystemPerception($system_perception_array,$motherids,$i);
  }



  exit;
}

?>

<head>
<?php head_tag("Admin pCare - "._("User Accounts")); ?>
</head>


<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php admin_menu(3); ?>

<div id="pagecontent">
<?php 

displayMessage( @$_SESSION['AccountMessage'], @$_SESSION['AccountDetails'], @$_SESSION['AccountType']);
unset( $_SESSION['AccountMessage'], $_SESSION['AccountDetails'], $_SESSION['AccountType'] );

$query = "SELECT email FROM Mothers m LEFT JOIN InfantProfile i ON m.mid = i.mid WHERE i.mid IS NULL;";
$result = mysql_query($query);
$needInfoEmails = array( );
if( mysql_num_rows( $result )) {
  while($row = mysql_fetch_array( $result )) {
    array_push( $needInfoEmails, $row[ 'email' ]);
  }
  if ( count( $needInfoEmails )) {
    $childMessage = "The following mother(s) need their child information entered: ";
    $childType = 2;
    $mothers = getMotherInfo( $needInfoEmails );
    $childDetails = "";
    foreach( $mothers as $mother ) {
      $childDetails .= sprintf( "%s (%s)<br />", $mother['Name'], $mother['email'] );
    }
    displayMessage( $childMessage, $childDetails, $childType ); 
  }
}
?> 

<div id="registercontent">

  <div id="container">
  <div class='tabs'>
    <ul class="menu"><!-- Each item is a tab -->
      <li><a href='#manage'><?php echo _("Add/Manage Users") ?></a></li>
      <li><a href='#proxy'><?php echo _("Proxy") ?></a></li>
      <li><a href='#editMother'><?php echo _("View/Edit Mother Info") ?></a></li>
      <li><a href='#editChild'><?php echo _("View/Edit Child Info") ?></a></li>
      <li><a href='#questionnaire'><?php echo _("Questionnaires") ?></a></li>
    </ul>
    <div id='manage'>
      <h1><img src="image/Portfolio.png" width=32 height=32> <?php echo _("Manage Users") ?></h1>
      <ul>
        <form name="manage_user" method="post" action="admin/post/user_accounts.post.php">
          <pre> <?php echo _("Action") ?>:     <select name="perform">
            <option value="<?php echo ACTION_CONSENT; ?>"><?php echo _("Request consent form agreement") ?></option>
            <option value="<?php echo ACTION_GU_CONSENT; ?>"><?php echo _("Request Graceland Univ. consent form agreement") ?></option>
            <option value="<?php echo ACTION_SYSTEM_PERCEPTION; ?>"><?php echo _("Request system feedback") ?></option>
            <option value="<?php echo ACTION_BREASTFEEDING_FOLLOWUP; ?>"><?php echo _("Send breastfeeding followup") ?></option>
            <option value="<?php echo ACTION_SELF_EFFICACY; ?>"><?php echo _("Send self-efficacy survey") ?></option>
            <option value="<?php echo ACTION_BREASTFEEDING_EVALUATION; ?>"><?php echo _("Send breastfeeding evaluation") ?></option>
            <option value="<?php echo ACTION_POSTNATAL_DEPRESSION; ?>"><?php echo _("Send postnatal depression survey") ?></option>
<!--            <option value="<?php // echo ACTION_CHANGE_PASSWORD; ?>"><?php echo _("Change Password") ?></option> -->
            <option value="<?php echo ACTION_MOTHER_INFORMATION; ?>"><?php echo _("Issue Mother Information") ?></option>
            <option value="<?php echo ACTION_RESET_PASSWORD; ?>"><?php echo _("Reset Password") ?></option>
            <option value="<?php echo ACTION_DISABLE_USER; ?>"><?php echo _("Disable User") ?></option>
            <option value="<?php echo ACTION_ENABLE_USER; ?>"><?php echo _("Enable User") ?></option>
            <option value="<?php echo ACTION_DELETE_ACCOUNT; ?>"><?php echo _("Delete User") ?></option>
          </select></pre>
          <pre> <?php echo _("Mothers") ?>:   <select multiple size="15" name="midi[]"> 
              <?php     
              $mothers = getMotherInfo( );
              foreach( $mothers as $mother ) {
                printf( "<option value='%s'>%s (%s)</option>",
                        $mother['mid'], $mother['Name'], $mother['email'] );
              } 
              ?></select>     <input type="submit" name="manage" value="  <?php echo _("Set") ?>  "></pre>
          <br />
          <br />
          <br />
          <br />
          <br />
          
        </form>
        <h1><?php echo _("Add User") ?></h1>
        <form name="add" method="post" action="admin/post/user_accounts.post.php">
          <pre><?php echo _("User Type") ?>:  <select name="user" onchange='show_hosp_select( this.options[this.selectedIndex].value );'>
            <option value="1"><?php echo _("Mother") ?></option>
            <?php if ( $_SESSION['admin'] > SCIENTIST ) {
              echo "<option value='2'>"._("Scientist")."</option>";
              if ( $_SESSION['admin'] > HOSPITAL_ADMIN ) {
                echo "<option value='3'>"._("Hospital Administrator")."</option>";
                echo "<option value='4'>"._("Super Administrator")."</option>";
              }
            }
            ?>
            </select> <?php echo _("Email") ?>:<input id="standardtextform" type="text" name="email" />  <input type="submit" name="add" value="<?php echo _("Add User") ?>"><?php 
            if ( $_SESSION['admin'] == SUPER_ADMIN ) {
              echo "<br /> <span id='hospital_selector' style=''>"._("Hospital").":   <select name='hospital'>";
              $query = "SELECT * FROM Hospital ORDER BY hospital_name";
              $result = mysql_query( $query );
              while( $hospital = mysql_fetch_assoc( $result )) {
                echo "<option value='".$hospital['hospital_id']."'>".$hospital['hospital_name']."</option>";
              }
              echo "</select></span>";
            } else {
              echo "       ";
            }
            ?> <?php echo _("Name") ?>:<input id="standardtextform" type="text" name="name" />
            </pre>
            <script type='text/javascript'><!--
              function show_hosp_select( value ) {
                if ( value <= 3 ) {
                  jQuery( '#hospital_selector' ).css( 'visibility', 'visible' );
                } else {
                  jQuery( '#hospital_selector' ).css( 'visibility', 'hidden' );
                }
              }
            //--></script>

          <br />
          <br />
        </form>
        <?php
        if ( $_SESSION['admin'] == SUPER_ADMIN ) {
          echo "<h1>"._("Hospital Management")."</h1>
                <form name='add_hospital' method='post' action='admin/post/user_accounts.post.php'>
                <pre>"._("Hospital").": <input type='text' name='new_hospital' style='width:20em;'>   <input type='submit' name='add_hospital' value='"._("Add Hospital")."'><br /><br /></form>";
          echo "<form name='delete_hospital' method='post' action='admin/post/user_accounts.post.php' 
                onsubmit='return confirm(\"Removing this hospital will also remove all lactation consultants and hospital administrators for this hospital. In addition all mothers associated with this hospital will no longer be associated with any hospital.\\n\\nAre you sure you want to do this?\")'
                >"._("Hospital").": <select name='hospital_delete' style='width:20em;'>";
              $query = "SELECT * FROM Hospital ORDER BY hospital_name";
              $result = mysql_query( $query );
              while( $hospital = mysql_fetch_assoc( $result )) {
                echo "<option value='".$hospital['hospital_id']."'>".$hospital['hospital_name']."</option>";
              }
              echo "</select>   <input type='submit' name='delete_hospital' value='"._("Remove Hospital")."'></pre>
                </form>";
        }
        ?>
    </div>
    
    <div id='proxy'>
      <h1><img src="image/Forward.png" width=32 height=32> <?php echo _("Log In As Mother") ?></h1>
      <ul>
        <form name="feedback" method="post" action="admin/post/user_accounts.post.php#proxy">
          <pre><?php echo _("Mother") ?>:    <select name="mid"> 
              <?php     
              foreach ( $mothers as $mother )  {
                printf( "<option value='%s'>%s (%s)</option>",
                        $mother['mid'], $mother['Name'], $mother['email'] );
              } 
              ?> </select>
          </pre>
          <br />
          <pre>                  <input type="submit" name="proxy" value="<?php echo _("Log In As Mother"); ?>"></pre>
          <br />
        </form>
      <ul>
    </div>
    
    <div id='editMother'>
    <h1><img src="image/female.png" width=32 height=32> <?php echo _("View / Edit Mother Information") ?></h1>
    <ul>
    <form name="info" method="post" action="admin/post/user_accounts.post.php#editMother">
    <?php echo _("Mother") ?>:    <select name="mid" onchange="this.form.elements['info'].click();"> 
        <?php     
        //get breastfeeding entries
        $mid;
        if(isset($_SESSION['s_mid'])) {
          $mid=$_SESSION['s_mid'];
        }
        else {
          $mid=1;
        }
        $query = "select * from Mothers ORDER BY mid;";
        $result = mysql_query($query);
        
        foreach ( $mothers as $mother ) {
          printf( "<option value='%s' %s>%s (%s)</option>",
                  $mother['mid'], ($mother['mid'] == $mid) ? "selected" : "",
                  $mother['Name'], $mother['email'] );
        } 
        ?> </select>  <input type="submit" name="info" value="<?php echo _("Mother Information"); ?>" />
    <br />
    <br />
    <?php
    $query = "SELECT * FROM MotherInfo mi,Mothers_Scientists ms,Scientists s,Mothers m LEFT JOIN Hospital h on h.hospital_id = m.hospital_id
        WHERE m.mid = mi.mid AND mi.mid = ms.mid AND ms.sid = s.sid AND mi.mid = $mid";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    {
      echo "<table border='0'><tbody>";
      echo "<tr><td>"._("Name").":</td><td><input class='standardtextform' type='text' name='FormalName' value='" . $row['Name'] . "'></td></tr>";
      echo "<tr><td>"._("Address").":</td><td><input class='standardtextform' type='text' name='Address' value='" . $row['Address'] . "'></td></tr>";
      echo "<tr><td>"._("Phone").":</td><td><input class='standardtextform' type='text' name='Phone' value='" . $row['Phone'] . "'></td></tr>";

      if ( $_SESSION['admin'] > SCIENTIST ) {
        echo "<tr><td>"._("Lactation Consultant").":</td><td><select class='standardselect' name='lactationConsultant'>";
        $query = "SELECT sid,name,email FROM Scientists ORDER BY email;";
        $scientist_result = mysql_query( $query );
        while( $scientist = mysql_fetch_assoc( $scientist_result )) {
          $displayname = $scientist['name'] ? $scientist['name'].' ('.$scientist['email'].')' : $scientist['email'];
          printf( "<option %s value='%s'>%s</option>",
            ( $scientist['sid'] == $row['sid'] ) ? "selected='true'" : "", $scientist['sid'], $displayname);
        }
        echo "</select></td></tr>";
        echo "<tr><td>"._("Hospital").":</td><td>".$row['hospital_name']."</td></tr>";
      }

      echo "<tr><td>"._("Age").":</td><td><select name='Age' class='standardselect'>"; echo selectControlledVocabulary("Age", $row['Age']); echo "</select></td></tr>";
      echo "<tr><td>"._("Ethnicity").":</td><td><select name='Ethnicity' cglass='standardselect'>"; echo selectControlledVocabulary("Ethnicity", $row['Ethnicity']); echo "</select></td></tr>";
      echo "<tr><td>"._("Race").":</td><td><select name='Race' class='standardselect'>"; echo selectControlledVocabulary("Race", $row['Race']); echo "</select></td></tr>";
      echo "<tr><td>"._("Educational Level").":</td><td><select name='Education' class='standardselect'>"; echo selectControlledVocabulary("Education", $row['Education']); echo "</select></td></tr>";
      echo "<tr><td>"._("Household Income").":</td><td><select name='HouseIncome' class='standardselect'>"; echo selectControlledVocabulary("HouseIncome", $row['HouseIncome']); echo "</select></td></tr>";
      echo "<tr><td>"._("Occupation").":</td><td><select name='Occupation' class='standardselect'>"; echo selectControlledVocabulary("Occupation", $row['Occupation']); echo "</select></td></tr>";
      echo "<tr><td>"._("Residence").":</td><td><select name='Residence' class='standardselect'>"; echo selectControlledVocabulary("Residence", $row['Residence']); echo "</select></td></tr>";
      echo "<tr><td>"._("This baby is your").":</td><td><select name='Parity' class='standardselect'>"; echo selectControlledVocabulary("Parity", $row['Parity']); echo "</select></td></tr>";
      echo "<tr><td>"._("Maternal History During Pregnancy")."</td><td>" . getCheckedMHDP($row['MHDP']) . "</td></tr>";
      echo "<tr><td>"._("Method Of Delivery").":</td><td><select name='MODel' class='standardselect'>"; echo selectControlledVocabulary("MODel", $row['MethodOfDelivery']); echo "</select></td></tr>";
      echo "<tr><td>Past Breastfeeding Experience:</td><td><select name='PBE' class='standardselect'>"; echo selectControlledVocabulary("PBE", $row['PBE']); echo "</select></td></tr>";
      echo "</tbody></table>";
/*
      echo "<tr><td>How long to you plan to breastfeed your baby?";
      echo createOptions("HowLong", array(
        "Three months" => "Three months",
        "6 months" => "6 months",
        "9 months" => "9 months",
        "12 months" => "12 months",
        "more"     =>  "more" ), $row["HowLong"], OPTIONS_TYPE_SELECT);
      echo "</pre>";
      echo "<pre>Did you have any breastfeeding class before giving birth or in the past?";
      echo createOptions("bfClass", array("Yes" => "Yes", "No" => "No"), $row["bfClass"], OPTIONS_TYPE_SELECT);
      echo "</pre>";
      echo "<pre>Nipple condition:</td><td>";
      echo createOptions("nipple_condition", array(
           "Protruded" => "Protruded", 
           "Flat" => "Flat", 
           "Inverted" => "Inverted"), $row["nipple_condition"], OPTIONS_TYPE_SELECT);
      echo "</td></tr>";
*/

    }
    
    if($row != NULL) {
    $value = "Update  ";
    $name = "mupdate";
    $download = "<input type='submit' name='motherDownload' value='"._("Download")."' />";
    } else {
    $value = "  Insert  ";
    $name = "minsert";
    $download = "";
    }
    printf( "<pre>                                 <input type='submit' name='%s' value='%s' />%s</pre>", 
      $name, $value, $download );
  
    ?>
    
    
    <br />
    </form>
    <ul>
    
    </div>
    
    <div id='editChild'>
    <h1><img src="image/baby.png" width=32 height=32> <?php echo _("View / Edit Child Information") ?></h1>
    <ul>
    <form name="child" method="post" action="admin/post/user_accounts.post.php#editChild">
    <pre><?php echo _("Mother") ?>:    <select name="mide" onchange="this.form.elements['childinfo'].click();"> 
        <?php     
        //get breastfeeding entries
        $mid;
        if(isset($_SESSION['e_mid'])) {
          $mid=$_SESSION['e_mid'];
        }
        else {
          $mid=1;
        }
        foreach( $mothers as $mother ) {
          printf( "<option value='%s' %s>%s (%s)</option>",
                  $mother['mid'], ($mother['mid'] == $mid) ? "selected" : "",
                  $mother['Name'], $mother['email'] );
        } 
        ?> </select>  <input type="submit" name="childinfo" value="<?php echo _("Child Information") ?>" /> </pre><br/>
    <?php
    function getWeight($str, $type) {
      $values=explode(" ", $str);
      return $values[$type];
    }
    
    $mid;
    if(isset($_SESSION['e_mid'])) {
      $mid=$_SESSION['e_mid'];
    }
    else {
      $mid=1;
    }
    //get breastfeeding entries
    $query = "SELECT * FROM InfantProfile WHERE mid = " . $mid . ";";
    //echo $query;
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    echo "<table border='0'><tbody>";
    echo "<tr><td>"._("Infant initials").":</td><td><input class='standardtextform' type='text' name='InfantInitials' value='" . $row['InfantInitials'] . "'></td></tr>";
    echo "<tr><td>"._("Gestational age").":</td><td><select name='GestationalAge' class='standardselect'>"; echo selectControlledVocabulary("gestate", $row['GestationalAge']); echo "</select></td></tr>";
    echo "<tr><td>"._("Appropiateness for gestational age").":</td><td><select name='AppropAge' class='standardselect'>"; echo selectControlledVocabulary("GestationalAge", $row['AppropAge']); echo "</select></td></tr>";
    echo "<br />";
    if(isset($row['DOB']) && $row['DOB'] != "0000-00-00 00:00:00") 
      $d1 = modDate2($row['DOB']);
    else
      $d1 = "";
    if(isset($row['DOD']) && $row['DOD'] != "0000-00-00 00:00:00" ) 
      $d2 = modDate2($row['DOD']);
    else
      $d2 = "";
    echo "<tr><td>"._("Date of birth").":</td><td><input class='datepicker' type='text' value='" . $d1 . "' name='dateBirth' /></td></tr>";
    echo "<tr><td>"._("Infant weight at birth").":</td><td><select name='BirthWeightPounds'>"; echo selectControlledVocabulary('pounds', getWeight($row['BirthWeight'], 0)); echo "</select> <select name='BirthWeightOunces'>"; echo selectControlledVocabulary('ounces', getWeight($row['BirthWeight'], 1)); echo "</select></td></tr>";
    echo "<tr><td>"._("Date of discharge").":</td><td><input class='datepicker' type='text' value='" . $d2 . "' name='dateDischarge' /></td></tr>";
    echo "<tr><td>"._("Weight at discharge").":</td><td><select name='DischargeWeightPounds'>"; echo selectControlledVocabulary('pounds', getWeight($row['DischargeWeight'], 0)); echo "</select> <select name='DischargeWeightOunces'>"; echo selectControlledVocabulary('ounces', getWeight($row['DischargeWeight'], 1)); echo "</select></td></tr>";
    echo "<tr><td>"._("Type of first feeding").":</td><td><select name='TypeFirstBreast' class='standardselect'>"; echo selectControlledVocabulary("TypeFirstDischarge", $row['TypeFirstBreast']); echo "</select></td></tr>";
    echo "<tr><td>"._("Infant's age at first feeding session").":</td><td><select name='AgeFirstFeed' class='standardselect'>"; echo selectControlledVocabulary("AgeFirstFeed", $row['AgeFirstFeed']); echo "</select></td></tr>";
    echo "<tr><td>"._("Time of starting breast milk expression").":</td><td><select name='TimeStartBreast' class='standardselect'>"; echo selectControlledVocabulary("TimeStartBreast", $row['TimeStartBreast']); echo "</select></td></tr>";
    echo "<tr><td>"._("Frequency of breast milk expression").":</td><td><select name='FreqBreastExpr' class='standardselect'>"; echo selectControlledVocabulary("FreqBreastExpr", $row['FreqBreastExpr']); echo "</select></td></tr>";
    echo "<tr><td>"._("First primary care provider visit").":</td><td><select name='FirstPrimCare' class='standardselect'>"; echo selectControlledVocabulary("FirstPrimCare", $row['FirstPrimCare']); echo "</select></td></tr>";
    echo "<tr><td>"._("Need for extra primary care provider").":</td><td><select name='NeedExtraCare' class='standardselect'>"; echo selectControlledVocabulary("NeedExtraCare", $row['NeedExtraCare']); echo "</select></td></tr>";
    echo "<tr><td>"._("Times of extra primary care on first month").":</td><td><select name='TimesExtraCare' class='standardselect'>"; echo selectControlledVocabulary("TimesExtraCare", $row['TimesExtraCare']); echo "</select></td></tr>";
    echo "<tr><td>"._("Hospitalization during the first month").":</td><td><select name='HospFirstMonth' class='standardselect'>"; echo selectControlledVocabulary("HospFirstMonth", $row['HospFirstMonth']); echo "</select></td></tr>";
    echo "</tbody></table>";
    
    if($row != NULL) {
    $value = "  Update  ";
    $name = "cupdate";
    } else {
    $value = "  Insert  ";
    $name = "cinsert";
    }
    printf( "<pre>                                 <input type='submit' name='%s' value='%s' /><input type='submit' name='childDownload' value='"._("Download")."' /></pre>",
      $name, _($value));
    ?>
    <br />
    <br />
    </form>
    <ul>
    </div>
    
    <div id='questionnaire'>
      <ul>
          <form name="questionaire" method="post" action="admin/post/user_accounts.post.php#questionnaire">
            <!-- drop down #1 -->
            <?php echo _("Mother"); ?>: <select name="midq">
            <?php   
              $mothers_questionnaire = getMotherInfo( false );
//                "M.mid IN (SELECT mid FROM SystemFeedback) OR M.mid IN (SELECT mid FROM SystemPerception)" );
              foreach( $mothers_questionnaire as $mother ) {
                printf( "<option value='%s' %s>%s (%s)</option>",
                        $mother['mid'], ($mother['mid'] == @$_SESSION['midq']) ? "selected" : "",
                        $mother['Name'], $mother['email'] );
              } 
              if (isset ($_SESSION['midq'])) {
                unset($_SESSION['midq']);
              }
            ?> 
            </select> 
            <br />
            <br />
            
            <!-- drop down #2 -->
            Questionaire: <select name="surveyType" onchange="this.form.elements['questionaire'].click();">
            <?php
              // Check if a survey has been selected (been to user_accounts.post.php and back)
              $survey = @$_SESSION['surveyT'];
              printf( "<option value='%s' %s>"._("System Feedback")."</option>\n",
                ACTION_SYSTEM_FEEDBACK, ($survey == ACTION_SYSTEM_FEEDBACK ) ? 'selected':'');
              printf( "<option value='%s' %s>"._("System Feedback")."</option>\n",
                ACTION_SYSTEM_PERCEPTION, ($survey == ACTION_SYSTEM_PERCEPTION ) ? 'selected':'');
              printf( "<option value='%s' %s>"._("Breastfeeding Followup")."</option>\n",
                ACTION_BREASTFEEDING_FOLLOWUP, ($survey == ACTION_BREASTFEEDING_FOLLOWUP ) ? 'selected':'');
              printf( "<option value='%s' %s>"._("Self Efficacy Survey")."</option>\n",
                ACTION_SELF_EFFICACY, ($survey == ACTION_SELF_EFFICACY ) ? 'selected':'');
              printf( "<option value='%s' %s>"._("Breastfeeding Evaluation")."</option>\n",
                ACTION_BREASTFEEDING_EVALUATION, ($survey == ACTION_BREASTFEEDING_EVALUATION ) ? 'selected':'');
              printf( "<option value='%s' %s>"._("Postnatal Depression")."</option>\n",
                ACTION_POSTNATAL_DEPRESSION, ($survey == ACTION_POSTNATAL_DEPRESSION ) ? 'selected':'');
              echo"</select>";
            ?>
            
            <input type="submit" name="questionaire" value="<?php echo _("Survey Results") ?>">
          </form>
          <?php
      if(isset($_SESSION['surveyT']) && isset($_SESSION['q_mid'])){    // Get survey resonses
        $query = "SELECT * FROM ". surveyTable($_SESSION['surveyT']) ." WHERE mid = " . $_SESSION['q_mid'] . " ;";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);

          echo "<br />";
          echo "<form method='post' action='admin/post/survey_download.php'>";
          echo "<input type='hidden' name='survey' value='$survey' />";
          echo "<input type='hidden' name='mid' value='".$_SESSION['q_mid']."' />";
          if ($row)
            echo "<input type='submit' name='downloadsurveyresults' value='"._('Download For This User')."'>";
          echo "<input type='submit' name='downloadAll' value='"._('Download All Of This Type')."'>";
          echo "</form>";
          echo "<br /><br />";
        
        // Check if anything was returned from query, if not no info to display
        if($row)
        {
          // Echo the results os SystemFeedback survey
          if($survey==ACTION_SYSTEM_FEEDBACK) {
            echo "<p style='font-size:large'><b>"._("System Feeback Survey Results")."</b></p><br />";
            echo "<b>"._("I think that I would like to use this system frequently")."</b><br />" . getResult($row['q1']) . "<br /><br />";
            echo "<b>"._("I found the system unnecessarily complex")."</b><br />" . getResult($row['q2']) . "<br /><br />";
            echo "<b>"._("I thought the system was easy to use")."</b><br />" . getResult($row['q3']) . "<br /><br />";
            echo "<b>"._("I think that I would need the support of a technical person to use this system")."</b><br />" . getResult($row['q4']) . "<br /><br />";
            echo "<b>"._("I found the various functions in this system were well integrated")."</b><br />" . getResult($row['q5']) . "<br /><br />";
            echo "<b>"._("I thought there was too much inconsistency in this system")."</b><br />" . getResult($row['q6']) . "<br /><br />";
            echo "<b>"._("I would imagine that most people would learn to use this system quickly")."</b><br />" . getResult($row['q7']) . "<br /><br />";
            echo "<b>"._("I found the system very cumbersome to use")."</b><br />" . getResult($row['q8']) . "<br /><br />";
            echo "<b>"._("I felt very confident using the system")."</b><br />" . getResult($row['q9']) . "<br /><br />";
            echo "<b>"._("I needed to learn a lot of the things before I could get going with this system")."</b><br />".  getResult($row['q10']) . "<br /><br />";

          } else if($survey==ACTION_SYSTEM_PERCEPTION) {
            echo "<div style='font-size:large'><b>"._("System Perception Survey Results")."</b></div><br />";
            echo "<b>"._("Was the web-based breastfeeding monitoring helpful in recognizing your baby's breastfeeding problems?")."</b><br />";
            echo "<b>"._("How did it help?")."</b><br />" . $row['q1'] . "<br /><br />";
            echo "<b>"._("Was the data entry time consuming for you? Do you have any suggestions?")."</b><br />" . $row['q2'] . "<br /><br />";
            echo "<b>"._("Was the data entry a burden for you or overwhelming? Do you have any suggestions?")."</b><br />" . $row['q3'] . "<br /><br />";
            echo "<b>"._("How did the web-based monitoring help in overcoming your baby's health problems?")."</b><br />" . $row['q4'] . "<br /><br />";
            echo "<b>"._("Do you think the system helped you to breastfeed longer?")."</b><br />" . $row['q5'] . "<br /><br />";
            echo "<b>"._("Do you think the monitoring helped you to decrease supplementation with any substance other than breast milk?")."</b><br />" . $row['q6'] . "<br /><br />";
            echo "<b>"._("Would you recommend this web-based monitoring for a friend? Why and why not?")."</b><br />" . $row['q7'] . "<br /><br />";

          } else if ($survey == ACTION_BREASTFEEDING_FOLLOWUP ) {
            echo "<div style='font-size:large'><b>"._("Breastfeeding Followup Survey Results")."</b></div><br />";
            echo "<b>"._("Which survey number is this for you?")."</b><br />" . $row['q1'] . "<br /><br />";
            echo "<b>"._("How long are you planning to breastfeed your baby?")."</b><br />" . $row['q2'] . "<br /><br />";
            echo "<b>"._("How many times do you breastfeed your baby per day?")."</b><br />" . $row['q3'] . "<br /><br />";
            echo "<b>"._("Do you give any other substances (supplementation) for your baby?")."</b><br />" . $row['q4'] . "<br /><br />";
            echo "<b>"._("If yes, what kind of supplement do you give?")."</b><br />" . $row['q5'] . "<br /><br />";
            echo "<b>"._("How often do you give a supplemental feeding per day?")."</b><br />" . $row['q6'] . "<br /><br />";
            echo "<b>"._("Causes of supplemental feeding")."</b><br />" . $row['q7'] . "<br /><br />";
            echo "<b>"._("Pattern of breastfeeding (How did you breastfeed your baby in the last 7 days)")."</b><br />" . $row['q8'] . "<br /><br />";
            echo "<b>"._("Breastfeeding/baby Problems")."</b><br />" . $row['q9'] . "<br /><br />";

          } else if ($survey == ACTION_SELF_EFFICACY) {
            echo "<div style='font-size:large'><b>"._("Self-efficacy Survey Results")."</b></div><br />";
            echo "<b>"._("I can always determine that my baby is getting enough milk.")."</b><br />".getResult2($row['q1'])."<br /><br />";
            echo "<b>"._("I can always successfully cope with breastfeeding like I have with other challenging tasks.")."</b><br />".getResult2($row['q2'])."<br /><br />";
            echo "<b>"._("I can always breastfeed my baby without first using formula as a supplement.")."</b><br />".getResult2($row['q3'])."<br /><br />";
            echo "<b>"._("I can always ensure that my baby is properly latched on for the whole feeding.")."</b><br />".getResult2($row['q4'])."<br /><br />";
            echo "<b>"._("I can always manage the breastfeeding situation to my satisfaction.")."</b><br />".getResult2($row['q5'])."<br /><br />";
            echo "<b>"._("I can always manage to breastfeed even if my baby is crying.")."</b><br />".getResult2($row['q6'])."<br /><br />";
            echo "<b>"._("I can always keep wanting to breastfeed")."</b><br />".getResult2($row['q7'])."<br /><br />";
            echo "<b>"._("I can always comfortably breastfeed with my family members present.")."</b><br />".getResult2($row['q8'])."<br /><br />";
            echo "<b>"._("I can always be satisfied with my breastfeeding experience.")."</b><br />".getResult2($row['q9'])."<br /><br />";
            echo "<b>"._("I can always deal with the fact that breastfeeding can be time consuming.")."</b><br />".getResult2($row['q10'])."<br /><br />";
            echo "<b>"._("I can always finish feeding my baby on one breast before switching to the other breast.")."</b><br />".getResult2($row['q11'])."<br /><br />";
            echo "<b>"._("I can always continue to breastfeed my baby for every feeding.")."</b><br />".getResult2($row['q12'])."<br /><br />";
            echo "<b>"._("I can always manage to keep up with my baby's breastfeeding demands.")."</b><br />".getResult2($row['q13'])."<br /><br />";
            echo "<b>"._("I can always tell when my baby is finished breastfeeding.")."</b><br />".getResult2($row['q14'])."<br /><br />";

          } else if ($survey == ACTION_BREASTFEEDING_EVALUATION) {
            echo "<div style='font-size:large'><b>"._("Breastfeeding Evaluation Survey Results")."</b></div><br />";
            echo "<b>"._("With breastfeeding I felt a sense of inner contentment.")."</b><br />".getResult2($row['q1'])."<br /><br />";
            echo "<b>"._("Breastfeeding was a special time with my baby.")."</b><br />".getResult2($row['q2'])."<br /><br />";
            echo "<b>"._("My baby wasn't interested in breastfeeding.")."</b><br />".getResult2($row['q3'])."<br /><br />";
            echo "<b>"._("My baby loved to nurse.")."</b><br />".getResult2($row['q4'])."<br /><br />";
            echo "<b>"._("It was a burden being my baby's main source of food.")."</b><br />".getResult2($row['q5'])."<br /><br />";
            echo "<b>"._("I felt extremely close to my baby when I breastfed.")."</b><br />".getResult2($row['q6'])."<br /><br />";
            echo "<b>"._("My baby was an eager breastfeeder.")."</b><br />".getResult2($row['q7'])."<br /><br />";
            echo "<b>"._("Breastfeeding was physically draining.")."</b><br />".getResult2($row['q8'])."<br /><br />";
            echo "<b>"._("It was important to me to be able to nurse.")."</b><br />".getResult2($row['q9'])."<br /><br />";
            echo "<b>"._("While breastfeeding, my baby's growth was excellent.")."</b><br />".getResult2($row['q10'])."<br /><br />";
            echo "<b>"._("My baby and I worked together to make breastfeeding go smoothly.")."</b><br />".getResult2($row['q11'])."<br /><br />";
            echo "<b>"._("Breastfeeding was a very nurturing, maternal experience.")."</b><br />".getResult2($row['q12'])."<br /><br />";
            echo "<b>"._("While breastfeeding, I felt self-concious about my body.")."</b><br />".getResult2($row['q13'])."<br /><br />";
            echo "<b>"._("With breastfeeding, I felt too tied down all the time.")."</b><br />".getResult2($row['q14'])."<br /><br />";
            echo "<b>"._("While breastfeeding, I worried about my baby gaining enough weight.")."</b><br />".getResult2($row['q15'])."<br /><br />";
            echo "<b>"._("Breastfeeding was soothing when my baby was upset or crying.")."</b><br />".getResult2($row['q16'])."<br /><br />";
            echo "<b>"._("Breastfeeding was like a high of sorts.")."</b><br />".getResult2($row['q17'])."<br /><br />";
            echo "<b>"._("The fact that I could produce the food to feed my own baby was very satisfying.")."</b><br />".getResult2($row['q18'])."<br /><br />";
            echo "<b>"._("In the beginning, my baby had trouble breastfeeding.")."</b><br />".getResult2($row['q19'])."<br /><br />";
            echo "<b>"._("Breastfeeding made me feel like a good mother.")."</b><br />".getResult2($row['q20'])."<br /><br />";
            echo "<b>"._("I really enjoyed nursing.")."</b><br />".getResult2($row['q21'])."<br /><br />";
            echo "<b>"._("While breastfeeding, I was anxious to have my body back.")."</b><br />".getResult2($row['q22'])."<br /><br />";
            echo "<b>"._("Breastfeeding made me feel more confident as a mother.")."</b><br />".getResult2($row['q23'])."<br /><br />";
            echo "<b>"._("My baby gained weight really well with breastmilk.")."</b><br />".getResult2($row['q24'])."<br /><br />";
            echo "<b>"._("Breastfeeding made me feel more confident as a mother.")."</b><br />".getResult2($row['q25'])."<br /><br />";
            echo "<b>"._("I could easily fit my baby's breastfeeding with my other activities.")."</b><br />".getResult2($row['q26'])."<br /><br />";
            echo "<b>"._("Breastfeeding made me feel like a cow.")."</b><br />".getResult2($row['q27'])."<br /><br />";
            echo "<b>"._("My baby did not relax while nursing.")."</b><br />".getResult2($row['q28'])."<br /><br />";
            echo "<b>"._("Breastfeeding was emotionally draining.")."</b><br />".getResult2($row['q29'])."<br /><br />";
            echo "<b>"._("Breastfeeding felt wonderful to me.")."</b><br />".getResult2($row['q30'])."<br /><br />";
          } else if ($survey == ACTION_POSTNATAL_DEPRESSION) {
            echo "<br /><br />";
            echo "<b style='font-size: 1.2em;'>"._("In the past 7 days")."</b></br>";
            echo "<b>"._("I have been able to laugh and see the funny side of things")."</b><br />";
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
            echo "<br /><br />";
            echo "<b>"._("I have looked forward with enjoyment to things")."</b><br />";
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
            echo "<br /><br />";
            echo "<b>"._("I have blamed myself unnecessarily when things went wrong")."</b><br />";
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
            echo "<br /><br />";
            echo "<b>"._("I have been anxious or worried for no good reason")."</b><br />";
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
            echo "<br /><br />";
            echo "<b>"._("I have felt scared of panicky for no very good reason")."</b><br />";
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
            echo "<br /><br />";
            echo "<b>"._("Things have been geting on top of me")."</b><br />";
            switch ( $row['q6'] ) {
              case 1:
                echo _("Yes, most of the time I haven't been able to cope at all"); break;
              case 2:
                echo _("Yes, sometimes I haven't been coping as well as usual"); break;
              case 3:
                echo _("No, most of the time I have coped quite well"); break;
              case 4:
                echo _("No, I have been coping as well as ever"); break;
              default:
                break;
            }
            echo "<br /><br />";
            echo "<b>"._("I have been so unhappy that I have had difficulty sleeping")."</b><br />";
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
            echo "<br /><br />";
            echo "<b>"._("I have felt sad or miserable")."</b><br />";
            switch ($row['q8']) {
              case 1:
                echo _("Yes, most of the time"); break;
              case 2:
                echo _("Yes, some of the time"); break;
              case 3:
                echo _("Not very often"); break;
              case 4:
                echo _("No, not at all"); break;
              default:
                break;
            }
            echo "<br /><br />";
            echo "<b>"._("I have been so unhappy that I have been crying")."</b><br />";
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
            echo "<br /><br />";
            echo "<b>"._("The thought of harming myself has occurred to me")."</b><br />";
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
            echo "<br /><br />";

          }
        } else 
        { // No survey results, display error message
          echo "<div style='color: red;'><b>"._("Error: No Suvery Results Avaliable")."</b></div>";
        }
      }
            
      function getResult($input){
        switch($input)
        {
          case 1:
            return '"'._("Strongly Disagree").'"';
          case 2:
            return '"'._("Disagree").'"';
          case 3:
            return '"'._("Neutral").'"';
          case 4:
            return '"'._("Agree").'"';
          case 5:
            return '"'._("Strongly Agree").'"';
          default:
            return "";
        }
      }

      function getResult2($input){
        switch($input)
        {
          case -2:
            return '"'._("Strongly Disagree").'"';
          case -1:
            return '"'._("Disagree").'"';
          case 0:
            return '"'._("Neutral").'"';
          case 1:
            return '"'._("Agree").'"';
          case 2:
            return '"'._("Strongly Agree").'"';
          default:
            return "";
        }
      }

      function writeSystemFeedback($system_feedback_array,$motherids,$questionIndex)
      {
        $myqindex=$questionIndex;
        if($questionIndex == 9)
        {
          $answerIndex = "q10";
        } 
        else
        {
          $questionIndex=strval($questionIndex+1);
          $answerIndex= "q".$questionIndex;
        }
        echo '"'.$system_feedback_array[$myqindex]."\"\n";
        foreach ($motherids as $mid) 
        {
          $query = "SELECT * FROM SystemFeedback WHERE mid = " . $mid . " ;";
          $result = mysql_query($query);
          $row = mysql_fetch_array($result);
          if($row)
          {
            echo "\"".getResult($row[$answerIndex])."\"\n";
          }
        }
        echo "\n";
      }

      function writeSystemPerception($array,$motherids,$questionIndex)
      {
        $myqindex=$questionIndex;
        $questionIndex=strval($questionIndex+1);
        $answerIndex= "q".$questionIndex;
        echo "\"".$array[$myqindex]."\"\n";
        foreach ($motherids as $mid) 
        {
          $query = "SELECT * FROM SystemPerception WHERE mid = " . $mid . " ;";
          $result = mysql_query($query);
          $row = mysql_fetch_array($result);
          if($row)
          {
            echo "\"".$row[$answerIndex]."\"\n";
          }
        }
        echo "\n";
      }


      function writeSurveyForUser($array,$questionIndex,$row,$isSystemFeedback)
      {
        $myqindex=$questionIndex;
        if($questionIndex == 9)
        {
           $answerIndex = "q10";
          }
        else
        {
           $questionIndex=strval($questionIndex+1);
             $answerIndex= "q".$questionIndex;
          }
        echo "\"".$array[$myqindex] . "\"\n";
        if($isSystemFeedback == 1)
        {
          echo "\"". getResult($row[$answerIndex]) . "\"\n";
        }
        else
        {
          echo "\"". $row[$answerIndex] . "\"\n";
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
            return false;
        }
      }

          ?>
      </ul>
    </div> <!-- End DIV questionaire -->
    
    <input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />
    
    <?php 
      /*
      * This code fades in and out each tab by editing the css values of each tabs respective div.
      * 'AccountDisplay' is set in 'post.user_acounts.php' found in the post directory
      */
    
      echo "<script type='text/javascript'>";
      if(!isset( $_SESSION['AccountDisplay' ]) || $_SESSION['AccountDisplay'] == 1) {
        echo "$('#breastfeeding').addClass('active');";
        echo "$('#supplement').removeClass('active');";
        echo "$('#output').removeClass('active');";
        echo "$('#morbidity').removeClass('active');";
        echo "$('#questionaire').removeClass('active');";
        echo "$('div.breastfeeding').fadeIn(0);  ";
        echo "$('div.supplement').css('display', 'none');";
        echo "$('div.output').css('display', 'none');";
        echo "$('div.morbidity').css('display', 'none');";
        echo "$('div.questionaire').css('display', 'none');";
        echo "document.getElementById('mothersubmit').name='breast';";
      }
      if(@$_SESSION['AccountDisplay'] == 2) {
        echo "$('#supplement').addClass('active');";
        echo "$('#breastfeeding').removeClass('active');";
        echo "$('#output').removeClass('active');";
        echo "$('#morbidity').removeClass('active');";
        echo "$('#questionaire').removeClass('active');";
        echo "$('div.supplement').fadeIn(0);  ";
        echo "$('div.breastfeeding').css('display', 'none');";
        echo "$('div.output').css('display', 'none');";
        echo "$('div.morbidity').css('display', 'none');";
        echo "$('div.questionaire').css('display', 'none');";
        echo "document.getElementById('mothersubmit').name='sup';";
      }
      if(@$_SESSION['AccountDisplay'] == 3) {
        echo "$('#output').addClass('active');";
        echo "$('#breastfeeding').removeClass('active');";
        echo "$('#supplement').removeClass('active');";
        echo "$('#morbidity').removeClass('active');";
        echo "$('#questionaire').removeClass('active');";
        echo "$('div.output').fadeIn(0);  ";
        echo "$('div.breastfeeding').css('display', 'none');";
        echo "$('div.supplement').css('display', 'none');";
        echo "$('div.morbidity').css('display', 'none');";
        echo "$('div.questionaire').css('display', 'none');";
        echo "document.getElementById('mothersubmit').name='out';";
      }
      if(@$_SESSION['AccountDisplay'] == 4) {
        echo "$('#morbidity').addClass('active');";
        echo "$('#breastfeeding').removeClass('active');";
        echo "$('#supplement').removeClass('active');";
        echo "$('#output').removeClass('active');";
        echo "$('#questionaire').removeClass('active');";
        echo "$('div.morbidity').fadeIn(0);  ";
        echo "$('div.breastfeeding').css('display', 'none');";
        echo "$('div.supplement').css('display', 'none');";
        echo "$('div.output').css('display', 'none');";
        echo "$('div.questionaire').css('display', 'none');";
        echo "document.getElementById('mothersubmit').name='morb';";
      }
      if(@$_SESSION['AccountDisplay'] == 5) {
        echo "$('#morbidity').removeClass('active');";
        echo "$('#breastfeeding').removeClass('active');";
        echo "$('#supplement').removeClass('active');";
        echo "$('#output').removeClass('active');";
        echo "$('#questionaire').addClass('active');";
        echo "$('div.morbidity').css('display', 'none');";
        echo "$('div.breastfeeding').css('display', 'none');";
        echo "$('div.supplement').css('display', 'none');";
        echo "$('div.output').css('display', 'none');";
        echo "$('div.questionaire').fadeIn(0);  ";
        echo "document.getElementById('mothersubmit').name='morb';";
      }
      unset($_SESSION['AccountDisplay']);
      echo "</script>\n";
    ?>
  </div>
  </div>
</div>
</div>


<?php page_footer(); ?>
</div>

</div>
<script type='text/javascript'><!--
  $(function( ) {

    $('form[name="manage_user"]').bind( 'submit', function( event ) {
      var message = "<?php echo _("You have chosen to delete the following accounts") ?>:\n\n";
      var opts = this['midi[]'].options;
      var accounts = "";
      for ( var i=0; i < opts.length; i++ ) {
        if ( opts[i].selected ) {
          accounts += opts[i].innerHTML + "\n";
        }
      }
      if ( accounts.length <= 0 ) {
        event.preventDefault( );
        return;
      }
      message += accounts + "\n";
      message += "<?php echo _("This action is irreversible. Are you sure you want to do this?") ?>";
      if ( this.perform.options[ this.perform.selectedIndex ].value == '<?php echo ACTION_DELETE_ACCOUNT; ?>' ) {
        if ( confirm( message )) {
          this.action += "?confirm=1"
        } else {
          event.preventDefault( );
          event.stopPropagation( );
        }
      }
    });

	$('ul.menu > li > a').click( function( ) {
    location.hash = $(this).attr('href');
		$('form.long')[0].reset( );
	});

  $('.datepicker').datepicker( );
  });

</script>
</body>
</html>
