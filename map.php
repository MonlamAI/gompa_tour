<?php 
session_start();
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here
require_once('includes/connect.php');
include('includes/header.php');
include('comment.php');
include('includes/navigation.php');



// Check if HTTPS is used, otherwise default to HTTP
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// Get the server name (e.g., www.example.com)
$serverName = $_SERVER['SERVER_NAME'];
// Get the port number
$port = $_SERVER['SERVER_PORT'];

// If the port is not standard, include it in the URL
if (($protocol === "https://" && $port != 443) || ($protocol === "http://" && $port != 80)) {
    $serverName .= ":" . $port;
}
// Get the web root path (if your application is in a subdirectory e.g., /myapp)
$webRoot = dirname($_SERVER['PHP_SELF']);
// Construct the base URL
$baseUrl = $protocol . $serverName . $webRoot;

$gonpaName ="<span>ཐེག་མཆོག་རྣམ་གྲོལ་གླིང་།</sapn>";
//<img width='100' src='media/1710571396WhatsApp Image 2024-02-28 at 9.30.23 AM.jpeg' alt=''>

?>


     <link rel="stylesheet" href="./vendor/leaflet/leaflet.css"/>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
     <style>
      #map{
        width: 100%;
        height: 100vh;
      }


  #map.expanded {
      width: 100%; /* Full width */
      height: 100vh; /* Full height of the viewport */
  }
     </style>
 
<div  class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-12">
    <a style="float: left;" href="javascript:history.back()"> <span style="font-size: 22px;position: relative;top: 2px;"><i class='fa fa-angle-left'></i></span> <?php echo htmlspecialchars(translate('go-back'), ENT_QUOTES, 'UTF-8'); ?></a>

      <h4 style="padding-top: 0px;margin-bottom: 0px; text-align:center;" class="map-title">ས་ཁྲ་བརྒྱུད་ནས་འཚོལ་ཞིབ།</h4>
      <div style="text-align: right;">
        <button class="btn" id="full-screen" style="margin-bottom: 5px;" onclick="fullScreenview()"><span><i class='fa fa-arrows-alt'></i></span>  Full Screen</button>
      </div>
      <div id="map"></div>
      <div style="color: rgb(5, 135, 101); font-size: 12px; padding: 6px;" class="coordinate">

      </div>
      
    
     
    </div>

  </div>
  <!-- /.row -->


</div>
<!-- /.container -->
<?php include('includes/footer.php'); ?>
<script src="./vendor/leaflet/leaflet.js"></script>
<script src="./vendor/leaflet/data/gonpa.js"></script>
<script src="./vendor/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="./vendor/leaflet/leaflet-search.css" />


<script src="./vendor/leaflet/leaflet-search.src.js"></script>
<script src="./vendor/leaflet/data/gonpa.js"></script>
<script>

var greenIcon = L.icon({
    iconUrl: 'vendor/img/logo-smal.png',
    iconSize: [38, 38]
   
});

var gonpaIcon = L.icon({
    iconUrl: 'vendor/img/marker-icon.png',
    iconSize: [25, 41]
   
});


  var map = L.map('map').setView([25.681,77.827], 5);
  map.zoomControl.setPosition('topright');

  var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});
osm.addTo(map);

//Esri.WorldImagery
var worldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
});
worldImagery.addTo(map);

var inlocation = L.marker([21.739,79.849], {icon: greenIcon}).addTo(map)
    .bindPopup('རྒྱ་གར།')
    .openPopup();
var nplocation2 = L.marker([27.645,85.309], {icon: greenIcon}).addTo(map)
    .bindPopup('བལ་ཡུལ');
var btlocation3 = L.marker([27.499,89.654], {icon: greenIcon}).addTo(map)
    .bindPopup('འབྲུག');

    <?php 

// Prepare the SQL query
$sql = "SELECT tbtitle, entitle, pic, slug, map FROM organization";

// Prepare and execute the query
$stmt = $db->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$results_count = count($results);
$no = $results_count;


foreach($results as $rs){
    
  echo "var mylocation";
  echo $no;
  echo ' = L.marker([';
  echo $rs['map'];
  echo "], {icon: gonpaIcon}).addTo(map)";
  
  echo ".bindPopup('";
  echo "<div><img style=\"width:100%;margin-bottom: 5px;\" src=\"".$rs['pic']."\" alt=\"\">";
  echo "</div><a href=\"organization.php?url=".$rs['slug']."\" style=\"color: #0078A8;\">";
  echo $rs['tbtitle'];
  echo "</a>');";
  echo "\n";  
  $no = $no - 1;

}
?>

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


// var geodata = L.geoJSON(gonpa).bindPopup(function(layer){
//   return layer.feature.properties.name
// }).openPopup();

// geodata.addTo(map);



  // Layer controller
var baseMpas ={
"OSM": osm,
"སྒོས་གྷུལ་འཁོར་སྐར།": googleSat,
"གྷོས་གྷུལ་སྲང་ལམ།": googleStreets,
"worldImagery": worldImagery

};
var overlayMpas ={
  "Marker": inlocation

};

L.control.layers(baseMpas, overlayMpas).addTo(map);

L.control.scale().addTo(map);


// full map view
var mapId =document.getElementById('map');
function fullScreenview(){
  
mapId.requestFullscreen();

};

// Map coordinate Display
map.on('mousemove', function(e){

  $('.coordinate').html(`གྱེན་ཐིག: ${e.latlng.lat} འཕྲེད་ཐིག: ${e.latlng.lng}`);
});

// Map coordinate Display on contextmenu (right-click)
map.on('contextmenu', function(e) {
  console.log(e);
  $('.coordinate').html(`Lat: ${e.latlng.lat} Lng: ${e.latlng.lng}`);
  
});

</script>
