<?php 
session_start();

require_once('includes/connect.php');
include('includes/header.php');
include('includes/navigation.php'); 


// get number of per page results from settings table
$rppsql = "SELECT * FROM settings WHERE name='resultsperpage'";
$rppresult = $db->prepare($rppsql);
$rppresult->execute();
$rpp = $rppresult->fetch(PDO::FETCH_ASSOC);
$perpage = $rpp['value'];

if(isset($_GET['page']) & !empty($_GET['page'])){
  $curpage = $_GET['page'];
}else{
  $curpage = 1;
}
// get the number of total posts from posts table
$sql = "SELECT * FROM posts WHERE status='published'";
$result = $db->prepare($sql);
$result->execute();
$totalres = $result->rowCount();
// create startpage, nextpage, endpage variables with values
$endpage = ceil($totalres/$perpage);
$startpage = 1;
$nextpage = $curpage + 1;
$previouspage = $curpage - 1;
$start = ($curpage * $perpage) - $perpage;
// fetch the results
$sql = "SELECT * FROM tensum WHERE status='published' ORDER BY created DESC LIMIT $start, $perpage";
$result = $db->prepare($sql);
$result->execute();
$posts = $result->fetchAll(PDO::FETCH_ASSOC);
// add the pagination links
?>
<!-- Page Content -->
<main>
<div class="p-1 text-center bg-body-tertiary rounded-3">
    
    <img class="bi mt-6 mb-3" src="vendor/img/logo.png" alt="" width="100"style="padding-top: 20px;">
    <h4  class="text-body-emphasis"><?php echo htmlspecialchars(translate('home_main_title'), ENT_QUOTES, 'UTF-8'); ?></h4>
    <p style="text-align: center;" class="col-lg-8 mx-auto fs-5 text-muted">
     <span style="color:#026dc4;font-size: 20px;"><?php echo htmlspecialchars(translate('home_welcome_massge'), ENT_QUOTES, 'UTF-8'); ?>
     <!-- </span> <br><?php echo htmlspecialchars(translate('home_discretion'), ENT_QUOTES, 'UTF-8'); ?> 
    <br><a href="post-page.php"><?php echo translate('how_to_use'); ?></a> -->
    </p>
   
  </div>
</main>
<div class="container">

