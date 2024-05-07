<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title></title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.0/dist/leaflet.css" />
<link rel="stylesheet" href="./vendor/leaflet/leaflet-search.css" />
<link rel="stylesheet" href="./vendor/leaflet/style.css" />
</head>

<body>


<div id="map"></div>



<script src="./vendor/leaflet/leaflet.js"></script>
<script src="./vendor/leaflet/leaflet-search.src.js"></script>
<script src="./vendor/leaflet/data/gonpa.js"></script>
<script>

	//sample data values for populate map
	var data = [
  
		{"loc":[76.32410842536859, 32.232383763880165], "title":"ཐེག་ཆེན་ཆོས་གླིང་གཙུག་ལག་ཁང་།"},
		{"loc":[76.32603425056584, 32.22606155677671], "title":"གནས་ཆུང་གྲྭ་ཚང་།"},
		{"loc":[76.32452816988882, 32.22575017304115], "title":"དགའ་གདོང་གྲྭ་ཚང་།"},
		{"loc":[ 76.32207127144466, 32.23778682125263], "title":"ཚེ་མཆོག་གླིང་།"},
		{"loc":[76.35882690264253, 32.19826582969313], "title":"ནོར་བུ་གླིང་ཀ"},
		{"loc":[76.36865263418878, 32.187238993685284], "title":"རྒྱུད་སྟོད་གྲྭ་ཚང་།"}
		
	];

	var map = new L.Map('map', {zoom: 9, center: new L.latLng(data[0].loc) });	//set center from first location

	map.addLayer(new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));	//base layer

	var markersLayer = new L.LayerGroup();	//layer contain searched elements
	
	map.addLayer(markersLayer);

	var controlSearch = new L.Control.Search({
		position:'topright',		
		layer: markersLayer,
		initial: false,
		zoom: 12,
		marker: false
	});

	map.addControl( controlSearch );








  

	////////////populate map with markers from sample data
	for(i in data) {
		var title = data[i].title,	//value searched
			loc = data[i].loc,		//position found
			marker = new L.Marker(new L.latLng(loc), {title: title} );//se property searched
		marker.bindPopup('title: '+ title );
		markersLayer.addLayer(marker);
	}

  var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});
osm.addTo(map);

//Esri.WorldImagery
var worldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
});
worldImagery.addTo(map);


var  googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});
googleSat.addTo(map);

var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});
googleStreets.addTo(map);

// Layer controller
var baseMpas ={
  "OMS": osm,
"worldImagery": worldImagery,
"གྷོས་གྷུལ་སྲང་ལམ།": googleStreets,
"སྒོས་གྷུལ་འཁོར་སྐར།": googleSat

};
var overlayMpas ={
  "Marker": map

};
L.control.layers(baseMpas, overlayMpas).addTo(map);
</script>




</body>
</html>
