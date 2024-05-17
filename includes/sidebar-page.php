<?php

?>

<!-- Sidebar Widgets Column -->
<div class="col-md-4"style="margin-bottom: 100px;">
<?php
$searchsql = "SELECT * FROM widget WHERE type='search'";
$searchresult = $db->prepare($searchsql);
$searchresult->execute();
$widgetcount = $searchresult->rowCount();
if($widgetcount == 1){
?>
  <!-- Search Widget -->
  <div class="card my-4">
    <h5 class="card-header"><?php echo translate('search_qr'); ?></h5>
    <div class="card-body">
      
      <div style="margin-top: 10px;">
        <a href="scann.php"><button class="btn btn-secondary col-lg-12" style="border-color: #e2dede;" type="submit">
          <img style="width: 30px; text-align: left; float: left;"src="vendor/img/qr-code.png" alt=""> <?php echo translate('search_qr'); ?></button></a>        
      </div>
      
      <form action="qr.php?" >
        <div style="margin-top: 10px;">
        <input type="hidden" name="qr-link" value="<?php echo $post['slug']; ?>">
        <input type="hidden" name="qr-type" value="tensum">
        <a href="" id="qr-link-data"><button  class="btn btn-secondary col-lg-12" style="border-color: #e2dede;" type="submit">
          <img style="width: 30px; text-align: left; float: left;"src="vendor/img/qr-downlaod.png" alt=""> <?php echo translate('download_qr'); ?></button></a>        
      </div>
      </form>
        
      

    </div>
  </div>
<?php } ?>

<?php
$searchsql = "SELECT * FROM widget WHERE type='categories'";
$searchresult = $db->prepare($searchsql);
$searchresult->execute();
$widgetcount = $searchresult->rowCount();
if($widgetcount == 1){

  $catsql = "SELECT * FROM categories";
  $catresult = $db->prepare($catsql);
  $catresult->execute();
  $catres = $catresult->fetchAll(PDO::FETCH_ASSOC);
?>
  <!-- Categories Widget -->
  <!-- <div class="card my-4">
    <h5 class="card-header"><?php echo translate('category'); ?></h5>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <ul class="list-unstyled mb-0">
            <?php foreach ($catres as $cat) { ?>
            <li>
              <a href="category/<?php echo $cat['slug']; ?>"><?php echo $cat['title']; ?></a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php } ?> -->

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
              <a href="<?php echo "single.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
              <?php
            }else if($_SESSION['lang'] === 'bo') {
              ?>
              <a href="<?php echo "single.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['tbtitle']; ?></a>
              <?php
            } else {
              ?>
              <a href="<?php echo "single.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
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
              <a href="<?php echo "tensum.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
              <?php
            }else if($_SESSION['lang'] === 'bo') {
              ?>
              <a href="<?php echo "tensum.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['tbtitle']; ?></a>
              <?php
            } else {
              ?>
              <a href="<?php echo "tensum.php?url=". $post['slug']; ?>"style="font-size: 18px !important;font-family: sans-serif, 'Monlam';"><?php echo $post['entitle']; ?></a>
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
              <a href="https://gompatour.com/<?php echo $page['slug']; ?>"><?php echo $page['title']; ?></a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

