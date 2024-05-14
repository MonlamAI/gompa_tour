<?php 
session_start();
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here
require_once('includes/connect.php');
include('includes/header.php');
include('includes/functions.php');
include('comment.php');
include('includes/navigation.php');

$baseUrl = getBaseUrl();

?>
<!-- Page Content -->
<div style="width: 97%!important; min-height: 800px;" class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-12"style="min-height: 100vh;">

            <form action="search.php" style="margin-top: 18px;" class="input-group custom-search-form">
    
         <input style="border-color: rgb(199, 228, 254); background-color: rgb(228, 242, 255);margin-bottom: 10px;"name="search" type="text" class="form-control" placeholder="འཚོལ་ཞིབ།...">
    
                <span class="input-group-btn">
                    <button style="border-radius: 0px 5px 5px 0px; padding-top: 10px; background-color: rgb(245, 246, 246);" class="btn btn-default" type="submit">འཚོལ།
                        <i class="fa fa-search"></i>
                </button>
        </span>
        <div style="margin-left: 20px;" class="col-12 p-1">
    
        <input name="tensum" value="tensum" type="checkbox" class="form-check-input" id="exampleCheck1" checked>
        <label class="form-check-label" for="exampleCheck1">རྟེན་བཤད། </label>
        <input name="organization" value="organization" style="margin-left: 4px;"type="checkbox" class="form-check-input" id="exampleCheck1">
        <label style="margin-left: 20px;"class="form-check-label" for="exampleCheck1">རྟེན་གཞི། </label>
        </div>
        </form>
		    <?php
            // Search form
            // Check if search data exists
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = $_GET['search'];
                
                if(isset($_GET['tensum'])){
                    $table1 =$_GET['tensum'];
                }elseif(isset($_GET['organization'])){
                $table1 =$_GET['organization'];
               };
                $limit = 10; // Number of results per page
               $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
               $start = ($page > 1) ? ($page * $limit) - $limit : 0;

                // Prepare the full-text search query
                $sql = "SELECT *, MATCH(tbtitle, entitle) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) AS score FROM $table1 WHERE MATCH(tbtitle, entitle) 
                AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) ORDER BY score DESC LIMIT :start, :limit";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':searchTerm', $searchTerm);
                $stmt->bindValue(':start', $start, PDO::PARAM_INT);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                

                // Calculate total pages
                $totalSql = "SELECT COUNT(*) FROM tensum WHERE MATCH(tbtitle, entitle) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)";
                $totalStmt = $db->prepare($totalSql);
                $totalStmt->bindValue(':searchTerm', $searchTerm);
                $totalStmt->execute();
                $totalResults = $totalStmt->fetchColumn();
                $totalPages = ceil($totalResults / $limit);

                

                // Display results
                if (empty($totalResults)) {
                    ?>
                    <p style="color: #999;border-bottom: 1px solid #ececec;margin: 0px 0px 13px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 12px !important;">
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
                    <div style="padding-top: 20px;" class="card-body">
                   
                    <a href="tensum.php?url=<?php echo $row['slug']; ?>"><h4 style="color: #1e5fa6;" class="card-title"><?php echo $titel ?></h4></a>
                       
                        <div style="border-bottom: 1px solid #ececec;">
                        <img style="width: 150px;object-fit: cover;flo;float: left;height: 90px;object-position: top;margin-right: 6px;position: relative;top: 8px;" class="img-fluid rounded" src="<?php echo $baseUrl ?>/<?php echo $row['pic']; ?>" alt="">  
                        <p style="position: relative;line-height: 30px; margin: 0px 0px 14px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px !important; background-color: #ffffff; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; max-height: 90px; -webkit-line-clamp: 3;">
                         
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
                ?>
                <p style="border-bottom: 1px solid #ececec;line-height: 25px;margin: 0px 0px 15px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px; background-color: #ffffff;">
                <span style="margin-top: 10px!important;color: #9E9E9E;" ><?php echo "ཁྱེད་ཀྱི་འཚོལ་ཞིབ་བྱ་ཡུལ་སྟོང་པ་རེད་་་་" ?></span>
                <?php
            }

            ?>
      

       
            
        </form>
</div>



  </div>
  <!-- /.row -->

</div>
</div>
</div>
<!-- /.container -->
<?php include('includes/footer.php'); ?>

