<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>
<?php
  $link = mysql_connect('localhost','alokbhav_abbhave','alokb123') or die('Could no connect: ' . mysql_error());
  $db = mysql_select_db('alokbhav_natureinfo') or die('Could not connect to db: ' . mysql_error());
  $tablename='ImageInfo';
  $idTable=$_GET['idTable'];
  //Jan 2010 for adding tags
  $tagTable=$_GET['tagTable'];
  $dbname='alokbhav_natureinfo';
  echo "<label>Db Name</label>\t<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />$dbname<br>";
  echo "<label>Table Name</label>\t<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>$tablename<br>";
  $query = "describe $tablename;";
  $heading = mysql_query($query) or die('Query failed: ' . mysql_error());
  echo "Query successful\n";
  echo "<table name=\"ImageTable\" id=\"ImageTable\"border=\"1\">\n";
  echo "<tr>\n";
  $i=0;
  $coltitle=array();
  //if($idTable=='CommonBirdInfo')
  //  $entityIdCol='CommonName';
  //else
    $entityIdCol='CommonName';
  $idNameQuery="select CommonName from $idTable;";
  $idNameResult = mysql_query($idNameQuery) or die('Query failed: ' . mysql_error());

  $idLocationQuery="select LocationName from locationinfo;";
  $idLocationResult = mysql_query($idLocationQuery) or die('Query failed: ' . mysql_error());
  //echo "\t<td align=\"center\">CommonName</td>";
  
  //image tag query Jan 2010
  $idTagQuery="select TagName from $tagTable order by TagName;";
  $idTagResult = mysql_query($idTagQuery) or die('Query failed: ' . mysql_error());

  while ($line = mysql_fetch_array($heading, MYSQL_NUM)) {
	  $coltitle[]=$line[0];
	  $i++;
      echo "\t<td align=\"center\">$line[0]</td>";
  }
  echo "</tr>\n";

  //change the query to just get the blank tag rows Jan 29 2010
  if($_GET['Rows']=='All')
  {
	  $query = "select * from $tablename;";
  }
  else
  {
	  $query = "select * from $tablename where Tag IS NULL;";
  }
  $result = mysql_query($query) or die('Query failed: ' . mysql_error());
  echo "<tr name=\"add\">\n";
  $nameidhash=array();
  //echo "<td name=\"CommonName\"><select onchange=\"javascript:fillId(this,this.options[this.selectedIndex].value)\" name=\"CommonName\">";
  //echo "<td name=\"CommonName\"><select onchange=\"javascript:fillId(this,$nameidhash[this.selectedIndex])\" name=\"CommonName\">";

  echo "<form action=\"${tablename}Add.php\" method=\"post\">\n";
  echo "<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />";
  echo "<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>";
  $line = mysql_fetch_array($result, MYSQL_NUM);
  $cnt=count($line);
  echo "count without tag $cnt";
  for($j=0;$j < $i;$j++)
    if($coltitle[$j] == 'CommonName')
    {
	  echo "\t<td name=\"CommonName\"><select name=\"$entityIdCol\">";
	  $count=0;
	  while($idArr = mysql_fetch_array($idNameResult, MYSQL_BOTH)){  
		  $commonname=$idArr['CommonName'];
		  $entityid=$idArr[$entityIdCol];
		  echo $idArr[$entityIdCol];
	      echo "<option value=\"".$entityid."\">".$commonname."</option>";
	      $count++;
	  }
	  echo "</select></td>";
    }
    else if($coltitle[$j] == 'LocationName')
    {
	  echo "\t<td name=\"LocationName\"><select name=\"LocationName\">";
	  $count=0;
	  while($idLocArr = mysql_fetch_array($idLocationResult, MYSQL_BOTH)){  
		  $locationname=$idLocArr['LocationName'];
		  $locationid=$idLocArr['LocationName'];
		  echo $idLocArr['LocationName'];
	      echo "<option value=\"".$locationid."\">".$locationname."</option>";
	      $count++;
	  }
	  echo "</select></td>";
    }
    //condition to fill imagetag column Jan 2010
    else if($coltitle[$j] == 'Tag')
    {
	  echo "\t<td name=\"Tag\"><select name=\"Tag\">";
	  $count=0;
	  //add a blank option Jan 29 2010
	  echo "<option value=\"\"></option>";
	  while($idLocArr = mysql_fetch_array($idTagResult, MYSQL_BOTH)){  
		  $tagname=$idLocArr['TagName'];
		  $tagid=$idLocArr['TagName'];
		  echo $idLocArr['TagName'];
	      echo "<option value=\"".$tagid."\">".$tagname."</option>";
	      $count++;
	  }
	  echo "</select></td>";
    }
    else
      echo "\t<td name =\"$coltitle[$j]\" align=\"center\"><input type=\"text\" name=\"$coltitle[$j]\" value=\"\"/></td>";
  echo "<td><input type=\"submit\" name=\"Submit\" value=\"Add\" /></td>";
  echo "</form>\n";
  echo "</tr>\n";
  do{
	  echo "<tr>\n";
	  //First col should be blank
	  //echo "<td></td>";
      echo "<form action=\"EditTagImageInfo.php\" method=\"post\">\n";
      echo "<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />";
      echo "<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>";
	  $i=0;
      foreach ($line as $col_value) {
        echo "\t<td align=\"center\"><input type=\"text\" name=\"$coltitle[$i]\" value=\"$col_value\"/></td>";
	    if($coltitle[$i] == 'Tag')
	    {
		  echo "\t<td name=\"TagName\"><select name=\"TagName\">";
		  $count=0;
		  //add a blank option Jan 29 2010
		  echo "<option value=\"\"></option>";
		  mysql_data_seek($idTagResult,0) ;
		  while($idLocArr = mysql_fetch_array($idTagResult, MYSQL_BOTH)){  
			  $tagname=$idLocArr['TagName'];
			  $tagid=$idLocArr['TagName'];
			  echo $idLocArr['TagName'];
		      echo "<option value=\"".$tagid."\">".$tagname."</option>";
		      $count++;
		  }
		  echo "</select></td>";
	    }
        $i++;
      }
      echo "<td><input type=\"submit\" name=\"Submit\" value=\"Edit\" /></td>";
      echo "</form>\n";
      echo "</tr>\n";
  }while ($line = mysql_fetch_array($result, MYSQL_NUM)) ;
  echo "</table>\n";
?>
<script type="text/javascript">
function fillId(combo,selectedValue) {
	alert("<?php echo $nameidhash[1]; ?>");
	alert(selectedValue);
	var table = document.getElementById("ImageTable");
	var tbrow = table.getElementsByTagName("tr");
	for(i=0 ; i < tbrow.length ; i++){
	  if(i==1){
	    var tbcol=tbrow[i].getElementsByTagName("td");
	    for(j=0;j<tbcol.length;j++)
	      if(j==1){
		      var cellInput=tbcol[1].getElementsByTagName("input");
		      cellInput[0].value=<?php $nameidhash['1'];?>;
        }
    }	
}
}
</script>
</body>
</html>
