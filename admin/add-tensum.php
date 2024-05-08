<?php 
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php'); 

$dbpath = ""; // Initialize to a default value or appropriate fallback
$dbpathSound = ""; // Initialize to a default value or appropriate fallback

if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['tbtitle'])){$errors[] = "Tibetan Title Field is Required";}
    if(empty($_POST['entitle'])){$errors[] = "English Title Field is Required";}
    if(empty($_POST['tbcontent'])){$errors[] = "Tibetan Content Field is Required";}
    if(empty($_POST['encontent'])){$errors[] = "English Content Field is Required";}
    if(empty($_POST['slug'])){$slug = trim($_POST['title']); }else{$slug = trim($_POST['slug']);}
    if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM tensum WHERE slug=?";
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
    
        if(empty($errors)) {
            $sql = "INSERT INTO tensum (uid, tbtitle, entitle, tbcontent, encontent, status, callnumber, slug, pic, sound) VALUES (:uid, :tbtitle, :entitle, :tbcontent, :encontent, :status, :callnumber, :slug, :pic, :sound)";
            $result = $db->prepare($sql);
            $values = array(':uid'      => $_SESSION['id'],
                            ':tbtitle'    => $_POST['tbtitle'],
                            ':entitle'    => $_POST['entitle'],
                            ':tbcontent'  => $_POST['tbcontent'],
                            ':encontent'  => $_POST['encontent'],
                            ':status'   => $_POST['status'],
                            ':callnumber'   => $_POST['callnumber'],
                            ':slug'     => $slug,
                            ':pic'      => $dbpath, // Ensure these are defined or default
                            ':sound'      => $dbpathSound // Ensure these are defined or default
                            );
            try {
                $res = $result->execute($values);
                if($res){
                    $pid = $db->lastInsertID();
                    foreach ($_POST['categories'] as $category) {
                        $sql = "INSERT INTO post_categories (pid, cid) VALUES (:pid, :cid)";
                        $result = $db->prepare($sql);
                        $values = array(':pid'  => $pid,
                                        ':cid'  => $category
                                        );
                        $result->execute($values);
                    }
                    header("Location: view-tensum.php");
                    exit; // Ensure script execution stops after redirect
                }
            } catch (Exception $e) {
                // Log the error or handle it as per your error handling logic
                $errors[] = "Failed to Add Tensum: " . $e->getMessage();
            }
        }
    }else{
        echo 'alert("I am an alert box!");';
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
            <h3 class="page-header">རྟེན་བཤད་གསར་སྣོན།</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
            
                    སྐུ་གསུང་ཐུགས་གསུམ་དང་འབྲེལ་བའི་རྟེན་བཤད་རྩོམ་སྒྲིག་བྱ་ཡུལ།...
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
                                    <label>བོད་ཡིག་མཚན་བྱང་།</label>
                                    <input class="form-control" name="tbtitle" placeholder="བོད་ཡིག་མཚན་བྱང་འགོད་རོགས།" value="<?php if(isset($_POST['tbtitle'])){ echo $_POST['tbtitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་མཚན་བྱང་།</label>
                                    <input id="entitle" class="form-control"oninput="updateSlug()" name="entitle" placeholder="དབྱིན་ཡིག་མཚན་བྱང་འགོད་རོགས།" value="<?php if(isset($_POST['entitle'])){ echo $_POST['entitle'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>བོད་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" name="tbcontent" rows="3"><?php if(isset($_POST['tbcontent'])){ echo $_POST['tbcontent'];} ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" name="encontent" rows="3"><?php if(isset($_POST['encontent'])){ echo $_POST['encontent'];} ?></textarea>
                                </div>
                                <div class="form-group col-lg-6">
                                <?php if(isset($post['pic']) && !empty($post['pic'])): ?>
                                    <img style="object-fit: cover; border-radius: 5px; margin-right: 6px;" src="<?php echo htmlspecialchars('../' . $post['pic']); ?>" height="70" width="100">
                                    <a href="delete-pic.php?id=<?php echo urlencode($_GET['id']); ?>&type=tensum">པར་རིས་གསུབ།</a>
                                <?php else: ?>
                                    <label for="pic">པར་རིས།</label>
                                    <input type="file" id="pic" name="pic">
                                    <div id="imageTypeError" style="color: red; display: block; margin-top: 10px;">པར་རིས་ནི། JPEG རྣམ་ཅན་ཁོ་ན་ལས་ངོས་ལེན་མི་བྱེད།</div>
                                <?php endif; ?>
                              
                                <?php if(isset($post['sound']) && !empty($post['sound'])): ?>
                                    <audio controls style="margin-right: 6px;height: 10px;" src="<?php echo htmlspecialchars('../' . $post['sound']); ?>"></audio>
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
                                                    <input type="radio" name="status" id="optionsRadios1" value="draft" <?php if(isset($_POST) & !empty($_POST)){ if($_POST['status'] == 'draft'){ echo "checked"; } } ?>>ཟིན་བྲིས།
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios2" value="review" <?php if(isset($_POST) & !empty($_POST)){ if($_POST['status'] == 'review'){ echo "checked"; } } ?>>བསྐྱར་ཞིབ་ངང་སྒུག
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios3" value="published" <?php if(isset($_POST) & !empty($_POST)){ if($_POST['status'] == 'published'){ echo "checked"; } } ?>>ཡོངས་ཁྱབ་འགྲེམ་སྤེལ།
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>འབོད་རྟགས་ཨང་།</label>
                                    <input class="form-control" name="callnumber" placeholder="འདིར་འབོད་རྟགས་ཨང་འགོད་དགོས།" value="<?php if(isset($_POST['slug'])){ echo $_POST['callnumber'];} ?>">
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
                                    <label>ཚད་ལྡན་སྦྲེལ་ཐག་ཡི་གེ།</label>
                                    <input id="slug" class="form-control" name="slug" placeholder="ཚད་ལྡན་སྦྲེལ་ཐག་ཡི་གེ་འགོད་དགོས" value="<?php if(isset($_POST['slug'])){ echo $_POST['slug'];} ?>">
                                </div>
                                <input type="submit" class="btn btn-success" value="གསར་འཇུག" />
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

<?php include('includes/footer.php'); ?>