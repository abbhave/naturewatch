<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Admin Links</title>
</head>
<body>
<?php
  echo "<table>\n";
  echo "<tr>\n";
  echo "\t<td><a href=DbBrowser.php>Database Browser</a></td></tr>\n";
  echo "\t<td><a href=AddBirdImageInfo.php?idTable=CommonBirdInfo&tagTable=imagetag>Enter Bird ImageInfo</a></td></tr>\n";
  echo "\t<td><a href=AddBirdImageInfo.php?idTable=MammalInfo&tagTable=imagetag>Enter Mammal ImageInfo</a></td></tr>\n";
  echo "\t<td><a href=AddBirdImageInfo.php?idTable=florainfo&tagTable=imagetag>Enter Flora ImageInfo</a></td></tr>\n";
  echo "\t<td><a href=AddBirdImageInfo.php?idTable=buttflyinsectinfo&tagTable=imagetag>Enter Butterfly Insect ImageInfo</a></td></tr>\n";
  echo "\t<td><a href=AddBirdImageInfo.php?idTable=NaturalScape&tagTable=imagetag>Enter Natural Scape ImageInfo</a></td></tr>\n";  
  echo "\t<td><a href=AddBirdImageInfo.php?idTable=Monuments&tagTable=imagetag>Enter Monuments ImageInfo</a></td></tr>\n";    
  echo "\t<td><a href=ShowContents.php?dbname=alokbhav_natureinfo&tablename=imagetag>Add Tags</a></td></tr>\n";
  echo "\t<td><a href=AddTagImageInfo.php?idTable=CommonBirdInfo&tagTable=imagetag>Add Tags to Images</a></td></tr>\n";
  echo "</table>\n";
?>
</body>
</html>