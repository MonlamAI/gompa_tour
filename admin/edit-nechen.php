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

    if (empty($_POST['tbtitle'])) {
        $errors[] = "Tibetan Title Field is Required";
    }
    if (empty($_POST['entitle'])) {
        $errors[] = "English Title Field is Required";
    }
    if (empty($_POST['tbcontent'])) {
        $errors[] = "Tibetan Content Field is Required";
    }
    if (empty($_POST['encontent'])) {
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
    $sql = "SELECT * FROM nechen WHERE slug=:slug AND id <> :id";
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
        // TODO : Only user with Administrator privillages or user who created the article can only edit 
        if (isset($_FILES) && !empty($_FILES)) {
            // Check for successful upload of the picture
            if ($_FILES['pic']['error'] == UPLOAD_ERR_OK) {
                $name = $_FILES['pic']['name'];
                $type = $_FILES['pic']['type'];
                $tmp_name = $_FILES['pic']['tmp_name'];

                if (isset($name) && !empty($name)) {
                    if ($type == "image/jpeg") {
                        $location = "../media/images/";
                        $key = 'media/images/' . time() . $name; // The key is the path and filename in the S3 bucket

                        $filename = time() . $name; // Securely generating a new filename
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

            // Check for successful upload of the sound file
            // if ($_FILES['sound']['error'] == UPLOAD_ERR_OK) { // Correctly checking the sound file now
            //     $nameSound = $_FILES['sound']['name'];
            //     $typeSound = $_FILES['sound']['type'];
            //     $tmp_nameSound = $_FILES['sound']['tmp_name'];

            //     if (isset($nameSound) && !empty($nameSound)) {
            //         if ($typeSound == "audio/mpeg") { // Correctly checking the MIME type for the sound file
            //             $locationSound = "../media/audios/";
            //             $filenameSound = time() . $nameSound; // Securely generating a new filename
            //             $uploadpathSound = $locationSound . $filenameSound;
            //             $dbpathSound = "media/audios/" . $filenameSound;
            //             move_uploaded_file($tmp_nameSound, $uploadpathSound);
            //         } else {
            //             $errors[] = "Only Upload Audio files";
            //         }
            //     }
            // }

        }
        $dbpathSound = $_POST['sound'];
        $sql = "UPDATE nechen SET tbtitle=:tbtitle, entitle=:entitle, tbcontent=:tbcontent, encontent=:encontent, categories=:categories, street=:street, address_2=:address_2, city=:city, state=:state, postal_code=:postal_code, country=:country, phone=:phone, email=:email, web=:web, map=:map, status=:status, callnumber=:callnumber, slug=:slug, ";
        if (isset($dbpath) && !empty($dbpath)) {
            $sql .= "pic=:pic, ";
        }
        if (isset($dbpathSound) && !empty($dbpathSound)) {
            $sql .= "sound=:sound, ";
        }
        $sql .= "updated=NOW() WHERE id=:id";

        $result = $db->prepare($sql);
        $values = array(
            ':tbtitle' => $_POST['tbtitle'],
            ':entitle' => $_POST['entitle'],
            ':tbcontent' => $_POST['tbcontent'],
            ':encontent' => $_POST['encontent'],
            ':categories' => $_POST['categories'],
            ':street' => $_POST['street'],
            ':address_2' => $_POST['address_2'],
            ':city' => $_POST['city'],
            ':state' => $_POST['state'],
            ':postal_code' => $_POST['postal_code'],
            ':country' => $_POST['country'],
            ':phone' => $_POST['phone'],
            ':email' => $_POST['email'],
            ':web' => $_POST['web'],
            ':map' => $_POST['map'],
            ':status' => $_POST['status'],
            ':callnumber' => $_POST['callnumber'],
            ':slug' => $_POST['slug'],
            ':id' => $_POST['id'],
        );

        if (isset($dbpath) && !empty($dbpath)) {
            $values[':pic'] = $dbpath;
        }
        if (isset($dbpathSound) && !empty($dbpathSound)) {
            $values[':sound'] = $dbpathSound;
        }
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if ($res) {
            // TODO : removing non selected categories from post_categories table
            $pid = $_POST['id'];
            foreach ($_POST['categories'] as $category) {
                $catsql = "SELECT * FROM post_categories WHERE pid=:pid AND cid=:cid";
                $catresult = $db->prepare($catsql);
                $values = array(
                    ':pid' => $pid,
                    ':cid' => $category,
                );
                $catresult->execute($values);
                $catcount = $catresult->rowCount();
                if ($catcount == 1) {
                } else {
                    $sql = "INSERT INTO post_categories (pid, cid) VALUES (:pid, :cid)";
                    $result = $db->prepare($sql);
                    $values = array(
                        ':pid' => $pid,
                        ':cid' => $category
                    );
                    $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
                }
            }
            header("location: view-nechen.php");
        } else {
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

if ($user['role'] == 'administrator') {
    $sql = "SELECT * FROM nechen WHERE id=?";
    $result = $db->prepare($sql);
    $result->execute(array($_GET['id']));
    $post = $result->fetch(PDO::FETCH_ASSOC);
} elseif ($user['role'] == 'editor') {
    $sql = "SELECT * FROM nechen WHERE id=? AND uid={$_SESSION['id']}";
    $result = $db->prepare($sql);
    $result->execute(array($_GET['id']));
    $postcount = $result->rowCount();
    $post = $result->fetch(PDO::FETCH_ASSOC);
    if ($postcount <= 0) {
        header("location: view-articles.php");
    }
}

include ('includes/header.php');
include ('includes/navigation.php');
?>

<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">གནས་བཤད་བཟོ་བཅོས།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    གནས་བཤད་བཟོ་བཅོས་བྱ་ཡུལ།...
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
                                    <input class="form-control" name="tbtitle" placeholder="བོད་ཡིག་མཚན་བྱང་འགོད་རོགས།"
                                        value="<?php if (isset($post['tbtitle'])) {
                                            echo $post['tbtitle'];
                                        } ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་མཚན་བྱང་།</label>
                                    <input class="form-control" id="entitle" name="entitle" oninput="updateSlug()"
                                        placeholder="དབྱིན་ཡིག་མཚན་བྱང་འགོད་རོགས།" value="<?php if (isset($post['entitle'])) {
                                            echo $post['entitle'];
                                        } ?>">
                                </div>
                                <div class="form-group">
                                    <label>བོད་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea style="line-height: 28px;" class="form-control" name="tbcontent" rows="3"><?php if (isset($post['tbcontent'])) {
                                        echo $post['tbcontent'];
                                    } ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea style="line-height: 28px;" class="form-control" name="encontent" rows="3"><?php if (isset($post['encontent'])) {
                                        echo $post['encontent'];
                                    } ?></textarea>
                                </div>

                                
                                
                                <div class="form-group">
                                    <label>གྲོང་ཁྱེར་རམ་གྲོང་སྡེ།</label>
                                    <input class="form-control" name="city"
                                        placeholder="གྲོང་ཁྱེར་རམ་གྲོང་སྡེ་གང་རུང་འགོད་དགོས།" value="<?php if (isset($post['city'])) {
                                            echo $post['city'];
                                        } ?>">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>མངའ་སྡེ།</label>
                                    <input class="form-control" name="state" placeholder="མངའ་སྡེ་འགོད་དགོས།" value="<?php if (isset($post['state'])) {
                                        echo $post['state'];
                                    } ?>">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>སྦྲག་རྟགས།</label>
                                    <input class="form-control" name="postal_code" placeholder="སྦྲག་རྟགས་འགོད་དགོས།།"
                                        value="<?php if (isset($post['postal_code'])) {
                                            echo $post['postal_code'];
                                        } ?>">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>རྒྱལ་ཁབ།</label>
                                    <input class="form-control" name="country" placeholder="རྒྱལ་ཁབ་འགོད་དགོས།" value="<?php if (isset($post['country'])) {
                                        echo $post['country'];
                                    } ?>">
                                </div>
                                
                                
                               
                                <div class="form-group">
                                    <label>ས་ཁྲ།</label>
                                    <input class="form-control" name="map" placeholder="གནས་ཡུལ་ས་ཁྲའི་ཨང་འགོད་དགོས།"
                                        value="<?php if (isset($post['map'])) {
                                            echo $post['map'];
                                        } ?>">
                                </div>

                                <div class="form-group col-lg-6">
                                    <?php if (isset($post['pic']) && !empty($post['pic'])): ?>
                                        <img style="object-fit: cover; border-radius: 5px; margin-right: 6px;"
                                            src="<?php echo htmlspecialchars($post['pic']); ?>" height="70" width="100">
                                        <a
                                            href="delete-pic.php?id=<?php echo urlencode($_GET['id']); ?>&type=nechen">པར་རིས་གསུབ།</a>
                                    <?php else: ?>
                                        <label for="pic">གནས་བཤད་པར་རིས།</label>
                                        <input type="file" id="pic" name="pic">
                                        <div id="imageTypeError" style="color: red; display: block; margin-top: 10px;">
                                            པར་རིས་ནི། JPEG རྣམ་ཅན་ཁོ་ན་ལས་ངོས་ལེན་མི་བྱེད།</div>
                                    <?php endif; ?>

                                    <?php if (isset($post['sound']) && !empty($post['sound'])): ?>
                                        <audio controls style="margin-right: 6px; height: 10px;"
                                            src="<?php echo htmlspecialchars($post['sound']); ?>"></audio>
                                        <a
                                            href="delete-sound.php?id=<?php echo urlencode($_GET['id']); ?>&type=nechen">སྒྲ་གསུབ།</a>
                                    <?php else: ?>
                                        <label for="sound">གནས་བཤད་འདི་དང་འབྲེལ་བའི་སྒྲ།</label>
                                        <input class="form-control" type="text" id="sound" name="sound">
                                        <!-- <div id="soundTypeError" style="color: red; display: block; margin-top: 10px;">
                                            སྒྲ་ནི། MP3 རྣམ་ཅན་ཁོ་ན་ལས་ངོས་ལེན་མི་བྱེད།</div> -->
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label for="sound_input">སྒྲ་ཐག་འཇུག་དགོས།</label>
                                        <input type="file" id="sound_input" class="form-control" name="sound_input"
                                            accept="audio/mpeg">
                                        <div id="soundUploadProgress" style="margin-top: 10px;"></div>
                                        <!-- Element to show upload progress -->
                                    </div>
                                </div>
                                <script>
                                    AWS.config.update({
                                        accessKeyId: '<?php echo getenv("AWS_ACCESS_KEY"); ?>',
                                        secretAccessKey: '<?php echo getenv("AWS_SECRET_KEY"); ?>',
                                        region: 'ap-south-1' // e.g., 'us-east-1'
                                    });
                                    var s3 = new AWS.S3()
                                    document?.getElementById('sound')?.addEventListener('change', function (e) {
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
                                    document?.getElementById('pic')?.addEventListener('change', function (e) {
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
                                    document?.getElementById('sound_input')?.addEventListener('change', function (e) {
                                        e.preventDefault(); // Prevent form submission (optional, depends on your needs)
                                        var file = e.target.files[0];
                                        if (file) {
                                            var params = {
                                                Bucket: 'gompa-tour',
                                                Key: 'media/audios/' + file.name,
                                                Body: file,
                                            };

                                            // Upload progress tracking
                                            var progressBar = document?.getElementById('soundUploadProgress');
                                            var uploadProgress = { loaded: 0, total: 0 };

                                            // Upload object
                                            var upload = s3.upload(params);
                                            upload.on('httpUploadProgress', function (event) {
                                                uploadProgress.loaded = event.loaded;
                                                uploadProgress.total = event.total;
                                                var percent = Math.round((event.loaded / event.total) * 100);
                                                progressBar.innerHTML = 'Upload Progress: ' + percent + '%';

                                            });

                                            // Execute upload
                                            upload.send(function (err, data) {
                                                if (err) {
                                                    console.error('Upload error:', err);
                                                } else {
                                                    console.log('Upload successful:', data);

                                                    // Replace progress with audio tag
                                                    var audioTag = document.createElement('audio');
                                                    audioTag.controls = true;
                                                    var source = document.createElement('source');
                                                    source.src = data.Location; // Use the S3 URL from the upload response
                                                    source.type = 'audio/mpeg';
                                                    audioTag.appendChild(source);
                                                    progressBar.parentNode.replaceChild(audioTag, progressBar);
                                                    // Update hidden input value
                                                    document.getElementById('sound').value = data?.Location;
                                                }
                                            });
                                        }
                                    });

                                </script>


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>སྡེ་ཚན་དབྱེ་བ།</label>
                                            <input type="text" class="form-control custom-font-input" value="<?php if (isset($post['categories'])) {
                                                echo $post['categories'];
                                            } ?>" name="categories" placeholder="སྡེ་ཚན་དབྱེ་འདེམས་རོགས།"
                                                list="list-timezone" id="input-datalist1">
                                            <datalist id="list-timezone">
                                                <option>བོན།</option>
                                                <option>སྙིང་མ།</option>
                                                <option>ས་སྐྱ།</option>
                                                <option>བཀའ་བརྒྱུད།</option>
                                                <option>དགེ་ལུགས།</option>
                                                <option>ཇོ་ནང་།</option>
                                                <option>རིས་མེད།</option>
                                        </div>
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
                                    <label>འབོད་རྟགས།</label>
                                    <input class="form-control" name="callnumber" placeholder="Enter Call Number Here"
                                        value="<?php if (isset($post['callnumber'])) {
                                            echo $post['callnumber'];
                                        } ?>">
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
                                            var title = document.getElementById("entitle").value;

                                            // Convert the title to a slug: lowercase and replace spaces with -
                                            var slug = title.toLowerCase().replace(/\s+/g, '-');

                                            // Update the slug input field
                                            document.getElementById("slug").value = slug;
                                        }
                                    </script>
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
    <?php include ('includes/footer.php'); ?>
