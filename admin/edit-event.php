<?php 
// Enable error reporting
error_reporting(E_ALL);
// Display errors
ini_set('display_errors', 1);

require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php'); 

require_once '../vendor/phpqrcode/qrlib.php';
if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
   
    if(empty($_POST['event_tbname'])){$errors[] = "Tibetan Title Field is Required";}
    if(empty($_POST['event_enname'])){$errors[] = "English Title Field is Required";}
    if(empty($_POST['tb_description'])){$errors[] = "Tibetan Content Field is Required";}
    if(empty($_POST['en_description'])){$errors[] = "English Content Field is Required";}
    if(empty($_POST['categories'])){$errors[] = "Call Number Field is Required";}
   
    //if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    if(empty($_POST['slug'])){$slug = trim($_POST['title']); }else{$slug = trim($_POST['slug']);}
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM tensum WHERE slug=:slug AND id <> :id";
    $result = $db->prepare($sql) or die(print_r($result->errorInfo(), true));
    $values = array(':slug'     => $_POST['slug'],
                    ':id'       => $_POST['id']
                    );
    $result->execute($values);
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
    // CSRF Token Time Validation
    $max_time = 60*60*24;
    if(isset($_SESSION['csrf_token_time'])){
        $token_time = $_SESSION['csrf_token_time'];
        if(($token_time + $max_time) >= time()){
        }else{
            $errors[] = "CSRF Token Expired";
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
    }else{
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
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
        
        
        //$sql = "UPDATE events SET tbtitle=:tbtitle, entitle=:entitle, tbcontent=:tbcontent, encontent=:encontent, status=:status, callnumber=:callnumber, slug=:slug, ";
        
        $sql = " UPDATE events SET event_tbname=:event_tbname ,event_enname=:event_enname, start_date=:start_date, end_date=:end_date, 
        tb_description=:tb_description, en_description=:en_description, categories=:categories, location=:location, 
        status=:status, slug=:slug,";
        
        
        if(isset($dbpath) & !empty($dbpath)){ $sql .="pic=:pic, "; }  
        $sql .= "updated=NOW() WHERE id=:id";
    
        $result = $db->prepare($sql);
        $values = array(':event_tbname'    => $_POST['event_tbname'],
                        ':event_enname'    => $_POST['event_enname'],

                        ':start_date'    => $_POST['start_date'],
                        ':end_date'    => $_POST['end_date'],

                        ':tb_description'  => $_POST['tb_description'],
                        ':en_description'  => $_POST['en_description'],
                        ':categories'   => $_POST['categories'],
                        ':location'   => $_POST['location'],

                        ':status'   => $_POST['status'],
                        ':created'   => $_POST['created'],
                        ':slug'     => $_POST['slug'],
                        ':id'       => $_POST['id'],
                        );
        if(isset($dbpath) & !empty($dbpath)){ $values[':pic'] = $dbpath;}
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        
        
        
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
    $sql = "SELECT * FROM tensum WHERE id=?";
    $result = $db->prepare($sql);
    $result->execute(array($_GET['id']));
    $post = $result->fetch(PDO::FETCH_ASSOC);       
}elseif($user['role'] == 'editor'){
    $sql = "SELECT * FROM tensum WHERE id=? AND uid={$_SESSION['id']}";
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
            <h1 class="page-header">Add New Tensum</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create a New Article Here...
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
                                    <label>Article Tb Title</label>
                                    <input class="form-control" name="tbtitle" placeholder="Enter Article Title" value="<?php if(isset($post['tbtitle'])){ echo $post['tbtitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>Article En Title</label>
                                    <input id="entitle" class="form-control" oninput="updateSlug()"name="entitle" placeholder="Enter Article Title" value="<?php if(isset($post['entitle'])){ echo $post['entitle'];} ?>">
                                </div>


                                
                                <div class="form-group">
                                    <label>Article Tb Content</label>
                                    <textarea class="form-control" name="tbcontent" rows="3"><?php if(isset($post['tbcontent'])){ echo $post['tbcontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Article En Content</label>
                                    <textarea class="form-control" name="encontent" rows="3"><?php if(isset($post['encontent'])){ echo $post['encontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <?php
                                        if(isset($post['pic']) & !empty($post['pic'])){
                                            echo "<img style='object-fit: cover;border-radius: 5px;margin-right: 6px;' src='../".$post['pic']."' height='70px' width='100px'>";
                                            echo "<a href='delete-pic.php?id=". $_GET['id'] ."&type=tensum'>Delete Pic</a>";
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
                                            <label>རྩོམ་སྒྲིག་གནས་བབ།</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios1" value="draft" <?php if($post['status'] == 'draft'){ echo "checked"; } ?>>ཟིན་བྲིས།
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios2" value="review" <?php if($post['status'] == 'review'){ echo "checked"; } ?>>བསྐྱར་བཅོས་ངང་སྒུག
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios3" value="published" <?php if($post['status'] == 'published'){ echo "checked"; } ?>>ཡོངས་གྲགས་འགྲེམ་སྤེལ།
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Call Number</label>
                                    <input class="form-control" name="callnumber" placeholder="Enter Call Number Here" value="<?php if(isset($post['callnumber'])){ echo $post['callnumber'];} ?>">
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
                                <div class="form-group">
                                    <label>སྦྲེལ་ཐག</label>
                                    <input id="slug" class="form-control" name="slug" placeholder="སྦེལ་ཐག་་་" value="<?php if(isset($post['slug'])){ echo $post['slug'];} ?>">
                                </div>
                                <input type="submit" class="btn btn-success" value="ནང་འཇུག" />
                            </form>
                            <?php 
                            if(isset($post['slug'])){                                  
                                $text ='http://localhost/Blog-PHP/tensum.php?url='.$post['slug'].'';
                                
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
<?php include('includes/footer.php'); ?>