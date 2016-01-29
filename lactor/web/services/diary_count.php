<?php

require("../includes/general.php");
loggedIn();

require("../includes/db.php");
db_connect();

include_once('access_control.php');

$breasfeed_query = 
  "SELECT d.mid, DATE(EntryDate) AS date, COUNT(*) AS value 
     FROM Diary d 
     LEFT JOIN BreastfeedEntry b ON d.EntryId=b.EntryId 
     WHERE mid=".$_SESSION['mid']."
       AND Latching IS NOT NULL 
       AND EntryDate > TIMESTAMPADD(DAY, -30, CURDATE()) 
       AND EntryType = 1
     GROUP BY date 
     ORDER BY date;";


$pumping_query = 
  "SELECT d.mid, DATE(EntryDate) AS date, COUNT(*) AS value 
     FROM Diary d 
     LEFT JOIN BreastfeedEntry b ON d.EntryId=b.EntryId 
     WHERE mid=".$_SESSION['mid']."
       AND PumpingMethod IS NOT NULL 
       AND EntryDate > TIMESTAMPADD(DAY, -30, CURDATE()) 
       AND EntryType = 1
     GROUP BY date 
     ORDER BY date;";

$supplement_query =
  "SELECT d.mid, DATE(EntryDate) AS date, SUM(NumberTimes) AS value 
     FROM Diary d 
     LEFT JOIN SupplementEntry s ON d.EntryId=s.EntryId 
     WHERE mid=".$_SESSION['mid']."
       AND EntryDate > TIMESTAMPADD(DAY, -30, CURDATE()) 
       AND EntryType = %d
     GROUP BY date 
     ORDER BY date;";

$weight_query =
  "SELECT mid, DATE(EntryDate) AS date, weight AS value
     FROM Weight w
     WHERE mid=".$_SESSION['mid']."
       AND EntryDate > TIMESTAMPADD(DAY, -30, CURDATE()) 
     GROUP BY date 
     ORDER BY date;";

$other_query =
  "SELECT d.mid, DATE(EntryDate) AS date, COUNT(*) AS value 
     FROM Diary d 
     WHERE mid=".$_SESSION['mid']."
       AND EntryDate > TIMESTAMPADD(DAY, -30, CURDATE()) 
       AND EntryType = %d
     GROUP BY date 
     ORDER BY date;";

switch($_REQUEST['type']) {
  case "breastfeeding":
    $query = $breasfeed_query;
    break;
  case "pumping":
    $query = $pumping_query;
    break;
  case "supplement":
    $query = sprintf($supplement_query, ENTRYTYPE_SUPPLEMENT);
    break;
  case "output":
    $query = sprintf($other_query, ENTRYTYPE_OUTPUT);
    break;
  case "health":
    $query = sprintf($other_query, ENTRYTYPE_HEALTH);
    break;
  case "weight":
    $query = $weight_query;
  default:
    break;
}

if (isset($_REQUEST['callback'])) {
  $callback = $_REQUEST['callback'];
  // jsonp request
  $jsonp = true;
  header('Content-Type: text/javascript');
} else {
  $callback = false;
  $jsonp = false;
  header('Content-Type: application/json');
}

$result = mysql_query($query);

if ($jsonp) 
  echo $callback . '(';

if (!$result) {
  error_log(mysql_error());
  echo '{"message": "There was an error processing your request", '.
       '"error": "There was an error processing your request"}';
} else {

  $values = [];
  while($row = mysql_fetch_assoc($result)) {
    $values[$row['date']] = $row['value'];
  }

  $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 30;
  echo utf8_encode(sprintf ('{ "title":"%s, '._("last %d days").'", ', _(ucfirst($_REQUEST['type'])), $length));
  $first = true;
  echo '"dates":[';
  for ($i=$length-1; $i >= 0 ; $i--) {
    if ($first) {
      $first = false;
    } else {
      echo ",";
    }
    $date = date("m/d", strtotime(sprintf("-%d days", $i)));
    printf('[ %d, "%s"]', $length - $i, $date);
  }
  $first = true;
  echo '], "count":[';
  for ($i=$length-1; $i >= 0 ; $i--) {
    if ($first) {
      $first = false;
    } else {
      echo ",";
    }
    $date = date("Y-m-d", strtotime(sprintf("-%d days", $i)));
    if ($_REQUEST['type'] == 'weight') {
      printf('%f', @$values[$date]/16);
    } else {
      printf('%d', @$values[$date]);
    }
  }
  echo "]}";
}
if ($jsonp) 
  echo ')';
?>
