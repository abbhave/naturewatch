<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	$link = mysqli_connect('mysql:3306','alokbhav_abbhave','alokb123')
    	or die('Could not connect: ' . mysql_error());

	mysqli_select_db('alokbhav_abbhave') or die('Could not select database');
	
	$query="select LocationName,GPSLocation,LocationDescription from locationinfo";
	
	$jsonresp = "{\"locations\":[";
	$count=0;
	$queryResult = mysqli_query($link,$query) or die('Query failed: ' . mysql_error());
	while($locationlist = mysqli_fetch_array($queryResult, MYSQLI_BOTH)){  
			if($count != 0) $jsonresp.=",";
			$locationname=$locationlist['LocationName'];
			$gpslocation=$locationlist['GPSLocation'];
			$locationdescription=$locationlist['LocationDescription'];			
			$jsonresp.="{\"Name\":\"".$locationname."\",\"position\":\"".$gpslocation."\",\"Description\":\"".$locationdescription."\",\"Categ\":[";			
			$count=1;
			//$querycateg="select category from ImageInfo where 'LocationName'='".$locationlist['LocationName']."'";
			$querycateg="select DISTINCT(Category) from ImageInfo where LocationName='".$locationname."'";
			$queryCategResult = mysqli_query($link,$querycateg) or die('Query failed: ' . mysql_error())."'";
			$countcateg=0;
			while($categorylist = mysqli_fetch_array($queryCategResult, MYSQLI_BOTH)){
				if($countcateg != 0) $jsonresp.=",";
				$jsonresp.="\"".$categorylist['Category']."\"";
				$countcateg=1;
			}
			$jsonresp.="]}";
	}
	$jsonresp.="]}";
	echo $jsonresp;
?>
