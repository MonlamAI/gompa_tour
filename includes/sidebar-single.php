<?php
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
?>

<!-- Sidebar Widgets Column -->
<div class="col-md-4"style="margin-bottom: 100px;">
<?php
$searchsql = "SELECT * FROM widget WHERE type='articles'";
$searchresult = $db->prepare($searchsql);
$searchresult->execute();
$widgetcount = $searchresult->rowCount();
if($widgetcount == 1){

  $postsql = "SELECT * FROM posts WHERE status='published' LIMIT 5";
  $postresult = $db->prepare($postsql);
  $postresult->execute();
  $postres = $postresult->fetchAll(PDO::FETCH_ASSOC);
?>
  <!-- Categories Widget -->
  <div class="card my-4">
    <h5 class="card-header"><?php echo translate('recent_articles'); ?></h5>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <ul class="list-unstyled mb-0">
            <?php foreach ($postres as $post) { ?>
            <li>
            <?php
            if($_SESSION['lang'] === 'en') {
              ?>
              <a href="<?php echo $baseUrl ."/single.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
              <?php
            }else if($_SESSION['lang'] === 'bo') {
              ?>
              <a href="<?php echo $baseUrl ."/single.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['tbtitle']; ?></a>
              <?php
            } else {
              ?>
              <a href="<?php echo $baseUrl ."/single.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
              <?php

            }
            ?>
            
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Tensum Widget -->

  

  <?php
  
    $postsql1 = "SELECT * FROM tensum WHERE status='published' LIMIT 5";
    $postresult1 = $db->prepare($postsql1);
    $postresult1->execute();
    $postres1 = $postresult1->fetchAll(PDO::FETCH_ASSOC);
  
  ?>
  <div class="card my-4">
    <h5 class="card-header"><?php echo translate('recent_tours'); ?></h5>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <ul class="list-unstyled mb-0">
            <?php foreach ($postres1 as $post) { ?>
            <li>
              <?php
            if($_SESSION['lang'] === 'en') {
              ?>
              <a href="<?php echo $baseUrl ."/tensum.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
              <?php
            }else if($_SESSION['lang'] === 'bo') {
              ?>
              <a href="<?php echo $baseUrl ."/tensum.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['tbtitle']; ?></a>
              <?php
            } else {
              ?>
              <a href="<?php echo $baseUrl ."/tensum.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
              <?php

            }
            ?>
              
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  




  
<?php } ?>

<?php
$searchsql = "SELECT * FROM widget WHERE type='pages'";
$searchresult = $db->prepare($searchsql);
$searchresult->execute();
$widgetcount = $searchresult->rowCount();
if($widgetcount == 1){

  $pagesql = "SELECT * FROM pages WHERE status='published' LIMIT 5";
  $pageresult = $db->prepare($pagesql);
  $pageresult->execute();
  $pageres = $pageresult->fetchAll(PDO::FETCH_ASSOC);
?>
  <!-- Categories Widget -->
  <div class="card my-4">
    <h5 class="card-header">ཤོག་ངོས།</h5>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <ul class="list-unstyled mb-0">
            <?php foreach ($pageres as $page) { ?>
            <li>
              <a href="https://gompatour.com/page/<?php echo $page['slug']; ?>"><?php echo $page['title']; ?></a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

