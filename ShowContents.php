<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>
<?php
  $link = mysql_connect('localhost','alokbhav_abbhave','alokb123') or die('Could no connect: ' . mysql_error());
  $db = mysql_select_db($_GET['dbname']) or die('Could not connect to db: ' . mysql_error());
  $tablename=$_GET['tablename'];
  $dbname=$_GET['dbname'];
  echo "<label>Db Name</label>\t<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />$dbname<br>";
  echo "<label>Table Name</label>\t<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>$tablename<br>";
  $query = "describe $tablename;";
  $heading = mysql_query($query) or die('Query failed: ' . mysql_error());
  echo "Query successful\n";
  echo "<table border=\"1\">\n";
  echo "<tr>\n";
  $i=0;
  $coltitle=array();
  while ($line = mysql_fetch_array($heading, MYSQL_NUM)) {
	  $coltitle[]=$line[0];
	  $i++;
      echo "\t<td align=\"center\">$line[0]</td>";
  }
  echo "</tr>\n";

  $query = "select * from $tablename;";
  $result = mysql_query($query) or die('Query failed: ' . mysql_error());
  echo "<tr>\n";
  echo "<form action=\"${tablename}Add.php\" method=\"post\">\n";
  echo "<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />";
  echo "<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>";
  $line = mysql_fetch_array($result, MYSQL_NUM);
  $cnt=count($line);
  echo $cnt;
  for($j=0;$j < $i;$j++)
    echo "\t<td align=\"center\"><input type=\"text\" name=\"$coltitle[$j]\" value=\"\"/></td>";
  echo "<td><input type=\"submit\" name=\"Submit\" value=\"Add\" /></td>";
  echo "</form>\n";
  echo "</tr>\n";
  do{
	  echo "<tr>\n";
      echo "<form action=\"${tablename}Edit.php\" method=\"post\">\n";
      echo "<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />";
      echo "<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>";
	  $i=0;
      foreach ($line as $col_value) {
        echo "\t<td align=\"center\"><input type=\"text\" name=\"$coltitle[$i]\" value=\"$col_value\"/></td>";
        $i++;
      }
      echo "<td><input type=\"submit\" name=\"Submit\" value=\"Edit\" /></td>";
      echo "</form>\n";
      echo "</tr>\n";
  }while ($line = mysql_fetch_array($result, MYSQL_NUM)) ;
  echo "</table>\n";
?>
</body>
</html>
