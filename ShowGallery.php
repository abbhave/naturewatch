<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<script>
function setTitle(title)
{
	alert("In showgallery settitle "+title);
}
</script>
<body>
<!--<?php
//No of photos per page
$perPage=12;

	if($_GET['type'] == 'gallery')
		{
			echo "'".$_GET['categ']." Gallery";
		}
		else if($_GET['type'] == 'speciesname')
		{
			echo "'".$_GET['speciesname']." Gallery";
		}
		else if($_GET['type'] == 'tagname')
		{
			echo "'".$_GET['TagName']." Gallery";
			$perPage=500;
		}
?>-->

<?php include("phpFunction.php"); ?>	
<!--span align="center" width=100% style="color: #fee19e;"-->
<!--<span style='position:absolute;z-index:15;left:10px;top:170px;width:100%;'>-->
<div id="galleryDiv" style='position:relative;z-index:15;width:100%;'>
<div id="imagedialog" title="Image Dialog"><div id="ImageContent"></div></div>
<center>Click on the <font color="#FFAD00">thumbnails</font> to view images OR to view the species info click on the species name OR to view pics by location or tag wise click on location or tag</center>
<!--/span-->
<!--span style='position:absolute;z-index:15;
left:160px;top:200px;width:950px;height:800px;'-->
<table onload="setTitle('Yrdy2')" border="0" cellspacing="4" cellpadding="4" width="100%">
<script>$( "#imagedialog" ).dialog({ autoOpen: false });</script>
<?php
$link = mysql_connect('mysql://mysql:3306/', 'alokbhav_abbhave', 'alokb123')
    or die('Could not connect: ' . mysql_error());

mysql_select_db('alokbhav_natureinfo') or die('Could not select database');

$category=$_GET['categ'];
$type=$_GET['type'];
$tagname=$_GET['TagName'];
$speciesname=$_GET['speciesname'];
//Get the value of pagenumber
$pagenumber = $_GET['pagenum']-1;
$locationname = $_GET['locationname'];
//Add Back and Goto in a div
echo "<div style=\"color: #ffff00; float:left;width:2%\">";
//save in history the current URL
if(isset($_GET['back']))
{
	$_SESSION['count']-=1;
	//echo "In if Session Count = ".$_SESSION['count'];
}
else
{
	$cur_add= $_SERVER['REQUEST_URI'];
	$_SESSION['count']+=1;
	//echo "In else Session Count = ".$_SESSION['count'];
	$_SESSION['pageadd'][$_SESSION['count']]=$cur_add;
	//echo "In else ".$_SERVER['PHP_SELF'];
}

if($_SESSION['count'] > 0)
{
	$prev_url=$_SESSION['pageadd'][$_SESSION['count']-1]."&back=1";
	echo "<a class=\"backhref\" style=\"color: rgb(250, 73, 5);\" onClick=\"loadURL('maindiv','".$prev_url."','document.title')\" onmouseover=\"showHand(this)\">Back</a>";
}

echo "</div><div align=\"right\" style=\"float:left;color: #ffff00;width:50%;\"><a style=\"color: rgb(250, 73, 5);\" href=\"/index.php\">Home</a>";

//Display the combo box with list of tags or birds
echo "</div><div align=\"right\" style=\"float:right;color: #ffff00;width:20%;\">Go To";
//echo "Go To";
if($type == 'gallery' || $type == 'speciesname')
{
	echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadInfo('speciesname','".$category."',this.options[this.selectedIndex].value,'1')>";
}
else if($type == 'tagname')
{
	echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadTags('".$type."','".$category."',this.options[this.selectedIndex].value,'1')>";
}
else if($type == 'location')
{
	echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadLocation('".$type."','".$category."',this.options[this.selectedIndex].value,'1')>";
}



echo "<option value= />";
$tagArray=getArrayforList($type,$category);
asort($tagArray);
//print_r($tagArray);
$speciescount=1;
foreach ($tagArray as $tags1)
{
  echo "<option value=\"".urlencode($tags1)."\">".$speciescount.". ".$tags1."</option>";
  $speciescount++;
}
echo "</select>";
/*if($type == 'gallery' || $type == 'speciesname')
{
	$("#speciestagname").value=$_GET['speciesname'];
}
else if($type == 'tagname')
{
	$("#speciestagname").value=$_GET['TagName'];
}*/
echo "</div>";

// Performing SQL query to populate gallery

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
//echo "perPage=".$perPage;
//echo "<br/>";
//echo "numofrow=".mysql_num_rows($result);
//echo "<br/>";
//echo "numpages=".$numpages;
//echo "<br/>";

