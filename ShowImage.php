<?php session_start(); ?>
//<body onload="makeBrowserFullscreen();">
<body>
<?php
include("phpFunction.php"); 
$link = mysqli_connect('mysql:3306','alokbhav_abbhave','alokb123');
if(! $link ) {
      die('Could not connect: ' . mysqli_error());
}
mysqli_select_db($link,'alokbhav_natureinfo');

//save in history the current URL


// Performing SQL query
$category=$_GET['categ'];
$imageid=$_GET['imageid'];
$tablename='ImageInfo';
$fortype=$_GET['fortype'];
$loadimageidx=$_GET['imageididx'];
//echo "<div style=\"color:yellow\">ImageID=".$imageid."</div><br/>";

$arrimageids=$_SESSION['arrimageidkey'];

$query = 'SELECT ImageId,imagelink,locationname,date,commonname from '.$tablename.' where ImageId=' .$imageid;
//$query.=$tablename;
$result = mysqli_query($link,$query);
$count1=mysqli_num_rows($link,$result);
if($count1==0) die('No results in query: ' . mysqli_error());

$line = mysqli_fetch_array($result, MYSQL_ASSOC);
$url=$line['imagelink'];
$url=str_ireplace("s144","s800",$url);
//$title="<td align=\"left\">";
//$title=$title."<a onClick=\"loadInfo('speciesname','".$category."','".$line['commonname']."','1')\" onmouseover=\"showHand(this)\" style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a><br>";

$title=$title."<a onClick=\"loadInfo('speciesname','".$category."','".$line['commonname']."','1')\" onmouseover=\"showHand(this)\" style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['commonname']."<font style=\"color:#F5F5A7\"> Taken At:</font>".$line['locationname']."<font style=\"color:#F5F5A7\"> Take On:</font>".$line['date']."</a><br/>";

$title1="Name: <a onClick=\"loadInfo('speciesname','".$category."','".$line['commonname']."','1')\" onmouseover=\"showHand(this)\" style='color: #5e5eff;'>".$title."</a><br>Location: ".$line['locationname']."<br>Taken On: ".$line['date'];
//echo "<center><img border=\"0\" src=\"".$url."\"/><center>";



if($loadimageidx != count($arrimageids)-1)
{

}

echo "<br/>";
//echo "<table onload=\"makeBrowserFullscreen();\" align=\"center\" width=\"100%\">";
echo "<table align=\"center\" width=\"100%\">";
echo "<tr>";

if($loadimageidx != 0)
    echo "<td/>";
    
echo "<td>".$title."</td>";

if($loadimageidx != count($arrimageids)-1)
    echo "<td/>";

echo "</tr>";
echo "<tr>";
//get the prev image id and show prev icon if loadimageid is not 0
if($loadimageidx != 0)
{
	$previmageididx=$loadimageidx-1;
	//get the prev image id from imageid array
	$previmageid=$arrimageids[$previmageididx];
	echo "<td id=\"PrevImage\" onmouseover=\"showHand(this)\" onClick=\"loadImage('dummyfor','".$category."','dummytitle','".$previmageid."','".$previmageididx."')\" align=\"center\" style=\"color:yellow\">Prev</td>";
}
	
echo "<td align=\"center\"><img align=\"center\" src=\"".$url."\" /></td>";

//get the next image id if loadimageidx is not equat to count of image ids array
if($loadimageidx != count($arrimageids)-1)
{
	$nextimageididx=$loadimageidx+1;
	//get next image id from imageids array
	$nextimageid=$arrimageids[$nextimageididx];
	echo "<td id=\"NextImage\" align=\"center\" onmouseover=\"showHand(this)\" onClick=\"loadImage('dummyfor','".$category."','dummytitle','".$nextimageid."','".$nextimageididx."')\" style=\"color:yellow\">Next</td>";
}
	
echo "</tr>";
echo "</table>";
//echo "<center style=\"padding:0.4em;\">$title</center>";
//echo $title;
?>
</body>
