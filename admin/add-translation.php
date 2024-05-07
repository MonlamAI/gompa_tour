<?php 
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php'); 

// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);



if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['key_name'])){$errors[] = "Key name Field is Required";}
    //if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    if(empty($_POST['key_name'])){$slug = trim($_POST['text_en']); }else{$slug = trim($_POST['key_name']);}
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM translations WHERE key_name=?";
    $result = $db->prepare($sql);
    $result->execute(array($slug));
    $count = $result->rowCount();
    if($count == 1){
        $errors[] = "Key name already exists in database";
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
        $sql = "INSERT INTO translations (key_name, text_en, text_bo, updated) VALUES (:key_name, :text_en, :text_bo, :updated)";
        $currentDateTime = date('Y-m-d H:i:s');
        $result = $db->prepare($sql);
        $values = array(
            ':key_name' => $_POST['key_name'],
            ':text_en'  => $_POST['text_en'],
            ':text_bo'  => $_POST['text_bo'],
            ':updated'  => $currentDateTime
        );

$res = $result->execute($values);
        if($res){
            header('location:view-translation.php');
        }else{
            $errors[] = "Failed to Add Category";
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
            <h1 class="page-header">ཡིག་སྒྱུར་གསར་སྣོན།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ཡིག་སྒྱུར་རྩོམ་སྒྲིག་བྱ་ཡུལ།
                </div>
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                   
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
                            <form role="form" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                <div class="form-group">
                                    <label>སྒྱུར་གཞི།</label>
                                    <input class="form-control" onchange="updateKey()" id="key_anme" name="key_name" placeholder="དཀར་ཆག་ཁ་བྱང་འདིར་འགོད།" value="<?php if(isset($_POST['key_name'])){ echo $_POST['key_name'];} ?>">
                                </div>
                                <script>
                                // Function to update the slug
                                function updateKey() {
                                    var title = document.getElementById("key_anme").value;

                                    // Convert the title to a slug: lowercase and replace spaces with -
                                    var slug = title.toLowerCase().replace(/\s+/g, '-');

                                    // Update the slug input field
                                    document.getElementById("key_anme").value = slug;
                                }
                                </script>
                                <div class="form-group">
                                    <label>དབྱིན་སྒྱུར།</label>
                                    <input class="form-control" name="text_en" placeholder="དཀར་ཆག་ཁ་བྱང་འདིར་འགོད།" value="<?php if(isset($_POST['text_en'])){ echo $_POST['text_en'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>བོད་སྒྱུར།</label>
                                    <input class="form-control" name="text_bo" placeholder="དཀར་ཆག་སྦྲེལ་ཐག་འདིར་འགོད།" value="<?php if(isset($_POST['text_bo'])){ echo $_POST['text_bo'];} ?>">
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
<?php include('includes/footer.php'); ?>