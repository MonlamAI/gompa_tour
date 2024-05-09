<?php 
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php'); 



// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

include('includes/header.php');
include('includes/navigation.php'); 
?>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.tiny.cloud/1/u21pwrlpb56ivo6bzi9y6qujxuawv9mzp7rdxqx1d8hs2jrh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">འཚོལ་ཞིབ་ལྟེ་གནས།</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">    
        <div class="col-xs-8 col-xs-offset-2">
            
            <form class="input-group custom-search-form">
                <input name="search" type="text" class="form-control" placeholder="འཚོལ་ཞིབ།...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search"></i>
                    </button>
                </span>
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
                $sql = "SELECT *, MATCH(title, content) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) AS score FROM posts WHERE MATCH(title, content) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) ORDER BY score DESC LIMIT :start, :limit";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':searchTerm', $searchTerm);
                $stmt->bindValue(':start', $start, PDO::PARAM_INT);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Calculate total pages
                $totalSql = "SELECT COUNT(*) FROM posts WHERE MATCH(title, content) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)";
                $totalStmt = $db->prepare($totalSql);
                $totalStmt->bindValue(':searchTerm', $searchTerm);
                $totalStmt->execute();
                $totalResults = $totalStmt->fetchColumn();
                $totalPages = ceil($totalResults / $limit);

                // Display results
                if (empty($totalResults)) {
                    ?>
                    <p style="border-bottom: 1px solid #ececec;line-height: 25px;margin: 0px 0px 15px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px; background-color: #ffffff;">
                    འདི་འདྲ་ཞིག་ <span style="color:red;" ><?php echo $_GET['search'] ?></span>  རྙེད་ཀྱི་མི་འདུག
                        </p>    
                        </div>
                    <?php
                }else{
                    ?>
                    <p style="border-bottom: 1px solid #ececec;line-height: 25px;margin: 0px 0px 15px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px; background-color: #ffffff;">
                    འཚོལ་ཞིབ་ཀྱི་རྙེད་དོན་གྲངས་ <span style="color:rgb(4, 162, 125);" ><?php echo $totalResults ?></span>  འདུག
                        </p>    
                  
                    <?php

                } 
                foreach ($results as $row) {
                    ?>
                    <div style="padding-top: 20px;" class="card-body">
                        <h4 style="color: #1e5fa6;" class="card-title"><?php echo htmlspecialchars($row['title']) ?></h4>
                        <div>
                        <p style="border-bottom: 1px solid #ececec;line-height: 25px;margin: 0px 0px 15px; padding: 0px 0px 10px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px; background-color: #ffffff;">
                            <?php echo htmlspecialchars($row['content']) ?>
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
                <span style="color:red;" ><?php echo "ཁྱེད་ཀྱི་འཚོལ་ཞིབ་བྱ་ཡུལ་སྟོང་པ་རེད་་་་" ?></span>
                <?php
            }

            ?>
        </div>
	</div>
</div>


    



<?php include('includes/footer.php'); ?>