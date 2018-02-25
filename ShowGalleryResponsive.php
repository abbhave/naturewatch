<?php session_start(); ?>
<?php include("phpFunction.php"); ?>	
<?php
error_reporting(E_ALL ^ E_DEPRECATED);
$link = mysql_connect('localhost', 'u802345539_alok', 'alokb123')
    or die('Could not connect: ' . mysql_error());

mysql_select_db('u802345539_alok') or die('Could not select database');

$category=$_GET['categ'];
$type=$_GET['type'];
$tagname=$_GET['TagName'];
$speciesname=$_GET['speciesname'];
//Get the value of pagenumber
//$pagenumber = $_GET['pagenum']-1;
$pagenumber = $_GET['pagenum'];
$locationname = $_GET['locationname'];
$action = $_GET['action'];

if($action == 'specieslist')
	$returnjson=getSpeciesList($type,$category);
else if($action == 'categoryalbum')
	$returnjson=getCategoryAlbum($type,$category);

function getSpeciesList($type,$category)
{
	$specieslist = array();
	$tagArray=getArrayforList($type,$category);
	asort($tagArray);
	$speciescount=1;
	foreach ($tagArray as $tags1)
	{
	  $specieslist[]=array('encodedname'=>urlencode($tags1),'name'=>$speciescount.'. '.$tags1);
	  $speciescount++;
	}
	return $specieslist;
}

function getCategoryAlbum($type,$category)
{
	$perPage=12;
	if($type == 'speciesname')
		$query = createQueryOnType($type,$category,$speciesname);
	else if($type == 'tagname')
		$query = createQueryOnType($type,$category,$tagname);
	else if($type == 'location')
		$query = createQueryOnType($type,$category,$locationname);
	else 
		$query = createQueryOnType($type,$category);
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	//Calculate the number of pages
	global $numpages;
	$numpages = ceil(mysql_num_rows($result)/$perPage);
	//echo 'Query='.$query;
	//seek a location depending on the value of page number
	//no pagenumber is considered to be 1
	$pagenumber=0;
	mysql_data_seek($result,$pagenumber*$perPage);
	$i=0;
	$title="";
	$arrimageids=array();
	//echo "<tr>";
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($i==$perPage)
		  break;
		$i++;
		//$url=$line['imagelink'];
		$imageurl=$line['imagelink'];
		$imageurl=str_ireplace("s144","s250",$imageurl);

		//array_push($arrimageids,$line['imageid']);
		$arrimageids[]=array('type'=>$type,'category'=>$category,'speciesname'=>$line['commonname'],'imageid'=>$line['imageid'],'specieslocation'=>$line['locationname'],'datetaken'=>$line['date'],'imageurl'=>$imageurl);
		//$url=str_ireplace("s144","s800",$url);
		//echo "<td align=\"center\">";
		//$title=$title."<td align=\"center\">";
		//$title=$title."<a style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadInfo('speciesname','".$line['category']."','".$line['commonname']."','1')\" onmouseover=\"showHand(this)\">".$line['commonname']."<br>".$line['date']."</a><br><a style=\"color:#FFAD00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadLocation('location','".$line['category']."','".$line['locationname']."','1')\" onmouseover=\"showHand(this)\">".$line['locationname']."</a><br>";
		//March 2010 adding tag
		$tagkeys=preg_split("/[\s,]/",$line['Tag']);
		//$title=$title."<span style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">Tags:</span>";
		foreach ($tagkeys as $tags) {
			//$title=$title."<a style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadTags('tagname','".$line['category']."','".$tags."','1')\" onmouseover=\"showHand(this)\">".$tags."</a> ";
		}
		//$title1="Name: <a style='color: #5e5eff;' onClick='loadInfo('speciesname','".$line['category']."','".$line['commonname']."','1')' onmouseover='showHand(this)'>".$line['commonname']."</a><br>Location: ".$line['locationname']."<br>Taken On: ".$line['date'];
		//$imageurl=$line['imagelink'];
		//$imageurl=str_ireplace("s144","s250",$imageurl);
		if($i == 0)
		{
			//echo "i is 0";
			//echo "<a href=\"ShowImage.php?imageid=".$line['imageid']."&title=".$line['commonname']."&categ=".$line['category']."\"><img border=\"0\" src=\"" . $imageurl . "\"/></a>";
		}
		else
		{
			$loadimageidx=$i-1;
			//echo "<a onClick=\"loadImage('abc','".$line['category']."','".$line['commonname']."','".$line['imageid']."','".$loadimageidx."')\" onmouseover=\"showHand(this)\" title=\"".$title1."\"><img border=\"0\" src=\"".$imageurl."\"/></a>";
		}
		//echo "</td>";
		if(($i % 4) == 0)
		{	
		  //echo "</tr><tr>$title</tr><tr>";
		  //$title="";
		}	
	}
	return $arrimageids;
}

echo json_encode($returnjson);
?>