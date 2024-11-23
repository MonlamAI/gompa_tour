<?php
session_start();
require_once ('includes/connect.php');
include ('includes/header.php');
include ('comment.php');
include ('includes/navigation.php');
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

$sql = "SELECT * FROM tensum WHERE slug=? AND status='published'";
$result = $db->prepare($sql);
$result->execute(array($_GET['url']));
$post = $result->fetch(PDO::FETCH_ASSOC);

$usersql = "SELECT * FROM users WHERE id=?";
$userresult = $db->prepare($usersql);
$userresult->execute(array($post['uid']));
$user = $userresult->fetch(PDO::FETCH_ASSOC);
if ($_SESSION['lang'] === 'en') {
  $titel = $post['entitle'];
  $web_content = $post['encontent'];
} else if ($_SESSION['lang'] === 'bo') {
  $titel = $post['tbtitle'];
  $web_content = $post['tbcontent'];
} else {
  $titel = $post['entitle'];
  $web_content = $post['encontent'];
}

?>
<!-- Page Content -->
<div class="container">
  <div class="row">
    <!-- Post Content Column -->
    <div class="col-lg-8">
    <a href="javascript:history.back()"> <span style="font-size: 22px;position: relative;top: 2px;"><i class='fa fa-angle-left'></i></span> <?php echo htmlspecialchars(translate('go-back'), ENT_QUOTES, 'UTF-8'); ?></a>

      <!-- Title -->
      <h4 style="margin-bottom: 20px; line-height: 35px; " class="mt-4"><?php echo $titel; ?></h4>
      <!-- Author -->
      <!-- Preview Image -->
      <?php if (isset($post['pic']) & !empty($post['pic'])) { ?>
        <img style="width: 100%;object-fit: cover;" class="img-fluid rounded" src="<?php echo $post['pic']; ?>" alt="" onerror="this.onerror=null; this.src='vendor/img/noimage.jpg';">
      <?php } else { ?>
        <img class="img-fluid rounded" src="vendor/img/noimage.jpg" alt="">
      <?php } ?>
      <hr>
      <!-- Post Content -->
      <?php
      if ($_SESSION['lang'] === 'en') {
        ?>
        
        <audio style="width: 100%;" controls>
          <?php 
    
          $ensound = $post['sound']; 
          $ensound = $str_to_replace("GP", "EP", $ensound);
          
          ?>
        <source src="<?php echo $ensound; ?>" type="audio/mp3">
        </audio>
        
        <?php

      } else if ($_SESSION['lang'] === 'bo') {
        ?>
          <audio style="width: 100%;" controls>
            <source src="<?php echo $post['sound']; ?>" type="audio/mp3">
            Your browser does not support the audio element.
          </audio>
        <?php

      }
      ?>

      <div id="textToRead"
        style="line-height: 30px; padding: 0px 0px 100px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 15px;"
        class="content">
        <?php echo $web_content; ?>
      </div>
      <div>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <hr>
        <script>
          let msg; // Define outside to make it reusable

          function populateVoiceList() {
            var voices = window.speechSynthesis.getVoices();
            var voiceSelect = document.getElementById('voiceSelection');

            voices.forEach((voice, index) => {
              var option = document.createElement('option');
              option.textContent = voice.name + ' (' + voice.lang + ')';

              // Optionally, try to infer gender from the voice name
              if (voice.name.toLowerCase().includes('female') || voice.name.toLowerCase().includes('woman')) {
                option.textContent += ' - Female';
              } else if (voice.name.toLowerCase().includes('male') || voice.name.toLowerCase().includes('man')) {
                option.textContent += ' - Male';
              }

              option.setAttribute('value', index);
              voiceSelect.appendChild(option);
            });
          }
          function speakText() {
            // Stop current speech if any
            window.speechSynthesis.cancel();
            // Get the selected voice
            var selectedVoiceIndex = document.getElementById('voiceSelection').value;
            var voices = window.speechSynthesis.getVoices();

            // Get the text from the div
            var text = document.getElementById("textToRead").innerText;
            // Create a new speech synthesis utterance with the selected voice
            msg = new SpeechSynthesisUtterance(text);
            msg.voice = voices[selectedVoiceIndex];
            // Speak the text
            window.speechSynthesis.speak(msg);
          }

          // Populate voice list when voices are loaded
          window.speechSynthesis.onvoiceschanged = populateVoiceList;

          function stopText() {
            // Stop the speech synthesis
            window.speechSynthesis.cancel();
          }
          function reReadText() {
            // Stop the current speech and start over
            speakText();
          }

        </script>
      </div>
      <?php
      require_once 'vendor/phpqrcode/qrlib.php';

      if (isset($post['slug'])) {
        $text = $baseUrl . '/tensum.php?url=' . $post['slug'] . '';
        $qtex = str_replace(" ", "", $text);

        // Start output buffering
        ob_start();
        // Generate the QR code and output it directly to the buffer
        QRcode::png($text, null, QR_ECLEVEL_L, 3, 2);
        // Capture the buffered output into a variable
        $imageString = ob_get_contents();
        // Clean (erase) the output buffer and turn off output buffering
        ob_end_clean();

        // Encode the image in base64 format
        $imageData = base64_encode($imageString);

        // Generate the HTML code for the image
        ?>
        <div style="text-align: center;">
          <?php
          echo '<img src="data:image/png;base64,' . $imageData . '" />';
          ?>
        </div>
        <?php
      }
      ?>
      <div style="padding-bottom: 200px;">
      </div>
    </div>
    <?php include ('includes/sidebar-page.php'); ?>

  </div>
  <!-- /.row -->

</div>
</div>
<!-- /.container -->
<?php include ('includes/footer.php'); ?>
