<?php

session_start();

include_once("./mobile.include.php");

if(!isset($_SESSION['mid'])) {
	$_SESSION['LoginMessage'] = "Session expired.";
	$_SESSION['LoginType'] = 3;
	header('Location: ./m.login.php');
	exit;
}

if(!isset($_SESSION['writeid'])) {
	header('Location: ./m.add_entry.php');
	exit;
}


if(isset($_POST['cancel'])) {
	$_SESSION['writetype'] = 2;
	$_SESSION['writemessage'] = "Modification canceled. " . "<a href=\"m.modify_entry.php\">Modify</a>";
	header('Location: ./m.add_entry.php');
	exit;
}

$conn = mysql_connect('localhost', 'lactor', 'breast1010');
if (!$conn) {
	die('Could not connect');
}

mysql_select_db("Breast1", $conn);
$_SESSION['db_con'] = $conn;


if(isset($_POST['morb'])) {	
	if($_POST["morb-type"] == 0) {
		$_SESSION['writetype'] = 3;
		$_SESSION['writemessage'] = "Health issue not modified. ";
	
		header('Location: ./m.modify_entry.php');
		exit;
	}
	


	$morbtype=$_POST['morb-type'];
	
	$query="Update MorbidityEntry SET Type = " . $morbtype . " WHERE EntryId = " . $_SESSION['writeid'] . ";";
	mysql_query($query);
	
	
	$_SESSION['writetype'] = 1;
	
	$_SESSION['writemessage'] = "Health issue modified. "  . "<a href=\"m.modify_entry.php\">Modify</a>";
	
	
	
	header('Location: ./m.add_entry.php');
	exit;	
} 


else if(isset($_POST['out'])) {

	if($_POST["out-u-color"] != 0 && $_POST["out-u-saturation"] == 0 || $_POST["out-u-color"] == 0 && $_POST["out-u-saturation"] != 0) {
		$_SESSION['writetype'] = 3 ;
		$_SESSION['writemessage'] = "Output not modified.";
		header('Location: ./m.modify_entry.php');
		exit;
	}
	if($_POST["out-s-color"] != 0 && $_POST["out-s-consistency"] == 0 || $_POST["out-s-color"] == 0 && $_POST["out-s-consistency"] != 0) {
		$_SESSION['writetype'] = 3;
		$_SESSION['writemessage'] = "Output not modified.";
		header('Location: ./m.modify_entry.php');
		exit;
	}
	if($_POST['NumberDiapers'] == 0) {
		$_SESSION['writetype'] = 3;
		$_SESSION['writemessage'] = "Output not modified.";
		header('Location: ./m.modify_entry.php');
		exit;;	
	}
	
	if($_POST['NumberDiapers'] != 0 && $_POST["out-s-color"] == 0 && $_POST["out-s-consistency"] == 0 && $_POST["out-u-color"] == 0 && $_POST["out-u-saturation"] == 0) {
		$_SESSION['writetype'] = 3;
		$_SESSION['writemessage'] = "Output not modified.";
		header('Location: ./m.modify_entry.php');
		exit;
	}
	
	
	$ucolor=$_POST['out-u-color'];
	$usat=$_POST["out-u-saturation"];
	$scolor=$_POST['out-s-color'];
	$scon=$_POST["out-s-consistency"];
	
	$query="UPDATE OutputEntry SET UrineColor = " . $ucolor . ", UrineSaturation = " . $usat . ", StoolColor = " . $scolor . ", StoolConsistency = " . $scon . ", NumberDiapers = " . $_POST['NumberDiapers'] . " WHERE EntryId = " . $_SESSION['writeid'] . ";";
	mysql_query($query);
	
	
	$_SESSION['writetype'] = 1;
		
	$_SESSION['writemessage'] = "Output modified. "   . "<a href=\"m.modify_entry.php\">Modify</a>" ;
	
	header('Location: ./m.add_entry.php');
	exit;

}

else if(isset($_POST['sup'])) {

if($_POST["sup-type"] == 0 || $_POST["sup-method"] == 0 || $_POST['TotalAmount'] == 0 || $_POST['NumberTimes'] == 0) {
	$_SESSION['writetype'] = 3;
	$_SESSION['writemessage'] = "Supplement not modified.";
	
	header('Location: ./m.modify_entry.php');
	exit;
}


$suptype=$_POST["sup-type"];
$supmethod=$_POST["sup-method"];

$query="Update SupplementEntry SET SupType = " . $suptype . ", SupMethod = " . $supmethod . ", TotalAmount = " . $_POST['TotalAmount'] . ", NumberTimes = " . $_POST['NumberTimes'] . " WHERE EntryId = " . $_SESSION['writeid'] . ";";
mysql_query($query);


$_SESSION['writemessage'] = "Supplement modified. " . "<a href=\"m.modify_entry.php\">Modify</a>";
$_SESSION['writetype'] = 1;

header('Location: ./m.add_entry.php');
exit;

}


else if(isset($_POST['breast'])) {


if(isset($_POST['pumping-method']) && $_POST['pumping-method'] == 4) {
	$_POST['pumping-amount'] = 0;
}

if($_POST['duration'] == 0 || $_POST['side'] == 0 || $_POST['latching'] == 0 || $_POST['pumping-method'] == 0 || $_POST['pumping-method'] != 4 && $_POST['pumping-amount'] == 0  || $_POST["infant-state"] == 0 || $_POST["maternal-problems"] == 0){
	$_SESSION['writetype'] = 3;
	$_SESSION['writemessage'] = "Breastfeeding not modified.";

	header('Location: ./m.modify_entry.php');
	exit;
}

$duration=$_POST['duration'];
$latching=$_POST["latching"];
$pumpingmethod=$_POST["pumping-method"];
$infantstate=$_POST["infant-state"];
$maternalproblems=$_POST["maternal-problems"];

$query="UPDATE BreastfeedEntry SET BreastfeedingDuration = " . $duration . ", Latching = " . $latching . ", PumpingMethod =" . $pumpingmethod . ", InfantState = " . $infantstate . ", MaternalProblems = " . $maternalproblems . ", Side = " . $_POST['side'] . ", PumpingAmount = " . $_POST['pumping-amount'] . " WHERE EntryId = " . $_SESSION['writeid'] . ";";
mysql_query($query);


$_SESSION['writetype'] = 1;
$_SESSION['writemessage'] = "Breastfeeding modified. " . "<a href=\"m.modify_entry.php\">Modify</a>";

header('Location: ./m.add_entry.php');
exit;
}

?>