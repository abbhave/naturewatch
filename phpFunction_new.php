<?php
	//function to get the table name from a category
	function getTableFromCateg($categ)
	{
		if($categ == 'Birds') $tablename='CommonBirdInfo';
		else if($categ == 'Mammals') $tablename='MammalInfo';
		else if($categ == 'Flora') $tablename='florainfo';
		else if($categ == 'ButtflyInsect') $tablename='buttflyinsectinfo';
		else if($categ == 'NaturalScape') $tablename='NaturalScape';
		else if($categ == 'Monuments') $tablename='Monuments';
		else $tablename='CommonBirdInfo';
		return $tablename;
	}
	
	//function to create query based on type for a category
	//'type' can have following values
	//	gallery		: 	to display pics of selected category like Birds, Mammals, etc
	//	speciesname	:	to display pics of selected species like Red Munia or Tiger etc
	//	tagname		:	to display pics of selected tag for a specific category
	//	familyname	:	(not yet implemented) to display pics of selected family like passerines or raptors or amphibians
	//'categ' is the category name like Birds or Mammals etc
	//'name' is additional details required type other than gallery like species name or tag name etc
	function createQueryOnType($type,$categ,$name='',$totalrecordsize=false)
	{
		//get table name from category
		$tablename = getTableFromCateg($categ);
		if($type == 'gallery')
		{			
			if($totalrecordsize==true)
				$query = 'SELECT count(c.commonname) from '.$tablename.' c,ImageInfo i,locationinfo l where c.commonname=i.commonname and i.locationname=l.locationname';
			else
				$query = 'SELECT c.commonname,imageid,imagelink,i.date,i.category,l.locationname,i.Tag from '.$tablename.' c,ImageInfo i,locationinfo l where c.commonname=i.commonname and i.locationname=l.locationname';
			$query.=' order by i.Date DESC,c.commonname DESC, imageid DESC';
		}
		else if($type == 'speciesname')
		{
			if($name == '' && $categ == 'Birds')
				$name='Red Munia';
			if($totalrecordsize==true)
				$query = 'SELECT count(commonname) FROM ImageInfo where commonname="' . $name . '"';
			else
				$query = 'SELECT commonname,imagelink,locationname,date,category,Tag,imageid FROM ImageInfo where commonname="' . $name . '"';
			$query.=' order by date DESC, imageid DESC';
		}
		else if($type == 'tagname')
		{
			if($name == '' && $categ == 'Birds')
				$name='Red Munia';
			//$query = 'SELECT c.commonname,imageid,imagelink,i.date,l.locationname,i.Tag from '.$tablename.' c,ImageInfo i,locationinfo l where c.commonname=i.commonname and i.locationname=l.locationname and i.Tag LIKE \'%'.$name.'%\'';
			if($totalrecordsize==true)
				$query = 'SELECT count(i.commonname) from ImageInfo i,locationinfo l where i.locationname=l.locationname and i.Tag LIKE \'%'.$name.'%\'';
			else
				$query = 'SELECT i.commonname,imageid,imagelink,i.date,i.category,l.locationname,i.Tag from ImageInfo i,locationinfo l where i.locationname=l.locationname and i.Tag LIKE \'%'.$name.'%\'';
			$query.=' order by i.Date DESC,i.commonname DESC, imageid DESC';
		}
		else if($type == 'location')
		{
			if($totalrecordsize==true)
				$query = 'SELECT count(commonname) FROM ImageInfo where locationname="'.$name.'"';
			else
				$query = 'SELECT commonname, imagelink,locationname,Tag,date,category,imageid FROM ImageInfo where locationname="'.$name.'"';
			$query.=' order by date DESC, imageid DESC';
		}
		return $query;
	}
	
	function getArrayforList($link,$type,$categ,$offset,$count)
	{
		$tablename = getTableFromCateg($categ);
		
		if($count != -1)
			$limitquery=" limit ".$offset.",".$count;
		if($type == 'gallery' || $type == 'speciesname' || $type == 'speciesalbum')
		{
			$query="select CommonName from ".$tablename;
			if($count != -1)
				$query.=$limitquery;			
			$queryResult = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error($link));
			$birdArray=array();
			while($birdList = mysqli_fetch_array($queryResult, MYSQLI_BOTH)){  
			  $birdName=$birdList['CommonName'];
			  array_push($birdArray,$birdName);
			}
			return $birdArray;
		}
		else if($type == 'tagname')
		{
			$query="select TagName from imagetag";
			if($count != -1)
				$query.=$limitquery;
			$queryResult = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error($link));
			
			$tagArray=array();
			while($tagList = mysqli_fetch_array($queryResult, MYSQLI_BOTH)){  
			  $tagName=$tagList[0];
			  $tagkeys=preg_split("/[\s,]/",$tagName);
			  foreach ($tagkeys as $tags) {
				if(!in_array($tags,$tagArray)){
				  array_push($tagArray,$tags);
				}
			  }
			}
			return $tagArray;
		}
		else if($type == 'location')
		{
			$query="select DISTINCT(LocationName) from ImageInfo";
			if($count != -1)
				$query.=$limitquery;
			$queryResult = mysqli_query($link,$query) or die('Query failed: ' . mysqli_error($link));
			$locationArray=array();
			while($locationList = mysqli_fetch_array($queryResult, MYSQLI_BOTH)){  
			  $locationName=$locationList['LocationName'];
			  //print("LocationName=".$locationName);
			  array_push($locationArray,$locationName);
			}
			return $locationArray;			
		}
	}
?>
