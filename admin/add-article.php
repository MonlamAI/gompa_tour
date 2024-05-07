<?php 
// Enable error reporting
error_reporting(E_ALL);
// Display errors
ini_set('display_errors', 1);

// Your PHP code here
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php'); 

if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['entitle'])){$errors[] = "Title Field is Required";}
    if(empty($_POST['encontent'])){$errors[] = "Content Field is Required";}
    if(empty($_POST['slug'])){$slug = trim($_POST['title']); }else{$slug = trim($_POST['slug']);}
    if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM posts WHERE slug=?";
    $result = $db->prepare($sql);
    $result->execute(array($slug));
    $count = $result->rowCount();
    if($count == 1){
        $errors[] = "Slug already exists in database";
    }
    // CSRF Token Validation
    if(isset($_POST['csrf_token'])){
        if($_POST['csrf_token'] === $_SESSION['csrf_token']){
        }else{
            $errors[] = "Problem with CSRF Token Verification";
        }
    }else{
        $errors[] = "Problem with CSRF Token Validation";
    }
    
    if(empty($errors)){
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

        $sql = "INSERT INTO posts (uid, tbtitle, entitle, tbcontent, encontent, status, slug, pic) VALUES (:uid, :tbtitle, :entitle, :tbcontent, :encontent, :status, :slug, :pic)";
        $result = $db->prepare($sql);
        $values = array(':uid'      => $_SESSION['id'],
                        ':tbtitle'    => $_POST['tbtitle'],
                        ':entitle'    => $_POST['entitle'],
                        ':tbcontent'  => $_POST['tbcontent'],
                        ':encontent'  => $_POST['encontent'],
                        ':status'   => $_POST['status'],
                        ':slug'     => $slug,
                        ':pic'      => $dbpath
                        );
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if($res){
            // After inserting the article, insert category id and article id into post_categories table
            $pid = $db->lastInsertID();
            foreach ($_POST['categories'] as $category) {
            $sql = "INSERT INTO post_categories (pid, cid) VALUES (:pid, :cid)";
            $result = $db->prepare($sql);
            $values = array(':pid'  => $pid,
                            ':cid'  => $category
                            );
            $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
            }
            header("location: view-articles.php");
        }else{
            $errors[] = "Failed to Add Article";
        }
    }
}
// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

include('includes/header.php');
include('includes/navigation.php'); 
?>

<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">རྩོམ་ཡིག་གསར་སྣོན།</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
            
                    འདི་ནི་རྩོམ་ཡིག་རྩོམ་སྒྲིག་བྱ་ཡུལ་ཡིན།
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
                                <div class="form-group">
                                    <label>བོད་ཡིག་ཁ་བྱང་།</label>
                                    <input class="form-control" name="tbtitle" placeholder="བོད་ཡིག་ཁ་བྱང་་་་" value="<?php if(isset($_POST['tbtitle'])){ echo $_POST['tbtitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་ཁ་བྱང་།</label>
                                    <input class="form-control" oninput="updateSlug()" id="entitle" name="entitle" placeholder="དབྱིན་ཡིག་ཁ་བྱང་་་་" value="<?php if(isset($_POST['entitle'])){ echo $_POST['entitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>བོད་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" id="editor" name="tbcontent" rows="3"><?php if(isset($_POST['tbcontent'])){ echo $_POST['tbcontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" id="editor1" name="encontent" rows="3"><?php if(isset($_POST['encontent'])){ echo $_POST['encontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>པར་རིས།</label>
                                    <input type="file" name="pic">
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php 
                                            // Fetch categories from categories table
                                            $sql = "SELECT * FROM categories";
                                            $result = $db->prepare($sql);
                                            $result->execute();
                                            $res = $result->fetchAll(PDO::FETCH_ASSOC);
                                        
                                            ?>
                                            <label>སྡེ་ཚན་དབྱེ་བ།</label>
                                            <select multiple="" name="categories[]" class="form-control">
                                                <?php
                                                    foreach ($res as $cat) {
                                                                     
                                                       // if(in_array($cat['id'], $_POST['categories'])){ $checked = "selected"; }else{ $checked = ""; }
                                                        echo "<option value='".$cat['id']."'".$checked .">".$cat['title']."</option>";
              
                                                    }
                                                    
                                                ?>
                                            </select>
                                            
                                        </div>
                                    </div>
                                 
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>རྩོམ་ཡིག་གནས་བབ།</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios1" value="draft" <?php if(isset($_POST) & !empty($_POST)){ if($_POST['status'] == 'draft'){ echo "checked"; } } ?>>ཟིན་བྲིས།
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios2" value="review" <?php if(isset($_POST) & !empty($_POST)){ if($_POST['status'] == 'review'){ echo "checked"; } } ?>>བསྐྱར་ཞིབ་ཆེད།
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios3" value="published" <?php if(isset($_POST) & !empty($_POST)){ if($_POST['status'] == 'published'){ echo "checked"; } } ?>>ཡོངས་བསྒྲགས་འགྲེམ་སྤེལ།
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>རྩོམ་ཡིག་སྦྲེལ་མཐུད།</label>
                                    <input class="form-control" id="slug" name="slug" placeholder="Enter Article Slug Here" value="<?php if(isset($_POST['slug'])){ echo $_POST['slug'];} ?>">
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