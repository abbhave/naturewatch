<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
<title><?php 
if($_GET['categ']=='Birds')
  echo "Birds";
else if($_GET['categ']=='Flora')
  echo "Flowers and trees";
else if ($_GET['categ']=='ButtflyInsect')
  echo "Butterfly and Insect";
else
  echo "Birds";
?>	Gallery</title>
</head>
<body>
<!-- Add this page to show galley by the tag name provided Jan 2010-->
<?php include("header.php"); ?>	
<span style='z-index:15;left:10px;top:170px;width:100%;'>
<center>Click on the thumbnails to view images using <a style="color: #ffff00;" href="http://www.lokeshdhakar.com/projects/lightbox2/#overview">lightbox</a> or to view the bird specific info click on the title below</center>
<table border="0" cellspacing="4" cellpadding="4">

<?php
$link = mysql_connect('localhost', 'alokbhav_abbhave', 'alokb123')
    or die('Could not connect: ' . mysql_error());

mysql_select_db('alokbhav_natureinfo') or die('Could not select database');

// Performing SQL query
$category=$_GET['categ'];
if($category == 'Birds') $tablename='CommonBirdInfo';
else if($category == 'Flora') $tablename='florainfo';
else if($category == 'ButtflyInsect') $tablename='buttflyinsectinfo';
else if($category == 'Mammals') $tablename='MammalInfo';
else $tablename='CommonBirdInfo';

$tagname=$_GET['TagName'];

//Display the combo box with list of birds
echo "<div style=\"color: #ffff00;\" align=\"right\">Go To";
echo "\t<select name=\"LocationId\" onchange=GotoLink(this.options[this.selectedIndex].value)>";
$tagNamesQuery="select TagName from imagetag;";
$tagNamesResult = mysql_query($tagNamesQuery) or die('Query failed: ' . mysql_error());
echo "<option value= />";
$tagArray=array();
while($tagList = mysql_fetch_array($tagNamesResult, MYSQL_BOTH)){  
  $tagName=$tagList['TagName'];
  $tagkeys=preg_split("/[\s,]/",$tagName);
  foreach ($tagkeys as $tags) {
	if(!in_array($tags,$tagArray)){
	  array_push($tagArray,$tags);
    }
  }
}
arsort($tagArray);
foreach ($tagArray as $tags1)
  echo "<option value=\"ShowTagGallery.php?categ=".$category."&TagName=".urlencode($tags1)."\">".$tags1."</option>";
echo "<option value=\"ShowGallery.php?categ=".$category."&pagenum=1\">Gallery</option>";
echo "</select></div><br><br>";

//Add check for tag Jan 2010
$query = 'SELECT c.commonname,imageid,imagelink,i.date,l.locationname,i.Tag from '.$tablename.' c,ImageInfo i,locationinfo l where c.commonname=i.commonname and i.locationname=l.locationname and i.Tag LIKE \'%'.$tagname.'%\'';
//$query.=$tablename;
$query.=' order by i.Date, imageid';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
/*
//Get the value of pagenumber
$pagenumber = $_GET['pagenum']-1;

//No of photos per page
$perPage=500;
//Calculate the number of pages
global $numpages;
$numpages = ceil(mysql_num_rows($result)/$perPage);
//echo "numofrows=$numpages";
*/
//seek a location depending on the value of page number
//no pagenumber is considered to be 1
//mysql_data_seek($result,$pagenumber*$perPage);
$i=0;
$title="";
//echo "<div align=\"center\">";
//echo "<table border=\"0\" cellspacing=\"4\" cellpadding=\"4\" width=\"700\"><tr>";
echo "<tr>";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	/*if($i==$perPage)
	  break;*/
	$i++;
	$url=$line['imagelink'];
	$url=str_ireplace("s144","s800",$url);
	echo "<td align=\"center\" width=\"200\">";
	//echo "<a href=\"$line[10]\"><img border=\"0\" src=\"$line[10]\" width=\"180\"/></a>";
	$title=$title."<td align=\"center\">";
	//$title=$title."<a href=\"$url\" style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a>";
	$title=$title."<a href='ShowBirdInfo.php?categ=".$category."&birdname=".$line['commonname']."' style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a><br>";
	//March 2010 adding tag
	$tagkeys=preg_split("/[\s,]/",$line['Tag']);
	$title=$title."<span style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">Tags:</span>";
	foreach ($tagkeys as $tags) {
	  $title=$title."<a href='ShowTagGallery.php?categ=".$category."&TagName=".$tags."' style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$tags."</a> ";
    }
    $title1="Name: <a href='ShowBirdInfo.php?categ=".$category."&birdname=".$line['commonname']."' style='color: #5e5eff;'>".$line['commonname']."</a><br>Location: ".$line['locationname']."<br>Taken On: ".$line['date'];
	if($i == 0)
	{
		echo "i is 0";
		echo "<a href=\"ShowImage.php?imageid=".$line['imageid']."&title=".$line['commonname']."&categ=".$category."\"><img border=\"0\" src=\"" . $line['imagelink'] . "\"/></a>";
	}
	else
		echo "<a href=\"ShowImage.php?imageid=".$line['imageid']."&title=".$line['commonname']."&categ=".$category."\" title=\"".$title1."\"><img border=\"0\" src=\"".$line['imagelink']."\"/></a>";
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
<div style="color: #ffff00;" align="right">Pages
<?php
for($i = 1; $i <= $numpages; $i++){
	if($_GET['pagenum'] == $i)
		echo "$i";
	else
	{
		$category=$_GET['categ'];
		echo "<a href=\"ShowTagGallery.php?categ=$category&pagenum=$i\" style=\"color: #ffff00;\"> $i </a>";
	}
}
?>
</div>
</span>
<script type="text/javascript">
function GotoLink(selectedValue) {
	window.location.href=selectedValue;
}
</script>
</body>
</html>