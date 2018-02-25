<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title id="pageTitle">
      Welcome to Nature Watch
    </title>
    <style>
	.areaselect {
		-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
		-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
		box-shadow:inset 0px 1px 0px 0px #ffffff;
		background-color:#ededed;
		-webkit-border-top-left-radius:6px;
		-moz-border-radius-topleft:6px;
		border-top-left-radius:6px;
		-webkit-border-top-right-radius:6px;
		-moz-border-radius-topright:6px;
		border-top-right-radius:6px;
		-webkit-border-bottom-right-radius:6px;
		-moz-border-radius-bottomright:6px;
		border-bottom-right-radius:6px;
		-webkit-border-bottom-left-radius:6px;
		-moz-border-radius-bottomleft:6px;
		border-bottom-left-radius:6px;
		text-indent:0;
		border:1px solid #dcdcdc;
		display:inline-block;
		color:#777777;
		font-family:arial;
		font-size:15px;
		font-weight:bold;
		font-style:normal;
		height:20px;		
		line-height:20px;
		width:120px;
		text-decoration:none;
		text-align:center;
		text-shadow:1px 1px 0px #ffffff;
	}
	.areaselect:hover {
		background-color:#dfdfdf;
	}
	.areaselect:active {
		position:relative;
		top:1px;
	}
    .mainmenu
    {
      border-bottom:1px solid white;
      border-right:1px solid white;
      padding-bottom: 10px;
      padding-top: 10px;
      padding-right: 10px;
    }
    .photostrip
    {
      //border:3px solid beige;
    }
    .PhotoStripTitle
    {
    	font-weight: 400;
    	color: #09F7E4;
    	font-size: 20px;
    }
    .photostrip1
    {
    	width:100px;
    	height:119px;
    	font-size:20px;
    }
    </style>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxq-llJI2gtIi9JPn1NuKXrr8tCjkcMoc&sensor=false"></script>   
    <script type="text/javascript" src="javascript/infobox.js"></script>   
	<script>
		var openinfowindow;
		$(document).keydown(function(e) {
		var keyCode = e.keyCode || e.which;
		var imagedlgexist=false;
		if( $( "#imagedialog" ).hasClass("ui-dialog-content"))
		    if($( "#imagedialog" ).dialog("isOpen"))    
		    	imagedlgexist=true;      
		switch(keyCode) {
		        case 37: // left
		        
		        if(imagedlgexist==true)
		        {
		        	//alert("Prev Image");
		        	$("#PrevImage").click();
		        }
		        else
		        {		        
		        	if($("#PrevPage").length)
		        	{		        
		                	//alert("Prev Page");
			        	$("#PrevPage").click();			        	
			        }
			        else if($(".backhref").length)
			        {
			        	//alert("Back Href");
			        	$(".backhref").click();
			        }
		        }
		        break;

		        case 39: // right
		        
		        if(imagedlgexist==true)
		        {
		        	//alert("Next Image");
		        	$("#NextImage").click();
		        }
		        else
		        {
		        	//alert("Next Page");
		        	$("#NextPage").click();
		        }
		        break;

		        default: return; // exit this handler for other keys
		}
		e.preventDefault(); // prevent the default action (scroll / move caret)
		});				

		$(document).mousedown(function(e) {
		var targetelem = $(e.target);		
		if( $( "#imagedialog" ).hasClass("ui-dialog-content"))
		    if($( "#imagedialog" ).dialog("isOpen"))  
		    	if(targetelem.is(".ui-widget-overlay"))  
			    	$( "#imagedialog" ).dialog("close")      
		});	
		
		function makeBrowserFullscreen()
		{
			alert("Make browser full screen");
			docElement = document.documentElement;
			request = docElement.requestFullScreen || docElement.webkitRequestFullScreen || docElement.mozRequestFullScreen || docElement.msRequestFullScreen;
			if(typeof request!="undefined" && request){
        			request.call(docElement);
    			}
		}
		
		function loadGallery(type,categ,pagenum)
		{
			//alert("In loadGallery");
			divName="maindiv";
			url="ShowGallery.php?type="+type+"&categ="+categ+"&pagenum="+pagenum;
			title=categ+" Gallery";
			loadURL(divName,url,title);
		}
		
		function loadInfo(type,categ,name,pagenum)
		{
			//alert("In loadInfo");
			divName="maindiv";
			//url="/ShowBirdInfo.php?categ="+categ+"&birdname="+name;
			url="/ShowGallery.php?type="+type+"&categ="+categ+"&speciesname="+name+"&pagenum="+pagenum;
			//alert("LoadInfo URL="+url);
			title=name+" Gallery";
			if( $( "#imagedialog" ).hasClass("ui-dialog-content"))
			  if($( "#imagedialog" ).dialog("isOpen"))
				$( "#imagedialog" ).dialog("close");
			loadURL(divName,url,title);
		}
		
		function loadTags(type,categ,tagname,pagenum)
		{
			//alert("In loadTags");
			divName="maindiv";
			//url="/ShowTagGallery.php?categ="+categ+"&TagName="+tagname;
			url="/ShowGallery.php?type="+type+"&categ="+categ+"&TagName="+tagname+"&pagenum="+pagenum;
			title=tagname+" Gallery";
			loadURL(divName,url,title);
		}

		function loadLocation(type,categ,locationname,pagenum)
		{
			//alert("In loadLocation");
			//alert("LocationName="+locationname);
			divName="maindiv";
			//url="/ShowTagGallery.php?categ="+categ+"&TagName="+tagname;
			url="/ShowGallery.php?type="+type+"&categ="+categ+"&locationname="+locationname+"&pagenum="+pagenum;
			//alert("URL="+url);
			title=locationname+" Gallery";
			loadURL(divName,url,title);
		}

		function loadImage(fortype,categ,speciesname,imageid,imageididx)
		{
			//alert("In loadImage "+imageididx+"***");
			docElement = document.documentElement;
			//request = docElement.requestFullScreen || docElement.webkitRequestFullScreen || docElement.mozRequestFullScreen || docElement.msRequestFullScreen;
			//if(typeof request!="undefined" && request){
        			//request.call(docElement);
    			//}

			divName="maindiv";
			//url="/ShowTagGallery.php?categ="+categ+"&TagName="+tagname;
			url="/ShowImage.php?fortype="+fortype+"&categ="+categ+"&title="+speciesname+"&imageid="+imageid+"&imageididx="+imageididx;
			//alert("LoadImage URL="+url);
			title=speciesname;
			$( "#imagedialog" ).dialog({ autoOpen: false });
			$( "#imagedialog" ).dialog({ modal : true });
			$( "#imagedialog" ).dialog({ resizable : false });
			$( "#imagedialog" ).dialog( {position: {my:"top", at:"top", of:window}} );
			$('.ui-dialog').css("padding",0);
			$('.ui-dialog-content').css("padding",0);
			$( "#imagedialog").dialog("option","width",900);
			$( "#imagedialog" ).css("background-color","#000000");			
			$( "#imagedialog" ).prev('.ui-dialog-titlebar').remove();
			$( "#imagedialog" ).dialog( "open" );

			loadURL("ImageContent",url,title);
		}
		
		function loadURL(div,url,title)
		{
			//alert("In loadURL");
			//alert("URL to load"+url);
			//alert("Title to set "+title);
			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				document.getElementById(div).innerHTML=xmlhttp.responseText;
				//document.getElementById("pageTitle").innerHTML=title;
				}
			  }
			xmlhttp.open("GET",url,true);
			xmlhttp.send();
		}
		
		function loadURLReturningJSON(url,callbackfunc)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				callbackfunc(xmlhttp);
				//document.getElementById("pageTitle").innerHTML=title;
				}
			  }
			xmlhttp.open("GET",url,true);
			xmlhttp.send();			
		}
		
		function setTitle(title)
		{
			//alert("In setTitle "+title+" **");
			document.getElementById("pageTitle").innerHTML=title;
		}

		function showHand(obj)
		{
			obj.style.cursor="pointer";
		}
		
		var map=null;
	        function initialize() {
	          //alert("Inside initialize");
	          var mapOptions = {
	            center: new google.maps.LatLng(23.240000, 77.400000),
	            zoom: 4
	          };
	          var mainpageheight=$('.mainpage').css('height');
	          map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);
	          $('#map-canvas').css('height',mainpageheight);
	          loadURLReturningJSON("getGPSLocation.php",function(data) {
	          	var jsonresp = eval ("(" + data.responseText + ")");	          	
	          	//alert(jsonresp.locations[41].Name+" "+jsonresp.locations[41].position+" "+jsonresp.locations[41].Categ);
	          	var i=0;
	          		/*var posarray = jsonresp.locations[0].position.split(",");
	          		var myLatlng1 = new google.maps.LatLng(posarray[0],posarray[1]);
	          		var marker1 = new google.maps.Marker({
		      		position: myLatlng1,
		      			map: map,
		      			title: "Hello"
		  		});*/		  		
	          	for(key in jsonresp.locations){
	          		//alert("Name "+jsonresp.locations[i].Name+" position "+jsonresp.locations[i].Categ);	          		
	          		var posarray = jsonresp.locations[i].position.split(",");
	          		var myLatlng = new google.maps.LatLng(posarray[0],posarray[1]);
	          		var marker = new google.maps.Marker({
		      			position: myLatlng,
		      			map: map,
		      			title:jsonresp.locations[i].Name
		  		});
				attachInfo(map,marker,jsonresp.locations[i]);		          	
		  		i++;		  		
	          	}	          		          
	          });
	        }		
	        function attachInfo(map, marker, locationjsonobj) {
	        	var title = marker.getTitle();	        		        	
	        	var contentstring="<a onClick=\"loadLocation('location','"+locationjsonobj.Categ[0]+"','"+title+"','1')\" onmouseover=\"showHand(this)\">"+"<p style=\"color:black;\">"+title+"<br/>"+locationjsonobj.Description+"<br/>";
	        	var j=0;	        	
	        	/*for(key in locationjsonobj.Categ)
	        	{	        	
	        		//contentstring+="<a href='/ShowGallery.php?type=\"location\"&categ=\""+locationjsonobj.Categ[j]+"\"&pagenum=\"1\"&locationname=\""+title+"\"'>"+locationjsonobj.Categ[j]+"</a>";
	        		contentstring+="<a onClick=\"loadLocation('location','"+locationjsonobj.Categ[j]+"','"+title+"','1')\" onmouseover=\"showHand(this)\">"+locationjsonobj.Categ[j]+"</a>";
	        		j++;
		        }*/
		        contentstring+="</p></a>";
			var infowindow = new InfoBox({
			      content: contentstring
			      ,opacity: 1
			      ,boxStyle: {
                   		border: "1px solid black"
                   		,background: "#09F7E4"
                  		,textAlign: "center"
                  		,fontSize: "8pt"
                  		,width: "250px"
                 		}
			});
	        	google.maps.event.addListener(marker, 'click', function() {
				//alert("Marker clicked");
				if(openinfowindow != null)
					openinfowindow.close();
				infowindow.open(map,marker);
				openinfowindow=infowindow;
				closeInfoWindow(map,infowindow);
			});
			
		}
		function closeInfoWindow(map,infowindow) {
			google.maps.event.addListener(map, 'click', function() {
       			        infowindow.close();
    			});		  		
		}
		function zoomArea(areatozoom) {
			if(areatozoom == "West"){
			var latLng = new google.maps.LatLng(18.4600708, 74.3637085);
			map.setCenter(latLng);
			map.setZoom(8);
			}							
			else if(areatozoom == "NE"){
			var latLng = new google.maps.LatLng(26.790378, 91.511270);
			map.setCenter(latLng);
			map.setZoom(6);
			}							
			else if(areatozoom == "South"){
			var latLng = new google.maps.LatLng(13.3415202, 77.0999908);
			map.setCenter(latLng);
			map.setZoom(6);
			}							
			else if(areatozoom == "Center"){
			var latLng = new google.maps.LatLng(23.1675075, 79.9454498);
			map.setCenter(latLng);
			map.setZoom(6);
			}							
			else if(areatozoom == "North"){
			var latLng = new google.maps.LatLng(30.9004572, 75.8571625);
			map.setCenter(latLng);
			map.setZoom(6);
			}							
			else if(areatozoom == "Restore"){
			var latLng = new google.maps.LatLng(23.240000, 77.400000);
			map.setCenter(latLng);
			map.setZoom(4);
			}							
		}
	</script>
  </head>
  <!-- Background white, links blue (unvisited), navy (visited), red (active) -->
  <body bgcolor="#292929" text="#FFFFFF" link="#0000FF" vlink="#000080" alink="#FF0000" onload="initialize()">
  <?php 
  	$_SESSION['count']=-1;
  	//include("header.php");
  ?>	
    <!--<p align="center">
      <img alt="PhotoStrip (337K)" src="/images/PhotoStrip.jpg" width='100%'/>
    </p>-->
    <table width='100%'>
    <tr style='color:yellow'>
    <td class='photostrip1' align="center">
    	<font color="#FDFDC0">Nature's Beatuty</font>    	
    </td>
    <td class='photostrip'>
      <a style="color: #ffff00;" onClick="loadGallery('gallery','Birds','1')" onmouseover="showHand(this)"><div class="PhotoStripTitle" style="position:absolute;">Birds</div><div><img src="https://lh6.googleusercontent.com/-e_YP6h0AFNE/UETv68xGzYI/AAAAAAAAOWw/FBDX3UOg8Uo/s144/DSC_0149.jpg" width='100%'/></div></a>
    </td>
    <td class='photostrip'>
      <a style="color: #ffff00;" onClick="loadGallery('gallery','NaturalScape','1')" onmouseover="showHand(this)"><div class="PhotoStripTitle" style="position:absolute;">Nature</div><div><img src="https://lh4.googleusercontent.com/-uCbhlBpYor4/TnS7bzAs5HI/AAAAAAAAJRA/m99noN1eTZw/s144/DSC_0160.jpg" width='100%'/></div></a>
    </td>
    <td class='photostrip'>
      <a style="color: #ffff00;" onClick="loadGallery('gallery','Mammals','1')" onmouseover="showHand(this)"><div class="PhotoStripTitle" style="position:absolute;">Mammals</div><div><img src="https://lh3.googleusercontent.com/-jXVoVYa-mgc/TnjeJyiXbLI/AAAAAAAAJwQ/Uq_4iAQrIwI/s144/DSC_0189.jpg" width='100%'/></div></a>
    </td>
    <td class='photostrip'>
      <a style="color: #ffff00;" onClick="loadGallery('gallery','ButtflyInsect','1')" onmouseover="showHand(this)"><div class="PhotoStripTitle" style="position:absolute;">Insects</div><div><img src="https://lh4.googleusercontent.com/-dsuSzEQGGM4/TgoLHkl3kdI/AAAAAAAAJFU/d7pobk7KBBU/s144/BlueTigerLifeCycle.jpg" width='100%'/></div></a>
    </td>
    <td class='photostrip'>
      <a style="color: #ffff00;" onClick="loadGallery('gallery','Flora','1')" onmouseover="showHand(this)"><div class="PhotoStripTitle"  style="position:absolute;">Flora</div><div><img src="https://lh4.googleusercontent.com/-wDbxjFZW00w/UlGb4AEa5CI/AAAAAAAASsg/6W8CzJYu_J8/s144/DSC_0034.jpg" width='100%'/></div></a>
    </td>
    <td class='photostrip'>
      <a style="color: #ffff00;" onClick="loadGallery('gallery','Monuments','1')" onmouseover="showHand(this)"><div class="PhotoStripTitle" style="position:absolute;">Monuments</div><div><img src="https://lh6.googleusercontent.com/-vBAb4ExZIlo/TwHJXIyk3SI/AAAAAAAAL0k/ehNMo32xxoE/s144/DSC_0174_mod.jpg" width='100%'/></div></a>
    </td>
    <td class='photostrip'>
      <img src="https://lh4.googleusercontent.com/-5TGanmnLj9k/UlGdIQTlbvI/AAAAAAAASvg/rVbSrCLzzKI/s144/DSC_0123.jpg" width='100%'/>
    </td>
    <td class='photostrip'>
      <img src="https://lh3.googleusercontent.com/-k9rdm9CTdfA/UgPwv6DMNXI/AAAAAAAAR8Q/KzYfSWXoM7I/s144/DSC_0106.jpg" width='100%'/>
    </td>
    <td class='photostrip1' align="center">        
    	<font><a href="http://alokbhave.blogspot.com" style="color:#FDFDC0" onclick="window.open(this.href);return false;">My Blog</a></font>
    </td>    
    </tr>
    </table>
    <hr width="100%" size="1" style="background-color: #555555;"/>
    <!--<table cellspacing="#000005" align="left" width='10%'>
    <tr>
    <td width='100%' style='vertical-align:top'>
	<table>
	<tr>
	        <td class="mainmenu" align='center' width='16%'>
	          <a href="/index.php" style="color: #ffff00;">Home</a>
	        </td>
        </tr>
        <tr>
	        <td class="mainmenu" align='center' width='16%'>
	          <a style="color: #ffff00;" onClick="loadGallery('gallery','Birds','1')" onmouseover="showHand(this)"><u>Birds<u></a>
	        </td>
	</tr>
	<tr>
	        <td class="mainmenu" align='center' width='16%'>
	          <a style="color: #ffff00;" onClick="loadGallery('gallery','Mammals','1')" onmouseover="showHand(this)"><u>Mammals</u></a>
	        </td>
	</tr>
	<tr>
	        <td class="mainmenu" align='center' width='20%'>
	          <a style="color: #ffff00;" onClick="loadGallery('gallery','ButtflyInsect','1')" onmouseover="showHand(this)"><u>Butterflies and Insects</u></a>
	        </td>
	</tr>
	<tr>
	        <td class="mainmenu" align='center' width='16%'>
	          <a style="color: #ffff00;" onClick="loadGallery('gallery','Flora','1')" onmouseover="showHand(this)"><u>Flora</u></a>
	        </td>
	</tr>
	<tr>
	        <td class="mainmenu" align='center' width='16%'>
	          <a href="/UnderConstruction.php" style="color: #ffff00;">Blog</a>
	        </td>
	</tr>
	</table>
	</td>
	</tr>
	</table>-->
	<table width='100%'><tr><td width='85%'>
    <div id="maindiv" width='100%' style='z-index:15;left:10px;top:170px;'>
	 <table width='100%' cellpadding=5>
	 <tr><center>Latest: <a style="color:#ffff00;text-decoration:none;font-family:verdana, sans-serif;font-size:11px;" onClick="loadTags('tagname','Birds','Dandeli 2016','1')" onmouseover="showHand(this)">Dandeli 2016 Bird Pics</a>. Click on the pics above to view category wise pics or on the marker to view the details of the location and then click on the box to view pics from this location</center></tr>
	 <tr>	
	  <td class="mainpage" width='60%' style='vertical-align:top'>
		<span style='width:100%'>
		<!--<p style='font-size:10.0pt;font-family:"Georgia";'><span lang=en-US style='language:en-US'>This site has been created with the main intention of sharing photographs and information I have gained over the years about birds, trees, butterflies & insects and others. I would be uploading interesting facts about these as I learn them.<br/>
		The blog section contains the reports of the trips with information and photo which is currently under construction</span></p>-->
		</span>		
		<span style='width:100%'>
		<!--<img width="100%" src="http://lh6.ggpht.com/alok.bhave/SITX0b_8n_I/AAAAAAAABhk/U_hhXBwPr08/s800/WireTailedSwallow_web.jpg"/>-->
		<!--<img width='100%' src="/images/WireTailedSwallow_web_crop.jpg"/>-->
		<div id="map-canvas" width="100%"></div>
		</span>		
	  </td>
	  <td class="mainpage" width='15%' style='vertical-align:top'>	  
	  	<a href="#" class="areaselect" onClick="zoomArea('West')"> West </a><br/><br/>
	  	<a href="#" class="areaselect" onClick="zoomArea('NE')"> North East </a><br/><br/>
	  	<a href="#" class="areaselect" onClick="zoomArea('South')"> South </a><br/><br/>
	  	<a href="#" class="areaselect" onClick="zoomArea('Center')"> Central </a><br/><br/>
	  	<a href="#" class="areaselect" onClick="zoomArea('North')"> North </a><br/><br/>
	  	<a href="#" class="areaselect" onClick="zoomArea('Restore')"> CompleteMap </a><br/><br/>
	  </td>
	  <td class="mainpage" width='20%' style='vertical-align:top'>    
		<span style='width=20%'>
		<center>
		<img width="80%" src="/images/image3611.jpg">
		</center>
		</span>
		<span style='width:20%'>		
		<p style='color:#ff5b15;font-size:14.0pt;'><span lang=en-US style='language:en-US'>Alok Bhave</span></p>
		<p style='color:#ff5b15;font-size:10.0pt;'><span lang=en-US style='language:en-US'>Interested in nature in general. Specially interested in stuying and observing birds. Recently started taking interest in plants, butterflies, insects, Reptiles, etc.</span></p>
		<p style='color:#ff5b15;font-size:10.0pt;'><span lang=en-US style='language:en-US'>I also try to document these using my camera. Some photos are taken with my old kit.</span></p>
		<p style='color:#ff5b15;font-size:10.0pt;'><span lang=en-US style='language:en-US'>Camera: Nikon D200 Lens: Sigma 150-500 OS HSM (since May 2009)</span></p>
		<p style='color:#ff5b15;font-size:10.0pt;'><span lang=en-US style='language:en-US'>Older Lens: Tamron 70-300 with Macro (since Nov 2007)</span></p>
		<p style='color:#ff5b15;font-size:10.0pt;'><span lang=en-US style='language:en-US'>Old Kit: Panasonic FZ20 inbuilt Leica lens</span></p>
		</span>
	  </td>
	  </tr></table>
	</div>
	</td></tr></table>
	<?php include("footer.php"); ?>
  </body>
</html>