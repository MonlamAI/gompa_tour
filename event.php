<?php 
session_start();
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


$sql = "SELECT * FROM events WHERE slug=? AND status='published'";
$result = $db->prepare($sql);
$result->execute(array($_GET['url']));
$post = $result->fetch(PDO::FETCH_ASSOC);


if($_SESSION['lang'] === 'en'){
  $titel = $post['event_enname'];
  $web_content = $post['en_description'];
}else if($_SESSION['lang'] === 'bo'){
  $titel = $post['event_tbname'];
  $web_content = $post['tb_description'];
}else{
  $titel = $post['event_enname'];
  $web_content = $post['en_description'];
}

?>
<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-8">

      <!-- Title -->
      <h3 style="margin-bottom: 20px;" class="mt-4"><?php echo $titel; ?></h3>
      <!-- Author -->
      <!-- Preview Image -->
      <?php if(isset($post['pic']) & !empty($post['pic'])){ ?>
          <img style="width: 100%;object-fit: cover;" class="img-fluid rounded" src="<?php echo $baseUrl?>/<?php echo $post['pic']; ?>" alt="">
      <?php }else{ ?>
          <img class="img-fluid rounded" src="http://placehold.it/900x300" alt="">
      <?php } ?>
      <hr>

      <!-- Post Content -->
      <div>
      <audio style="width: 100%;" controls>
        <source src="media/audios/bensound-buddy.mp3" type="audio/mp3">
        Your browser does not support the audio element.
      </audio>
      </div>
     
      <div style="line-height: 30px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 15px;" class="content">
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
        if(voice.name.toLowerCase().includes('female') || voice.name.toLowerCase().includes('woman')) {
            option.textContent += ' - Female';
        } else if(voice.name.toLowerCase().includes('male') || voice.name.toLowerCase().includes('man')) {
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
          
          if(isset($post['slug'])){                                  
          $text =$baseUrl.'/tensum.php?url='.$post['slug'].'';
          $qtex = str_replace(" ", "",$text);
          
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
   

    <?php include('includes/sidebar-single.php'); ?>

  </div>
  <!-- /.row -->

</div>
<!-- /.container -->
<?php include('includes/footer.php'); ?>