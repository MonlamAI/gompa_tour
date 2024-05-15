<!-- Sidebar Widgets Column -->
<div class="col-md-4"style="margin-bottom: 100px;">
<?php
// Prepare a parameterized SQL statement for widget search
$searchsql = "SELECT * FROM widget WHERE type=:widget_type";
$searchresult = $db->prepare($searchsql);
$searchresult->execute([':widget_type' => 'categories']); // Bind the parameter 'categories'
$widgetcount = $searchresult->rowCount();
if($widgetcount == 1){
?>
  <!-- Search Widget -->
  <div class="card my-4">
    <h5 class="card-header"><?php echo translate('search'); ?></h5>
    <div class="card-body">
      <form  action="search.php?search=">
      <div class="input-group">
        <input style="font-size: 14px; border: 1px solid #e2dede;" name="search" type="text" class="form-control" placeholder="<?php echo translate('search_for'); ?>">
        <span class="input-group-btn">
          <button style="border-radius: 0px 5px 5px 0px; border-color: #e2dede;"class="btn btn-secondary" type="submit"><?php echo translate('search_btn'); ?></button>
         
        </span>
      </div>
      </form>
      <div style="margin-top: 10px;">
        <a href="scann.php"><button class="btn btn-secondary col-lg-12" style="border-color: #e2dede;"type="submit">
          <img style="width: 30px; text-align: left; float: left;"src="vendor/img/qr-code.png" alt=""> <?php echo translate('search_qr'); ?></button></a>
      </div>
      <form action="qr.php?" >
        <div style="margin-top: 10px;">
        <input type="hidden" name="qr-link" value="<?php echo $post['slug']; ?>">
        <input type="hidden" name="qr-type" value="organization">
        <a href="" id="qr-link-data"><button  class="btn btn-secondary col-lg-12" style="border-color: #e2dede;" type="submit">
          <img style="width: 30px; text-align: left; float: left;"src="vendor/img/qr-downlaod.png" alt=""> <?php echo translate('download_qr'); ?></button></a>        
      </div>
      </form>
    </div>
  </div>
<?php } ?>

<?php
// Prepare a parameterized SQL statement for widget search
$searchsql = "SELECT * FROM widget WHERE type=:widget_type";
$searchresult = $db->prepare($searchsql);
$searchresult->execute([':widget_type' => 'categories']); // Bind the parameter 'categories'
$widgetcount = $searchresult->rowCount();
if ($widgetcount == 1) {
  // Prepare a parameterized SQL statement for category search
  $catsql = "SELECT * FROM categories";
  $catresult = $db->prepare($catsql);
  $catresult->execute();
  $catres = $catresult->fetchAll(PDO::FETCH_ASSOC);
?>
  <!-- Categories Widget -->
  <div class="card my-4">
    <h5 class="card-header">ཚོགས་སྡེའི་ཁ་བྱང་།</h5>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
    

          <span style="color: #b3b0b0">Name: </span><?php echo htmlspecialchars($titel); ?><br>
          <span style="color: #b3b0b0">Street: </span><?php echo htmlspecialchars($post['street']); ?><br>
          <span style="color: #b3b0b0">Address 2: </span><?php echo htmlspecialchars($post['address_2']); ?><br>
          <span style="color: #b3b0b0">City: </span><?php echo htmlspecialchars($post['city']); ?><br>
          <span style="color: #b3b0b0">State: </span><?php echo htmlspecialchars($post['state']); ?><br>
          <span style="color: #b3b0b0">Postal code: </span><?php echo htmlspecialchars($post['postal_code']); ?><br>
          <span style="color: #b3b0b0">Country: </span><?php echo htmlspecialchars($post['country']); ?><br>
          <span style="color: #b3b0b0">Phone: </span><?php echo htmlspecialchars($post['phone']); ?><br>
          <span style="color: #b3b0b0">Email: </span><?php echo htmlspecialchars($post['email']); ?><br>
          <span style="color: #b3b0b0">Web: </span><?php echo htmlspecialchars($post['web']); ?><br>
          <span style="color: #b3b0b0">Map: </span><?php echo htmlspecialchars($post['map']); ?><br>

        </div>
      </div>
    </div>
  </div>
<?php } ?>

<!-- ས་ཁྲ། Widget -->
<!-- map js -->
<script src="./vendor/leaflet/leaflet.js"></script>
<script src="./vendor/leaflet/data/gonpa.js"></script>
<link rel="stylesheet" href="vendor/leaflet/leaflet.css" />


<div class="card my-4">
    <h5 class="card-header"><?php echo translate('map'); ?></h5>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <!-- map -->
                <div style="height: 250px;position: relative;outline-style: none;border-radius: 4px;" id="map"></div>
                <!-- map end -->
            </div>
        </div>
    </div>
