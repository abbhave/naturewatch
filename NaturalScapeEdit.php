<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Untitled</TITLE>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
<META content="MSHTML 6.00.6000.16711" name=GENERATOR></HEAD>
<BODY>
<?php
    $link = mysqli_connect('mysql:3306','alokbhav_abbhave','alokb123');
    if(! $link ) {
      die('Could not connect: ' . mysqli_error());
    }
    $db = mysqli_select_db($link,$_POST['dbname']) or die('Could not connect to db: ' . mysqli_error());
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
  $whrcnd=$_POST['Name'];
  $query=$query. " where $tablename.Name=\"$whrcnd\"";
  echo "<br>$query<br>";
  $result = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error());
  echo "Query Successful<br>";
  print_r($result);
  /*while ($line = mysqli_fetch_array($result, MYSQLI_NUM)) {
	  print_r($line);
  } */ 
?>
</BODY></HTML>

