<?php 
require_once('../includes/connect.php');
include('includes/check-login.php'); 
include('includes/check-subscriber.php');
if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['text_en'])){$errors[] = "Title Field is Required";}
    //if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    if(empty($_POST['key_name'])){$slug = trim($_POST['text_en']); }else{$slug = trim($_POST['key_name']);}
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM translations WHERE key_name=:key_name AND id <> :id";
    $result = $db->prepare($sql) or die(print_r($result->errorInfo(), true));
    $values = array(':key_name'     => $_POST['key_name'],
                    ':id'       => $_POST['id']
                    );
    $result->execute($values);
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
        // Update SQL Query
        $sql = "UPDATE translations SET key_name=:key_name, text_en=:text_en, text_bo=:text_bo, updated=NOW() WHERE id=:id";
        $result = $db->prepare($sql);
        $values = array(':key_name'            => $_POST['key_name'],
                        ':text_en'      => $_POST['text_en'],
                        ':text_bo'             => $_POST['text_bo'],
                        ':id'               => $_POST['id']
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

$sql = "SELECT * FROM translations WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_GET['id']));
$cat = $result->fetch(PDO::FETCH_ASSOC);
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ཡིག་བསྒྱུར་བཟོ་བཅོས།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    དཀར་ཆག་བཟོ་བཅོས་བྱ་ཡུལ་འདི་ཡིན།
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
                            <form role="form" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    <label>སྒྱུར་གཞི།</label>
                                    <input class="form-control" id="title" oninput="updateSlug()" name="key_name" placeholder="འདིར་དཀར་ཆག་ཁ་བྱང་འགོད་དགོས།" value="<?php if(isset($cat['key_name'])){ echo $cat['key_name'];} ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་སྒྱུར།</label>
                                    <input class="form-control" id="title" oninput="updateSlug()" name="text_en" placeholder="འདིར་དཀར་ཆག་ཁ་བྱང་འགོད་དགོས།" value="<?php if(isset($cat['text_en'])){ echo $cat['text_en'];} ?>">

                                </div>
                                <div class="form-group">
                                    <label>བོད་སྒྱུར།</label>
                                    <input class="form-control" name="text_bo" placeholder="འདིར་ཁ་བྱང་གི་སྦྲེལ་ཐག་འགོད་དགོས།" value="<?php if(isset($cat['text_bo'])){ echo $cat['text_bo'];} ?>">
                                </div>

                                <input type="submit"  class="btn btn-success" value="ནང་འཇུག" />
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