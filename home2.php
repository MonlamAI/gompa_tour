<?php 
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php'); 
$errors = []; 

if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['tbtitle'])){$errors[] = "Tibetan Title Field is Required";}
    if(empty($_POST['entitle'])){$errors[] = "English Title Field is Required";}
    if(empty($_POST['tbcontent'])){$errors[] = "Tibetan Content Field is Required";}
    if(empty($_POST['encontent'])){$errors[] = "English Content Field is Required";}
    if(empty($_POST['slug'])){$slug = trim($_POST['title']); }else{$slug = trim($_POST['slug']);}
    if(empty($_FILES['imageFile']['name'])){$errors[] = "You Should Upload a Image File";}
    if(empty($_FILES['soundFile']['name'])){$errors[] = "You Should Upload a Sound File";}
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
        if(isset($_FILES) & !empty($_FILES)){
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $type = $_FILES['file']['type'];
            $tmp_name = $_FILES['file']['tmp_name'];

            if(isset($name) && !empty($name)){
                // Set location based on file type
                if($type == "image/jpeg"){
                    $location = "../media/images/";
                    $dbpathImage = "media/images/" . $name;
                } elseif($type == "audio/mpeg") {
                    $location = "../media/sounds/";
                    $dbpathSound = "media/sounds/" . $name;
                } else {
                    $errors[] = "Only JPEG images and MP3 sounds are allowed.";
                }
                // If no errors, proceed with the file upload
                if(empty($errors)){
                    $filename = time() . '_' . $name; // Prefixing the filename with the current timestamp to avoid overwriting files with the same name
                    $uploadpath = $location . $filename;
                    if(move_uploaded_file($tmp_name, $uploadpath)){
                        // Here you would insert $dbpath (and potentially other file info) into your database
                        // For example: $db->query("INSERT INTO uploads (filename, filepath) VALUES ('".$filename."', '".$dbpath."')");
                        echo "The file has been uploaded successfully.";
                    } else {
                        $errors[] = "Failed to move the uploaded file.";
                    }
                }
        }else {
            $errors[] = "No file selected.";
        }
    }

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
                        ':pic'      => $dbpathImage,
                        ':sound'      => $dbpathSound
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
            header("location: view-tensum.php");
        }else{
            $errors[] = "Failed to Add Tensum";
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
<script src="https://cdn.tiny.cloud/1/u21pwrlpb56ivo6bzi9y6qujxuawv9mzp7rdxqx1d8hs2jrh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>


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
            
                    སྐུ་གསུང་ཐུགས་གསུམ་དང་འབྲེལ་བའི་རྩོམ་སྒྲིག་བྱ་ཡུལ།...
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
                                <div class="form-group">
                                    <label>Featured Image</label>
                                    <input type="file" name="imageFile" id="imageFile" accept="image/jpeg">
                                </div>
                                <div class="form-group">
                                    <label>Sound file</label>
                                    <input type="file" name="soundFile" id="soundFile" accept="audio/mpeg">
                                </div>

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
                                <!-- <div class="form-group">
                                    <label>Qr code</label>
                                    <div style="width: 400px; margin: 10px auto; text-align:center">
                                    <input type="text" id="qrText" placeholder="Enter text or URL">
                                    <button id="generateButton">Generate QR Code</button>
                                    <div id="qrcode"></div>
                                    </div>

                                </div> -->
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
                                <input type="submit" class="btn btn-success" value="ནང་འཇུག" />
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
<!-- <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
  <script>
    const qrText = document.getElementById('qrText');
    const generateButton = document.getElementById('generateButton');
    const qrcode = document.getElementById('qrcode');

    generateButton.addEventListener('click', () => {
    // Clear any previous QR code
    qrcode.innerHTML = ''; 

    // Create the QR code
    new QRCode(qrcode, {
        text: qrText.value,
        width: 256,
        height: 256,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H // Higher error correction level
    });
    });
  </script> -->
<?php include('includes/footer.php'); ?>