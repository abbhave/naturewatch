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
  $tablename=$_GET['tablename'];
  $dbname=$_GET['dbname'];
  echo "<label>Db Name</label>\t<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />$dbname<br>";
  echo "<label>Table Name</label>\t<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>$tablename<br>";
  $query = "describe $tablename;";
  $heading = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error());
  echo "Query successful\n";
  echo "<table border=\"1\">\n";
  echo "<tr>\n";
  $i=0;
  $coltitle=array();
  while ($line = mysqli_fetch_array($heading, MYSQLI_NUM)) {
	  $coltitle[]=$line[0];
	  $i++;
      echo "\t<td align=\"center\">$line[0]</td>";
  }
  echo "</tr>\n";

  $query = "select * from $tablename;";
  $result = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error());
  echo "<tr>\n";
  echo "<form action=\"${tablename}Add.php\" method=\"post\">\n";
  echo "<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />";
  echo "<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>";
  $line = mysqli_fetch_array($result, MYSQLI_NUM);
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
  }while ($line = mysqli_fetch_array($result, MYSQLI_NUM)) ;
  echo "</table>\n";
?>
</body>
</html>
