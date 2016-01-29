<?php

function db_connect() {
	$conn = mysql_connect('localhost', 'lactor', 'breast1010');
	if (!$conn) {
  		die('Could not connect');
	}

	mysql_select_db("lactor", $conn);
	$_SESSION['db_con'] = $conn;
}

function credentials() {
	$query = "SELECT * FROM Mothers WHERE email = '" . $_SESSION['email'] . "' AND password = '" . $_SESSION['password'] . "';";
	return mysql_query($query);
}

function admin_credentials() {
	$query = "SELECT * FROM Scientists WHERE email = '" . $_SESSION['email'] . "' AND password = '" . $_SESSION['password'] . "';";
	return mysql_query($query);
}

function selectControlledVocabulary($attr) {
	$query = "SELECT * FROM ControlledVocabulary WHERE Attribute = '" . $attr . "' ORDER BY NumValue";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result))
	  {
		echo "<option value=\"" . $row['NumValue'] ."\">" . $row['TextValue'] . "</option>";
	  }
}

function selectSelectedControlledVocabulary($attr, $num) {
	$query = "SELECT * FROM ControlledVocabulary WHERE Attribute = '" . $attr . "' ORDER BY NumValue";
	$result = mysql_query($query);
	if(!isset($num)) {
		$num = 0;
	}
	while($row = mysql_fetch_array($result))
	  {
	  	$c = "";
	  	if($num == $row['NumValue']) {
	  		$c = " SELECTED ";
	  	}
		echo "<option value=\"" . $row['NumValue'] ."\" " . $c . ">" . $row['TextValue'] . "</option>";
	  }
}

function checkControlledVocabulary($attr, $group, $type) {
	$query = "SELECT * FROM ControlledVocabulary WHERE Attribute = '" . $attr . "' ORDER BY NumValue";
	$result = mysql_query($query);
	mysql_fetch_array($result);
	$i = 0;
	while($row = mysql_fetch_array($result))
	  {
	  	$i++;
	  	if($type != 0) {
	  		$checked = "";
	  		if($type == 1 && $_SESSION["InfoInput"][9 + $i] != 0 || $type == 2 && $_SESSION["InfoInput"][14 + $i]) {
	  			$checked = " checked ";
	  		}
			echo "<pre>                                       <input type=\"checkbox\" value=\"" . $row['NumValue'] ."\" name=\"" . $group . "" . $i . "\"" . $checked . ">" . $row['TextValue'] . "</pre>";
		} else {
			echo "<pre>                                       <input type=\"checkbox\" value=\"" . $row['NumValue'] ."\" name=\"" . $group . "" . $i . "\">" . $row['TextValue'] . "</pre>";		
		}
	  }
}

function loadVocabulary() {
	if(!isset($_SESSION['vocab'])) {
		$vq1 = "SELECT * from ControlledVocabulary ORDER BY Attribute, NumValue ASC";
		$result = mysql_query($vq1);
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
				$str = $str .  "<pre>                     History of pregnancy loss" . "</pre>";
			} else if($i == 1) {
				$str = $str .  "<pre>                     Previous premature < 37 weeks" . "</pre>";
			} else if($i == 2) {
				$str = $str .  "<pre>                     Previous antepartum hemorrahage" . "</pre>";
			} else if($i == 3) {
				$str = $str .  "<pre>                     Low birth weight infant" . "</pre>";
			} else if($i == 4) {
				$str = $str .  "<pre>                     Other" . "</pre>";
			}
		} 
	}
	if($str == "") {
		$str = "<pre>                     None" . "</pre>";
	}
	
	return $str;
}

function getMHDP($num) {
	$str = "";
	for($i = 0; $i < 7; $i++) {
		if(substr($num, $i, 1) == "2") {
			if($i == 0) {
				$str = $str .  "<pre>                     Low maternal weight <50 kg" . "</pre>";
			} else if($i == 1) {
				$str = $str .  "<pre>                     Bleeding during pregnancy" . "</pre>";
			} else if($i == 2) {
				$str = $str .  "<pre>                     Toxemia of Pregnancy" . "</pre>";
			} else if($i == 3) {
				$str = $str .  "<pre>                     Lack or late prenatal care" . "</pre>";
			} else if($i == 4) {
				$str = $str .  "<pre>                     PROM" . "</pre>";
			} else if($i == 5) {
				$str = $str .  "<pre>                     Gestational Diabetes" . "</pre>";
			} else if($i == 6) {
				$str = $str .  "<pre>                     Other" . "</pre>";
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
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"1\" name=\"MHDP1\"" . $checked . ">Low maternal weight <50 kg" . "</pre>";
		} else if($i == 1) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"2\" name=\"MHDP2\"" . $checked . ">Bleeding during pregnancy" . "</pre>";
		} else if($i == 2) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"3\" name=\"MHDP3\"" . $checked . ">Toxemia of Pregnancy" . "</pre>";
		} else if($i == 3) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"4\" name=\"MHDP4\"" . $checked . ">Lack or late prenatal care" . "</pre>";
		} else if($i == 4) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"5\" name=\"MHDP5\"" . $checked . ">PROM" . "</pre>";
		} else if($i == 5) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"6\" name=\"MHDP6\"" . $checked . ">Gestational Diabetes" . "</pre>";
		} else if($i == 6) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"7\" name=\"MHDP7\"" . $checked . ">Other" . "</pre>";
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
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"1\" name=\"POB1\"" . $checked . ">History of pregnancy loss" . "</pre>";
		} else if($i == 1) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"2\" name=\"POB2\"" . $checked . ">Previous premature < 37 weeks" . "</pre>";
		} else if($i == 2) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"3\" name=\"POB3\"" . $checked . ">Previous antepartum hemorrahage" . "</pre>";
		} else if($i == 3) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"4\" name=\"POB4\"" . $checked . ">Low birth weight infant" . "</pre>";
		} else if($i == 4) {
			$str = $str .  "<pre>                     <input type=\"checkbox\" value=\"5\" name=\"POB5\"" . $checked . ">Other" . "</pre>";
		}
		
	}

	
	return $str;
}

function getVocab($attr, $num) {
	if($num == 0) {
		return "---";
	} 
	
	return $_SESSION['vocab'][$attr][$num]['TextValue'];
}

?>