</div>

<!-- གནམ་གཤིས Widget -->
<!-- map js -->

<script src="vendor/leaflet-weather/leaflet-openweathermap.css"></script>
<script src="vendor/leaflet-weather/leaflet-openweathermap.js"></script>


<div class="card my-4">
    <h5 class="card-header"><?php echo translate('weather'); ?></h5>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <!-- map -->
                <div  id="weatherInfo">
                
                <style>
        /* Style for weather widget */
        .weather-widget {
            font-family: Monlam, sans-serif;
            border-radius: 5px;
            padding: 10px;
            max-width: 400px;
            margin: 0 auto;
            background: linear-gradient(to top, rgba(0, 163, 251, 1), rgba(251, 251, 251, 0.1)), linear-gradient(to right, #026dc4, #4492e2);
        }

        .weather-widget h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .weather-widget .forecast {
            margin-top: 10px;
        }

        .weather-widget .forecast-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .weather-widget .forecast-item .date {
            font-size: 16px;
            font-weight: bold;
            margin-right: 10px;
        }

        .weather-widget .forecast-item .temperature {
            font-size: 16px;
            font-weight: bold;
        }

        /* Icon styles */
        .weather-icon {
            display: inline-block;
            width: 42px;
            height: 42px;
            background-size: cover;
            margin-right: 10px;
        }
        .date{
          color: rgb(254, 245, 213);
        }
        .temperature{
          color: white;
        }
    </style>
                <div class="weather-widget">
        <div style="color: rgb(175, 215, 250);font-size: 25px"><?php echo htmlspecialchars($post['city']); ?></div>
        <div class="forecast" id="forecast"></div>
    </div>

    <script>
      var pin = <?php echo $post['postal_code']; ?>;
      var contCode = "IN";
        // Fetch daily weather forecast data from OpenWeatherMap API
        fetch('https://api.openweathermap.org/data/2.5/forecast?zip='+pin+','+contCode+'&appid=cb2723b0ec6f86f52ed99bcf2fde498c')
            .then(response => response.json())
            .then(data => {
                // Filter to get only one forecast per day
                const dailyForecast = data.list.filter((item, index) => index % 8 === 0);
                
                // Update DOM with daily weather forecast data
                const forecastContainer = document.getElementById('forecast');

                dailyForecast.forEach(forecast => {
                    const date = new Date(forecast.dt * 1000);
                    const dateString = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                    const temperature = (forecast.main.temp - 273.15).toFixed(1) + "°C";
                    const iconCode = forecast.weather[0].icon;
                    const iconUrl = `http://openweathermap.org/img/wn/${iconCode}.png`;

                    const forecastItem = document.createElement('div');
                    forecastItem.classList.add('forecast-item');

                    const dateElement = document.createElement('div');
                    dateElement.classList.add('date');
                    dateElement.textContent = dateString;

                    const temperatureElement = document.createElement('div');
                    temperatureElement.classList.add('temperature');
                    temperatureElement.textContent = temperature;

                    const iconElement = document.createElement('div');
                    iconElement.classList.add('weather-icon');
                    iconElement.style.backgroundImage = `url(${iconUrl})`;

                    forecastItem.appendChild(dateElement);
                    forecastItem.appendChild(iconElement);
                    forecastItem.appendChild(temperatureElement);

                    forecastContainer.appendChild(forecastItem);
                });
            })
            .catch(error => {
                console.error('Error fetching daily weather forecast data:', error);
            });
    </script>
             
     <script>
    //https://api.openweathermap.org/data/2.5/weather?zip=$176215,$IN&appid=$cb2723b0ec6f86f52ed99bcf2fde498c
    </script>
                </div>
                <!-- map end -->
            </div>
        </div>
    </div>
</div>
<!-- དུས་ཆེན། Widget -->
<div class="card my-4">
    <h5 class="card-header"><?php echo translate('qr'); ?></h5>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div style="text-align: center;">
                    <?php
                    echo '<img src="data:image/png;base64,' . $imageData . '" />';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var map = L.map('map').setView([<?php echo $post['map']; ?>], 16);

    var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: 'Monalm IT'
    }).addTo(map);

    var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });
    googleStreets.addTo(map);

    var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });
    googleSat.addTo(map);

    // Layer controller
    var baseMpas = {
        "OSM": osm,
        "གྷོས་གྷུལ་སྲང་ལམ།": googleStreets,
        "སྒོས་གྷུལ་འཁོར་སྐར།": googleSat

    };


    L.control.layers(baseMpas).addTo(map);

</script>
<script>

</script>

<!-- end map -->


  




