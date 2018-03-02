<?php session_start(); ?>
<?php include("phpFunction_new.php"); ?>	
<?php
error_reporting(E_ALL ^ E_DEPRECATED);
$link = mysqli_connect('mysql:3306','alokbhav_abbhave','alokb123');

if(! $link ) {
      die('Could not connect: ' . mysqli_error());
}

mysqli_select_db($link,'alokbhav_natureinfo');

$pagesize=12;
$speciespagesize=200;
$category=$_GET['categ'];
$type=$_GET['type'];
$tagname=$_GET['TagName'];
$speciesname=$_GET['speciesname'];
//Get the value of pagenumber
//$pagenumber = $_GET['pagenum']-1;
$pagenumber = $_GET['pagenum'];
$locationname = $_GET['locationname'];
$action = $_GET['action'];
//echo "Action=".$action;
//$returnjson="";
if($action == 'specieslist')
	$returnjson=getSpeciesList($link,$type,$category);
else if($action == 'allPicAlbum')
	$returnjson=getAllPicAlbum($link,$type,$category,$speciesname,$pagenumber,$pagesize);
else if($action == 'allPicAlbumSize')
	$returnjson=getAllPicAlbum($link,$type,$category,$speciesname,$pagenumber,$pagesize,true);
else if($action == 'speciesalbum')
	$returnjson=getSpeciesAlbum($link,$type,$category,'',$pagenumber,$speciespagesize);

function getSpeciesList($link,$type,$category,$offset=0,$count=-1)
{
	$specieslist = array();
	if($count==-1)
		$tagArray=getArrayforList($link,$type,$category,0,-1);
	else
		$tagArray=getArrayforList($link,$type,$category,$offset,$count);
	asort($tagArray);
	$speciescount=1;
	foreach ($tagArray as $tags1)
	{
	  $specieslist[]=array('encodedname'=>urlencode($tags1),'name'=>$speciescount.'. '.$tags1);
	  $speciescount++;
	}
	return $specieslist;
}

function getSpeciesListForAlbum($link,$type,$category,$offset=0,$count=-1)
{
	$specieslist = array();
	if($count==-1)
		$tagArray=getArrayforList($link,$type,$category,0,-1);
	else
		$tagArray=getArrayforList($link,$type,$category,$offset,$count);
	asort($tagArray);
	$speciescount=1;
	foreach ($tagArray as $tags1)
	{
	  $specieslist[]=array('name'=>$tags1);
	  $speciescount++;
	}
	return $specieslist;
}

function getAllPicAlbum($link,$type,$category,$speciesname,$pagenumber,$pagesize,$totalrecordsize=false)
{
	//$perPage=12;
	//echo "type=".$type."speciesname=".$speciesname;
	if($type == 'speciesname')
		$query = createQueryOnType($type,$category,$speciesname,$totalrecordsize);
	else if($type == 'tagname')
		$query = createQueryOnType($type,$category,$tagname,$totalrecordsize);
	else if($type == 'location')
		$query = createQueryOnType($type,$category,$locationname,$totalrecordsize);
	else 
		$query = createQueryOnType($type,$category,' ',$totalrecordsize);
	if($totalrecordsize==false)
	{
		$offset=($pagenumber-1)*$pagesize;
		$query.=" limit ".$offset.",".$pagesize;
	}		
	$result = mysqli_query($link,$query);
	if($totalrecordsize==true)
	{
		$line = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return $line;
	}
	//Calculate the number of pages
	//global $numpages;
	//$numpages = ceil(mysqli_num_rows($result)/$perPage);
	//echo 'Query='.$query;
	//seek a location depending on the value of page number
	//no pagenumber is considered to be 1
	//$pagenumber=0;
	//mysql_data_seek($result,$pagenumber*$perPage);
	//mysql_data_seek($result);
	$i=0;
	$title="";
	$arrimageids=array();
	//echo "<tr>";
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		//if($i==$perPage)
		  //break;
		$i++;
		$imageurl=$line['imagelink'];
		$imageurl=str_ireplace("s144","s250",$imageurl);

		$arrimageids[]=array('type'=>$type,'category'=>$category,'speciesname'=>$line['commonname'],'imageid'=>$line['imageid'],'specieslocation'=>$line['locationname'],'datetaken'=>$line['date'],'imageurl'=>$imageurl);
		$tagkeys=preg_split("/[\s,]/",$line['Tag']);
	}
	return $arrimageids;
}

function getSpeciesAlbum($link,$type,$category,$speciesname,$pagenumber,$pagesize,$totalrecordsize=false)
{
	/* if($type == 'speciesname')
		$query = createQueryOnType($type,$category,$speciesname,$totalrecordsize);
	else if($type == 'tagname')
		$query = createQueryOnType($type,$category,$tagname,$totalrecordsize);
	else if($type == 'location')
		$query = createQueryOnType($type,$category,$locationname,$totalrecordsize);
	else 
		$query = createQueryOnType($type,$category,' ',$totalrecordsize);  */
	//get species list
	//$specieslist = array();
	$specieslist[]=getSpeciesListForAlbum($link,$type,$category,($pagenumber-1)*$pagesize,$pagesize);
	
	//For each species get the first imageurl
	$species='';
	$arrimageids=array();
	foreach ($specieslist as $key => $species) {
		//create a query based on table on the image table to get the first url
		$tablename = getTableFromCateg($category);
		foreach ($species as $key => $species_name) {
			$query = 'SELECT commonname,imagelink,locationname,date,category,Tag,imageid FROM ImageInfo where commonname="' . $species_name['name'] . '"';
			$query.=' order by date DESC, imageid DESC LIMIT 1';
			$result = mysqli_query($link,$query);
			$i=0;
			$resArr = mysqli_fetch_array($result,MYSQLI_ASSOC)
			while ($resArr as $line_key=>$line ) {
				echo"Line=".$line;
				$i++;
				$imageurl=$line['imagelink'];
				$imageurl=str_ireplace("s144","s250",$imageurl);
				$imageid=$line['imageid'];
			}
			$arrimageids[]=array('type'=>$type,'category'=>$category,'speciesname'=>$species_name['name'],'imageid'=>$imageid,'imageurl'=>$imageurl);
		}
	}
	return $arrimageids;
}

echo json_encode($returnjson);
?>
