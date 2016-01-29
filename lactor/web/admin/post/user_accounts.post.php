<?php 

include_once("../../includes/constants.php");
include_once("../../includes/general.php");

loggedIn();

if(isset($_POST['midq'])) {
  $_SESSION['midq'] = $_POST['midq'];
}

if(isset($_POST['proxy'])) {
	$_SESSION['mid'] = $_POST['mid'];
	$_SESSION['s_mid'] = 1;
	
	header("Location: ". 
				( isset( $_SERVER['HTTPS']) ? "https://" : "http://" ) .
	       $_SERVER["HTTP_HOST"] . "/add_entry.php");
	exit;
} 

include_once("../../includes/db.php");
include_once("../../includes/mail.include.php");
db_connect();
loadVocabulary( );

if (isset($_POST['motherDownload'])) {
  $query = "SELECT *,s.email as s_email FROM Scientists s,Mothers m,MotherInfo mi,Mothers_Scientists ms WHERE m.mid=mi.mid AND m.mid=ms.mid AND ms.sid=s.sid AND m.mid=".mysql_real_escape_string($_POST['mid']).";";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
  }
  $row = mysql_fetch_assoc($result);
  header('Content-type: application/csv');
  header("Content-disposition: attachment; filename=".str_replace(" ","_",$row['Name'])."_Mother_Info.csv");
  echo '"Name","Address","Phone","Email","Lactation Consultant","Age","Ethnicity",'.
       '"Race","Education","Household Income","Occupation","Residence","Current Child",'.
       '"Past Obsterical History","Method of Delivery","Maternal History During Pregnancy",'.
       '"Past Breastfeeding Experience"'."\n";
  do {
    echo '"'.$row['Name']."\",";
    echo '"'.$row['Address']."\",";
    echo '"'.$row['Phone']."\",";
    echo '"'.$row['email']."\",";
    echo '"'.$row['s_email']."\",";
    echo '"'.$_SESSION['vocab']['Age'][$row['Age']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['Ethnicity'][$row['Ethnicity']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['Race'][$row['Race']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['Education'][$row['Education']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['HouseIncome'][$row['HouseIncome']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['Occupation'][$row['Occupation']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['Residence'][$row['Residence']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['Parity'][$row['Parity']]['TextValue']."\",";
    $rowsplit = str_split($row['POH']);
    for ( $i=0; $i < 5; $i++ ) {
      if ( $rowsplit[$i] == "2" ) {
        echo ',"'.$_SESSION['vocab']['POB'][$i+1]['TextValue'].'"';
      }
    }
    echo ",";
    echo '"'.$_SESSION['vocab']['MODel'][$row['MethodOfDelivery']]['TextValue']."\",";
    $rowsplit = str_split($row['MHDP']);
    for ( $i=0; $i < 7; $i++ ) {
      if ( $rowsplit[$i] == "2" ) {
        echo ',"'.$_SESSION['vocab']['MHDP'][$i+1]['TextValue'].'"';
      }
    }
    echo "\n";
  } while($row = mysql_fetch_assoc($result));
  exit( );
}

if (isset($_POST['childDownload'])) {
  function formatWeight( $weight ) {
    $split_weight = preg_split("/ /", $weight);
    return $split_weight[0]."lbs. ".$split_weight[1]."oz.";
  }
  $query = "SELECT * FROM MotherInfo mi,InfantProfile i WHERE mi.mid=i.mid AND mi.mid=".mysql_real_escape_string($_POST['mide']).";";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
  }
  $row = mysql_fetch_assoc($result);
  header('Content-type: application/csv');
  header("Content-disposition: attachment; filename=".str_replace(" ","_",$row['Name'])."_Child_Info.csv");
//  header('Content-type: text/plain');
  echo '"Mother\'s Name","Infant\'s Initials","Gestational Age","Appropriateness of gestational age",'.
       '"Date of Birth","Weight at birth","Date of Discharge","Type of first feeding",'.
       '"Age at first feeding session","Time of starting breast milk expression",'.
       '"Frequency of breast milk expression","First primary care provider visit",'.
       '"Need for extra primary care provider","Times of extra pimary care on the first month",'.
       '"Hopitalization during the first month"'."\n";
  do {
    echo '"'.$row['Name']."\",";
    echo '"'.$row['InfantInitials']."\",";
    echo '"'.$_SESSION['vocab']['gestate'][$row['GestationalAge']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['GestationalAge'][$row['AppropAge']]['TextValue']."\",";
    echo '"'.modDate2($row['DOB'])."\",";
    echo '"'.formatWeight($row['BirthWeight'])."\",";
    echo '"'.modDate2($row['DOD'])."\",";
    echo '"'.formatWeight($row['DischargeWeight'])."\",";
    echo '"'.$_SESSION['vocab']['sup-type'][$row['TypeFirstBreast']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['AgeFirstFeed'][$row['AgeFirstFeed']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['TimeStartBreast'][$row['TimeStartBreast']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['FreqBreastExpr'][$row['FreqBreastExpr']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['FirstPrimCare'][$row['FirstPrimCare']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['NeedExtraCare'][$row['NeedExtraCare']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['TimesExtraCare'][$row['TimesExtraCare']]['TextValue']."\",";
    echo '"'.$_SESSION['vocab']['HospFirstMonth'][$row['HospFirstMonth']]['TextValue']."\"\n";
  } while($row = mysql_fetch_assoc($result));
  exit( );
}

function action_text( $action ) {
  switch( $action ) {
    case ACTION_SYSTEM_FEEDBACK:
      return "were issued system feedback questionnaires.";
    case ACTION_RESET_PASSWORD:
      return "were issued a change password request.";
    case ACTION_MOTHER_INFORMATION:
      return "were issued a change information request.";
    case ACTION_RESET_PASSWORD:
      return "had their password reset.";
    case ACTION_DISABLE_USER:
      return "had their accounts disabled.";
    case ACTION_ENABLE_USER:
      return "had their accounts enabled.";
    case ACTION_SYSTEM_FEEDBACK:
      return "had their accounts deleted.";
    case ACTION_POSTNATAL_DEPRESSION:
      return "were issued a postnatal depression survey.";
    default:
      return "";
  }
}

if(isset($_REQUEST['manage'])) {
	$c = 0;
	$where = "";
	while(isset($_REQUEST['midi'][$c])) {
		if($c != 0) 
			$where .= " OR ";
		$where .= " mid = " . $_REQUEST['midi'][$c];
		$c++;
	}
	$message = $c . " users ";
	//echo $where;
	if($_REQUEST['perform'] == ACTION_RESET_PASSWORD ) {
		$query = "SELECT * FROM Mothers WHERE " . $where . ";";
		$result = mysql_query($query); $i = 0;
		while($row=mysql_fetch_array($result)) {
			$newpassdisplay = genRandomString();
			$newpass[$i] = mysql_real_escape_string(md5($newpassdisplay));
			$m[$i] = $row['mid'];
			$ls[$i] = "2";
			if($row['loginstep'] == 1) {
				$ls[$i] = "1";
			}
			$mail = generatePassMail($row['email'], "Password Reset", $newpassdisplay, "You will be prompted to 
change the password as soon as you log in. If you are not, you can change it by going to the profile tab, and selecting the 
Change Password option. <br /><br />Click the following link to login to <a href='https://lactor.org/'>Lactor</a>.");		
			$i++;
		}
		for($j = 0; $j < $i; $j++) {
			$query = "UPDATE Mothers SET password = '" . $newpass[$j] . "', loginstep = " . $ls[$j] . " WHERE  mid = " . $m[$j] . ";";
			$result=mysql_query($query);
		}
		$message .= "had their password reset.";
	} else if ($_REQUEST['perform'] == ACTION_DELETE_ACCOUNT ) {
		if ( $_REQUEST['confirm']) {
			$query="DELETE FROM Mothers WHERE $where;";
			if ( mysql_query( $query ) ) {
				$message = "The account was successfully removed.";
			} else {
				$message = "An error occurred while attempting to delete the specified account.";
			}
			go( "../user_accounts.php?" );
		} else {
			// this doesn't work. The confirmation should be being made via javascript for now.
			$redirect = "../delete_mother_accounts.php?";
			foreach( $_REQUEST['midi'] as $mid ) {
				$redirect .= "midi%5B%5D=$mid";
			}
			go( $redirect );
		}
	} else if($_REQUEST['perform']) {

		$query="UPDATE Mothers SET actions_required = (actions_required | ".$_REQUEST['perform'].")  WHERE " . $where . ";";
		$result=mysql_query($query);
		$message .=  action_text( $_REQUEST['perform'] );
  }
    $_SESSION['AccountMessage'] = $message;
    $_SESSION['AccountDisplay'] = 1;
    $_SESSION['AccountType'] = 1;

  } else if(isset($_POST['add'])) {

	if(trim($_POST['user']) == '') {
		$_SESSION['AccountMessage'] = "Please enter an email address.";
		$_SESSION['AccountType'] = 3;

	} else if($_POST['user'] >= 2) {
		$pass = genRandomString();
    $userlevel = $_POST['user'] - 2;
    $adminlevel = $_SESSION['admin'];
    // only a super admin can create admins.
    if ( $adminlevel < SUPER_ADMIN ) {
      $userlevel = 0;
    }
		$query= sprintf( "INSERT INTO 
											  Scientists (name, email, password, loginstep, hospital_id, admin) 
											VALUES ('%s', '%s', '%s', 1, %d, %d);", 
											mysql_real_escape_string( $_POST['name'] ),
											mysql_real_escape_string( $_POST['email'] ),
											hash("sha256",$pass.SALT),
                      $_REQUEST['hospital'],
											$userlevel );
		if( !mysql_query($query)) {
			error_log( mysql_error( ));
			$_SESSION['AccountMessage'] = "Scientist/Admin " . $_POST['email'] . " was not added successfully.";
		} else {
			$_SESSION['AccountMessage'] = "Scientist/Admin " . $_POST['email'] . " added successfully.";
		}
		$_SESSION['AccountType'] = 1;
		$mail = generatePassMail($_POST['email'], "New Scientist User", $pass, "You will be prompted to change the password as soon as you log in. <br /><br />Click the following link to login to <a href='https://lactor.org/admin/'>Lactor</a>.");
	} else if($_POST['user'] == 1) {
		$pass = genRandomString();
    $hospital_id = $_SESSION['hospital_id'];
    if ( isset($_REQUEST['hospital']) && $_SESSION['admin'] == SUPER_ADMIN) {
      $hospital_id = $_REQUEST['hospital'];
    }
		$query=sprintf( "INSERT INTO Mothers (email, password, actions_required, loginstep, hospital_id) VALUES ('%s', '%s', %d, 0, %d);",
                    $_POST['email'], hash("sha256",$pass.SALT), ACTION_RESET_PASSWORD | ACTION_CONSENT, $hospital_id);
		$result=mysql_query($query);
    if (!$result) {
      error_log(mysql_error());
    }

		$get_last_mid ="SELECT mid from Mothers where email = '" .$_POST['email'] . "';";
    if ( !$get_last_mid ) {
      $_SESSION['AccountMessage'] = "Mother " . $_POST['email'] . " not added successfully.";
    } else {
      $result_get_last_mid = mysql_query($get_last_mid);
      $row_get = mysql_fetch_array($result_get_last_mid);

      $_SESSION['linked_mid'] = $row_get['mid'];

      $link_query = "INSERT INTO Mothers_Scientists(sid,mid) VALUES('".$_SESSION['sid']."','". $_SESSION['linked_mid']."');";
      mysql_query($link_query);
      if ($_POST["name"]) {
        $name_query = "INSERT INTO MotherInfo (mid,Name) VALUES(".$_SESSION['linked_mid'].",'".$_POST['name']."');";
        mysql_query($name_query);
      }

      $_SESSION['AccountMessage'] = "Mother " . $_POST['email'] . " added successfully.";
      $_SESSION['AccountType'] = 1;
      $mail = generatePassMail($_POST['email'], "New Mother Account", $pass, "You will be prompted to change the password as soon as you log in. If you are not, you can change it by going to the profile tab, and selecting the Change Password option. <br /><br />Click the following link to login to <a href='https://lactor.org/'>Lactor</a>.");
    }
	}
	$_SESSION['AccountDisplay'] = 1;
	
} else if(isset($_POST['proxy'])) {
	$_SESSION['mid'] = $_POST['mid'];
	$_SESSION['s_mid'] = 1;
	
	header("Location: http://www.lactor.org/add_entry.php");
	exit;
	
	
} else if( isset($_POST['info'] )) {
	$_SESSION['s_mid']=$_POST['mid'];
	$_SESSION['AccountDisplay'] = 3;

} else if( isset( $_POST['download'] )) {
	$_SESSION['s_mid']=$_POST['mid'];
	$_SESSION['AccountDisplay'] = 3;

	$query = "SELECT * FROM MotherInfo WHERE mid = ". $_POST['mid'] . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$filename = "Mother_Info.csv";
//	print_r( $_SESSION['vocab'] );
	
	header('Content-type: text/csv');
	header("Content-Disposition: attachment; filename=".$filename);
	printf( "\"Name\",\"%s\"\n", $row["Name"] );
	printf( "\"Address\",\"%s\"\n", $row["Address"] );
	printf( "\"Phone\",\"%s\"\n", $row["Phone"]) ;
	printf( "\"Age\",\"%s\"\n", getVocab( "Age", $row["Age"] ));
	printf( "\"Ethnicity\",\"%s\"\n", getVocab( "Ethnicity", $row["Ethnicity"] ));
	printf( "\"Race\",\"%s\"\n",getVocab( "Race", $row["Race"] ));
	printf( "\"Education\",\"%s\"\n", getVocab( "Education", $row["Education"] ));
	printf( "\"Household Income\",\"%s\"\n".getVocab( "HouseIncome", $row["HouseIncome"] ));
	printf( "\"Occupation\",\"%s\"\n", getVocab( "Occupation", $row["Occupation"] ));
	printf( "\"Residence\",\"%s\"\n", getVocab( "Residence", $row["Residence"] ));
	printf( "\"Parity\",\"%s\"\n", getVocab( "Parity", $row["Parity"] ));
	printf( "\"Past Obsterical History\",\"%s\"\n", $row["POH"] );
	printf( "\"Maternal History During Pregnancy\",\"%s\"\n", $row["MHDP"] );
	printf( "\"Method of Delivery\",\"%s\"\n", getVocab( "MODel", $row["MethodOfDelivery"] ));
	printf( "\"Past Breastfeeding Experience\",\"%s\"\n", getVocab( "PBE", $row["PBE"] ));
	exit;


} else if(isset($_POST['minsert'])) {

$_SESSION['AccountDetails'] = "";
if(trim($_POST["FormalName"]) == "") {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Name field was left blank.<br />";
}
if(trim($_POST["Address"]) == "") {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Address field was left blank.<br />";
}
if(trim($_POST["Phone"]) == "") {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Phone field was left blank.<br />";
} else { 
	if (!ereg ("[0-9]{3}-[0-9]{3}-[0-9]{4}", trim($_POST["Phone"]))) {
		$_SESSION['AccountMessage'] = "Phone input not filled correctly.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Phone field has to be inputed with the following syntax: XXX-XXX-XXXX.<br />";         
	} 
}
if($_POST["Age"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Age field was left blank.<br />";
}
if($_POST["Ethnicity"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Ethnicity field was left blank.<br />";
}
if($_POST["Race"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Race field was left blank.<br />";
}
if($_POST["Education"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Education field was left blank.<br />";
}
if($_POST["Occupation"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Occupation field was left blank.<br />";
}
if($_POST["Residence"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Residence field was left blank.<br />";
}
if($_POST["HouseIncome"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Household Income field was left blank.<br />";
}
if($_POST["Parity"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Parity field was left blank.<br />";
}
if($_POST["MODel"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Latest Method of Delivery field was left blank.<br />";
}
if($_POST["PBE"] == 0) {
	$_SESSION['AccountMessage'] = "Mother information insertion failed.\n";
	$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Past Breastfeeding Experience field was left blank.<br />";
}
if(isset($_SESSION['AccountMessage'])) {
	$_SESSION['s_mid']=$_POST['mid'];
	$_SESSION['AccountDisplay'] = 3;
	$_SESSION['AccountType'] = 3;
	go("../user_accounts.php");
}

	for ( $i = 0; $i < 5; $i += 1) {
		if($_POST["POB" . "" . ($i + 1)] == $i + 1) {
			$POB = $POB . "2";
		} else {
			$POB = $POB . "1";
		}
	}
	$MHDP="";
	for ( $i = 0; $i < 7; $i += 1) {
		if($_POST["MHDP" . "" . ($i + 1)] == $i + 1) {
			$MHDP = $MHDP . "2";
		} else {
			$MHDP = $MHDP . "1";
		}
	}
	
	
	$query="INSERT INTO MotherInfo VALUES (" . $_POST['mid'] . ", '" . mysql_real_escape_string($_POST['FormalName']) . "', '" . mysql_real_escape_string($_POST['Address']) . "', " . $_POST['Age'] . "," . $_POST['Ethnicity'] . "," . $_POST['Race'] . "," . $_POST['Education'] . ", " . $_POST['HouseIncome'] . ", " . $_POST['Occupation'] . ", " . $_POST['Residence'] . ", " . $_POST['Parity'] . ", " . $POB . ", " . $MHDP  . ", " . $_POST['MODel'] . ", " . $_POST['PBE'] . ", '" .  mysql_real_escape_string($_POST['Phone']) . "')";
	mysql_query($query);
	$_SESSION['AccountMessage'] = "Mother information inserted successfully.";
	//$_SESSION['AccountMessage'] = $query;
	$_SESSION['AccountType'] = 1;
	

	$query="UPDATE Mothers SET loginstep=2 WHERE mid = " . $_SESSION['mid'] . " AND loginstep = 1 ;";
	mysql_query($query);
	
	$query="UPDATE Mothers SET loginstep=0 WHERE mid = " . $_SESSION['mid'] . " AND loginstep = 3 ;";
	mysql_query($query);
	
	$_SESSION['s_mid']=$_POST['mid'];
	$_SESSION['AccountDisplay'] = 3;	
} else if(isset($_POST['mupdate'])) {

	$_SESSION['AccountDetails'] = "";
	if(trim($_POST["FormalName"]) == "") {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Name field was left blank.<br />";
	}
	if(trim($_POST["Address"]) == "") {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Address field was left blank.<br />";
	}
	if(trim($_POST["Phone"]) == "") {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Phone field was left blank.<br />";
	} else { 
		if (!ereg ("[0-9]{3}-[0-9]{3}-[0-9]{4}", trim($_POST["Phone"]))) {
			$_SESSION['AccountMessage'] = "Phone input not filled correctly.\n";
			$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Phone field has to be inputed with the following syntax: XXX-XXX-XXXX.<br />";         
		} 
	}
	if($_POST["Age"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Age field was left blank.<br />";
	}
	if($_POST["Ethnicity"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Ethnicity field was left blank.<br />";
	}
	if($_POST["Race"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Race field was left blank.<br />";
	}
	if($_POST["Education"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Education field was left blank.<br />";
	}
	if($_POST["Occupation"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Occupation field was left blank.<br />";
	}
	if($_POST["Residence"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Residence field was left blank.<br />";
	}
	if($_POST["HouseIncome"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Household Income field was left blank.<br />";
	}
	if($_POST["Parity"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Parity field was left blank.<br />";
	}
	if($_POST["MODel"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Latest Method of Delivery field was left blank.<br />";
	}
	if($_POST["PBE"] == 0) {
		$_SESSION['AccountMessage'] = "Mother information update failed.\n";
		$_SESSION['AccountDetails'] = $_SESSION['AccountDetails'] . "Past Breastfeeding Experience field was left blank.<br />";
	}
	if(isset($_SESSION['AccountMessage'])) {
		$_SESSION['s_mid']=$_POST['mid'];
		$_SESSION['AccountDisplay'] = 3;
		$_SESSION['AccountType'] = 3;
		go("../user_accounts.php");
	}


		for ( $i = 0; $i < 5; $i += 1) {
			if($_POST["POB" . "" . ($i + 1)] == $i + 1) {
				$POB = $POB . "2";
			} else {
				$POB = $POB . "1";
			}
		}
		$MHDP="";
		for ( $i = 0; $i < 7; $i += 1) {
			if($_POST["MHDP" . "" . ($i + 1)] == $i + 1) {
				$MHDP = $MHDP . "2";
			} else {
				$MHDP = $MHDP . "1";
			}
		}
		
		
		if ( isset( $_POST['lactationConsultant'] )) {
			$query = sprintf( "UPDATE Mothers_Scientists SET sid=%d WHERE mid=%d", $_POST['lactationConsultant'], $_POST['mid'] );
			$result = mysql_query( $query );
      if ( !$result ) {
        error_log( "The query $query generated the following error: ".mysql_error( ));
      }
			$query = sprintf( "UPDATE Mothers SET hospital_id=(SELECT hospital_id FROM Scientists WHERE sid=%d) WHERE mid=%d", $_POST['lactationConsultant'], $_POST['mid'] );
			$result = mysql_query( $query );
      if ( !$result ) {
        error_log( "The query $query generated the following error: ".mysql_error( ));
      }
		}
		$query="UPDATE MotherInfo SET Name = '" . mysql_real_escape_string($_POST['FormalName']) . "' , Address = '" . mysql_real_escape_string($_POST['Address']) . "' , Phone = '" . mysql_real_escape_string($_POST['Phone']) . "' , Age = " . $_POST['Age'] . " , Race = " . $_POST['Race'] .  " , Ethnicity = " . $_POST['Ethnicity'] .  " , Education = " . $_POST['Education'] .  " , HouseIncome = " . $_POST['HouseIncome'] .  " , Occupation = " . $_POST['Occupation'] .  " , Residence = " . $_POST['Residence'] .  " , Parity = " . $_POST['Parity'] .  " , MethodOfDelivery = " . $_POST['MODel'] .  " , MHDP = " . $MHDP .  " , POH = " . $POB . " WHERE mid = " . $_POST['mid'] . ";";
		$r=mysql_query($query);
		$_SESSION['AccountMessage'] = "Mother information updated successfully.";
		//$_SESSION['AccountMessage'] = $query . " " . $r;
		$_SESSION['AccountType'] = 1;
		
		
		$_SESSION['s_mid']=$_POST['mid'];
		$_SESSION['AccountDisplay'] = 3;	

} else if(isset($_POST['childinfo'])) {

	$_SESSION['e_mid']=$_POST['mide'];
	$_SESSION['AccountDisplay'] = 4;
	
} else if(isset($_POST['questionaire'])) {

	$_SESSION['q_mid']=$_POST['midq'];
	$_SESSION['surveyT']=$_POST['surveyType'];
	$_SESSION['AccountDisplay'] = 5;

} else if(isset($_POST['downloadsurveyresults'])) {

	$_SESSION['q_mid']=$_POST['midq'];
	$_SESSION['surveyT']=$_POST['surveyType'];
	$_SESSION['AccountDisplay'] = 5;
	$_SESSION['downloadSurvey'] = "TRUE";
	$_SESSION['downloadAll'] = "FALSE";

} else if(isset($_POST['downloadAll'])) {

	$_SESSION['q_mid']=$_POST['midq'];
	$_SESSION['downloadAll'] = "TRUE";
	$_SESSION['AccountDisplay'] = 5;
	$_SESSION['downloadSurvey'] = "FALSE";


} else if(isset($_POST['cinsert'])) {


$_SESSION['AccountDetails'] = "";
if(trim($_POST['InfantInitials']) == "") {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the infant initials.<br />";
}
if($_POST['GestationalAge'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the gestational age.<br />";
}
if($_POST['AppropAge'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the appropiateness for gestational age.<br />";
}
if($_POST['TypeFirstBreast'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the type of first feeding.<br />";
}
if($_POST['AgeFirstFeed'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the age of first feeding.<br />";
}
if($_POST['TimeStartBreast'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter time of starting breast expression.<br />";
}
if($_POST['FreqBreastExpr'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the frequency of the breast expression.<br />";
}
if($_POST['NeedExtraCare'] == 1 && $_POST['TimesExtraCare'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "If there was a need for extra care, please enter the amount.<br />";
}
if($_POST['BirthWeightPounds'] == 0 || $_POST['BirthWeightOunces'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the childs birth weight in pounds and ounces.<br />";
}
if($_POST['DischargeWeightPounds'] == 0 || $_POST['DischargeWeightOunces'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the childs discharge weight in pounds and ounces.<br />";
}
if(trim($_POST['dateDischarge']) == "") {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the date of discharge.<br />";
}
if(trim($_POST['dateBirth']) == "") {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the date of birth.<br />";
}
if(isset($_SESSION['AccountMessage'])) {
	$_SESSION['e_mid']=$_POST['mide'];
	$_SESSION['AccountDisplay'] = 4;
	$_SESSION['AccountType'] = 3;
	go("../user_accounts.php");
}


$query = sprintf("INSERT INTO InfantProfile (
          mid,
          cid,
          InfantInitials,
          AppropAge,
          GestationalAge, 
          TypeFirstBreast,
          AgeFirstFeed,
          TimeStartBreast,
          FreqBreastExpr, 
          FirstPrimCare,
          NeedExtraCare,
          TimesExtraCare,
          HospFirstMonth,
          DOB, 
          BirthWeight,
          DOD,
          DischargeWeight
        )
        VALUES ( %d, %d, '%s', %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, '%s', '%s', '%s', '%s');",
        $_POST['mide'],
        1,
        $_POST['InfantInitials'],
        $_POST['AppropAge'],
        $_POST['GestationalAge'],
        $_POST['TypeFirstBreast'],
        $_POST['AgeFirstFeed'],
        $_POST['TimeStartBreast'],
        $_POST['FreqBreastExpr'],
        $_POST['FirstPrimCare'],
        $_POST['NeedExtraCare'],
        $_POST['TimesExtraCare'],
        $_POST['HospFirstMonth'],
        date("Y-m-d 00:00:00", strtotime($_POST['dateBirth'])),
        $_POST['BirthWeightPounds']." ".$_POST['BirthWeightOunces'],
        date("Y-m-d 00:00:00", strtotime($_POST['dateDischarge'])),
        $_POST['DischargeWeightPounds']." ".$_POST['DischargeWeightOunces']);

//$query="UPDATE InfantProfile SET ";
//$query=$query . "InfantInitials = '" . $_POST['InfantInitials'] . "', AppropAge = " . $_POST['AppropAge'] . ", GestationalAge = ";
//$query=$query . $_POST['GestationalAge'] . ", TypeFirstBreast = " . $_POST['TypeFirstBreast'] . ", AgeFirstFeed = " . $_POST['AgeFirstFeed'] . ", TimeStartBreast = " . $_POST['TimeStartBreast'] . ", FreqBreastExpr = ";
//$query=$query . $_POST['FreqBreastExpr'] . ", FirstPrimCare = " . $_POST['FirstPrimCare'] . ", NeedExtraCare = " . $_POST['NeedExtraCare'] . ", TimesExtraCare = ";
//$query=$query . $_POST['TimesExtraCare'] . ", HospFirstMonth = " . $_POST['HospFirstMonth'] . ", DOB=";
//$query=$query . "'" . modDate($_POST['dateBirth']) . " 00:00:00', BirthWeight='" . $_POST['BirthWeightPounds'] . " " . $_POST['BirthWeightOunces'] . "', DOD = ";
//$query=$query . "'" . modDate($_POST['dateDischarge']) . " 00:00:00', DischargeWeight = '" . $_POST['DischargeWeightPounds'] . " " . $_POST['DischargeWeightOunces'] . "' WHERE mid = " . $_POST['mide'] . "; ";

$result = mysql_query($query);
if (!$result) {
  error_log(mysql_error());
} else {

  $_SESSION['AccountMessage'] = "Child information entered successfully.";
  $_SESSION['AccountType'] = 1;
  $_SESSION['e_mid']=$_POST['mide'];
  $_SESSION['AccountDisplay'] = 4;
}

} else if(isset($_POST['cupdate'])) {

$_SESSION['AccountDetails'] = "";
if(trim($_POST['InfantInitials']) == "") {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the infant initials.<br />";
}
if($_POST['GestationalAge'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the gestational age.<br />";
}
if($_POST['AppropAge'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the appropiateness for gestational age.<br />";
}
if($_POST['TypeFirstBreast'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the type of first feeding.<br />";
}
if($_POST['AgeFirstFeed'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the age of first feeding.<br />";
}
if($_POST['TimeStartBreast'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter time of starting breast expression.<br />";
}
if($_POST['FreqBreastExpr'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the frequency of the breast expression.<br />";
}
if($_POST['NeedExtraCare'] == 1 && $_POST['TimesExtraCare'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "If there was a need for extra care, please enter the amount.<br />";
}
if($_POST['BirthWeightPounds'] == 0 || $_POST['BirthWeightOunces'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the childs birth weight in pounds and ounces.<br />";
}
if($_POST['DischargeWeightPounds'] == 0 || $_POST['DischargeWeightOunces'] == 0) {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the childs discharge weight in pounds and ounces.<br />";
}
if(trim($_POST['dateDischarge']) == "") {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the date of discharge.<br />";
}
if(trim($_POST['dateBirth']) == "") {
	$_SESSION['AccountMessage'] = "Child information insertion failed.\n";
	$_SESSION['AccountDetails'] .= "Please enter the date of birth.<br />";
}
if(isset($_SESSION['AccountMessage'])) {
	$_SESSION['e_mid']=$_POST['mide'];
	$_SESSION['AccountDisplay'] = 4;
	$_SESSION['AccountType'] = 3;
	go("../user_accounts.php");
}


$query = sprintf("UPDATE InfantProfile SET
          mid=%d,
          cid=%d,
          InfantInitials='%s',
          AppropAge=%d,
          GestationalAge=%d, 
          TypeFirstBreast=%d,
          AgeFirstFeed=%d,
          TimeStartBreast=%d,
          FreqBreastExpr=%d, 
          FirstPrimCare=%d,
          NeedExtraCare=%d,
          TimesExtraCare=%d,
          HospFirstMonth=%d,
          DOB='%s', 
          BirthWeight='%s',
          DOD='%s',
          DischargeWeight='%s'
        WHERE mid=%d;",
        $_POST['mide'],
        1,
        $_POST['InfantInitials'],
        $_POST['AppropAge'],
        $_POST['GestationalAge'],
        $_POST['TypeFirstBreast'],
        $_POST['AgeFirstFeed'],
        $_POST['TimeStartBreast'],
        $_POST['FreqBreastExpr'],
        $_POST['FirstPrimCare'],
        $_POST['NeedExtraCare'],
        $_POST['TimesExtraCare'],
        $_POST['HospFirstMonth'],
        date("Y-m-d 00:00:00", strtotime($_POST['dateBirth'])),
        $_POST['BirthWeightPounds']." ".$_POST['BirthWeightOunces'],
        date("Y-m-d 00:00:00", strtotime($_POST['dateDischarge'])),
        $_POST['DischargeWeightPounds']." ".$_POST['DischargeWeightOunces'],
        $_POST['mide']);

//$query="UPDATE InfantProfile SET ";
//$query=$query . "InfantInitials = '" . $_POST['InfantInitials'] . "', AppropAge = " . $_POST['AppropAge'] . ", GestationalAge = ";
//$query=$query . $_POST['GestationalAge'] . ", TypeFirstBreast = " . $_POST['TypeFirstBreast'] . ", AgeFirstFeed = " . $_POST['AgeFirstFeed'] . ", TimeStartBreast = " . $_POST['TimeStartBreast'] . ", FreqBreastExpr = ";
//$query=$query . $_POST['FreqBreastExpr'] . ", FirstPrimCare = " . $_POST['FirstPrimCare'] . ", NeedExtraCare = " . $_POST['NeedExtraCare'] . ", TimesExtraCare = ";
//$query=$query . $_POST['TimesExtraCare'] . ", HospFirstMonth = " . $_POST['HospFirstMonth'] . ", DOB=";
//$query=$query . "'" . modDate($_POST['dateBirth']) . " 00:00:00', BirthWeight='" . $_POST['BirthWeightPounds'] . " " . $_POST['BirthWeightOunces'] . "', DOD = ";
//$query=$query . "'" . modDate($_POST['dateDischarge']) . " 00:00:00', DischargeWeight = '" . $_POST['DischargeWeightPounds'] . " " . $_POST['DischargeWeightOunces'] . "' WHERE mid = " . $_POST['mide'] . "; ";

$result = mysql_query($query);
if (!$result) {
  error_log(mysql_error());
} else {

  $_SESSION['AccountMessage'] = "Child information updated successfully.";
  $_SESSION['AccountType'] = 1;
  $_SESSION['e_mid']=$_POST['mide'];
  $_SESSION['AccountDisplay'] = 4;
}

} else if(isset ($_REQUEST['add_hospital'])) {
  $query = "INSERT INTO Hospital (hospital_name) VALUES ('".$_REQUEST['new_hospital']."')";
  if (!mysql_query($query)) {
    error_log("The query $query generated the following error: ".mysql_error());
  }
} else if(isset ($_REQUEST['delete_hospital'])) {
  $query = " DELETE FROM Hospital WHERE hospital_id=".$_REQUEST['hospital_delete'];
  if (!mysql_query($query)) {
    error_log("The query $query generated the following error: ".mysql_error());
  } else {
    $query = " DELETE FROM Scientists WHERE hospital_id=".$_REQUEST['hospital_delete'];
    if (!mysql_query($query)) {
      error_log("The query $query generated the following error: ".mysql_error());
    } 
    $query = " UPDATE Mothers SET hospital_id=NULL WHERE hospital_id=".$_REQUEST['hospital_delete'];
    if (!mysql_query($query)) {
      error_log("The query $query generated the following error: ".mysql_error());
    }
  }
} 




go("../user_accounts.php");


?>
