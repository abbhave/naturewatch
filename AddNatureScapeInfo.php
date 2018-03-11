<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Add Image Info</title>
</head>
<body>
<?php
  $link = mysqli_connect('mysql:3306','alokbhav_abbhave','alokb123');
  if(! $link ) {
      die('Could not connect: ' . mysqli_error());
  }
  $db = mysqli_select_db($link,'alokbhav_natureinfo') or die('Could not connect to db: ' . mysql_error());
  $tablename='ImageInfo';
  $idTable=$_GET['idTable'];
  //Jan 2010 for adding tags
  $tagTable=$_GET['tagTable'];
  $dbname='alokbhav_natureinfo';
  echo "<label>Db Name</label>\t<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />$dbname<br>";
  echo "<label>Table Name</label>\t<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>$tablename<br>";
  $query = "describe $tablename;";
  $heading = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error());
  echo "Query successful\n";
  echo "<table name=\"ImageTable\" id=\"ImageTable\"border=\"1\">\n";
  echo "<tr>\n";
  $i=0;
  $coltitle=array();
  //if($idTable=='commonbirdinfo')
  //  $entityIdCol='CommonName';
  //else
    $entityIdCol='CommonName';
  $idNameQuery="select CommonName from $idTable;";
  $idNameResult = mysqli_query($link,$idNameQuery) or die('Query failed: ' . mysqli_error());

  $idLocationQuery="select LocationName from locationinfo;";
  $idLocationResult = mysqli_query($link,$idLocationQuery) or die('Query failed: ' . mysqli_error());
  //echo "\t<td align=\"center\">CommonName</td>";
  
  //image tag query Jan 2010
  $idTagQuery="select TagName from $tagTable;";
  $idTagResult = mysqli_query($link,$idTagQuery) or die('Query failed: ' . mysqli_error());
  
  while ($line = mysqli_fetch_array($heading, MYSQLI_NUM)) {
	  $coltitle[]=$line[0];
	  $i++;
      echo "\t<td align=\"center\">$line[0]</td>";
  }
  echo "</tr>\n";

  $query = "select * from $tablename;";
  $result = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error());
  echo "<tr name=\"add\">\n";
  $nameidhash=array();
  //echo "<td name=\"CommonName\"><select onchange=\"javascript:fillId(this,this.options[this.selectedIndex].value)\" name=\"CommonName\">";
  //echo "<td name=\"CommonName\"><select onchange=\"javascript:fillId(this,$nameidhash[this.selectedIndex])\" name=\"CommonName\">";

  echo "<form action=\"${tablename}Add.php\" method=\"post\">\n";
  echo "<input type=\"hidden\" name=\"dbname\" value=\"$dbname\" />";
  echo "<input type=\"hidden\" name=\"tablename\" value=\"$tablename\"/>";
  $line = mysqli_fetch_array($result, MYSQLI_NUM);
  $cnt=count($line);
  echo $cnt;
  for($j=0;$j < $i;$j++)
    if($coltitle[$j] == 'CommonName')
    {
	  echo "\t<td name=\"CommonName\"><select name=\"$entityIdCol\">";
	  $count=0;
	  while($idArr = mysqli_fetch_array($idNameResult, MYSQLI_BOTH)){  
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
	  while($idLocArr = mysqli_fetch_array($idLocationResult, MYSQLI_BOTH)){  
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
	  while($idLocArr = mysqli_fetch_array($idTagResult, MYSQLI_BOTH)){  
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
  $allRows=$_GET['AllRows'];
  if ($allRows == 'True')
  {
	  do{
		  echo "<tr>\n";
		  //First col should be blank
		  //echo "<td></td>";
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
  }
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