//seek a location depending on the value of page number
//no pagenumber is considered to be 1
mysql_data_seek($result,$pagenumber*$perPage);
$i=0;
$title="";
$arrimageids=array();
echo "<tr>";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($i==$perPage)
	  break;
	$i++;
	$url=$line['imagelink'];
	array_push($arrimageids,$line['imageid']);
	$url=str_ireplace("s144","s800",$url);
	echo "<td align=\"center\">";
	//Oct 2013 adding ajax support
	//echo "<a href=\"$line[10]\"><img border=\"0\" src=\"$line[10]\" width=\"180\"/></a>";
	$title=$title."<td align=\"center\">";
	//$title=$title."<a href=\"$url\" style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a>";
	//$title=$title."<a href='/ShowBirdInfo.php?categ=".$category."&birdname=".$line['commonname']."' style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a><br>";
	//$title=$title."<a style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadInfo("$category,$line['commonname']")\" onmouseover=\"showHand(this)\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a><br>";
	//$title=$title."<a style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadInfo('speciesname','".$category."','".$line['commonname']."','1')\" onmouseover=\"showHand(this)\">".$line['commonname']."<br>".$line['locationname']."<br>".$line['date']."</a><br>";
	$title=$title."<a style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadInfo('speciesname','".$line['category']."','".$line['commonname']."','1')\" onmouseover=\"showHand(this)\">".$line['commonname']."<br>".$line['date']."</a><br><a style=\"color:#FFAD00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadLocation('location','".$line['category']."','".$line['locationname']."','1')\" onmouseover=\"showHand(this)\">".$line['locationname']."</a><br>";
	//March 2010 adding tag
	$tagkeys=preg_split("/[\s,]/",$line['Tag']);
	$title=$title."<span style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">Tags:</span>";
	foreach ($tagkeys as $tags) {
	  //$title=$title."<a href='/ShowTagGallery.php?categ=".$category."&TagName=".$tags."' style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\">".$tags."</a> ";
	  //$title=$title."<a style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadTags('tagname','".$category."','".$tags."','1')\" onmouseover=\"showHand(this)\">".$tags."</a> ";
	  $title=$title."<a style=\"color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;\" onClick=\"loadTags('tagname','".$line['category']."','".$tags."','1')\" onmouseover=\"showHand(this)\">".$tags."</a> ";
    }
	//$title1="Name: <a style='color: #5e5eff;' onClick='loadInfo('speciesname','".$category."','".$line['commonname']."','1')' onmouseover='showHand(this)'>".$line['commonname']."</a><br>Location: ".$line['locationname']."<br>Taken On: ".$line['date'];
	$title1="Name: <a style='color: #5e5eff;' onClick='loadInfo('speciesname','".$line['category']."','".$line['commonname']."','1')' onmouseover='showHand(this)'>".$line['commonname']."</a><br>Location: ".$line['locationname']."<br>Taken On: ".$line['date'];
	//$title1="Name: <a href='/ShowBirdInfo.php?categ=".$category."&birdname=".$line['commonname']."' style='color: #5e5eff;'>".$line['commonname']."</a><br>Location: ".$line['locationname']."<br>Taken On: ".$line['date'];
	$imageurl=$line['imagelink'];
	$imageurl=str_ireplace("s144","s250",$imageurl);
	if($i == 0)
	{
		echo "i is 0";
		//echo "<a href=\"ShowImage.php?imageid=".$line['imageid']."&title=".$line['commonname']."&categ=".$category."\"><img border=\"0\" src=\"" . $line['imagelink'] . "\"/></a>";
		echo "<a href=\"ShowImage.php?imageid=".$line['imageid']."&title=".$line['commonname']."&categ=".$line['category']."\"><img border=\"0\" src=\"" . $imageurl . "\"/></a>";
	}
	else
	{
		$loadimageidx=$i-1;
		//echo "<a onClick=\"loadImage('abc','".$category."','".$line['commonname']."','".$line['imageid']."','".$loadimageidx."')\" onmouseover=\"showHand(this)\" title=\"".$title1."\"><img border=\"0\" src=\"".$line['imagelink']."\"/></a>";
		echo "<a onClick=\"loadImage('abc','".$line['category']."','".$line['commonname']."','".$line['imageid']."','".$loadimageidx."')\" onmouseover=\"showHand(this)\" title=\"".$title1."\"><img border=\"0\" src=\"".$imageurl."\"/></a>";
	}
	echo "</td>";
	if(($i % 4) == 0)
	{	
	  echo "</tr><tr>$title</tr><tr>";
	  $title="";
    }	
}
echo "</tr><tr>$title</tr>";
$_SESSION['arrimageidkey']=$arrimageids;
?>
</table>
<?php
$currpage=$_GET['pagenum'];
$prevpage = $currpage-1;
//echo "<div style=\"color: #ffff00; float:left;width:2%\">";
//echo "</div><div align=\"right\" style=\"float:right;color: #FF8500;width:50%;\">Pages";
echo "<div align=\"right\" style=\"float:right;color: #FF8500;width:50%;\">";
$isPrevPagesComboSet=0;
if($currpage > 1)
{
	if($_GET['type'] == 'gallery')
		echo "<a id=\"PrevPage\" onClick=\"loadGallery('".$type."','".$category."',$prevpage)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> Prev </a>";
	else if($_GET['type'] == 'tagname')
		echo "<a id=\"PrevPage\" onClick=\"loadTags('".$type."','".$category."','".$tagname."',$prevpage)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> Prev </a>";
	else if($_GET['type'] == 'location')
		echo "<a id=\"PrevPage\" onClick=\"loadLocation('".$type."','".$category."','".$locationname."',$prevpage)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> Prev </a>";
	for($j=$currpage-1;$j>0;$j--)
	{
		if($isPrevPagesComboSet==0)
		{
			if($_GET['type'] == 'gallery')
				echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadGallery('".$type."','".$category."',this.options[this.selectedIndex].value)>";
			else if($_GET['type'] == 'tagname')
				echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadTags('".$type."','".$category."','".$tagname."',this.options[this.selectedIndex].value)>";
			else if($_GET['type'] == 'location')			
				echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadLocation('".$type."','".$category."','".$locationname."',this.options[this.selectedIndex].value)>";	
		}
		echo "<option value=\"".urlencode($j)."\">".$j."</option>";		
		$isPrevPagesComboSet=1;
	}
	if($isPrevPagesComboSet==1)
		echo "</select>";	
		
}
?>
<!--<div style="color: #FF7A00;" align="right">Pages-->

