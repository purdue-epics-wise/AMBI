<?php

include_once( "constants.php" );

// if there is an ongoing transaction, 
// assume an error occurred and roll it back.
if (@$_SESSION['in_transaction']) {
  rollback_transaction( );
}

function db_connect() {
	$conn = mysql_connect('localhost', 'lactor', 'breast1010');
	if (!$conn) {
  		die('<h1>Could not connect to database</h1>');
	}

	mysql_select_db("lactor", $conn);
	$_SESSION['db_con'] = $conn;
	return $conn;
}

function begin_transaction( ) {
  mysql_query( "BEGIN TRANSACTION;" );
  $_SESSION['in_transaction'] = true;
}

function commit_transaction( ) {
  mysql_query( "COMMIT;" );
  $_SESSION['in_transaction'] = false;
}

function rollback_transaction( ) {
  mysql_query( "ROLLBACK;" );
  $_SESSION['in_transaction'] = false;
}

function authenticate($email, $password) {
    $_SESSION['email'] = mysql_real_escape_string(trim($email));
    $_SESSION['legacy_password'] = hash_password_legacy($password);
    $_SESSION['password'] = hash_password($password);

  //Query for retriving user name and password inputed
  $result = credentials();
  if ( !$result ) {
    error_log('Failed login attempt - username '.$_SESSION['email']);
    error_log( mysql_error( ));
  }

  //we scan results, mark if there is one
  if($result && $row = mysql_fetch_array($result))
  {
    //save the mid
    $_SESSION['mid'] = $row['mid'];
    $loginstep = $row['loginstep'];
    $requiredActions = $row['actions_required'];
    $_SESSION['loginstep'] = $loginstep;
  }
}

function hash_password($password) {
  defined('SALT') or 
    die( 'Unable to continue; SALT constant not defined in environment');

  return hash( "sha256", $password.SALT );
}

function hash_password_legacy($password) {
  return md5($password);
}

function credentials() {
	$query = "SELECT * FROM Mothers WHERE email = '" . $_SESSION['email'] . 
    "' AND password = '" . $_SESSION['password'] . "';";
  $result = mysql_query($query);
  if ( !$result ){
    error_log( mysql_error( ));
    return false;
  }
  if ( mysql_num_rows($result) == 0 ) {
    $legacy_query = "SELECT * FROM Mothers WHERE email = '" . $_SESSION['email'] . 
      "' AND password = '" . $_SESSION['legacy_password'] . "';";
    $result = mysql_query($legacy_query);
    if ( mysql_num_rows($result)) {
      $update_query = "UPDATE Mothers SET password='".$_SESSION['password'].
        "' WHERE email='".$_SESSION['email']."';";
      $update_result = mysql_query($update_query);
      if ($update_result)
        error_log("Password storage for Mother ".$_SESSION['email']." updated.");
    }
    if ( !$result ) {
      error_log( mysql_error( ));
      return false;
    }
  }
  if ( mysql_num_rows($result) == 0 ) {
    return false;
  }
	return $result;
}

function admin_credentials() {
	$query = "select * from Scientists where email = '" .  $_SESSION['email'] .
    "' and password = '" . $_SESSION['password'] . "';";
  $result = mysql_query($query);
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
  if ( mysql_num_rows($result) == 0 ) {
    $legacy_query = "SELECT * from Scientists WHERE email = '" . $_SESSION['email'] . 
      "' AND password = '" . $_SESSION['legacy_password'] . "';";
    $result = mysql_query($legacy_query);
    if ( mysql_num_rows($result)) {
      $update_query = "UPDATE Scientists SET password='".$_SESSION['password'].
        "' WHERE email='".$_SESSION['email']."';";
      $update_result = mysql_query($update_query);
      if ($update_result)
        error_log("Password storage for Scientist ".$_SESSION['email']." updated.");
    }
    if ( !$result ) {
      error_log( mysql_error( ));
      return false;
    }
  }
	return $result;
}

function consultantInfo( $mid ) {
  $query = "SELECT * FROM Mothers_Scientists MS RIGHT JOIN Scientists S on MS.sid=S.sid WHERE mid=".$mid;
  $result = mysql_query($query);
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  } else {
    if ( mysql_num_rows( $result )) {
      return mysql_fetch_row( $result );
    }
  }
  return false;
}

function scientistInfo( $sid ) {
  $query = "SELECT * FROM Scientists where sid=$sid";
  $result = mysql_query($query);
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  } else {
    if ( mysql_num_rows( $result )) {
      return mysql_fetch_assoc( $result );
    }
  }
  return false;
}


function selectControlledVocabulary($attr, $num=PHP_INT_MAX) {
	$query = sprintf( "SELECT * FROM ControlledVocabulary WHERE Attribute = '%s' ORDER BY NumValue", mysql_real_escape_string( $attr ));
	$result = mysql_query($query);
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
	$returnvalue = "";
	while($row = mysql_fetch_array($result)) {
		$returnvalue .= sprintf( "<option value='%s' %s>%s</option>", $row[ 'NumValue' ], (( $num == $row[ 'NumValue' ]) ? "selected" : ""), _($row[ 'TextValue' ]));
	}
	return $returnvalue;
}

