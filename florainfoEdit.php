<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Untitled</TITLE>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
<META content="MSHTML 6.00.6000.16711" name=GENERATOR></HEAD>
<BODY>
<?php
    $link = mysql_connect('localhost','alokbhav_abbhave','alokb123') or die('Could no connect: ' . mysql_error());
    $db = mysql_select_db($_POST['dbname']) or die('Could not connect to db: ' . mysql_error());
    $tablename=$_POST['tablename'];
    $query = "UPDATE $tablename SET ";
    $i=0;
	foreach($_POST as $key => $value) {
		if(!($key=="Submit" || $key=="dbname" || $key=="tablename")){
	      echo "<label>$key</label><input type=\"text\" name=\"$key\" value=\"$value\"/>";
	      if($i==0)
	        $query=$query. "$key=\"$value\"";
	      else
	      	$query=$query. ",$key=\"$value\"";
	      $i=1;
      }
  }
  $whrcnd=$_POST['CommonName'];
  $query=$query. " where $tablename.CommonName=\"$whrcnd\"";
  echo "<br>$query<br>";
  $result = mysql_query($query) or die('Query failed: ' . mysql_error());
  echo "Query Successful<br>";
  print_r($result);
  /*while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
	  print_r($line);
  } */ 
?>
</BODY></HTML>