<div  class="row ">

    
</div>
<!-- མདུན་ངོས་དངོས་གཞི། -->
          <div  style="padding-bottom: 100px;" class="row">
            <div style="padding-bottom: 50px;" class="col-md-4">
              <div style="background: linear-gradient(to top, rgba(0, 163, 251, 1), rgba(251, 251, 251, 0.1)), linear-gradient(to right, #026dc4, #4492e2); border: 0px; border-radius: 10px; min-height: 500px;">
              <div style="text-align: center; border-bottom: 1px solid #297ecc; padding-bottom: 4px;">
              <img style="max-height: 240px;min-height: 240px;object-fit: cover;object-position: top;object-fit: contain;" class="card-img-top" src="vendor/img/buddha.png" alt="">
              </div>
                
                <div class="card-body">
                  <p style="color: white; line-height: 40px;text-align: center; font-size: 25px!important; padding-top: 4px;" class="card-text">
                  <?php echo htmlspecialchars(translate('pilgrimage'), ENT_QUOTES, 'UTF-8'); ?>
                   
                  </p>
                  <p style="color: white;text-align: center;margin-bottom: 40px;" class="card-text">
          
                    <a style="color: white;" href="scann.php">
                   <span style="padding: 8px;font-size: 18px;"><?php echo htmlspecialchars(translate('Introduction_txt_sound'), ENT_QUOTES, 'UTF-8'); ?></span> 
                  </a>
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                  
                  <a href="scann.php">
                  <button  style="background-color: color(srgb 0.8557 0.9406 0.995); border: none;" class="btn btn-secondary" type="submit">
                    <img style="width: 45px; text-align: left; float: left;" src="vendor/img/qr-code.png" alt="">
                  </button>
                  </a>
                  
                  <button data-toggle="modal" data-target="#searchModal" style="background-color: color(srgb 0.8557 0.9406 0.995); border: none;" class="btn btn-secondary" type="submit">
                    <img style="width: 45px; text-align: left; float: left;" src="vendor/img/search-code.png" alt="">
                  </button>
                 
                
                  </div>
                </div>
              </div>
            </div>
            <div style="padding-bottom: 50px;" class="col-md-4">
              <div style="background: linear-gradient(to top, rgba(0, 163, 251, 1), rgba(251, 251, 251, 0.1)), linear-gradient(to right, #026dc4, #4492e2); border: 0px; border-radius: 10px; min-height: 500px;">
              <div style="text-align: center; border-bottom: 1px solid #297ecc; padding-bottom: 4px;">
              <img style="max-height: 240px;min-height: 240px;object-fit: cover;object-position: top;object-fit: contain;" class="card-img-top" src="vendor/img/potala2.png" alt="">
              </div>
                
                <div class="card-body">
                  <p style="color: white; text-align: center; line-height: 40px;font-size: 25px!important; padding-top: 4px;" class="card-text">
                  <?php echo htmlspecialchars(translate('organization'), ENT_QUOTES, 'UTF-8'); ?>
                   
                  </p>
                  <p style="color: white;text-align: center;margin-bottom: 40px;" class="card-text">
          
                    <a style="color: white;" href="scann.php">
  
                   <span style="padding: 8px;font-size: 18px;"><?php echo htmlspecialchars(translate('introduction_txt_sound_map'), ENT_QUOTES, 'UTF-8'); ?></span> 
                  </a>
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                  <a href="scann.php">
                  <button  style="background-color: color(srgb 0.8557 0.9406 0.995); border: none;" class="btn btn-secondary" type="submit">
                    <img style="width: 45px; text-align: left; float: left;" src="vendor/img/qr-code.png" alt="">
                  </button>
                  </a>
                  

                  <a href="map.php">
                  <button data-toggle="modal" data-target="#myModal" style="background-color: color(srgb 0.8557 0.9406 0.995); border: none;" class="btn btn-secondary" type="submit">
                    <img style="width: 45px; text-align: left; float: left;" src="vendor/img/map.png" alt="">
                  </button>
                  </a>

               
                  <button data-toggle="modal" data-target="#searchModal1" style="background-color: color(srgb 0.8557 0.9406 0.995); border: none;" class="btn btn-secondary" type="submit">
                    <img style="width: 45px; text-align: left; float: left;" src="vendor/img/search-code.png" alt="">
                  </button>
              
                
                  </div>
                </div>
              </div>
            </div>
            <div style="padding-bottom: 50px;" class="col-md-4">
              <div style="background: linear-gradient(to top, rgba(0, 163, 251, 1), rgba(251, 251, 251, 0.1)), linear-gradient(to right, #026dc4, #4492e2); border: 0px; border-radius: 10px; min-height: 500px;">
              <div style="text-align: center; border-bottom: 1px solid #297ecc; padding-bottom: 4px;">
              <img style="max-height: 240px;min-height: 240px;object-fit: cover;object-position: top; object-fit: contain;" class="card-img-top" src="vendor/img/duchen.png" alt="">
              </div>
                
                <div class="card-body">
                  <p style="color: white; text-align: center; line-height: 40px;font-size: 25px!important; padding-top: 4px;" class="card-text">
                  <?php echo htmlspecialchars(translate('festival'), ENT_QUOTES, 'UTF-8'); ?>
                   
                  </p>
                  <p style="color: white;text-align: center;margin-bottom: 40px;" class="card-text">
          
                    <a style="color: white;" href="scann.php">
                   <span style="padding: 8px;font-size: 18px;"><?php echo htmlspecialchars(translate('Introduction_txt_sound'), ENT_QUOTES, 'UTF-8'); ?></span> 
                  </a>
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                  <a href="scann.php">
                  <button  data-toggle="modal" data-target="#myModal" style="background-color: color(srgb 0.8557 0.9406 0.995); border: none;" class="btn btn-secondary" type="submit">
                    <img style="width: 45px; text-align: left; float: left;" src="vendor/img/qr-code.png" alt="">
                  </button>
                  </a>
             
                  
                  <button data-toggle="modal" data-target="#searchModal2" style="background-color: color(srgb 0.8557 0.9406 0.995); border: none;" class="btn btn-secondary" type="submit">
                    <img style="width: 45px; text-align: left; float: left;" src="vendor/img/search-code.png" alt="">
                  </button>
                  
                
                  </div>
                </div>
              </div>
            </div>

            
            
            

            
            
          </div>
    
  
  <!-- /.row -->