function checkControlledVocabulary($attr, $group, $type) {
  $returnvalue = "";
	$query = "SELECT * FROM ControlledVocabulary WHERE Attribute = '" . $attr . "' ORDER BY NumValue";
	$result = mysql_query($query);
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
	mysql_fetch_array($result);
	$i = 0;
	while($row = mysql_fetch_array($result)) {
	  	$i++;
	  	if($type != 0) {
	  		$checked = "";
	  		if($type == 1 && $_SESSION["InfoInput"][9 + $i] != 0 || $type == 2 && $_SESSION["InfoInput"][14 + $i]) {
	  			$checked = " checked ";
	  		}
			$returnvalue .= "<label><input type=\"checkbox\" value=\"" . $row['NumValue'] ."\" name=\"" . $group . "" . $i . "\"" . $checked . ">" . _($row['TextValue'])."</label><br />";
		} else {
			$returnvalue .= "<label><input type=\"checkbox\" value=\"" . $row['NumValue'] ."\" name=\"" . $group . "" . $i . "\">" . _($row['TextValue'])."<label><br />";	
		}
  }
  return $returnvalue;
}


function loadVocabulary() {
	if(!isset($_SESSION['vocab'])) {
		$vq1 = "SELECT * from ControlledVocabulary ORDER BY Attribute, NumValue ASC";
		$result = mysql_query($vq1);
    if ( !$result ) {
      error_log( mysql_error( ));
      return false;
    }
		$count = 0;
		$attr = '';
		while($temp_rec = mysql_fetch_array($result)) 
		{
			if($attr != $temp_rec['Attribute']) {
				$attr = $temp_rec['Attribute'];
				$count = 0;
			}
			$_SESSION['vocab'][$attr][$count] = $temp_rec;
			$count++;
		}	
	}
}

function getPOH($num) {
	$str = "";
	for($i = 0; $i < 5; $i++) {
		if(substr($num, $i, 1) == "2") {
			if($i == 0) {
				$str = $str .  _("History of pregnancy loss")."<br />";
			} else if($i == 1) {
				$str = $str .  _("Previous premature &lt; 37 weeks")."<br />";
			} else if($i == 2) {
				$str = $str .  _("Previous antepartum hemorrahage")."<br />";
			} else if($i == 3) {
				$str = $str .  _("Low birth weight infant")."<br />";
			} else if($i == 4) {
				$str = $str .  _("Other")."<br />";
			}
		} 
	}
	if($str == "") {
		$str = _("None");
	}
	
	return $str;
}

function getMHDP($num) {
	$str = "";
	for($i = 0; $i < 7; $i++) {
		if(substr($num, $i, 1) == "2") {
			if($i == 0) {
				$str = $str .  _("Low maternal weight")." &lt; 50 kg<br />";
			} else if($i == 1) {
				$str = $str .  _("Bleeding during pregnancy")."<br />";
			} else if($i == 2) {
				$str = $str .  _("Toxemia of Pregnancy")."<br />";
			} else if($i == 3) {
				$str = $str .  _("Lack or late prenatal care")."<br />";
			} else if($i == 4) {
				$str = $str .  _("PROM")."<br />";
			} else if($i == 5) {
				$str = $str .  _("Gestational Diabetes")."<br />";
			} else if($i == 6) {
				$str = $str . _("Other")."<br />";
			}
		} 
	}
	if($str == "") {
		$str = "<pre>                     None" . "</pre>";
	}
	
	return $str;
}

function getCheckedMHDP($num) {
	$str = "";
	for($i = 0; $i < 7; $i++) {
		$checked = "";
		if(substr($num, $i, 1) == "2") {
			$checked = " checked ";
		}
		
		if($i == 0) {
			$str = $str .  "<input type=\"checkbox\" value=\"1\" name=\"MHDP1\"" . $checked . ">"._("Low maternal weight")." &lt;50 kg" . "<br />";
		} else if($i == 1) {
			$str = $str .  "<input type=\"checkbox\" value=\"2\" name=\"MHDP2\"" . $checked . ">"._("Bleeding during pregnancy") . "<br />";
		} else if($i == 2) {
			$str = $str .  "<input type=\"checkbox\" value=\"3\" name=\"MHDP3\"" . $checked . ">"._("Toxemia of Pregnancy") . "<br />";
		} else if($i == 3) {
			$str = $str .  "<input type=\"checkbox\" value=\"4\" name=\"MHDP4\"" . $checked . ">"._("Lack or late prenatal care") . "<br />";
		} else if($i == 4) {
			$str = $str .  "<input type=\"checkbox\" value=\"5\" name=\"MHDP5\"" . $checked . ">"._("PROM")."" . "<br />";
		} else if($i == 5) {
			$str = $str .  "<input type=\"checkbox\" value=\"6\" name=\"MHDP6\"" . $checked . ">"._("Gestational Diabetes") . "<br />";
		} else if($i == 6) {
			$str = $str .  "<input type=\"checkbox\" value=\"7\" name=\"MHDP7\"" . $checked . ">"._("Other")."" . "<br />";
		}
	}
	
	return $str;
}

