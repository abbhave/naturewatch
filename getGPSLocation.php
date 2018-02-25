<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	$link = mysql_connect('localhost', 'u802345539_alok', 'alokb123')
    	or die('Could not connect: ' . mysql_error());

	mysql_select_db('u802345539_alok') or die('Could not select database');
	
	$query="select LocationName,GPSLocation,LocationDescription from locationinfo";
	
	$jsonresp = "{\"locations\":[";
	$count=0;
	$queryResult = mysql_query($query) or die('Query failed: ' . mysql_error());
	while($locationlist = mysql_fetch_array($queryResult, MYSQL_BOTH)){  
			if($count != 0) $jsonresp.=",";
			$locationname=$locationlist['LocationName'];
			$gpslocation=$locationlist['GPSLocation'];
			$locationdescription=$locationlist['LocationDescription'];			
			$jsonresp.="{\"Name\":\"".$locationname."\",\"position\":\"".$gpslocation."\",\"Description\":\"".$locationdescription."\",\"Categ\":[";			
			$count=1;
			//$querycateg="select category from ImageInfo where 'LocationName'='".$locationlist['LocationName']."'";
			$querycateg="select DISTINCT(Category) from ImageInfo where LocationName='".$locationname."'";
			$queryCategResult = mysql_query($querycateg) or die('Query failed: ' . mysql_error())."'";
			$countcateg=0;
			while($categorylist = mysql_fetch_array($queryCategResult, MYSQL_BOTH)){
				if($countcateg != 0) $jsonresp.=",";
				$jsonresp.="\"".$categorylist['Category']."\"";
				$countcateg=1;
			}
			$jsonresp.="]}";
	}
	$jsonresp.="]}";
	echo $jsonresp;
?>