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
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div style="max-height: 500px;" class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100" src="vendor/img/dalailama.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="vendor/img/buddha-Dsa.jpg" alt="Second slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="vendor/img/dalailama.jpg" alt="Third slide">
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
</main>
<div class="container">

<div style="padding-top: 50px;" class="row ">
    
</div>
<!-- མདུན་ངོས་དངོས་གཞི། -->
          <div  style="padding-bottom: 100px;" class="row">
          <!-- སྐུ་གསུང་ཐུགས་རྟེན། -->
            <div style="padding-bottom: 50px; max-height:500px;" class="col-md-4">
              <div class="card mb-4 "style="background-color: rgb(0, 160, 237); height:500px">
              <div style="text-align: center; padding-top: 10px; border-bottom: 1px solid #0195dd;">
              <img style="width: 50%;" class="card-img-top" src="vendor/img/buddha.png" alt="Kuten">
              </div>
                
                <div class="card-body">
                  <h4 style="text-align: center; color: white;">རྟེན་བཤད།</h4>
                  <div id="tensum-list">
                  <p class="card-text">
                    <a href="scann.php">
                   <span>འགྲེལ་བཤད་ཡིག་ཆ།</span> 
                  </a>
                  </p>
                  <div class=" d-flex justify-content-between align-items-center">
                   <a href="scann.php">
                  <button style="background-color: color(srgb 0.9154 0.9616 0.995);"class="btn btn-secondary" type="submit">
                    <img style="width: 50px; text-align: left; float: left;" src="vendor/img/qr-code.png" alt="">
                  </button>
                  </a>
                  <a href="scann.php">
                  <button style="background-color: color(srgb 0.9154 0.9616 0.995);"class="btn btn-secondary" type="submit">
                    <img style="width: 50px; text-align: left; float: left;" src="vendor/img/search-code.png" alt="">
                  </button>
                  </a>
                  </div>                 
                
                  </div>
                </div>
              </div>
            </div>
            
            

            
            
            

            
            
          </div>
    
  
  <!-- /.row -->

</div>
<!-- /.container -->
<?php include('includes/footer.php'); ?>