function getCheckedPOH($num) {
	$str = "";
	for($i = 0; $i < 5; $i++) {
		$checked = "";
		if(substr($num, $i, 1) == "2") {
			$checked = " checked ";
		}
		
		if($i == 0) {
			$str = $str .  "<input type=\"checkbox\" value=\"1\" name=\"POB1\"" . $checked . ">"._("History of pregnancy loss") . "<br />";
		} else if($i == 1) {
			$str = $str .  "<input type=\"checkbox\" value=\"2\" name=\"POB2\"" . $checked . ">"._("Previous premature &lt; 37 weeks") . "<br />";
		} else if($i == 2) {
			$str = $str .  "<input type=\"checkbox\" value=\"3\" name=\"POB3\"" . $checked . ">"._("Previous antepartum hemorrahage") . "<br />";
		} else if($i == 3) {
			$str = $str .  "<input type=\"checkbox\" value=\"4\" name=\"POB4\"" . $checked . ">"._("Low birth weight infant") . "<br />";
		} else if($i == 4) {
			$str = $str .  "<input type=\"checkbox\" value=\"5\" name=\"POB5\"" . $checked . ">"._("Other") . "<br />";
		}
		
	}

	
	return $str;
}

function getVocab($attr, $num) {
	if($num == 0) {
		return "---";
	} 
	return _($_SESSION['vocab'][$attr][$num]['TextValue']);
}

function db_escape( $var ) {
	if ( is_numeric( $var )) {
		return $var;
	} else {
		return mysql_real_escape_string( $var );
	}
}

function addLogEntry( $s_mid, $mid ) {
	$s_mid = db_escape( $s_mid );
	$mid = db_escape( $mid );
  $result = mysql_query( "SELECT IFNULL(MAX(EntryId),0) + 1 AS ID FROM LogEntry" );
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
	$row = mysql_fetch_array( $result );
	$id = $row[ "ID" ];
	mysql_query( "INSERT INTO ScienceWrite VALUES ($s_mid, NOW(), $mid, 1, 4, $id)" );
	if ( mysql_error( )) {
		error_log( mysql_error( ));
		return false;
	}
	return true;
}