</div>

  <!-- The QR Modal -->
  <div class="modal fade" id="myModal">
  <div class="modal-dialog">
      <div class="modal-content" style="background-color: color(srgb 0.0602 0.6275 0.9653);">
      
        <!-- Modal Header -->
        <div class="modal-header" style="border-color: color(srgb 0.0697 0.5124 0.775); text-align: center!important;">
          <h5 class="modal-title" style="color: white !important; text-align: center;">
          <?php echo htmlspecialchars(translate('qr-search'), ENT_QUOTES, 'UTF-8'); ?></h5>
          <button type="button" class="close" data-dismiss="modal" style="color: white;">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <div class="default-style" id="video-container">
    <video class="col-lg video-wrapper" id="qr-video" disablepictureinpicture="" playsinline="" style="transform: scaleX(1); height:320px;"></video><div class="scan-region-highlight" style="position: absolute; pointer-events: none; transform: scaleX(1); width: 218.666667px; height: 218.666667px; top: 70.666667px; left: 139.666667px;"><svg class="scan-region-highlight-svg" viewBox="0 0 238 238" preserveAspectRatio="none" style="position:absolute;width:100%;height:100%;left:0;top:0;fill:none;stroke:#e9b213;stroke-width:4;stroke-linecap:round;stroke-linejoin:round"><path d="M31 2H10a8 8 0 0 0-8 8v21M207 2h21a8 8 0 0 1 8 8v21m0 176v21a8 8 0 0 1-8 8h-21m-176 0H10a8 8 0 0 1-8-8v-21"></path></svg><svg class="code-outline-highlight" preserveAspectRatio="none" style="display:none;width:100%;height:100%;fill:none;stroke:#e9b213;stroke-width:5;stroke-dasharray:25;stroke-linecap:round;stroke-linejoin:round"><polygon></polygon></svg></div>
      </div>
      
      <div style="display: none;">
          <select class="form-select" id="inversion-mode-select">
              <option value="original">Scan original (dark QR code on bright background)</option>
              <option value="invert">Scan with inverted colors (bright QR code on dark background)</option>
              <option value="both">Scan both</option>
          </select>
          <br>
      </div>
      <span style="display: none;">པར་ཆས་དྲ་སྒྲིག་ཡིན་མིན: </span>
      <span style="display: none;" id="cam-has-camera">true</span>
      <br>
      <div style="display: none;">
          <span>པར་ཆས་འདེམས་སྒྲིགས།:</span>
          <select class="form-select" id="cam-list">
              <option value="environment" selected="">Example custom style 1</option>
              <option value="user">User Facing</option>
          <option value="E50FE747DE3E5A6469D232A573075D637A70C367">w300</option><option value="FFC62BA997CD56E91FA0F5B4E3BBEE5778E8C37F">FaceTime HD Camera</option></select>
      </div>
      <span style="display: none;">པར་ཆས་གློག་འོད།: </span>
      <span style="display: none;" id="cam-has-flash">false</span>
      <div style="display: none;">
          <button id="flash-toggle" style="display: none;">📸 གློག་སྒྲོན།: <span id="flash-state">off</span></button>
      </div>
      <p style="display: none;">
        <br>
      <span>རྟགས་རིས་ངོས་འཛིན།: </span>
      <span id="cam-qr-result" style="color: inherit;">No QR code found</span>
      <br>
      </p>
      
      <p style="display: none;">
        <span>མཐའ་མའི་རྟགས་རིས་ངོས་འཛིན་དུས།: </span>
      <span id="cam-qr-result-timestamp"></span>
      <br>
      </p>
      
      <button style="background-color: color(srgb 0.7474 0.8997 0.99); color: rgb(107, 112, 110);" class="btn" id="start-button">
      <?php echo htmlspecialchars(translate('start'), ENT_QUOTES, 'UTF-8'); ?>
    </button>
      <button style="background-color: color(srgb 0.7474 0.8997 0.99); color: rgb(107, 112, 110);" class="btn" id="stop-button">
      <?php echo htmlspecialchars(translate('end'), ENT_QUOTES, 'UTF-8'); ?>
    </button>
      <hr>
      <?php echo htmlspecialchars(translate('qr-from-file'), ENT_QUOTES, 'UTF-8'); ?>
      <h5>

      </h5>
      <input class="btn" type="file" id="file-selector">
      
        </div>
        
        <!-- Modal footer -->
        <div style="border: none;" class="modal-footer">
          <button style="border: none;background-color: color(srgb 0.448 0.7254 0.9152);" type="button" class="btn btn-danger" data-dismiss="modal">
          <?php echo htmlspecialchars(translate('close'), ENT_QUOTES, 'UTF-8'); ?>
        </button>
        </div>
        
      </div>
    </div>
  </div>
  

  <!-- The search Modal -->
  <div class="modal fade" id="searchModal">
  <div class="modal-dialog">
      <div class="modal-content" style="background-color: color(srgb 0.0602 0.6275 0.9653);">
      
        <!-- Modal Header -->
        <div class="modal-header" style="border-color: color(srgb 0.0602 0.6275 0.9653); text-align: center!important;">
          <h5 style="color: white!important; text-align: center;" class="modal-title" style="color: white; text-align: center;">
          <?php echo htmlspecialchars(translate('search'), ENT_QUOTES, 'UTF-8'); ?>
        </h5>
          <button type="button" class="close" data-dismiss="modal" style="color: white;">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form action="search.php" style="margin-top: 18px;" class="input-group custom-search-form">
    
         <input name="search" type="text" class="form-control" placeholder="<?php echo htmlspecialchars(translate('search_for'), ENT_QUOTES, 'UTF-8'); ?>">
    
                <span class="input-group-btn">
                    <button style="border-radius: 0px 5px 5px 0px; padding-top: 10px; background-color: rgb(245, 246, 246);" class="btn btn-default" type="submit">འཚོལ།
                        <i class="fa fa-search"></i>
                </button>
        </span>
        <div style="margin-left: 20px;" class="col-12 p-1">
    
        <input name="tensum" value="tensum" type="checkbox" class="form-check-input" id="exampleCheck1" checked>
        <label class="form-check-label" for="exampleCheck1"> 
        <?php echo htmlspecialchars(translate('pilgrimage'), ENT_QUOTES, 'UTF-8'); ?>

        </label>
        <input name="organization" value="organization" style="margin-left: 4px;"type="checkbox" class="form-check-input" id="exampleCheck1">
        <label style="margin-left: 20px;"class="form-check-label" for="exampleCheck1"> 
        <?php echo htmlspecialchars(translate('organization'), ENT_QUOTES, 'UTF-8'); ?>
      </label>
        </div>
        </form>
      
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer" style="border: none;">
          <button type="button" style="border: none; background-color: rgb(231, 243, 254);" class="btn btn-danger" data-dismiss="modal">
          <?php echo htmlspecialchars(translate('close'), ENT_QUOTES, 'UTF-8'); ?>
        </button>
        </div>
        
      </div>
    </div>
  </div>

  <!-- The search1 Modal -->
  <div class="modal fade" id="searchModal1">
  <div class="modal-dialog">
      <div class="modal-content" style="background-color: color(srgb 0.0602 0.6275 0.9653);">
      
        <!-- Modal Header -->
        <div class="modal-header" style="border-color: color(srgb 0.0602 0.6275 0.9653); text-align: center!important;">
          <h5 style="color: white!important; text-align: center;" class="modal-title" style="color: white; text-align: center;">
          <?php echo htmlspecialchars(translate('search'), ENT_QUOTES, 'UTF-8'); ?>
        </h5>
          <button type="button" class="close" data-dismiss="modal" style="color: white;">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form action="organization-page.php" style="margin-top: 18px;" class="input-group custom-search-form">
    
         <input name="search" type="text" class="form-control" placeholder="<?php echo htmlspecialchars(translate('search_for'), ENT_QUOTES, 'UTF-8'); ?>">
    
                <span class="input-group-btn">
                    <button style="border-radius: 0px 5px 5px 0px; padding-top: 10px; background-color: rgb(245, 246, 246);" class="btn btn-default" type="submit">འཚོལ།
                        <i class="fa fa-search"></i>
                </button>
        </span>
        <div style="margin-left: 20px;" class="col-12 p-1">
    
        <input name="tensum" value="tensum" type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1"> 
        <?php echo htmlspecialchars(translate('pilgrimage'), ENT_QUOTES, 'UTF-8'); ?>

        </label>
        <input name="organization" value="organization" style="margin-left: 4px;"type="checkbox" class="form-check-input" id="exampleCheck1" checked>
        <label style="margin-left: 20px;"class="form-check-label" for="exampleCheck1"> 
        <?php echo htmlspecialchars(translate('organization'), ENT_QUOTES, 'UTF-8'); ?>
      </label>
        </div>
        </form>
      
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer" style="border: none;">
          <button type="button" style="border: none; background-color: rgb(231, 243, 254);" class="btn btn-danger" data-dismiss="modal">
          <?php echo htmlspecialchars(translate('close'), ENT_QUOTES, 'UTF-8'); ?>
        </button>
        </div>
        
      </div>
    </div>
  </div>
  <!-- The search2 Modal -->
  <div class="modal fade" id="searchModal2">
  <div class="modal-dialog">
      <div class="modal-content" style="background-color: color(srgb 0.0602 0.6275 0.9653);">
      
        <!-- Modal Header -->
        <div class="modal-header" style="border-color: color(srgb 0.0602 0.6275 0.9653); text-align: center!important;">
          <h5 style="color: white!important; text-align: center;" class="modal-title" style="color: white; text-align: center;">
          <?php echo htmlspecialchars(translate('search'), ENT_QUOTES, 'UTF-8'); ?>
        </h5>
          <button type="button" class="close" data-dismiss="modal" style="color: white;">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form action="festival-page.php" style="margin-top: 18px;" class="input-group custom-search-form">
    
         <input name="search" type="text" class="form-control" placeholder="<?php echo htmlspecialchars(translate('search_for'), ENT_QUOTES, 'UTF-8'); ?>">
    
                <span class="input-group-btn">
                    <button style="border-radius: 0px 5px 5px 0px; padding-top: 10px; background-color: rgb(245, 246, 246);" class="btn btn-default" type="submit">འཚོལ།
                        <i class="fa fa-search"></i>
                </button>
        </span>
        <div style="margin-left: 20px;" class="col-12 p-1">
    
        <input name="tensum" value="tensum" type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1"> 
        <?php echo htmlspecialchars(translate('pilgrimage'), ENT_QUOTES, 'UTF-8'); ?>

        </label>
        <input name="organization" value="organization" style="margin-left: 4px;"type="checkbox" class="form-check-input" id="exampleCheck1">
        <label style="margin-left: 20px;"class="form-check-label" for="exampleCheck1"> 
        <?php echo htmlspecialchars(translate('organization'), ENT_QUOTES, 'UTF-8'); ?>
      </label>
      <input name="events" value="events" style="margin-left: 4px;"type="checkbox" class="form-check-input" id="exampleCheck1" checked>
        <label style="margin-left: 20px;"class="form-check-label" for="exampleCheck1"> 
        <?php echo htmlspecialchars(translate('festival'), ENT_QUOTES, 'UTF-8'); ?>
      </label>
        </div>
        </form>
      
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer" style="border: none;">
          <button type="button" style="border: none; background-color: rgb(231, 243, 254);" class="btn btn-danger" data-dismiss="modal">
          <?php echo htmlspecialchars(translate('close'), ENT_QUOTES, 'UTF-8'); ?>
        </button>
        </div>
        
      </div>
    </div>
  </div>
