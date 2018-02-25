<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
<title>Birds Gallery</title>
</head>
<body>
<?php include("header.php"); ?>	
<span style='z-index:15;left:10px;top:170px;width:100%;'>
<center>Click on the thumbnails to view images using <a style="color: #ffff00;" href="http://www.lokeshdhakar.com/projects/lightbox2/#overview">lightbox</a> or to view the bird specific info click on the title below</center>
<table border="0" cellspacing="4" cellpadding="4" width="100%">
<?php
$link = mysql_connect('localhost', 'alokbhav_abbhave', 'alokb123')
    or die('Could not connect: ' . mysql_error());

mysql_select_db('alokbhav_natureinfo') or die('Could not select database');

// Performing SQL query
$category=$_GET['categ'];
if($_GET['categ'] == 'Birds') $tablename='CommonBirdInfo';
else if($_GET['categ'] == 'Flora') $tablename='florainfo';
else if($_GET['categ'] == 'ButtflyInsect') $tablename='buttflyinsectinfo';
else $tablename='CommonBirdInfo';

//Display the combo box with list of birds
echo "<div style=\"color: #ffff00;\" align=\"right\">Go To";
echo "\t<select name=\"LocationId\" onchange=GotoLink(this.options[this.selectedIndex].value)>";
$birdNamesQuery="select CommonName from ".$tablename.";";
$birdNamesResult = mysql_query($birdNamesQuery) or die('Query failed: ' . mysql_error());
echo "<option value= />";
while($birdList = mysql_fetch_array($birdNamesResult, MYSQL_BOTH)){  
  $displayName=$birdList['CommonName'];
  echo "<option value=\"ShowBirdInfo.php?categ=".$category."&birdname=".urlencode($displayName)."\">".$displayName."</option>";
}
echo "<option value=\"ShowGallery.php?categ=".$category."&pagenum=1\">Gallery</option>";
echo "</select></div><br><br>";

//Start the table
//echo "<div align=\"center\">";
//echo "<table border=\"1\" cellspacing=\"4\" cellpadding=\"4\" width=\"700\">";
//echo "<table border=\"0\" cellspacing=\"4\" cellpadding=\"4\">";
echo "<tr>";
//get the bird name
$birdName = urldecode($_GET['birdname']);
//If bird name is blank then always start with Red Munia
if($birdName=="")
  $birdName="Red Munia";
  
$query = 'SELECT CommonName,ImageLink,LocationName,Date,Tag,imageid FROM ImageInfo where CommonName="' . $birdName . '" order by Date, imageid';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

$title="";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	//print_r($line);
	//echo "BirdId " . $line['BirdId'];
	$i++;
	$url=$line['ImageLink'];
	//echo "ImageLink is " .$url;
	$url=str_ireplace("s144","s800",$url);
	echo "<td align=\"center\">";
	//echo "<a href=\"$line[10]\"><img border=\"0\" src=\"$line[10]\" width=\"180\"/></a>";
	$title=$title."<td align=\"center\">";
	//$title=$title."<a href=\"$url\" style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a>";
	$title=$title."<a href='ShowBirdInfo.php?categ=".$category."&birdname=".$line['CommonName']."' style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['CommonName']."<br>".$line['LocationName']."<br>".$line['Date']."</a><br>";
	//March 2010 adding tag
	$tagkeys=preg_split("/[\s,]/",$line['Tag']);
	$title=$title."<span style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">Tags:</span>";
	foreach ($tagkeys as $tags) {
	  $title=$title."<a href='ShowTagGallery.php?categ=".$category."&TagName=".$tags."' style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$tags."</a> ";
    }
	$title1="Name: <a href='ShowBirdInfo.php?categ=".$category."&birdname=".$line['CommonName']."' style='color: #5e5eff;'>".$line['CommonName']."</a><br>Location: ".$line['LocationName']."<br>Taken On: ".$line['Date'];
	echo "<a href=\"ShowImage.php?imageid=".$line['imageid']."&title=".$line['CommonName']."&categ=".$category."\" title=\"".$title1."\"><img border=\"0\" src=\"".$line['ImageLink']."\"/></a>";
	echo "</td>";
	if(($i % 6) == 0)
	{
	  echo "</tr><tr>$title</tr><tr>";
	  $title="";
    }
}
echo "</tr><tr>$title</tr>";
?>
<!--/div-->
</table>
</span>
<script type="text/javascript">
function GotoLink(selectedValue) {
	window.location.href=selectedValue;
}
</script>
</body>
</html>