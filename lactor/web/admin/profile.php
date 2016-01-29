<?php
	
	include_once("../includes/general.php");
	include_once("../includes/db.include.php");
	
	loggedIn();
	initialize();
	db_connect();
	loadVocabulary();

  $query = "SELECT name FROM Scientists where sid=".$_SESSION["sid"];
  $result = mysql_query($query);
  $row = mysql_fetch_assoc($result);
  $name = $row["name"]
	
?>


<head>
	<?php head_tag("Admin LACTOR - Dashboard"); ?>
  <style type="text/css">
    th { 
      background-color: #ddd;
    }
    td:first-child {
      background-color: #ddd;
    }
  </style>
</head>

<body>
	<div id="maincontainer">
	<?php page_header(); ?>
	<?php admin_menu(0); ?>
	
	
	<div id="pagecontent">
    <h1>Lactation consultant profile</h1>
    <form action="admin/post/profile.php" method="post">
      Name: <input type="text" name="name" value="<?php echo $name; ?>">
      <input type="submit" value="Update information">
    </form>

	
	<?php page_footer(); ?>
	</div>
	</body>
</html>
