<?php 
session_start();
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here
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

// get the number of total tensum from tensum table
$sqlPage = "SELECT * FROM organization";
$resultPage = $db->prepare($sqlPage);
$resultPage->execute();
$totalres = $resultPage->rowCount();
// create startpage, nextpage, endpage variables with values
$endpage =  ceil($totalres/$perpage);
$startpage = 1;
$nextpage = $curpage + 1;
$previouspage = $curpage - 1;
$start = ($curpage * $perpage) - $perpage;

?>


<div style="width: 97%!important;" class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-8"style="min-height: 100vh;">

  

            <form action="organization-page.php" style="margin-top: 18px;" class="input-group custom-search-form">
    
         <input style="border-color: rgb(199, 228, 254); background-color: rgb(228, 242, 255);margin-bottom: 10px;"name="search" type="text" class="form-control" placeholder="<?php echo htmlspecialchars(translate('search_for'), ENT_QUOTES, 'UTF-8'); ?>">
    
                <span class="input-group-btn">
                    <button style="border-radius: 0px 5px 5px 0px; padding-top: 10px; background-color: rgb(245, 246, 246);" class="btn btn-default" type="submit">
                    <?php echo htmlspecialchars(translate('search_btn'), ENT_QUOTES, 'UTF-8'); ?>
                        <i class="fa fa-search"></i>
                </button>
        </span>
        <div style="margin-left: 20px;" class="col-12 p-1">
    
        <input name="organization" value="organization" type="checkbox" class="form-check-input" id="exampleCheck1" checked>
        <label class="form-check-label" for="exampleCheck1"><?php echo htmlspecialchars(translate('organization'), ENT_QUOTES, 'UTF-8'); ?> </label>
       
        </div>
        </form>
		    <?php
            // Search form
            // Check if search data exists
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = $_GET['search'];
                
                
             
                $limit = 10; // Number of results per page
               $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
               $start = ($page > 1) ? ($page * $limit) - $limit : 0;

                // Prepare the full-text search query
                $sql = "SELECT *, MATCH(tbtitle, entitle) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) AS score FROM organization WHERE MATCH(tbtitle, entitle) 
                AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) ORDER BY score DESC LIMIT :start, :limit";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':searchTerm', $searchTerm);
                $stmt->bindValue(':start', $start, PDO::PARAM_INT);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                

                // Calculate total pages
                $totalSql = "SELECT COUNT(*) FROM organization WHERE MATCH(tbtitle, entitle) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)";
                $totalStmt = $db->prepare($totalSql);
                $totalStmt->bindValue(':searchTerm', $searchTerm);
                $totalStmt->execute();
                $totalResults = $totalStmt->fetchColumn();
                $totalPages = ceil($totalResults / $limit);

                

                // Display results
                if (empty($totalResults)) {
                    ?>
                    <div class="col-12">
                    <p style="color: #999;border-bottom: 1px solid #ececec;margin: 0px 0px 13px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 12px!important;">
                    འདི་འདྲ་ཞིག་ <span style="color:red;" ><?php echo $_GET['search'] ?></span>  རྙེད་ཀྱི་མི་འདུག
                        </p>    
                        </div>
                    <?php
                }else{
                    ?>
                    <p style="color: #999;border-bottom: 1px solid #ececec;margin: 0px 0px 13px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 12px !important; ">
                    འཚོལ་ཞིབ་ཀྱི་རྙེད་དོན་གྲངས་ <span style="color:rgb(4, 162, 125);" ><?php echo $totalResults ?></span>  འདུག
                        </p>    
                  
                    <?php

                } 
                foreach ($results as $row) {
                    if($_SESSION['lang'] === 'en'){
                        $titel = $row['entitle'];
                        $web_content = $row['encontent'];
                      }else if($_SESSION['lang'] === 'bo'){
                        $titel = $row['tbtitle'];
                        $web_content = $row['tbcontent'];
                      }else{
                        $titel = $row['entitle'];
                        $web_content = $row['encontent'];
                      }
                    ?>
                    <div style="padding-top: 20px; padding-bottom: 0px;" class="card-body">
                   
                    <a href="organization.php?url=<?php echo $row['slug']; ?>"><h4 style="color: #1e5fa6;margin: 1px;font-size: 20px;line-height: 35px;" class="card-title"><?php echo $titel ?></h4></a>
                       
                        <div style="border-bottom: 1px solid #ececec;min-height: 110px;">
                        <img style="width: 150px;object-fit: cover;flo;float: left;height: 90px;object-position: top;margin-right: 6px;position: relative;top: 0px;" class="img-fluid rounded" src="<?php echo $row['pic']; ?>" alt="" onerror="this.onerror=null; this.src='vendor/img/noimage.jpg';">  
                        <p style="position: relative; line-height: 30px; margin: 0px 0px 14px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px !important; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; max-height: 90px; -webkit-line-clamp: 3;">
                         
                        <?php echo htmlspecialchars($web_content) ?>
                        </p>    
                        </div>                           
                    </div>
                    <?php
                                       
                }
                

                // Pagination
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<a href="?search=' . urlencode($searchTerm) . '&page=' . $i . '">' . $i . '</a> ';
                }
            } else {
                
              if($_SESSION['lang'] === 'en'){
                $titel = "entitle";
                $web_content = "encontent";
              }else if($_SESSION['lang'] === 'bo'){
                $titel = "tbtitle";
                $web_content = "tbcontent";
              }else{
                $titel = "entitle";
                $web_content = "encontent";
              }
                $limit2 = 10; // Number of results per page
               $page2 = isset($_GET['page']) ? (int)$_GET['page'] : 1;
               $start2 = ($page2 > 1) ? ($page2 * $limit2) - $limit2 : 0;

                // Prepare the full-text search query
                $sql2 = "SELECT * FROM organization ORDER BY $titel DESC LIMIT :start, :limit";
                $stmt2 = $db->prepare($sql2);
          
                $stmt2->bindValue(':start', $start2, PDO::PARAM_INT);
                $stmt2->bindValue(':limit', $limit2, PDO::PARAM_INT);
                $stmt2->execute();
                $results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                

                // Calculate total pages
                $totalSql2 = "SELECT COUNT(*) FROM organization ";
                $totalStmt2 = $db->prepare($totalSql2);
                $totalStmt2->execute();
                $totalResults2 = $totalStmt2->fetchColumn();
                $totalPages2 = ceil($totalResults2 / $limit2);

                

                // Display results
                
                foreach ($results2 as $row) {
                    if($_SESSION['lang'] === 'en'){
                        $titel = $row['entitle'];
                        $web_content = $row['encontent'];
                      }else if($_SESSION['lang'] === 'bo'){
                        $titel = $row['tbtitle'];
                        $web_content = $row['tbcontent'];
                      }else{
                        $titel = $row['entitle'];
                        $web_content = $row['encontent'];
                      }
                    ?>
                    <div style="padding-top: 20px; padding-bottom: 0px;" class="card-body">
                   
                    <a href="organization.php?url=<?php echo $row['slug']; ?>"><h4 style="color: #1e5fa6;margin: 1px;font-size: 20px;line-height: 35px;" class="card-title"><?php echo $titel ?></h4></a>
                       
                        <div style="border-bottom: 1px solid #ececec;min-height: 110px;">
                        <img style="width: 150px;object-fit: cover;flo;float: left;height: 90px;object-position: top;margin-right: 6px;position: relative;top: 8px;" class="img-fluid rounded" src="<?php echo $row['pic']; ?>" alt="" onerror="this.onerror=null; this.src='vendor/img/noimage.jpg';">  
                        <p style="position: relative;line-height: 30px; margin: 0px 0px 14px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px !important; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; max-height: 90px; -webkit-line-clamp: 3;">
                         
                        <?php echo htmlspecialchars($web_content) ?>
                        </p>    
                        </div>                           
                    </div>
                <?php
                }
            }
            ?>
        </form>
        <div style="margin: 10px;">
          <!-- Pagination -->
          <ul class="pagination justify-content-center mb-4">
                        <?php if($curpage != $startpage){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $startpage; ?>">&laquo; <?php echo htmlspecialchars(translate('first'), ENT_QUOTES, 'UTF-8'); ?></a>
                        </li>
                        <?php } ?>
                        <?php if($curpage >= 2){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $previouspage; ?>"><?php echo $previouspage; ?></a>
                        </li>
                        <?php } ?>
                        <?php if($curpage != $endpage ){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $nextpage; ?>"><?php echo $nextpage; ?></a>
                        </li>
                        <?php } ?>
                        <?php if($curpage != $endpage){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $endpage; ?>">&raquo; <?php echo htmlspecialchars(translate('last'), ENT_QUOTES, 'UTF-8'); ?></a>
                        </li>
                        <?php } ?>
                      </ul>
        </div>
</div>


<?php include('includes/sidebar.php'); ?>
  </div>

  

</div>
</div>
<?php include('includes/footer.php'); ?>