<!-- The LangModel Modal -->
<!-- Button to trigger modal -->
<button id="modalTrigger" type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#exampleModal">
  Launch modal
</button>

<!-- Modal -->
<div class="modal fade" id="langModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">སྐད་ཡིག་འདེམས་ཡུལ།</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div style="text-align: center;" class="modal-body">
        འདི་ནས་སྐད་ཡིག་འདེམས་རོགས།
        <div>
          <div class="col-6" style="margin: 10px;padding: 10px;text-align: center;">
          <a  href="?lang=bo" onclick="changeLanguageBo()">
          <img src="vendor/img/bo-lang.png" width="42px" alt="">
            བོད་ཡིག</a>
          </div>
          <div class="col-6" style="margin: 10px;padding: 10px;text-align: center;">
          <a  href="?lang=en" onclick="changeLanguageEn()">
          <img src="vendor/img/en-lang.png" width="42px" alt="">
            English</a>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
        
      </div>
    </div>
  </div>
</div>

<!-- /.container -->
<?php include('includes/footer.php'); ?>
<?php

?>
<script>
  // Check if session is empty and show modal
  document.addEventListener("DOMContentLoaded", function() {
    var sessionData = sessionStorage.getItem("showlang");
    if (sessionData === 'yes') {
      $('#modalTrigger').trigger('click');
      sessionStorage.setItem("showlang", 'yes');
    }
  });
