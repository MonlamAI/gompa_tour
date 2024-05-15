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
   
    if(empty($_POST['tbtitle'])){$errors[] = "Tibetan Title Field is Required";}
    if(empty($_POST['entitle'])){$errors[] = "English Title Field is Required";}
    if(empty($_POST['tbcontent'])){$errors[] = "Tibetan Content Field is Required";}
    if(empty($_POST['encontent'])){$errors[] = "English Content Field is Required";}
    if(empty($_POST['slug'])){$errors[] = "English tow char and 6 numbers feled is Rewuired";}
   
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
        if(isset($_FILES) && !empty($_FILES)){
            // Check for successful upload of the picture
            if ($_FILES['pic']['error'] == UPLOAD_ERR_OK) {
                $name = $_FILES['pic']['name'];
                $type = $_FILES['pic']['type'];
                $tmp_name = $_FILES['pic']['tmp_name'];
    
                if(isset($name) && !empty($name)){
                    if($type == "image/jpeg"){
                        $location = "../media/images/";
                        $filename = time() . $name; // Securely generating a new filename
                        $uploadpath = $location.$filename;
                        $dbpath = "media/images/" . $filename;
                        move_uploaded_file($tmp_name, $uploadpath);
                    }else{
                        $errors[] = "Only Upload JPEG files";
                    }
                }
            }
    
            // Check for successful upload of the sound file
            if ($_FILES['sound']['error'] == UPLOAD_ERR_OK) { // Correctly checking the sound file now
                $nameSound = $_FILES['sound']['name'];
                $typeSound = $_FILES['sound']['type'];
                $tmp_nameSound = $_FILES['sound']['tmp_name'];
    
                if(isset($nameSound) && !empty($nameSound)){
                    if($typeSound == "audio/mpeg"){ // Correctly checking the MIME type for the sound file
                        $locationSound = "../media/audios/";
                        $filenameSound = time() . $nameSound; // Securely generating a new filename
                        $uploadpathSound = $locationSound.$filenameSound;
                        $dbpathSound = "media/audios/" . $filenameSound;
                        move_uploaded_file($tmp_nameSound, $uploadpathSound);
                    }else{
                        $errors[] = "Only Upload Audio files";
                    }
                }
            }
            
        }
    
        
        
        $sql = "UPDATE tensum SET tbtitle=:tbtitle, entitle=:entitle, tbcontent=:tbcontent, encontent=:encontent, status=:status, callnumber=:callnumber, slug=:slug, ";
        if(isset($dbpath) && !empty($dbpath)){ $sql .="pic=:pic, "; }  
        if(isset($dbpathSound) && !empty($dbpathSound)){ $sql .="sound=:sound, "; } 
        $sql .= "updated=NOW() WHERE id=:id";
    
        $result = $db->prepare($sql);
        $values = array(':tbtitle'    => $_POST['tbtitle'],
                        ':entitle'    => $_POST['entitle'],
                        ':tbcontent'  => $_POST['tbcontent'],
                        ':encontent'  => $_POST['encontent'],
                        ':status'   => $_POST['status'],
                        ':callnumber'   => $_POST['callnumber'],
                        ':slug'     => $_POST['slug'],
                        ':id'       => $_POST['id'],
                        );
        if(isset($dbpath) && !empty($dbpath)){ $values[':pic'] = $dbpath;}
        if(isset($dbpathSound) && !empty($dbpathSound)){ $values[':sound'] = $dbpathSound;}
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        
        if($res){
            // TODO : removing non selected categories from post_categories table
            // $pid = $_POST['id'];
            // foreach ($_POST['categories'] as $category) {
            //     $catsql = "SELECT * FROM post_categories WHERE pid=:pid AND cid=:cid";
            //     $catresult = $db->prepare($catsql);
            //     $values = array(':pid'      => $pid,
            //                     ':cid'      => $category,
            //                     );
            //     $catresult->execute($values);
            //     $catcount = $catresult->rowCount();
            //     if($catcount == 1){}else{
            //         $sql = "INSERT INTO post_categories (pid, cid) VALUES (:pid, :cid)";
            //         $result = $db->prepare($sql);
            //         $values = array(':pid'  => $pid,
            //                         ':cid'  => $category
            //                         );
            //         $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
            //     }
            // }
            header("location: view-tensum.php");
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
            <h1 class="page-header">རྟེན་བཤད་གསར་སྣོན།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    རྟེན་བཤད་དང་འབྲེལ་བའི་རྩོམ་སྒྲིག་བྱ་ཡུལ།...
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
                                    <label>བོད་ཡིག་མཚན་བྱང་།</label>
                                    <input class="form-control" name="tbtitle" placeholder="Enter Article Title" value="<?php if(isset($post['tbtitle'])){ echo $post['tbtitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་མཚན་བྱང་།</label>
                                    <input id="entitle" class="form-control" name="entitle" placeholder="Enter Article Title" value="<?php if(isset($post['entitle'])){ echo $post['entitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>བོད་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" name="tbcontent" rows="3"><?php if(isset($post['tbcontent'])){ echo $post['tbcontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" name="encontent" rows="3"><?php if(isset($post['encontent'])){ echo $post['encontent'];} ?></textarea>
                                </div>
                                <div class="form-group col-lg-6">
                                <?php if(isset($post['pic']) && !empty($post['pic'])): ?>
                                    <img style="object-fit: cover; border-radius: 5px; margin-right: 6px;" src="<?php echo htmlspecialchars('../' . $post['pic']); ?>" height="70" width="100">
                                    <a href="delete-pic.php?id=<?php echo urlencode($_GET['id']); ?>&type=tensum">པར་རིས་གསུབ།</a>
                                <?php else: ?>
                                    <label for="pic">པར་རིས།</label>
                                    <input type="file" id="pic" name="pic">
                                    <div id="imageTypeError" style="color: red; display: block; margin-top: 10px;">པར་རིས་ JPEG རྣམ་ཅན་ཁོ་ན་ལས་ངོས་ལེན་མི་བྱེད།</div>
                                <?php endif; ?>
                                
                                <?php if(isset($post['sound']) && !empty($post['sound'])): ?>
                                    <audio controls style="margin-right: 6px;" src="<?php echo htmlspecialchars('../' . $post['sound']); ?>"></audio>
                                    <a href="delete-sound.php?id=<?php echo urlencode($_GET['id']); ?>&type=tensum">སྒྲ་གསུབ།</a>
                                <?php else: ?>
                                    <label for="sound">རྟེན་བཤད་འདིའི་སྒྲ།</label>
                                    <input type="file" id="sound" name="sound">
                                    <div id="soundTypeError" style="color: red; display: block; margin-top: 10px;">སྒྲ་ནི། MP3 རྣམ་ཅན་ཁོ་ན་ལས་ངོས་ལེན་མི་བྱེད།</div>
                                <?php endif; ?>
                                </div>
                                <script>
                                document.getElementById('sound').addEventListener('change', function(e) {
                                    var allowedExtensions = /(\.mp3)$/i; // Regex to check for .mp3 extension
                                    var filePath = this.value;
                                    if (!allowedExtensions.exec(filePath)) {
                                        document.getElementById('soundTypeError').style.display = 'block'; // Show error message
                                        this.value = ''; // Reset the file input
                                        e.preventDefault(); // Prevent form submission (optional, depends on your needs)
                                    } else {
                                        document.getElementById('soundTypeError').style.display = 'none'; // Hide error message if file is valid
                                    }
                                });
                                document.getElementById('pic').addEventListener('change', function(e) {
                                    var allowedExtensions = /(\.jpg)$/i; // Regex to check for .mp3 extension
                                    var filePath = this.value;
                                    if (!allowedExtensions.exec(filePath)) {
                                        document.getElementById('imageTypeError').style.display = 'block'; // Show error message
                                        this.value = ''; // Reset the file input
                                        e.preventDefault(); // Prevent form submission (optional, depends on your needs)
                                    } else {
                                        document.getElementById('imageTypeError').style.display = 'none'; // Hide error message if file is valid
                                    }
                                });
                                </script>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            
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
                                                    <input type="radio" name="status" id="optionsRadios2" value="review" <?php if($post['status'] == 'review'){ echo "checked"; } ?>>བསྐྱར་ཞིབ་ངང་སྒུག
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
                                    <label>འབོད་རྟགས།</label>
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
                                    <label>སྦྲེལ་ཐག <label style="color: brown;">འདི་ནི་ཧ་ཅང་གི་གལ་ཆེན་ཡིན། དཔེར་ན། TN693842 འདི་ལྟ་བུའི་ཡི་གེ་གཉིས་དང་ཨང་གྲངས་ ༦ ངེས་པར་དགོས།</label> </label>
                                    <input id="slug" class="form-control" name="slug" placeholder="Enter Article Slug Here" value="<?php if(isset($post['slug'])){ echo $post['slug'];} ?>">
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