<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>
<?php
  $link = mysqli_connect('mysql:3306','alokbhav_abbhave','alokb123');
  if(! $link ) {
      die('Could not connect: ' . mysqli_error());
  }
  $db = mysqli_select_db($link,$_GET['dbname']) or die('Could not connect to db: ' . mysqli_error());
  $dbname = $_GET['dbname'];
  $query = 'show tables' ;
  $result = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error());
  echo "Query successful\n";
  echo "<table>\n";
  while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	  echo "<tr>\n";
	  foreach ($line as $colval)
	    echo "\t<td><a href=ShowContents.php?dbname=$dbname&tablename=$colval>$colval</a></td>\n";
	  echo "</tr>\n";
  }
  echo "</table>\n";
?>
</body>
</html>
