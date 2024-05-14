<?php 
session_start();
require_once('includes/connect.php');
include('includes/header.php');
include('comment.php');
include('includes/navigation.php'); 

// $sql = "SELECT * FROM posts WHERE slug=? AND status='published'";
// $result = $db->prepare($sql);
// $result->execute(array($_GET['url']));
// $post = $result->fetch(PDO::FETCH_ASSOC);

// $usersql = "SELECT * FROM users WHERE id=?";
// $userresult = $db->prepare($usersql);
// $userresult->execute(array($post['uid']));
// $user = $userresult->fetch(PDO::FETCH_ASSOC);
?>
<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-8">      
      <h4 class="mt-4"style="margin-left: 20px; margin-bottom: 15px;"><?php echo translate('search_qr'); ?></h4>
      
      <div class="col-lg-8" id="video-container">
    <video style="transform: scaleX(-1); height: 320px;"class="col-lg video-wrapper" id="qr-video"></video>
      </div>
      <div>
          <label>
          <?php echo translate('high-quality'); ?>
              <select class="form-select" id="scan-region-highlight-style-select">
                  <option value="default-style"><?php echo translate('default-style'); ?></option>
                  <option value="example-style-1"><?php echo translate('example-custom-style-1'); ?></option>
                  <option value="example-style-2"><?php echo translate('example-custom-style-2'); ?></option>
              </select>
          </label>
          <label>
              <input id="show-scan-region" type="checkbox">
              <?php echo translate('show-scan-region-canvas'); ?>
          </label>
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
      <span style="display: none;" id="cam-has-camera"></span>
      <br>
      <div style="display: none;">
          <span>པར་ཆས་འདེམས་སྒྲིགས།:</span>
          <select class="form-select" id="cam-list">
              <option value="environment" selected>Example custom style 1</option>
              <option value="user">User Facing</option>
          </select>
      </div>
      <span style="display: none;">པར་ཆས་གློག་འོད།: </span>
      <span style="display: none;" id="cam-has-flash"></span>
      <div style="display: none;">
          <button id="flash-toggle">📸 གློག་སྒྲོན།: <span id="flash-state">off</span></button>
      </div>
      <p style="display: none;">
        <br>
      <span>རྟགས་རིས་ངོས་འཛིན།: </span>
      <span id="cam-qr-result">མེད།</span>
      <br>
      </p>
      
      <p style="display: none;">
        <span>མཐའ་མའི་རྟགས་རིས་ངོས་འཛིན་དུས།: </span>
      <span id="cam-qr-result-timestamp"></span>
      <br>
      </p>
      
      <button class="btn" id="start-button"style="color: white;background-color: #1081b4;"><?php echo translate('start-bt'); ?></button>
      <button class="btn"id="stop-button"><?php echo translate('end-bt'); ?></button>
      <hr>

      <h5><?php echo translate('scan-from-file'); ?></h5>
      <input class="btn" type="file" id="file-selector">
      <b>QR: </b>
      <span id="file-qr-result">No</span>
     
      <!--<script src="../qr-scanner.umd.min.js"></script>-->
      <!--<script src="../qr-scanner.legacy.min.js"></script>-->
      <script type="module">
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
      </script>

      

    </div>

    <?php include('includes/sidebar.php'); ?>

  </div>
  <!-- /.row -->

</div>
</div>
<!-- /.container -->
<?php include('includes/footer.php'); ?>
