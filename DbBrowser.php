<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>
<?php
  $link = mysqli_connect('mysql:3306','alokbhav_abbhave','alokb123') or die('Could no connect: ' . mysql_error());
  echo "connected to db\n";
  $query = 'show databases' ;
  $db = mysqli_query($link,$query) or die('Query failed: ' . mysql_error());
  echo "Query successful\n";
  echo "<table>\n";
  while ($line = mysqli_fetch_array($db, MYSQLI_ASSOC)) {
	  echo "<tr>\n";
	  foreach ($line as $colval)
	    echo "\t<td><a href=ShowTables.php?dbname=$colval>$colval</a></td>\n";
	  echo "</tr>\n";
  }
  echo "</table>\n";
?>
</body>
</html>
