<?php 
session_start();
$errors = [];
$successful = '';
require_once('includes/connect.php');
require_once('includes/functions.php');
include('includes/header.php');
include('includes/navigation.php');

if(isset($_POST) && !empty($_POST)){
  // PHP Form Validations
  if(empty($_POST['name'])) { $errors[] = "Name Field is Required"; }
  if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = "Valid Email Field is Required"; }
  if(empty($_POST['message'])) { $errors[] = "Message Field is Required"; }
  
  // Insert into database if no errors
  if(empty($errors)){
    $currentDateTime = date('Y-m-d H:i:s');
    $sql = "INSERT INTO contact_messages (name, email, message, created) VALUES (:name, :email, :message, :created)";
    $result = $db->prepare($sql);
    $isSuccess = $result->execute(array(
      ':name'    => $_POST['name'],
      ':email'   => $_POST['email'],
      ':message' => $_POST['message'],
      ':created' => $currentDateTime                    
    ));

    if($isSuccess){
      $successful = "Successfully Sent your message!";
    } else {
      $errors[] = "Failed to send your message";
    }
  }
}

$baseUrl = getBaseUrl(); 
?>
<div style="padding-bottom: 200px; border-bottom: 1px solid #999" class="container">

  <div class="row">
  

    <!-- Post Content Column -->
    <div class="col-lg-8">
    <div style="padding-top: 80px;border-bottom: 1px solid #e2dddd;padding-bottom: 20px;">
    <div style="text-align: center;padding-bottom: 20px;border-bottom: 1px solid #c1bcbc;margin-bottom: 10px;">
    <img width="150px" src="vendor/img/drclogo.png" alt="">
    <h3>ཆོས་རིག་ལྷན་ཁང་།</h3>
    <h3>Department of Religion & Culture</h3>
    <div>
      
    </div>
    
    </div>

    
    Central Tibetan Administration<br>
    Gangchen Kyishong, Dharamshala<br>
    Kangra District, HP 176215, India<br>
    Tel: +91-1892-222685, 226737<br>
    Fax: +91-1892-228037<br>
    Email: religion@tibet.net<br>
  </div>
 
        <h3 style="padding-top: 50px"><?php echo htmlspecialchars(translate('contact-form'), ENT_QUOTES, 'UTF-8'); ?></h3>
        <div style="color: #878484; padding-top: 10px; padding-bottom: 10px;">
        <?php echo htmlspecialchars(translate('contact-info'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php 

      // Display errors (if any)
        if (!empty($errors)) {
            echo "<div class='alert alert-danger'>";
            foreach ($errors as $error) {
            echo "<p>$error</p>";
            }
            echo "</div>";
        }
        
        // Success message
        if(!empty($successful)){
            echo "<div id='success_message' class='alert alert-success'>";       
                echo $successful;      
            echo "</div>";
        }
      ?>
        <form method="post">
            <div class="form-group">
                <label for="name"><?php echo htmlspecialchars(translate('name'), ENT_QUOTES, 'UTF-8'); ?></label>
                <input class="form-control" placeholder="<?php echo htmlspecialchars(translate('your-name'), ENT_QUOTES, 'UTF-8'); ?>" id="name" name="name" style="border: 1px solid rgba(228, 215, 215, 1);" required>
           
                                
            </div>
            <div class="form-group">
                <label for="email"><?php echo htmlspecialchars(translate('email'), ENT_QUOTES, 'UTF-8'); ?></label>
                <input type="email" class="form-control" placeholder="<?php echo htmlspecialchars(translate('your-email'), ENT_QUOTES, 'UTF-8'); ?>" id="email" name="email" style="border: 1px solid rgba(228, 215, 215, 1);" required>
            </div>
            <div class="form-group">
                <label for="message"><?php echo htmlspecialchars(translate('message'), ENT_QUOTES, 'UTF-8'); ?></label>
                <textarea class="form-control" id="message" name="message" rows="5" style="border: 1px solid rgba(228, 215, 215, 1);" required></textarea>
            </div>
            <div style="text-align: right;">
            <button type="submit"  class="btn btn-primary"><?php echo htmlspecialchars(translate('submit'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
            
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script type="text/javascript">
          $(document).ready(function() {
              // Ensure the element exists
              if ($("#success_message").length > 0) {
                  window.setTimeout(function() {
                      $("#success_message").fadeTo(1000, 0).slideUp(1000, function() {
                          $(this).remove();
                      });
                  }, 3000);
              }
          });
    </script>
<div class="col-lg-8">
</div>
<script src="./vendor/leaflet/leaflet.js"></script>

<link rel="stylesheet" href="vendor/leaflet/leaflet.css" />


<div class="card my-4">
    <h5 class="card-header">ས་ཁྲ།</h5>
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
</div>
<script>
  var greenIcon = L.icon({
    iconUrl: 'vendor/img/drclogo-sl.png',
    iconSize: [38, 38]
   
});

var gonpaIcon = L.icon({
    iconUrl: 'vendor/img/marker-icon.png',
    iconSize: [25, 41]
   
});

    var map = L.map('map').setView([32.22589329738568, 76.32533999442133], 19);

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
    
    var inlocation = L.marker([32.22589329738568, 76.32533999442133], {icon: greenIcon}).addTo(map)
    .bindPopup('ཆོས་རིག་ལྷན་ཁང་།<br>Department of Religion & Culture')
    .openPopup();

    // Layer controller
    var baseMpas = {
        "OSM": osm,        
        "སྒོས་གྷུལ་འཁོར་སྐར།": googleSat,
        "གྷོས་གྷུལ་སྲང་ལམ།": googleStreets,

    };


    L.control.layers(baseMpas).addTo(map);

</script>

<?php include('includes/sidebar.php'); ?>
  </div>
  <!-- /.row -->
</div>
<!-- /.container -->
<?php include('includes/footer.php'); ?>

