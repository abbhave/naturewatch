<?php session_start(); ?>
<?php include("phpFunction.php"); ?>	
<?php
$link = mysql_connect('localhost', 'alokbhav_abbhave', 'alokb123')
    or die('Could not connect: ' . mysql_error());

mysql_select_db('alokbhav_natureinfo') or die('Could not select database');

$category=$_GET['categ'];
$type=$_GET['type'];
$tagname=$_GET['TagName'];
$speciesname=$_GET['speciesname'];
//Get the value of pagenumber
$pagenumber = $_GET['pagenum']-1;
$locationname = $_GET['locationname'];

$specieslist = array();

$tagArray=getArrayforList($type,$category);
asort($tagArray);
$speciescount=1;
foreach ($tagArray as $tags1)
{
  $specieslist[]=array('encodedname'=>urlencode($tags1),'name'=>$speciescount.'. '.$tags1);
  $speciescount++;
}
echo json_encode($specieslist);
?>