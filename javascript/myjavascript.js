	$(function(){
		$("#header").load("headernew.html"); 
		$("#footer").load("footer.html"); 
		console.log($('.nav a'));
		$('.nav a').on('click', function(){
			//$('.btn-navbar').click(); //bootstrap 2.x
			$('.navbar-toggle').click() //bootstrap 3.x by Richard
		});

	});
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

		function loadHTMLGallery(type,categ,name,pagenum)
		{
			//alert("In loadGallery");
			divName="maindiv";
			//url="ShowGalleryResponsive.html?type="+type+"&categ="+categ+"&pagenum="+pagenum;
			title=categ+" Gallery";
			//loadURL(divName,url,title);
			$("#maindiv").empty();
			$("#maindiv").load("ShowGalleryResponsive.html",function() {
				loadShowGalleryPage(type,categ,name,pagenum);
			}); 			
		}
		
		function loadShowGalleryPage(type,categ,name,pagenum) {
			/*var queryString = new Array();
			if (queryString.length == 0) {
				if (window.location.search.split('?').length > 1) {
					var params = window.location.search.split('?')[1].split('&');
					for (var i = 0; i < params.length; i++) {
						var key = params[i].split('=')[0];
						var value = decodeURIComponent(params[i].split('=')[1]);
						queryString[key] = value;
					}
				}
			}*/

			$("#albumtitle").text(categ+" Album");
			
			// grab our template code from the DOM
			var source   = $("#speciesselect-template").html();

			// compile the template so we can use it
			var templateSpeciesSelect = Handlebars.compile(source);

			// create some data
			var dataSelectText='{"type":"speciesname","category":"Birds","speciesname":"'+name+'"}';
			var dataSelect = JSON.parse(dataSelectText);

			// generate HTML from the data
			var htmlSelect    = templateSpeciesSelect(dataSelect);

			// add the HTML to the content div
			$('#speciesselect').html(htmlSelect);


			//get specieslist by doing a webservice call
			var speciesname='';
			var locationname='';
			var tagname='';
			if(type=='speciesname')
				speciesname=name;
			var showgalleryurl = "ShowGalleryResponsive.php?type=gallery&categ="+categ+"&action=specieslist&pagenum="+pagenum+"&TagName="+tagname+"&speciesname="+speciesname+"&locationname="+locationname;
			$.get(showgalleryurl, function(response,name) {
					var dataOptionArray=JSON.parse(response);
					var htmlAllOptions="";
					for (i = 0; i < dataOptionArray.length; i++) {
						var sourceSpeciesList = $("#specieslist-template").html();

						// compile the template so we can use it
						var templateSpeciesList = Handlebars.compile(sourceSpeciesList);
						var dataOption = dataOptionArray[i];
						if(name==dataOption.encodedname)
							dataOption.selected="selected";
						// generate HTML from the data
						var htmlSelectList    = templateSpeciesList(dataOption);
						htmlAllOptions+=htmlSelectList;
					}
					var idselect="#"+"speciestagname";
					// add the HTML to the content div
					$(idselect).html(htmlAllOptions);
			   }).error(function(){
			  alert("Sorry could not proceed");
			});

			if(type=='speciesalbum')
			{
			//get species wise data
			var getAlbumUrl = "ShowGalleryResponsive.php?type="+type+"&categ="+categ+"&action=speciesalbum&pagenum="+pagenum+"&TagName="+tagname+"&speciesname="+speciesname+"&locationname="+locationname;
			$.get(getAlbumUrl, function(response) {
					var dataOptionArray=JSON.parse(response);
					var htmlAllOptions="";
					for (i = 0; i < dataOptionArray.length; i++) {
						var sourcespeciesalbum = $("#speciesalbum-template").html();

						// compile the template so we can use it
						var templatespeciesalbum = Handlebars.compile(sourcespeciesalbum);
						var dataOption = dataOptionArray[i];
						// generate HTML from the data
						var htmlSelectList    = templatespeciesalbum(dataOption);
						htmlAllOptions+=htmlSelectList;
					}
					var idselect="#"+"speciesalbum";
					// add the HTML to the content div
					$(idselect).html(htmlAllOptions);
			   }).error(function(){
			  alert("Sorry could not proceed");
			});
			}
			else if(type=='gallery' || type=='speciesname'){
			//get album data
			var getAlbumUrl = "ShowGalleryResponsive.php?type="+type+"&categ="+categ+"&action=allPicAlbum&pagenum="+pagenum+"&TagName="+tagname+"&speciesname="+speciesname+"&locationname="+locationname;
			$.get(getAlbumUrl, function(response) {
					var dataOptionArray=JSON.parse(response);
					var htmlAllOptions="";
					for (i = 0; i < dataOptionArray.length; i++) {
						var sourceallpicsalbum = $("#allpicsalbum-template").html();

						// compile the template so we can use it
						var templateallpicsalbum = Handlebars.compile(sourceallpicsalbum);
						var dataOption = dataOptionArray[i];
						// generate HTML from the data
						var htmlSelectList    = templateallpicsalbum(dataOption);
						htmlAllOptions+=htmlSelectList;
					}
					var idselect="#"+"speciesalbum";
					// add the HTML to the content div
					$(idselect).html(htmlAllOptions);
			   }).error(function(){
			  alert("Sorry could not proceed");
			});
			}
			
			//get album size
			var getAlbumCount = "ShowGalleryResponsive.php?type="+type+"&categ="+categ+"&action=allPicAlbumSize&pagenum="+pagenum+"&TagName="+tagname+"&speciesname="+speciesname+"&locationname="+locationname;
			$.get(getAlbumCount, function(response) {
				//alert("Total Count = "+response);
			});

			// grab our template code from the DOM
			var source   = $("#pagination-template").html();

			// compile the template so we can use it
			var templatePagination = Handlebars.compile(source);

			// create some data
			var prevpage= parseInt(pagenum) - 1;
			if(pagenum==1)
				var prevpage=parseInt(pagenum);
			
			var previousURL = "loadHTMLGallery('"+type+"','"+categ+"','','"+prevpage+"')";
			
			var nextPage = parseInt(pagenum) + 1;
			var nextURL = "loadHTMLGallery('"+type+"','"+categ+"','','"+nextPage+"')";
			
			dataSelectText='{"currentpage":"'+pagenum+'","PreviousURL":"'+previousURL+'","NextURL":"'+nextURL+'"}';
			var dataSelect = JSON.parse(dataSelectText);

			// generate HTML from the data
			var htmlSelect    = templatePagination(dataSelect);

			// add the HTML to the content div
			$('#pagination').html(htmlSelect);
			
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
			$('.ui-dialog-content').css("z-index",100);
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
			function initializeImage(){
				$("#map-canvas").load("coverImage.html"); 
			}
	        function initializeMap() {
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
