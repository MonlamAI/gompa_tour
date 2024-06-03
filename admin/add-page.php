<?php
use Aws\Exception\AwsException;

// Enable error reporting
error_reporting(E_ALL);
// Display errors
ini_set('display_errors', 1);
require_once ('../includes/connect.php');
include ('includes/check-login.php');
include ('includes/check-subscriber.php');
require_once ('../includes/s3_functions.php');

if (isset($_POST) & !empty($_POST)) {
    // PHP Form Validations
    if (empty($_POST['title'])) {
        $errors[] = "Title Field is Required";
    }
    if (empty($_POST['content'])) {
        $errors[] = "Content Field is Required";
    }
    if (empty($_FILES['pic']['name'])) {
        $errors[] = "You Should Upload a File";
    }
    if (empty($_POST['slug'])) {
        $slug = trim($_POST['title']);
    } else {
        $slug = trim($_POST['slug']);
    }
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM pages WHERE slug=?";
    $result = $db->prepare($sql);
    $result->execute(array($slug));
    $count = $result->rowCount();
    if ($count == 1) {
        $errors[] = "Slug already exists in database";
    }
    // CSRF Token Validation
    if (isset($_POST['csrf_token'])) {
        if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        } else {
            $errors[] = "Problem with CSRF Token Verification";
        }
    } else {
        $errors[] = "Problem with CSRF Token Validation";
    }
    // CSRF Token Time Validation
    $max_time = 60 * 60 * 24;
    if (isset($_SESSION['csrf_token_time'])) {
        $token_time = $_SESSION['csrf_token_time'];
        if (($token_time + $max_time) >= time()) {
        } else {
            $errors[] = "CSRF Token Expired";
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
    } else {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
    }
    if (empty($errors)) {
        if (isset($_FILES) & !empty($_FILES)) {
            $name = $_FILES['pic']['name'];
            $size = $_FILES['pic']['size'];
            $type = $_FILES['pic']['type'];
            $tmp_name = $_FILES['pic']['tmp_name'];

            if (isset($name) && !empty($name)) {
                if ($type == "image/jpeg") {
                    $location = "../media/";
                    $key = 'media/images/' . time() . $name; // The key is the path and filename in the S3 bucket

                    $filename = time() . $name;
                    $uploadpath = $location . $filename;
                    $dbpath = "media/images/" . $filename;

                    try {
                        $dbpath = uploadToS3($key, $tmp_name);
                        // Print the URL of the uploaded file
                    } catch (AwsException $e) {
                        // Catch any errors that occur during the upload process
                        echo 'Error uploading picture: ' . $e->getMessage();
                    }
                } else {
                    $errors[] = "Only Upload JPEG files";
                }
            }
        }
        $sql = "INSERT INTO pages (uid, title, content, status, slug, pic, page_order) VALUES (:uid, :title, :content, :status, :slug, :pic, :pageorder)";
        $result = $db->prepare($sql);
        $values = array(
            ':uid' => $_SESSION['id'],
            ':title' => $_POST['title'],
            ':content' => $_POST['content'],
            ':status' => $_POST['status'],
            ':slug' => $slug,
            ':pageorder' => $_POST['pageorder'],
            ':pic' => $dbpath
        );
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if ($res) {
            header("location: view-pages.php");
        } else {
            $errors[] = "Failed to Add Page";
        }
    }
}
// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

include ('includes/header.php');
include ('includes/navigation.php');
?>

<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ཤོག་ངོས་གསར་སྣོན།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ཤོག་ངོས་གསར་སྣོན་རྩོམ་སྒྲིག་བྱ་ཡུལ།...
                </div>
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                    <?php
                    if (!empty($messages)) {
                        echo "<div class='alert alert-success'>";
                        foreach ($messages as $message) {
                            echo "<span class='glyphicon glyphicon-ok'></span>&nbsp;" . $message . "<br>";
                        }
                        echo "</div>";
                    }
                    ?>
                    <?php
                    if (!empty($errors)) {
                        echo "<div class='alert alert-danger'>";
                        foreach ($errors as $error) {
                            echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;" . $error . "<br>";
                        }
                        echo "</div>";
                    }
                    ?>
                    <div class="row">

                        <div class="col-lg-12">
                            <form role="form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                <div class="form-group">
                                    <label>ཤོག་ངོས་ཁ་བྱང་།</label>
                                    <input class="form-control" name="title" placeholder="ཤོག་ངོས་ཁ་བྱང་།" value="<?php if (isset($_POST['title'])) {
                                        echo $_POST['title'];
                                    } ?>">
                                </div>
                                <div class="form-group">
                                    <label>འགྲེལ་བཤད།</label>
                                    <textarea class="form-control" id="editor" name="content" rows="3"><?php if (isset($_POST['content'])) {
                                        echo $_POST['content'];
                                    } ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>པར་རིས།</label>
                                    <input type="file" name="pic">
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>ཤོག་ངོས་གོ་རིམ།</label>
                                            <select name="pageorder" class="form-control">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>ཤོག་ངོས་གནས་བབ།</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios1" value="draft"
                                                        checked="">ཟིན་བྲིས།
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios3"
                                                        value="published">ཡོངས་གྲགས་འགྲེམ་སྤེལ།
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>སྦྲེལ་ཐག</label>
                                    <input class="form-control" name="slug" placeholder="སྦྲེལ་ཐག" value="<?php if (isset($_POST['slug'])) {
                                        echo $_POST['slug'];
                                    } ?>">
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
    <script src="../vendor/ckeditor/ckeditor.js"></script>
    <script>

        CKEDITOR.replace('editor');

    </script>
    </script>


    <?php include ('includes/footer.php'); ?>