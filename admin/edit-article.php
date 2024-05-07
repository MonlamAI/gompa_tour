<?php 
// Enable error reporting
error_reporting(E_ALL);
// Display errors
ini_set('display_errors', 1);
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php'); 
if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['entitle'])){$errors[] = "Title Field is Required";}
    if(empty($_POST['encontent'])){$errors[] = "Content Field is Required";}
    //if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    if(empty($_POST['slug'])){$slug = trim($_POST['title']); }else{$slug = trim($_POST['slug']);}
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM posts WHERE slug=:slug AND id <> :id";
    $result = $db->prepare($sql) or die(print_r($result->errorInfo(), true));
    $values = array(':slug'     => $_POST['slug'],
                    ':id'       => $_POST['id']
                    );
    $result->execute($values);
    $count = $result->rowCount();
    if($count == 1){
        $errors[] = "Slug already exists in database";
    }
    
    
    if(empty($errors)){
        // TODO : Only user with Administrator privillages or user who created the article can only edit 
        if(isset($_FILES) & !empty($_FILES)){
            $name = $_FILES['pic']['name'];
            $size = $_FILES['pic']['size'];
            $type = $_FILES['pic']['type'];
            $tmp_name = $_FILES['pic']['tmp_name'];

            if(isset($name) && !empty($name)){
                if($type == "image/jpeg"){
                    $location = "../media/";
                    $filename = time() . $name;
                    $uploadpath = $location.$filename;
                    $dbpath = "media/" . $filename;
                    move_uploaded_file($tmp_name, $uploadpath);
                }else{
                    $errors[] = "Only Upload JPEG files";
                }
            }
        }

        $sql = "UPDATE posts SET tbtitle=:tbtitle, entitle=:entitle, tbcontent=:tbcontent, encontent=:encontent, status=:status, slug=:slug, ";
        if(isset($dbpath) & !empty($dbpath)){ $sql .="pic=:pic, "; }  
        $sql .= "updated=NOW() WHERE id=:id";
 
        $result = $db->prepare($sql);
        $values = array(':tbtitle'    => $_POST['tbtitle'],
                        ':entitle'    => $_POST['entitle'],
                        ':tbcontent'  => $_POST['tbcontent'],
                        ':encontent'  => $_POST['encontent'],
                        ':status'   => $_POST['status'],
                        ':slug'     => $_POST['slug'],
                        ':id'       => $_POST['id'],
                        );
        if(isset($dbpath) & !empty($dbpath)){ $values[':pic'] = $dbpath;}
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if($res){
            // TODO : removing non selected categories from post_categories table
            $pid = $_POST['id'];
            foreach ($_POST['categories'] as $category) {
                $catsql = "SELECT * FROM post_categories WHERE pid=:pid AND cid=:cid";
                $catresult = $db->prepare($catsql);
                $values = array(':pid'      => $pid,
                                ':cid'      => $category,
                                );
                $catresult->execute($values);
                $catcount = $catresult->rowCount();
                if($catcount == 1){}else{
                    $sql = "INSERT INTO post_categories (pid, cid) VALUES (:pid, :cid)";
                    $result = $db->prepare($sql);
                    $values = array(':pid'  => $pid,
                                    ':cid'  => $category
                                    );
                    $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
                }
            }
            
            header("location: view-articles.php");
        }else{
            $errors[] = "Failed to Add Category";
        }
    }
}
// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));
$user = $result->fetch(PDO::FETCH_ASSOC); 

if($user['role'] == 'administrator'){
    $sql = "SELECT * FROM posts WHERE id=?";
    $result = $db->prepare($sql);
    $result->execute(array($_GET['id']));
    $post = $result->fetch(PDO::FETCH_ASSOC);       
}elseif($user['role'] == 'editor'){
    $sql = "SELECT * FROM posts WHERE id=? AND uid={$_SESSION['id']}";
    $result = $db->prepare($sql);
    $result->execute(array($_GET['id']));
    $postcount = $result->rowCount();
    $post = $result->fetch(PDO::FETCH_ASSOC);
    if($postcount <= 0){
        header("location: view-articles.php");
    }
} 

include('includes/header.php');
include('includes/navigation.php');
?>

