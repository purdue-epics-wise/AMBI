<?php

include_once("../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();

//connect to db
db_connect();


//TODO: errors, phone

$_SESSION['InfoDetails'] = "";
if(trim($_POST["FormalName"]) == "") {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Name field was left blank.<br />";
}
if(trim($_POST["Address"]) == "") {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Address field was left blank.<br />";
}
if(trim($_POST["Phone"]) == "") {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Phone field was left blank.<br />";
} else { 
	if (!ereg ("[0-9]{3}-[0-9]{3}-[0-9]{4}", trim($_POST["Phone"]))) {
		$_SESSION['InfoMessage'] = "Phone input not filled correctly.\n";
		$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Phone field has to be inputed with the following syntax: XXX-XXX-XXXX.<br />";         
	} 
}
if($_POST["Age"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Age field was left blank.<br />";
}
if($_POST["Ethnicity"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Ethnicity field was left blank.<br />";
}
if($_POST["Race"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Race field was left blank.<br />";
}
if($_POST["Education"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Education field was left blank.<br />";
}
if($_POST["Occupation"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Occupation field was left blank.<br />";
}
if($_POST["Residence"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Residence field was left blank.<br />";
}
if($_POST["HouseIncome"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Household Income field was left blank.<br />";
}
if($_POST["Parity"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Parity field was left blank.<br />";
}
if($_POST["MODel"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Latest Method of Delivery field was left blank.<br />";
}
if($_POST["PBE"] == 0) {
	$_SESSION['InfoMessage'] = "Not all required inputs were filled.\n";
	$_SESSION['InfoDetails'] = $_SESSION['InfoDetails'] . "Past Breastfeeding Experience field was left blank.<br />";
}

if(isset($_SESSION['InfoMessage'])) {
	$_SESSION['InfoInput'][0] = $_POST["FormalName"];
	$_SESSION['InfoInput'][1] = $_POST["Address"];
	$_SESSION['InfoInput'][2] = $_POST["Phone"];
	$_SESSION['InfoInput'][3] = $_POST["Age"];
	$_SESSION['InfoInput'][4] = $_POST["Ethnicity"];
	$_SESSION['InfoInput'][5] = $_POST["Race"];
	$_SESSION['InfoInput'][6] = $_POST["Education"];
	$_SESSION['InfoInput'][7] = $_POST["Occupation"];
	$_SESSION['InfoInput'][8] = $_POST["HouseIncome"];
	$_SESSION['InfoInput'][9] = $_POST["Parity"];
	$_SESSION['InfoInput'][10] = $_POST["POB1"];
	$_SESSION['InfoInput'][11] = $_POST["POB2"];
	$_SESSION['InfoInput'][12] = $_POST["POB3"];
	$_SESSION['InfoInput'][13] = $_POST["POB4"];
	$_SESSION['InfoInput'][14] = $_POST["POB5"];
	$_SESSION['InfoInput'][15] = $_POST["MHDP1"];
	$_SESSION['InfoInput'][16] = $_POST["MHDP2"];
	$_SESSION['InfoInput'][17] = $_POST["MHDP3"];
	$_SESSION['InfoInput'][18] = $_POST["MHDP4"];
	$_SESSION['InfoInput'][19] = $_POST["MHDP5"];
	$_SESSION['InfoInput'][20] = $_POST["MHDP6"];
	$_SESSION['InfoInput'][21] = $_POST["MHDP7"];
	$_SESSION['InfoInput'][22] = $_POST["MODel"];
	$_SESSION['InfoInput'][23] = $_POST["PBE"];
	$_SESSION['InfoType'] = 3;
	go('../mother_info.php');
}

$Age=$_POST["Age"];
$Ethnicity=$_POST["Ethnicity"];
$Race=$_POST["Race"];
$Education=$_POST["Education"];
$HouseIncome=$_POST["HouseIncome"];
$Occupation=$_POST["Occupation"];
$Residence=$_POST["Residence"];
$Parity=$_POST["Parity"];
$POB="";
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

$MODel=$_POST["MODel"];
$PBE=$_POST["PBE"];


$query="INSERT INTO MotherInfo VALUES (" . $_SESSION['mid'] . ", '" . mysql_real_escape_string($_POST['FormalName']) . "', '" . mysql_real_escape_string($_POST['Address']) . "', " . $Age['NumValue'] . "," . $Ethnicity['NumValue'] . "," . $Race['NumValue'] . "," . $Education['NumValue'] . ", " . $HouseIncome['NumValue'] . ", " . $Occupation['NumValue'] . ", " . $Residence['NumValue'] . ", " . $Parity['NumValue'] . ", " . $POB . ", " . $MHDP  . ", " . $MODel['NumValue'] . ", " . $PBE['NumValue'] . ", '" .  mysql_real_escape_string($_POST['Phone']) . "')";
mysql_query($query);
echo $query;
$query="UPDATE Mothers SET loginstep=0 WHERE mid = " . $_SESSION['mid'] . ";";
mysql_query($query);

$_SESSION['ChangePassMessage'] = "Mother information stored successfully.\n";
$_SESSION['ChangePassType'] = 1;

go('../profile.php');


?>
