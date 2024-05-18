<?php
  require_once('includes/functions.php');

 // Function to fetch setting value by name
function fetchSettingValue($db, $settingName) {
  $sql = "SELECT value FROM settings WHERE name = :name";
  try {
      $stmt = $db->prepare($sql);
      $stmt->execute([':name' => $settingName]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result !== false ? $result['value'] : null;
  } catch (PDOException $e) {
      // Log the error instead of displaying it directly
      error_log("Database error: " . $e->getMessage());
      // Show a generic error message to the user
      die("An error occurred. Please try again later.");
  }
}

// Retrieve site title and tagline
$title = fetchSettingValue($db, 'sitetitle');
$tag = fetchSettingValue($db, 'tagline');

$baseUrl = getBaseUrl(); 
?>
<!-- Navigation -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php"><?php echo translate('web_title'); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo $baseUrl ?>"><?php echo translate('home'); ?>
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <?php
          // Fetch menu items using a prepared statement
          $sql = "SELECT * FROM menu";
          $stmt = $db->prepare($sql);
          $stmt->execute();
          $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Assuming $_SESSION['lang'] is set securely elsewhere
          // $lang = $_SESSION['lang'] ?? 'en'; // Default to English if not set
           // Default to English if not set
          if($_SESSION['lang'] === 'en'){
            $lang ='en';
          }else if($_SESSION['lang'] === 'bo'){
            $lang ='bo';
       
          }else{
            $lang ='en';
       
          }
          // $showlang = $_SESSION['showlang'] ?? 'no';
          $showlang ='no';

          foreach ($res as $cat) {
              $link = htmlspecialchars($baseUrl . $cat['link']);
              $title = htmlspecialchars($cat[$lang . '_title']); // Dynamically choose the title based on session language

              echo "<li class='nav-item'>
                      <a class='nav-link' href='{$link}'>{$title}</a>
                    </li>";
          }
          
        ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo htmlspecialchars($baseUrl); ?>contact-page.php"><?php echo htmlspecialchars(translate('contact')); ?></a>
        </li>
        
      </ul >
      
        <div class="dropdown">
          <a style="color: white;" type="link" class="dropdown-toggle" data-toggle="dropdown">
          <i class='fa fa-globe'></i> <?php echo translate('global_language'); ?>
          </a>
          <div class="dropdown-menu">
          <a class="dropdown-item" href="#" onclick="changeLanguage('bo')">བོད་ཡིག</a>
          <a class="dropdown-item" href="#" onclick="changeLanguage('en')">English</a>
          </div>
        </div>        
    </div>
  </div>
</nav>
        <script>
function changeLanguage(lang) {
    // Update the current URL with the new lang query parameter
    var currentUrl = new URL(window.location);
    currentUrl.searchParams.set('lang', lang);
    window.history.pushState({path:currentUrl.href}, '', currentUrl.href);
    location.reload(); 

}
</script>