<?php
//for($i = 1; $i <= $numpages; $i++){
$isPagesComboSet=0;
for($i = $currpage; $i <= $numpages; $i++){
	if($i > $currpage+5)
	{
		if($isPagesComboSet == 0)
		{
			if($_GET['type'] == 'gallery')
				echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadGallery('".$type."','".$category."',this.options[this.selectedIndex].value)>";
			else if($_GET['type'] == 'tagname')
				echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadTags('".$type."','".$category."','".$tagname."',this.options[this.selectedIndex].value)>";
			else if($_GET['type'] == 'location')			
				echo "\t<select id=\"speciestagname\" name=\"LocationId\" onchange=loadLocation('".$type."','".$category."','".$locationname."',this.options[this.selectedIndex].value)>";
		}
		echo "<option value=\"".urlencode($i)."\">".$i."</option>";		
		$isPagesComboSet=1;	
		continue;	
	}
	if($_GET['pagenum'] == $i)
		echo "$i";
	else
	{
		//$category=$_GET['categ'];
		if($_GET['type'] == 'gallery')
			echo "<a id=\"Page".$i."\" onClick=\"loadGallery('".$type."','".$category."',$i)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> $i </a>";
		else if($_GET['type'] == 'tagname')
			echo "<a id=\"Page".$i."\" onClick=\"loadTags('".$type."','".$category."','".$tagname."',$i)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> $i </a>";
		else if($_GET['type'] == 'location')
			echo "<a id=\"Page".$i."\" onClick=\"loadLocation('".$type."','".$category."','".$locationname."',$i)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> $i </a>";			
	}
}
if($isPagesComboSet==1)
	echo "</select>";
$nextpage=$_GET['pagenum']+1;
if($currpage < $numpages)
{
	if($_GET['type'] == 'gallery')
		echo "<a id=\"NextPage\" onClick=\"loadGallery('".$type."','".$category."',$nextpage)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> Next </a>";	
	else if($_GET['type'] == 'tagname')
		echo "<a id=\"NextPage\" onClick=\"loadTags('".$type."','".$category."','".$tagname."',$nextpage)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> Next </a>";
	else if($_GET['type'] == 'location')
		echo "<a id=\"NextPage\" onClick=\"loadLocation('".$type."','".$category."','".$locationname."',$nextpage)\" onmouseover=\"showHand(this)\" style=\"color: #ffff00;\"> Next </a>";	
}
?>
</div>
</span>
</body>
</html>
