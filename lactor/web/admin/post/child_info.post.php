<?php

include_once("../../includes/general.php");
include_once("../includes/db.include.php");

loggedIn();

//connect to db
db_connect();


if(trim($_POST['InfantInitials']) == "" || $_POST['GestationalAge'] == 0 || $_POST['AppropAge'] == 0) {
	$_SESSION['ChildMessage'] = "Child information NOT stored.\n";
	$_SESSION['ChildDetails'] = "Please enter all fields.\n";
	$_SESSION['ChildType'] = 3;
	go('../child_info.php');
}
else if($_POST['TypeFirstBreast'] == 0 || $_POST['AgeFirstFeed'] == 0 || $_POST['TimeStartBreast'] == 0 || $_POST['FreqBreastExpr'] == 0 || $_POST['morb-type'] == 0 || $_POST['FirstPrimCare'] == 0 || $_POST['NeedExtraCare'] == 0 || ($_POST['NeedExtraCare'] == 1 && $_POST['TimesExtraCare'] == 0) || $_POST['HospFirstMonth'] == 0)  {
	$_SESSION['ChildMessage'] = "Child information NOT stored.\n";
	$_SESSION['ChildDetails'] = "Please enter all fields.\n";
	$_SESSION['ChildType'] = 3;
	go('../child_info.php');
} else if($_POST['BirthWeightPounds'] == 0 || $_POST['BirthWeightOunces'] == 0 || $_POST['DischargeWeightPounds'] == 0 || $_POST['DischargeWeightOunces'] == 0) {
	$_SESSION['ChildMessage'] = "Child information NOT stored.\n";
	$_SESSION['ChildDetails'] = "Please enter all fields.\n";
	$_SESSION['ChildType'] = 3;
	go('../child_info.php');
} else if(trim($_POST['dateDischarge']) == "" || trim($_POST['dateBirth']) == ""){
	$_SESSION['ChildMessage'] = "Child information NOT stored.\n";
	$_SESSION['ChildDetails'] = "Please enter all fields.\n";
	$_SESSION['ChildType'] = 3;
	go('../child_info.php');
}



$query="INSERT INTO InfantProfile (mid, cid, InfantInitials, AppropAge, GestationalAge, TypeFirstBreast, AgeFirstFeed, TimeStartBreast, FreqBreastExpr, ChildMorb, FirstPrimCare, NeedExtraCare, TimesExtraCare, HospFirstMonth, DOB, BirthWeight, DOD, DischargeWeight)";
$query=$query . " VALUES (" . $_POST['mid'] . ", 1" . ", '" . $_POST['InfantInitials'] . "', " . $_POST['AppropAge'] . ", ";
$query=$query . $_POST['GestationalAge'] . ", " . $_POST['TypeFirstBreast'] . ", " . $_POST['AgeFirstFeed'] . ", " . $_POST['TimeStartBreast'] . ", ";
$query=$query . $_POST['FreqBreastExpr'] . ", " . $_POST['morb-type'] . ", " . $_POST['FirstPrimCare'] . ", " . $_POST['NeedExtraCare'] . ", ";
$query=$query . $_POST['TimesExtraCare'] . ", " . $_POST['HospFirstMonth'] . ", ";
$query=$query . "'" . modDate($_POST['dateBirth']) . " 00:00:00', '" . $_POST['BirthWeightPounds'] . " " . $_POST['BirthWeightOunces'] . "', ";
$query=$query . "'" . modDate($_POST['dateDischarge']) . " 00:00:00', '" . $_POST['DischargeWeightPounds'] . " " . $_POST['DischargeWeightOunces'] . "'); ";

mysql_query($query);


$_SESSION['ChildMessage'] = "Child information stored successfully.\n";
$_SESSION['ChildType'] = 1;

go('../child_info.php');


?>
