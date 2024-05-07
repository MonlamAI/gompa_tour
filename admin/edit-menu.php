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
    if(empty($_POST['en_title'])){$errors[] = "Title Field is Required";}
    //if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    if(empty($_POST['en_title'])){$slug = trim($_POST['en_title']); }else{$slug = trim($_POST['en_title']);}
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM menu WHERE en_title=:en_title AND id <> :id";
    $result = $db->prepare($sql) or die(print_r($result->errorInfo(), true));
    $values = array(':en_title'     => $_POST['en_title'],
                    ':id'       => $_POST['id']
                    );
    $result->execute($values);
    $count = $result->rowCount();
    if($count == 1){
        $errors[] = "en_title already exists in database";
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
        $sql = "UPDATE menu SET bo_title=:bo_title, en_title=:en_title, link=:link, updated=NOW() WHERE id=:id";
        $result = $db->prepare($sql);
        $values = array(':bo_title'            => $_POST['bo_title'],
                        ':en_title'      => $_POST['en_title'],
                        ':link'             => $_POST['link'],
                        ':id'               => $_POST['id']
                        );
        $res = $result->execute($values);
        if($res){
            header('location:view-menu.php');
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

$sql = "SELECT * FROM menu WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_GET['id']));
$cat = $result->fetch(PDO::FETCH_ASSOC);
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">དཀར་ཆག་བཟོ་བཅོས།</h1>
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
                                    <label>དཀར་ཆག་ཁ་བྱང་།</label>
                                    <input class="form-control"  name="bo_title" placeholder="འདིར་དཀར་ཆག་ཁ་བྱང་འགོད་དགོས།" value="<?php if(isset($cat['bo_title'])){ echo $cat['bo_title'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་ཁ་བྱང་།</label>
                                    <input class="form-control" id="en_title" oninput="updateSlug()" name="en_title" placeholder="འདིར་དཀར་ཆག་ཁ་བྱང་འགོད་དགོས།" value="<?php if(isset($cat['en_title'])){ echo $cat['en_title'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label>སྦྲེལ་ཐག</label>
                                    <input class="form-control"  name="link" placeholder="འདིར་དཀར་ཆག་ཁ་བྱང་འགོད་དགོས།" value="<?php if(isset($cat['link'])){ echo $cat['link'];} ?>">
                                </div>

                                <input type="submit"  class="btn btn-success" value="ནང་འཇུག" />
                            </form>
                            <script>
                                // Function to update the slug
                                function updateSlug() {
                                    var title = document.getElementById("en_title").value;

                                    // Convert the title to a slug: lowercase and replace spaces with -
                                    var slug = title.toLowerCase().replace(/\s+/g, '-');

                                    // Update the slug input field
                                    document.getElementById("slug").value = slug;
                                }
                                </script>
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