<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">རྩོམ་ཡིག་བཟོ་བཅོས།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    འདི་ནི་རྩོམ་ཡིག་བཟོ་བཅོས་བྱ་ཡུལ་ཡིན།
                </div>
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                    <?php
                        if(!empty($messages)){
                            echo "<div class='alert alert-success'>";
                            foreach ($messages as $message) {
                                echo "<span class='glyphicon glyphicon-ok'></span>&nbsp;". $message ."<br>";
                            }
                            echo "</div>";
                        }
                    ?>
                    <?php
                        if(!empty($errors)){
                            echo "<div class='alert alert-danger'>";
                            foreach ($errors as $error) {
                                echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;". $error ."<br>";
                            }
                            echo "</div>";
                        }
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    <label>བོད་ཡིག་ཁ་བྱང་།</label>
                                    <input class="form-control" name="tbtitle" placeholder="བོད་ཡིག་ཁ་བྱང་་་" value="<?php if(isset($post['tbtitle'])){ echo $post['tbtitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་ཁ་བྱང་།</label>
                                    <input class="form-control" oninput="updateSlug()" id="entitle" name="entitle" placeholder="དབྱིན་ཡིག་ཁ་བྱང་་་" value="<?php if(isset($post['entitle'])){ echo $post['entitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>བོད་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" id="editor" name="tbcontent" rows="3"><?php if(isset($post['tbcontent'])){ echo $post['tbcontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" id="editor1" name="encontent" rows="3"><?php if(isset($post['encontent'])){ echo $post['encontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <?php
                                        if(isset($post['pic']) & !empty($post['pic'])){
                                            echo "<img src='../".$post['pic']."' height='50px' width='100px'>";
                                            echo "<a href='delete-pic.php?id=". $_GET['id'] ."&type=post'>Delete Pic</a>";
                                        }else{
                                    ?>
                                    <label>Featured Image</label>
                                    <input type="file" name="pic">
                                    <?php } ?>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php 
                                                // TODO : Select Existing Categories from Database Values
                                            $sql = "SELECT * FROM categories";
                                            $result = $db->prepare($sql);
                                            $result->execute();
                                            $res = $result->fetchAll(PDO::FETCH_ASSOC);

                                            $catsql = "SELECT * FROM post_categories WHERE pid=?";
                                            $catresult = $db->prepare($catsql);
                                            $catresult->execute(array($_GET['id']));
                                            $categories = $catresult->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <label>Categories</label>
                                            <select multiple="" name="categories[]" class="form-control">
                                            <?php
                                                foreach ($res as $cat) {
                                                    if(in_array($cat['id'], array_column($categories, 'cid'))){$selected = "selected"; }else{ $selected = ""; }
                                                    echo "<option value='".$cat['id']."'". $selected .">".$cat['title']."</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Article Status</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios1" value="draft" <?php if($post['status'] == 'draft'){ echo "checked"; } ?>>Draft
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios2" value="review" <?php if($post['status'] == 'review'){ echo "checked"; } ?>>Pending Review
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios3" value="published" <?php if($post['status'] == 'published'){ echo "checked"; } ?>>Published
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Article Slug</label>
                                    <input class="form-control" id="slug" name="slug" placeholder="Enter Article Slug Here" value="<?php if(isset($post['slug'])){ echo $post['slug'];} ?>">
                                </div>
                                <script>
                                // Function to update the slug
                                function updateSlug() {
                                    var title = document.getElementById("entitle").value;

                                    // Convert the title to a slug: lowercase and replace spaces with -
                                    var slug = title.toLowerCase().replace(/\s+/g, '-');

                                    // Update the slug input field
                                    document.getElementById("slug").value = slug;
                                }
                                </script>
                                <input type="submit" class="btn btn-success" value="Submit" />
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->   
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<script src="../vendor/ckeditor/ckeditor.js"></script>
<script>

CKEDITOR.replace('editor',{
    filebrowserUploadUrl: '../vendor/ckeditor/ck_upload.php'
});
CKEDITOR.replace('editor1',{
    filebrowserUploadUrl: '../vendor/ckeditor/ck_upload.php'
});
</script>
</script>

<?php include('includes/footer.php'); ?>