</script>
<!-- <script type="module">
  import QrScanner from "./vendor/qr-scanner/qr-scanner.min.js";
  
          const video = document.getElementById('qr-video');        
          const videoContainer = document.getElementById('video-container');
          const camHasCamera = document.getElementById('cam-has-camera');
          const camList = document.getElementById('cam-list');
          const camHasFlash = document.getElementById('cam-has-flash');
          const flashToggle = document.getElementById('flash-toggle');
          const flashState = document.getElementById('flash-state');
          const camQrResult = document.getElementById('cam-qr-result');
          const camQrResultTimestamp = document.getElementById('cam-qr-result-timestamp');
          const fileSelector = document.getElementById('file-selector');
          const fileQrResult = document.getElementById('file-qr-result');

          function setResult(label, result) {
              console.log(result.data);
              label.textContent = result.data;
              camQrResultTimestamp.textContent = new Date().toString();
            
              if (camQrResult) {
                  // Example action: redirecting to another page with the timestamp
                  // Modify the URL and parameters according to your needs
                  window.location.href = camQrResult.textContent;
              } else {
                  alert('Please scan a QR code first.');
              }
              label.style.color = 'teal';
              clearTimeout(label.highlightTimeout);
              label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
          }

          // ####### Web Cam Scanning #######

          const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
              onDecodeError: error => {
                  camQrResult.textContent = error;
                  camQrResult.style.color = 'inherit';
              },
              highlightScanRegion: true,
              highlightCodeOutline: true,
          });

          const updateFlashAvailability = () => {
              scanner.hasFlash().then(hasFlash => {
                  camHasFlash.textContent = hasFlash;
                  flashToggle.style.display = hasFlash ? 'inline-block' : 'none';
              });
          };

          scanner.start().then(() => {
              updateFlashAvailability();
              // List cameras after the scanner started to avoid listCamera's stream and the scanner's stream being requested
              // at the same time which can result in listCamera's unconstrained stream also being offered to the scanner.
              // Note that we can also start the scanner after listCameras, we just have it this way around in the demo to
              // start the scanner earlier.
              QrScanner.listCameras(true).then(cameras => cameras.forEach(camera => {
                  const option = document.createElement('option');
                  option.value = camera.id;
                  option.text = camera.label;
                  camList.add(option);
              }));
          });

          QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);

          // for debugging
          window.scanner = scanner;

          document.getElementById('scan-region-highlight-style-select').addEventListener('change', (e) => {
              videoContainer.className = e.target.value;
              scanner._updateOverlay(); // reposition the highlight because style 2 sets position: relative
          });

          document.getElementById('show-scan-region').addEventListener('change', (e) => {
              const input = e.target;
              const label = input.parentNode;
              label.parentNode.insertBefore(scanner.$canvas, label.nextSibling);
              scanner.$canvas.style.display = input.checked ? 'block' : 'none';
          });

          document.getElementById('inversion-mode-select').addEventListener('change', event => {
              scanner.setInversionMode(event.target.value);
          });

          camList.addEventListener('change', event => {
              scanner.setCamera(event.target.value).then(updateFlashAvailability);
          });

          flashToggle.addEventListener('click', () => {
              scanner.toggleFlash().then(() => flashState.textContent = scanner.isFlashOn() ? 'on' : 'off');
          });

          document.getElementById('start-button').addEventListener('click', () => {
              scanner.start();
          });

          document.getElementById('stop-button').addEventListener('click', () => {
              scanner.stop();
              
          });

          // ####### File Scanning #######

          fileSelector.addEventListener('change', event => {
              const file = fileSelector.files[0];
              if (!file) {
                  return;
              }
              QrScanner.scanImage(file, { returnDetailedScanResult: true })
                  .then(result => setResult(fileQrResult, result))
                  .catch(e => setResult(fileQrResult, { data: e || 'No QR code found.' }));
          });
        
          
      </script> -->


<script>
  
  // Add event listener for window load
  window.addEventListener('load', function() {
    // Show the language modal
    var localValue = localStorage.getItem('session_start');
    if (localValue === 'bo-lang') {
      
    }else{
      $('#langModal').modal('show');
    }
    
  });

  function changeLanguageBo() {
    localStorage.setItem('session_start', 'bo-lang');

    $('#langModal').modal('hide');
  }
  function changeLanguageEn() {
    localStorage.setItem('session_start', 'en-lang');

    $('#langModal').modal('hide');
  }
</script>
    

