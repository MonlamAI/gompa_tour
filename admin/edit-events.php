<?php
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);
require_once ('../includes/connect.php');
include ('includes/check-login.php');
include ('includes/check-subscriber.php');

if (isset($_POST) & !empty($_POST)) {
    // PHP Form Validations

    if (empty($_POST['event_tbname'])) {
        $errors[] = "Tibetan Title Field is Required";
    }
    if (empty($_POST['event_enname'])) {
        $errors[] = "English Title Field is Required";
    }
    if (empty($_POST['tb_description'])) {
        $errors[] = "Tibetan Content Field is Required";
    }
    if (empty($_POST['en_description'])) {
        $errors[] = "English Content Field is Required";
    }
    //if(empty($_POST['callnumber'])){$errors[] = "Call Number Field is Required";}

    //if(empty($_FILES['pic']['name'])){$errors[] = "You Should Upload a File";}
    if (empty($_POST['slug'])) {
        $slug = trim($_POST['title']);
    } else {
        $slug = trim($_POST['slug']);
    }
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM events WHERE slug=:slug AND id <> :id";
    $result = $db->prepare($sql) or die(print_r($result->errorInfo(), true));
    $values = array(
        ':slug' => $_POST['slug'],
        ':id' => $_POST['id']
    );
    $result->execute($values);
    $count = $result->rowCount();
    if ($count == 1) {
        $errors[] = "Slug already exists in database";
    }


    if (empty($errors)) {
        // TODO : Only user with Administrator privillages or user who created the article can only edit 
        if (isset($_FILES) & !empty($_FILES)) {
            $name = $_FILES['pic']['name'];
            $size = $_FILES['pic']['size'];
            $type = $_FILES['pic']['type'];
            $tmp_name = $_FILES['pic']['tmp_name'];

            if (isset($name) && !empty($name)) {
                if ($type == "image/jpeg") {
                    $location = "../media/";
                    $filename = time() . $name;
                    $uploadpath = $location . $filename;
                    $dbpath = "media/" . $filename;
                    move_uploaded_file($tmp_name, $uploadpath);
                } else {
                    $errors[] = "Only Upload JPEG files";
                }
            }
        }

        $sql = "UPDATE events SET event_tbname=:event_tbname, event_enname=:event_enname, start_date=:start_date, end_date=:end_date, tb_description=:tb_description, en_description=:en_description, categories=:categories, location=:location, status=:status, slug=:slug, ";
        if (isset($dbpath) & !empty($dbpath)) {
            $sql .= "pic=:pic, ";
        }
        $sql .= "updated=NOW() WHERE id=:id";

        //echo $startDate = isset($post['start_date']) ? date('Y-m-d', strtotime($post['start_date'])) : null;
        // echo $endDate = isset($post['end_date']) ? date('Y-m-d', strtotime($post['end_date'])) : null;
        $inputStartDate = $_POST['start_date'];
        $mysqlStartDateFormat = date('Y-m-d', strtotime($inputStartDate));
        $inputEndDate = $_POST['end_date'];
        $mysqlEndDateFormat = date('Y-m-d', strtotime($inputEndDate));


        $result = $db->prepare($sql);
        $values = array(
            ':event_tbname' => $_POST['event_tbname'],
            ':event_enname' => $_POST['event_enname'],
            ':start_date' => $mysqlStartDateFormat,
            ':end_date' => $mysqlEndDateFormat,
            ':tb_description' => $_POST['tb_description'],
            ':en_description' => $_POST['en_description'],
            ':categories' => $_POST['categories'],
            ':location' => $_POST['location'],
            ':status' => $_POST['status'],
            ':slug' => $_POST['slug'],
            ':id' => $_GET['id'],
        );

        if (isset($dbpath) & !empty($dbpath)) {
            $values[':pic'] = $dbpath;
        }
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        header("location: view-event.php");

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

if ($user['role'] == 'administrator') {
    $sql = "SELECT * FROM events WHERE id=?";
    $result = $db->prepare($sql);
    $result->execute(array($_GET['id']));
    $post = $result->fetch(PDO::FETCH_ASSOC);
} elseif ($user['role'] == 'editor') {
    $sql = "SELECT * FROM events WHERE id=? AND uid={$_SESSION['id']}";
    $result = $db->prepare($sql);
    $result->execute(array($_GET['id']));
    $postcount = $result->rowCount();
    $post = $result->fetch(PDO::FETCH_ASSOC);
    if ($postcount <= 0) {
        header("location: view-event.php");
    }
}

include ('includes/header.php');
include ('includes/navigation.php');
?>



<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">དུས་ཆེན་བཟོ་བཅོས།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    དུས་ཆེན་དང་འབྲེལ་བའི་རྩོམ་སྒྲིག...
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
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    <label>བོད་ཡིག་མཚན་བྱང་།</label>
                                    <input class="form-control" name="event_tbname"
                                        placeholder="བོད་ཡིག་མཚན་བྱང་འགོད་རོགས།" value="<?php if (isset($post['event_tbname'])) {
                                            echo $post['event_tbname'];
                                        } ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་མཚན་བྱང་།</label>
                                    <input class="form-control" id="event_enname" name="event_enname"
                                        oninput="updateSlug()" placeholder="དབྱིན་ཡིག་མཚན་བྱང་འགོད་རོགས།" value="<?php if (isset($post['event_enname'])) {
                                            echo $post['event_enname'];
                                        } ?>">

                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                            <label class="control-label"
                                                for="datepicker-start">འགོ་འཛུགས་ཟླ་ཚེས།</label>
                                            <input value="<?php echo date('Y-m-d', strtotime($post['start_date'])); ?>"
                                                class="form-control" name="start_date" data-date-format="m/d/Y"
                                                id="datepicker">

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                            <label class="control-label" for="datepicker-end">མཇུག་མཐའི་ཟླ་ཚེས།</label>
                                            <input value="<?php echo date('Y-m-d', strtotime($post['end_date'])); ?>"
                                                class="form-control" name="end_date" data-date-format="m/d/Y"
                                                id="datepicker1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>བོད་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea style="line-height: 28px;" class="form-control" name="tb_description"
                                        rows="3"><?php if (isset($post['tb_description'])) {
                                            echo $post['tb_description'];
                                        } ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea style="line-height: 28px;" class="form-control" name="en_description"
                                        rows="3"><?php if (isset($post['en_description'])) {
                                            echo $post['en_description'];
                                        } ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>སྡེ་ཚན་དབྱེ་བ།</label>
                                        <input type="text" class="form-control custom-font-input" value="<?php if (isset($post['categories'])) {
                                            echo $post['categories'];
                                        } ?>" name="categories" placeholder="Enter the categories" list="list-timezone"
                                            id="input-datalist1">
                                        <datalist id="list-timezone">
                                            <option>ཆོས་ཀྱི་དུས་ཆེན།</option>
                                            <option>རིག་གཞུང་།</option>
                                            <option>སྐད་ཡིག།</option>
                                            <option>དམིགས་བསལ།</option>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                            <label>ས་ཕྱོགས་ཨང་རྟགས།</label>
                                            <input class="form-control" name="location"
                                                placeholder="Enter Location Here" value="<?php if (isset($post['location'])) {
                                                    echo $post['location'];
                                                } ?>">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?php
                                    if (isset($post['pic']) & !empty($post['pic'])) {
                                        echo "<img style='object-fit: cover;border-radius: 5px;margin-right: 6px;' src=" . $post['pic'] . " height='70px' width='100px'>";
                                        echo "<a href='delete-pic.php?id=" . $_GET['id'] . "&type=events'>Delete Pic</a>";
                                    } else {
                                        ?>
                                        <label>དུས་ཆེན་དང་འབྲེལ་བའི་པར་རིས།</label>
                                        <input type="file" name="pic">
                                    <?php } ?>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>རྩོམ་སྒྲིག་གནས་བབ།</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios1" value="draft"
                                                        <?php if ($post['status'] == 'draft') {
                                                            echo "checked";
                                                        } ?>>ཟིན་བྲིས།
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios2" value="review"
                                                        <?php if ($post['status'] == 'review') {
                                                            echo "checked";
                                                        } ?>>བསྐྱར་ཞིབ་ངང་སྒུག
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios3"
                                                        value="published" <?php if ($post['status'] == 'published') {
                                                            echo "checked";
                                                        } ?>>ཡོངས་གྲགས་འགྲེམ་སྤེལ།
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>སྦྲེལ་ཐག</label>
                                    <input class="form-control" id="slug" name="slug"
                                        placeholder="Enter Article Slug Here" value="<?php if (isset($post['slug'])) {
                                            echo $post['slug'];
                                        } ?>">
                                    <script>
                                        // Function to update the slug
                                        function updateSlug() {
                                            var title = document.getElementById("event_enname").value;

                                            // Convert the title to a slug: lowercase and replace spaces with -
                                            var slug = title.toLowerCase().replace(/\s+/g, '-');

                                            // Update the slug input field
                                            document.getElementById("slug").value = slug;
                                        }
                                    </script>
                                </div>
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
    <?php include ('includes/footer.php'); ?>



    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


    <!-- Include jQuery UI CSS for styling -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <script type="text/javascript">
        $(document).ready(function () {
            $('#datepicker').datepicker({
                weekStart: 1,
                daysOfWeekHighlighted: "6,0",
                autoclose: true,
                todayHighlight: true
            });



            <?php if (!empty($post['start_date'])): ?>
                $('#datepicker').datepicker("setDate", "<?php echo htmlspecialchars(date('Y-m-d', strtotime($post['start_date']))); ?>");
            <?php endif; ?>

        });

        $(document).ready(function () {

            $('#datepicker1').datepicker({
                weekStart: 1,
                daysOfWeekHighlighted: "6,0",
                autoclose: true,
                todayHighlight: true
            });

            <?php if (!empty($post['end_date'])): ?>
                $('#datepicker1').datepicker("setDate", "<?php echo htmlspecialchars(date('m/d/Y', strtotime($post['end_date']))); ?>");
            <?php endif; ?>
        });
    </script>