function addDiaryEntry( $mid, $entryDate, $entryType, $entryId ) {
	$mid = db_escape( $mid );
	$entryDate = db_escape( $entryDate );
	$entryType = db_escape( $entryType );
	$entryId = db_escape( $entryId );
	$row = mysql_fetch_array( mysql_query( "SELECT IFNULL(MAX(Number),0) + 1 AS number FROM Diary WHERE mid=$mid" ));
	$number = $row[ "number" ];
	$result = mysql_query( "INSERT INTO Diary 
	              (mid, Number, InputDate, EntryDate, EntryType, EntryId)
	              VALUES
	              ($mid, $number, NOW(), '$entryDate', $entryType, $entryId)");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
	return true;
}

function addScienceWriteEntry( $s_mid, $mid, $writeType, $entryType, $entryId ) {
	$result = mysql_query( "INSERT INTO ScienceWrite VALUES($s_mid, NOW(), $mid, $writeType, $entryType, $entryId);");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
	return true;
}

function addBreastfeedEntry( $mid, $duration, $latching, $infantState, $maternalProblems, $side, $entryDate ) {
	$duration = db_escape( $duration );
	$latching = db_escape( $latching );
	$infantState = db_escape( $infantState );
	$maternalProblems = db_escape( $maternalProblems );
	$side = db_escape( $side );
	$entryDate = db_escape( $entryDate );
  $result = mysql_query( "SELECT IFNULL(MAX(EntryId),0) + 1 AS ID FROM BreastfeedEntry" );
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
	$row = mysql_fetch_array( $result );
	$id = $row[ "ID" ];
	$result = mysql_query("INSERT INTO BreastfeedEntry
	             (EntryId, BreastfeedingDuration, Latching, InfantState, MaternalProblems, Side ) 
							 VALUES
							 ($id, $duration, $latching, $infantState, $maternalProblems, $side)");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
	if ( !addDiaryEntry( $mid, $entryDate, 1, $id )) {
		return false;
	}
	if(isset($_SESSION['s_mid'])) {
		return addScienceWriteEntry( $_SESSION['s_mid'], $mid, 1, 1, $id);
	}	
	return true;
}


function addPumpingEntry( $mid, $pumpingMethod, $pumpingDuration, $pumpingAmount, $entryDate ) {
	$pumpingMethod = db_escape( $pumpingMethod );
	$pumpingDuration = db_escape( $pumpingDuration );
	$pumpingAmount = db_escape( $pumpingAmount );
  $result = mysql_query( "SELECT IFNULL(MAX(EntryId),0) + 1 AS ID FROM BreastfeedEntry" );
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
	$row = mysql_fetch_array( $result );
	$id = $row[ "ID" ];
	$result = mysql_query("INSERT INTO BreastfeedEntry
	             (EntryId, PumpingMethod, BreastfeedingDuration, PumpingAmount) 
							 VALUES
							 ($id, $pumpingMethod, $pumpingDuration, $pumpingAmount)");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
	if ( !addDiaryEntry( $mid, $entryDate, 1, $id )) {
		return false;
	}
	if(isset($_SESSION['s_mid'])) {
		return addScienceWriteEntry( $_SESSION['s_mid'], $mid, 1, 1, $id);
	}	
	return true;
}

function addSupplementEntry( $mid, $supplementType, $supplementMethod, $supplementAmount, $supplementNumber, $entryDate ) {
	$supplementType = db_escape( $supplementType );
	$supplementMethod = db_escape( $supplementMethod );
	$supplementAmount = db_escape( $supplementAmount );
	$supplementNumber = db_escape( $supplementNumber );
  $result = mysql_query( "SELECT IFNULL(MAX(EntryId),0) + 1 AS ID FROM SupplementEntry" );
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
	$row = mysql_fetch_array( $result );
	$id = $row[ "ID" ];
	$result = mysql_query("INSERT INTO SupplementEntry
	             (EntryId, SupType, SupMethod, TotalAmount, NumberTimes) 
							 VALUES
							 ($id, $supplementType, $supplementMethod, $supplementAmount, $supplementNumber)");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
	if ( !addDiaryEntry( $mid, $entryDate, 2, $id )) {
		return false;
	}
	if(isset($_SESSION['s_mid'])) {
		return addScienceWriteEntry( $_SESSION['s_mid'], $mid, 1, 2, $id);
	}	
	return true;
}

function addOutputEntry ( $mid, $uColor, $uSaturation, $sColor, $sConsistency, $numberOfDiapers, $entryDate ) {
	$uColor = db_escape( $uColor );
	$uSaturation = db_escape( $uSaturation );
	$sColor = db_escape( $sColor );
	$sConsistency = db_escape( $sConsistency );
	$numberofDiapers = db_escape( $numberOfDiapers );
  $result = mysql_query( "SELECT IFNULL(MAX(EntryId),0) + 1 AS ID FROM OutputEntry" );
  if ( !$result ) {
    error_log( mysql_error( ));
    return false;
  }
	$row = mysql_fetch_array( $result );
	$id = $row[ "ID" ];
	$result = mysql_query ("INSERT INTO OutputEntry
	              (EntryId, UrineColor, UrineSaturation, StoolColor, StoolConsistency, NumberDiapers) 
								VALUES 
								($id, $uColor, $uSaturation, $sColor, $sConsistency, $numberOfDiapers)");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
	if ( !addDiaryEntry( $mid, $entryDate, 3, $id )) {
		return false;
	}
	if(isset($_SESSION['s_mid'])) {
		return addScienceWriteEntry( $_SESSION['s_mid'], $mid, 1, 3, $id);
	}	
	return true;
}

function addWeightEntry( $mid, $ounces, $entryDate ) {
//	$symptom = db_escape( $symptom );
//	$row = mysql_fetch_array( $result );
	$result = mysql_query("INSERT INTO Weight (`mid`, `weight`, `EntryDate`, `InputDate`) VALUES ($mid, $ounces, '$entryDate', NOW());");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
//	if(isset($_SESSION['s_mid'])) {
//		return addScienceWriteEntry( $_SESSION['s_mid'], $mid, 1, ENTRYTYPE_WEIGHT, $id);
//	}	
	return true;
}

function addMorbidityEntry( $mid, $symptom, $entryDate ) {
	$symptom = db_escape( $symptom );
  $result = mysql_query( "SELECT IFNULL(MAX(EntryId),0) + 1 AS ID FROM MorbidityEntry" );
  if ( !$result ){
    error_log( mysql_error( ));
    return false;
  }
	$row = mysql_fetch_array( $result );
	$id = $row[ "ID" ];
	$result = mysql_query("INSERT INTO MorbidityEntry (EntryId, Type) VALUES ($id, $symptom);");
	if ( !$result ) {
		error_log( mysql_error( ));
		return false;
	}
	if ( !addDiaryEntry( $mid, $entryDate, ENTRYTYPE_HEALTH, $id )) { return false;
	}
	if(isset($_SESSION['s_mid'])) {
		return addScienceWriteEntry( $_SESSION['s_mid'], $mid, 1, 4, $id);
	}	
	return true;
}

function getScientistInfo($mid, $everything=false) {
    if ($everything) {
      $fields = '*';
    } else {
      $fields = 's.sid, s.email, h.hospital_name';
    }
    $returnvalue = false;
    $query = "SELECT $fields FROM ".
      "Mothers_Scientists ms,Scientists s,Hospital h WHERE ".
      "s.sid=ms.sid AND s.hospital_id=h.hospital_id AND mid=$mid;";
    $result = mysql_query($query);
    if (!$result) {
      error_log(mysql_error());
    } 
    $returnvalue = mysql_fetch_assoc($result);
    if (!$returnvalue) {
      if (!$everything) {
        $fields = 's.sid, s.email';
      }
      $query = "SELECT $fields FROM ".
        "Mothers_Scientists ms,Scientists s WHERE ".
        "s.sid=ms.sid AND mid=$mid;";
      $result = mysql_query($query);
      $returnvalue = mysql_fetch_assoc($result);
      if (!$result) {
        error_log(mysql_error());
      } 
    }
    return $returnvalue;
}

function getBreastfeedingEntries($mid, $fromdate, $todate) {
  $fromSqlDate = date("Y-m-d 00:00:00", strtotime($fromdate));
  $toSqlDate = date("Y-m-d 23:59:59", strtotime($todate));
  $query = "SELECT * FROM Diary, BreastfeedEntry WHERE EntryDate BETWEEN " .
    "'$fromSqlDate' AND '$toSqlDate' AND EntryType = 1 AND " .
    "Diary.EntryId = BreastfeedEntry.EntryId AND mid = $mid ".
    "AND PumpingMethod IS NULL ORDER BY EntryDate DESC, InputDate DESC";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue = Array();
  while ($row = mysql_fetch_assoc($result)) {
    $returnrow = Array();
    // $returnrow['id'] = $row['Number']; // not sure what this field is.
    $returnrow['entryTime'] = strtoTime($row['EntryDate']);
    $returnrow['inputTime'] = strtoTime($row['InputDate']);
    $returnrow['id'] = $row['EntryId'];
    $returnrow['duration'] = $row['BreastfeedingDuration'];
    $returnrow['infantState'] = $row['InfantState'];
    $returnrow['problems'] = $row['MaternalProblems'];
    $returnrow['latching'] = $row['Latching'];
    $returnrow['side'] = $row['Side'];
    array_push($returnvalue, $returnrow);
  }
  return $returnvalue;
}

function getPumpingEntries($mid, $fromdate, $todate) {
  $fromSqlDate = date("Y-m-d 00:00:00", strtotime($fromdate));
  $toSqlDate = date("Y-m-d 23:59:59", strtotime($todate));
  $query = "SELECT * FROM Diary, BreastfeedEntry WHERE EntryDate BETWEEN " .
    "'$fromSqlDate' AND '$toSqlDate' AND EntryType = 1 AND " .
    "Diary.EntryId = BreastfeedEntry.EntryId AND mid = $mid ".
    "AND PumpingMethod IS NOT NULL ORDER BY EntryDate DESC, InputDate DESC";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue = Array();
  while ($row = mysql_fetch_assoc($result)) {
    $returnrow = Array();
    // $returnrow['id'] = $row['Number']; // not sure what this field is.
    $returnrow['entryTime'] = strtoTime($row['EntryDate']);
    $returnrow['inputTime'] = strtoTime($row['InputDate']);
    $returnrow['id'] = $row['EntryId'];
    $returnrow['duration'] = $row['BreastfeedingDuration'];
    $returnrow['method'] = $row['PumpingMethod'];
    $returnrow['amount'] = $row['PumpingAmount'];
    array_push($returnvalue, $returnrow);
  }
  return $returnvalue;
}

function getSupplementEntries($mid, $fromdate, $todate) {
  $fromSqlDate = date("Y-m-d 00:00:00", strtotime($fromdate));
  $toSqlDate = date("Y-m-d 23:59:59", strtotime($todate));
  $query = "SELECT * FROM Diary, SupplementEntry WHERE EntryDate BETWEEN " .
    "'$fromSqlDate' AND '$toSqlDate' AND EntryType = 2 AND " .
    "Diary.EntryId = SupplementEntry.EntryId AND mid = $mid ".
    "ORDER BY EntryDate DESC, InputDate DESC";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue = Array();
  while ($row = mysql_fetch_assoc($result)) {
    $returnrow = Array();
    // $returnrow['id'] = $row['Number']; // not sure what this field is.
    $returnrow['entryTime'] = strtoTime($row['EntryDate']);
    $returnrow['inputTime'] = strtoTime($row['InputDate']);
    $returnrow['id'] = $row['EntryId'];
    $returnrow['supType'] = $row['SupType'];
    $returnrow['supMethod'] = $row['SupMethod'];
    $returnrow['numberOfTimes'] = $row['NumberTimes'];
    $returnrow['totalAmount'] = $row['TotalAmount'];
    array_push($returnvalue, $returnrow);
  }
  return $returnvalue;
}

function getOutputEntries($mid, $fromdate, $todate) {
  $fromSqlDate = date("Y-m-d 00:00:00", strtotime($fromdate));
  $toSqlDate = date("Y-m-d 23:59:59", strtotime($todate));
  $query = "SELECT * FROM Diary, OutputEntry WHERE EntryDate BETWEEN " .
    "'$fromSqlDate' AND '$toSqlDate' AND EntryType = 3 AND " .
    "Diary.EntryId = OutputEntry.EntryId AND mid = $mid ".
    "ORDER BY EntryDate DESC, InputDate DESC";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue = Array();
  while ($row = mysql_fetch_assoc($result)) {
    $returnrow = Array();
    // $returnrow['id'] = $row['Number']; // not sure what this field is.
    $returnrow['entryTime'] = strtoTime($row['EntryDate']);
    $returnrow['inputTime'] = strtoTime($row['InputDate']);
    $returnrow['id'] = $row['EntryId'];
    $returnrow['urineColor'] = $row['UrineColor'];
    $returnrow['urineSaturation'] = $row['UrineSaturation'];
    $returnrow['stoolColor'] = $row['StoolColor'];
    $returnrow['stoolConsistency'] = $row['StoolConsistency'];
    $returnrow['numberOfDiapers'] = $row['NumberDiapers'];
    array_push($returnvalue, $returnrow);
  }
  return $returnvalue;
}

function getWeightEntries($mid, $fromdate, $todate) {
  $fromSqlDate = date("Y-m-d 00:00:00", strtotime($fromdate));
  $toSqlDate = date("Y-m-d 23:59:59", strtotime($todate));
  $query = "SELECT * FROM Weight WHERE EntryDate BETWEEN " .
    "'$fromSqlDate' AND '$toSqlDate' AND mid = $mid ".
    "ORDER BY EntryDate DESC, InputDate DESC";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue = Array();
  while ($row = mysql_fetch_assoc($result)) {
    $returnrow = Array();
    // $returnrow['id'] = $row['Number']; // not sure what this field is.
    $returnrow['entryTime'] = strtoTime($row['EntryDate']);
    $returnrow['inputTime'] = strtoTime($row['InputDate']);
    $returnrow['id'] = $row['EntryId'];
    $returnrow['weight'] = $row['weight'];
    array_push($returnvalue, $returnrow);
  }
  return $returnvalue;
}

function getHealthEntries($mid, $fromdate, $todate) {
  $fromSqlDate = date("Y-m-d 00:00:00", strtotime($fromdate));
  $toSqlDate = date("Y-m-d 23:59:59", strtotime($todate));
  $query = "SELECT * FROM Diary, MorbidityEntry WHERE EntryDate BETWEEN " .
    "'$fromSqlDate' AND '$toSqlDate' AND EntryType = 4 AND " .
    "Diary.EntryId = MorbidityEntry.EntryId AND mid = $mid ".
    "ORDER BY EntryDate DESC, InputDate DESC";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue = Array();
  while ($row = mysql_fetch_assoc($result)) {
    $returnrow = Array();
    // $returnrow['id'] = $row['Number']; // not sure what this field is.
    $returnrow['entryTime'] = strtoTime($row['EntryDate']);
    $returnrow['inputTime'] = strtoTime($row['InputDate']);
    $returnrow['id'] = $row['EntryId'];
    $returnrow['type'] = $row['Type'];
    array_push($returnvalue, $returnrow);
  }
  return $returnvalue;
}



function getMotherInfo( $emails=false, $conditions=false ) {
	
  if ( $_SESSION['admin'] == SUPER_ADMIN ) {
		$query  = "SELECT * FROM MotherInfo MI RIGHT JOIN Mothers M ON M.mid=MI.mid";
  } else if ( $_SESSION['admin'] == HOSPITAL_ADMIN ) {
    $query = "SELECT * from MotherInfo MI 
      RIGHT JOIN 
        (Mothers_Scientists MS, Scientists S,Mothers M) 
      ON 
        M.mid=MI.mid 
      WHERE M.mid=MS.mid AND MS.sid=S.sid AND M.hospital_id=".$_SESSION['hospital_id'];
	} else {
		$query  = "SELECT * FROM MotherInfo MI 
			RIGHT JOIN 
				(Scientists S,Mothers_Scientists MS, Mothers M) 
			ON 
				M.mid=MI.mid 
			WHERE 
				M.mid=MS.mid AND S.sid=MS.sid AND S.sid=".$_SESSION['sid'];
  }
	if ( $emails ) {
		if ( stristr( $query, "WHERE" )) {
			$query .= " AND";
		} else {
			$query .= " WHERE";
		}
		$query .= " M.email IN (";
		$sep="";
		foreach( $emails as $email ) {
			$query .= "$sep'$email'";
			$sep = ',';
		}
		$query .= ");";
	}
	if ( $conditions ) {
		if ( !is_array( $conditions )) {
			$conditions = array( $conditions );
		}
		foreach ( $conditions as $condition ) {
			if ( stristr( $query, "WHERE" )) {
				$query .= " AND";
			} else {
				$query .= " WHERE";
			}
			$query .= " $condition";
		}
	}
	$result = mysql_query( $query );
  if ( !$result ) error_log( mysql_error( ));
	$returnvalue = array( );
	while ( $row = mysql_fetch_assoc( $result )) {
		if ( !$row['Name'] ) {
			// if name isn't set, set it to the part before the @ in the e-mail.
			$row['Name'] = strstr( $row[ 'email' ], '@', true );
		}
    unset($row['password']); // don't show the password hash!!
		array_push( $returnvalue, $row );
	}
	return $returnvalue;
}

function getProfile() {
		$query  = "SELECT * FROM MotherInfo MI 
			RIGHT JOIN 
				Mothers M
			ON 
				M.mid=MI.mid 
			WHERE 
				M.mid=".$_SESSION['mid'];
	$result = mysql_query($query);
  if (!$result) error_log(mysql_error());
	$returnvalue = array();
	$row = mysql_fetch_assoc($result);
  if ( !$row['Name'] ) {
    // if name isn't set, set it to the part before the @ in the e-mail.
    $row['Name'] = strstr($row['email'], '@', true);
  }
  unset($row['password']); // don't show the password hash!!
	return $row;
}

function decodeMotherInfo($info) {
}

/**
 * Retrieves the list of messages from the user id.
 *
 * : Parameters :
 *  $id : int
 *   The user id.
 *  $type : string
 *   The type of messages to retrieve. This can be 'read', 'unread', or 'all'.
 *  $scientist : boolean
 *   Whether or not the id belongs to a scientist.
*/
function getMessages($id, $type='all', $scientist=false) {
  $recipientMask = INBOX_RECIPIENT_MOTHER;
  if ($scientist)
    $recipientMask = INBOX_RECIPIENT_SCIENTIST;

  if (!strcmp($type, 'unread'))
    $typeMask = INBOX_MESSAGE_UNREAD;
  else if (!strcmp($type, 'read'))
    $typeMask = INBOX_MESSAGE_READ;
  else
    $typeMask = INBOX_MESSAGE_UNREAD | INBOX_MESSAGE_READ;
  $query = "SELECT EntryId, message, messageDate, email as senderEmail ".
           "FROM Inbox, ".
           ($scientist ?  "Mothers WHERE senderId = mid " :
                        "Scientists WHERE senderId = sid ")."AND ".
            (($type === "sent") ? "recipientId" : "senderId" )." = $id AND ".
           "(metadata & $recipientMask) > 0 AND (metadata & $typeMask) > 0 ".
           "ORDER BY messageDate DESC";

  error_log($query);
  $result = mysql_query($query);
  if (!$result) {
    error_log( mysql_error( ));
    return array();
  }

  $messages = array();
  for( $i=0; $row = mysql_fetch_assoc($result); $i++) {
   $timestamp = strtotime($row['messageDate']);
   $row['timestamp'] = $timestamp;
   $row['messageDate'] =  formatDate($timestamp);
   $messages[$i] = $row;
  }
  return $messages;
}

function getMotherMessages($id) {
  $query = "SELECT * ".
           "FROM Inbox ".
           "WHERE ((senderId = $id AND (metadata & ".INBOX_RECIPIENT_SCIENTIST.") > 0) ".
           "OR (recipientId = $id AND (metadata & ".INBOX_RECIPIENT_MOTHER.") > 0)) ".
           "ORDER BY messageDate DESC";

  $result = mysql_query($query);
  if (!$result) {
    error_log( mysql_error( ));
    return array();
  }

  $scientitsts = array();
  $messages = array();
  for( $i=0; $row = mysql_fetch_assoc($result); $i++) {
    $returnvalue = array();
    $timestamp = strtotime($row['messageDate']);
    $returnvalue['timestamp'] = $timestamp;
    $returnvalue['messageDate'] =  formatDate($timestamp);
    $returnvalue['id'] = $row['EntryId'];
    $returnvalue['sent'] = ($row['senderId'] == $id);
    $returnvalue['seen'] = !!($row['metadata'] & INBOX_MESSAGE_READ);
    $returnvalue['message'] = $row['message']; //deprecated
    $returnvalue['content'] = $row['message'];
    $returnvalue['id'] = $row['EntryId'];
    if (!$returnvalue['sent']) {
      $scientistId = $row['senderId'];
      if (!isset($scientists[$scientistId])) {
        $scientists[$scientistId] = scientistInfo($scientistId);
      }
    } else {
      $scientistId = $row['recipientId'];
      if (!isset($scientists[$scientistId])) {
        $scientists[$scientistId] = scientistInfo($scientistId);
      }
    }
    if (isset($scientists[$scientistId])) {
      $returnvalue['from'] = $scientists[$scientistId]['email'];
    } else {
      continue;
    }
    $messages[$i] = $returnvalue;
  }
  return $messages;
}

function getScientistMessages($id) {
  $query = "SELECT * ".
           "FROM Inbox i ".
           "RIGHT JOIN Mothers m on m.mid = i.senderId ".
           "LEFT OUTER JOIN MotherInfo mi on mi.mid = i.senderId " .
           "WHERE ((senderId = $id AND (metadata & ".INBOX_RECIPIENT_MOTHER.") > 0) ".
           "OR (recipientId = $id AND (metadata & ".INBOX_RECIPIENT_SCIENTIST.")) > 0) ".
           "ORDER BY messageDate DESC";

  error_log($query);
  $result = mysql_query($query);
  if (!$result) {
    error_log( mysql_error( ));
    return array();
  }

  $scientitsts = array();
  $messages = array();
  for( $i=0; $row = mysql_fetch_assoc($result); $i++) {
    $returnvalue = array();
    $timestamp = strtotime($row['messageDate']);
    $returnvalue['timestamp'] = $timestamp;
    $returnvalue['messageDate'] =  formatDate($timestamp);
    $returnvalue['id'] = $row['EntryId'];
    $returnvalue['sent'] = ($row['senderId'] == $id);
    $returnvalue['from'] = (isset($row['Name']) ? $row['Name'] : $row['email']);
    $returnvalue['seen'] = !!($row['metadata'] & INBOX_MESSAGE_READ);
    $returnvalue['message'] = $row['message'];
    $messages[$i] = $returnvalue;
  }
  return $messages;
}

function markMessageRead($messageId, $id) {
  $query = "UPDATE Inbox SET metadata = (metadata | ".INBOX_MESSAGE_READ.") ".
  " & ~".INBOX_MESSAGE_UNREAD." WHERE recipientId = $id AND EntryId = $messageId";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
  }
  return !!$result;
}

function getMessageCount($id, $scientist=false) {
  $returnvalue = array();
  $recipientMask = INBOX_RECIPIENT_MOTHER;
  if ($scientist)
    $recipientMask = INBOX_RECIPIENT_SCIENTIST;

  $typeMask = INBOX_MESSAGE_READ;
  $query = "SELECT COUNT(*) FROM Inbox WHERE recipientId = $id AND ".
           "metadata & $recipientMask > 0 AND metadata & $typeMask > 0";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue['read'] = (int)mysql_fetch_row($result)[0];
  mysql_free_result($result);

  $typeMask = INBOX_MESSAGE_UNREAD;
  $query = "SELECT COUNT(*) FROM Inbox WHERE recipientId = $id AND ".
           "metadata & $recipientMask > 0 AND metadata & $typeMask > 0";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  $returnvalue['unread'] = (int)mysql_fetch_row($result)[0];
  mysql_free_result($result);

  return $returnvalue;
}

function getNotificationCount($id, $scientist=false) {
  $query = "SELECT COUNT(*) FROM Notifications WHERE mid = $id AND ".
           "status NOT IN (2, 8)";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  return (int)mysql_fetch_row($result)[0];
}

function getNotifications($id, $status=NOTIFICATION_STATUS_CURRENT, $type=0) {
  $returnvalue = array();
  $query = "SELECT * FROM Notifications n 
      WHERE mid = $id AND status & $status <> 0 ".
      (($type !== 0 ) ? "AND n.ntype = $type " : "").
      "ORDER BY NotificationIssued DESC;";
  $result = mysql_query($query);
  if (!$result)
    error_log(mysql_error());
  while($row = mysql_fetch_assoc($result))
  {
    $returnedRow = array();
    $returnedRow['id'] = (int)$row['nid'];
    $returnedRow['status'] = (int)$row['status'];
    $returnedRow['astatus'] = (int)$row['astatus'];
    $returnedRow['issued'] = strtotime($row['NotificationIssued']);
    $returnedRow['type'] = (int)$row['ntype'];
    array_push($returnvalue, $returnedRow);
  }
  return $returnvalue;

}

function markNotificationRead($nid, $mid) {
  $query = "UPDATE Notifications set status = 2 WHERE nid=$nid AND mid=$mid";
  $result = mysql_query($query);
  if (!$result) {
    error_log(mysql_error());
    return false;
  }
  return !!mysql_affected_rows();
}

function can_access_mother( $mid ) {
  $mid = (int)$mid;
	if ( $_SESSION['admin'] == SUPER_ADMIN ) {
    return true;
  } else if ( $_SESSION['admin'] == HOSPITAL_ADMIN ) {
    $query = "SELECT * from Mothers_Scientists MS, Scientists S,Mothers M 
      WHERE 
        M.mid=MS.mid AND MS.sid=S.sid AND 
        M.mid=$mid AND M.hospital_id=".$_SESSION['hospital_id'];
	} else {
		$query  = "SELECT * FROM Scientists S,Mothers_Scientists MS, Mothers M
			WHERE 
				M.mid=MS.mid AND S.sid=MS.sid AND 
        M.mid=$mid AND S.sid=".$_SESSION['sid'];
  }
  $result = mysql_query( $query );
  if (!$result) {
    error_log( mysql_error( ));
  }
  if ($result && mysql_num_rows( $result )) {
    return true;
  }
  return false;
}

?>
