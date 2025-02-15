<?php
use Aws\Exception\AwsException;

// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);
// Your PHP code here
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
    if (empty($_POST['slug'])) {
        $slug = trim($_POST['title']);
    } else {
        $slug = trim($_POST['slug']);
    }
    if (empty($_FILES['pic']['name'])) {
        $errors[] = "You Should Upload a File";
    }
    // check slug is unique with db query
    $search = array(" ", ",", ".", "_");
    $slug = strtolower(str_replace($search, '-', $slug));
    $sql = "SELECT * FROM nechen WHERE slug=?";
    $result = $db->prepare($sql);
    $result->execute(array($slug));
    $count = $result->rowCount();
    if ($count == 1) {
        $errors[] = "Slug already exists in database";
    }

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
        // Start the session if it's not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Assuming $db is your PDO database connection instance
        $sql = "INSERT INTO nechen (uid, tbtitle, entitle, tbcontent, encontent, categories, city, state, postal_code, country, map, status, callnumber, slug, pic, sound) 
        VALUES (:uid, :tbtitle, :entitle, :tbcontent, :encontent, :categories, :city, :state, :postal_code, :country, :map, :status, :callnumber, :slug, :pic, :sound)";

        $result = $db->prepare($sql);

        // Ensure you validate or sanitize your $_SESSION and $_POST data as necessary before using them
        $values = array(
            ':uid' => $_SESSION['id'],
            ':tbtitle' => $_POST['tbtitle'],
            ':entitle' => $_POST['entitle'],
            ':tbcontent' => $_POST['tbcontent'],
            ':encontent' => $_POST['encontent'],
            ':categories' => $_POST['categories'],
            ':city' => $_POST['city'],
            ':state' => $_POST['state'],
            ':postal_code' => $_POST['postal_code'],
            ':country' => $_POST['country'],
            ':map' => $_POST['map'],
            ':status' => $_POST['status'],
            ':callnumber' => $_POST['callnumber'],
            ':slug' => $slug,
            ':pic' => $dbpath,
            ':sound' => $_POST['sound'],

        );

        try {
            $res = $result->execute($values);
            if ($res) {
                // Redirect the user after a successful insertion
                header("location: view-nechen.php");
                exit(); // Make sure to exit after redirection
            } else {
                echo "Failed to insert the record.";
            }
        } catch (PDOException $e) {
            die("Error occurred: " . $e->getMessage());
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
            <h3 class="page-header">གནས་ཆེན་གསར་སྣོན།</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                    རྩོམ་སྒྲིགས་གསར་པ་བྱ་ཡུལ།...
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
                                    <label>བོད་ཡིག་མཚན་བྱང་།</label>
                                    <input class="form-control" name="tbtitle" placeholder="བོད་ཡིག་མཚན་བྱང་འགོད་རོགས།"
                                        value="<?php if (isset($_POST['tbtitle'])) {
                                            echo $_POST['tbtitle'];
                                        } ?>">
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་མཚན་བྱང་།</label>
                                    <input class="form-control" id="entitle" name="entitle" oninput="updateSlug()"
                                        placeholder="དབྱིན་ཡིག་མཚན་བྱང་འགོད་རོགས།" value="<?php if (isset($_POST['entitle'])) {
                                            echo $_POST['entitle'];
                                        } ?>">
                                </div>
                                <div class="form-group">
                                    <label>བོད་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea style="line-height: 28px;" class="form-control" name="tbcontent" rows="3"><?php if (isset($_POST['tbcontent'])) {
                                        echo $_POST['tbcontent'];
                                    } ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>དབྱིན་ཡིག་འགྲེལ་བཤད།</label>
                                    <textarea style="line-height: 28px;" class="form-control" name="encontent" rows="3"><?php if (isset($_POST['encontent'])) {
                                        echo $_POST['encontent'];
                                    } ?></textarea>
                                </div>

                                
                                
                                <div class="form-group">
                                    <label>གྲོང་ཁྱེར་རམ་གྲོང་སྡེ།</label>
                                    <input class="form-control" name="city"
                                        placeholder="གྲོང་ཁྱེར་རམ་གྲོང་སྡེ་གང་རུང་འགོད་དགོས།" value="<?php if (isset($_POST['city'])) {
                                            echo $_POST['city'];
                                        } ?>">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>མངའ་སྡེ།</label>
                                    <input class="form-control" name="state" placeholder="མངའ་སྡེ་འགོད་དགོས།" value="<?php if (isset($_POST['state'])) {
                                        echo $_POST['state'];
                                    } ?>">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>སྦྲག་རྟགས།</label>
                                    <input class="form-control" name="postal_code" placeholder="སྦྲག་རྟགས་འགོད་དགོས།།"
                                        value="<?php if (isset($_POST['postal_code'])) {
                                            echo $_POST['postal_code'];
                                        } ?>">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>རྒྱལ་ཁབ།</label>
                                    <input class="form-control" name="country" placeholder="རྒྱལ་ཁབ་འགོད་དགོས།" value="<?php if (isset($_POST['country'])) {
                                        echo $_POST['country'];
                                    } ?>">
                                </div>

                                <div class="form-group">
                                    <label>ས་ཁྲ།</label>
                                    <input class="form-control" name="map" placeholder="གནས་ཡུལ་ས་ཁྲའི་ཨང་འགོད་དགོས།"
                                        value="<?php if (isset($_POST['map'])) {
                                            echo $_POST['map'];
                                        } ?>">
                                </div>


                                <div class="form-group col-lg-6">
                                    <?php if (isset($post['pic']) && !empty($post['pic'])): ?>
                                        <img style="object-fit: cover; border-radius: 5px; margin-right: 6px;"
                                            src="<?php echo htmlspecialchars('../' . $post['pic']); ?>" height="70"
                                            width="100">
                                        <a
                                            href="delete-pic.php?id=<?php echo urlencode($_GET['id']); ?>&type=tensum">པར་རིས་གསུབ།</a>
                                    <?php else: ?>
                                        <label for="pic">གནས་ཆེན་པར་རིས།</label>
                                        <input type="file" id="pic" name="pic">
                                        <div id="imageTypeError" style="color: red; display: block; margin-top: 10px;">
                                            པར་རིས་ནི། JPEG རྣམ་ཅན་ཁོ་ན་ལས་ངོས་ལེན་མི་བྱེད།</div>
                                    <?php endif; ?>

                                    <?php if (isset($post['sound']) && !empty($post['sound'])): ?>
                                        <audio controls style="margin-right: 6px;"
                                            src="<?php echo htmlspecialchars('../' . $post['sound']); ?>"></audio>
                                        <a
                                            href="delete-sound.php?id=<?php echo urlencode($_GET['id']); ?>&type=tensum">སྒྲ་གསུབ།</a>
                                    <?php else: ?>
                                        <label for="sound">གནས་ཆེན་དང་འབྲེལ་བའི་སྒྲ།</label>
                                        <input class="form-control" type="text" id="sound" name="sound">
                                        <!-- 
                                        <div id="soundTypeError" style="color: red; display: block; margin-top: 10px;">
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
                                    document.getElementById('sound').addEventListener('change', function (e) {
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
                                    document.getElementById('pic').addEventListener('change', function (e) {
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
                                    document.getElementById('sound_input').addEventListener('change', function (e) {
                                        e.preventDefault(); // Prevent form submission (optional, depends on your needs)
                                        var file = e.target.files[0];
                                        if (file) {
                                            var params = {
                                                Bucket: 'gompa-tour',
                                                Key: 'media/audios/' + file.name,
                                                Body: file,
                                            };

                                            // Upload progress tracking
                                            document.getElementById('sound').value = "wait while uploading";
                                            var progressBar = document.getElementById('soundUploadProgress');
                                            var uploadProgress = { loaded: 0, total: 0 };
                                            progressBar.innerHTML = "uploading ... "

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
                                                    document.getElementById('sound').value = data.Location;
                                                }
                                            });
                                        }
                                    });

                                </script>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>སྡེ་ཚན་དབྱེ་བ།</label>
                                            <input type="text" class="form-control custom-font-input" name="categories"
                                                placeholder="སྡེ་ཚན་དབྱེ་བ་འདེམས་རོགས།" list="list-timezone"
                                                id="input-datalist1">
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
                                            <label>ཡིག་ཆའི་གནས་བབ།</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios1" value="draft"
                                                        <?php if (isset($_POST) & !empty($_POST)) {
                                                            if ($_POST['status'] == 'draft') {
                                                                echo "checked";
                                                            }
                                                        } ?>>ཟིན་བྲིས།
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios2" value="review"
                                                        <?php if (isset($_POST) & !empty($_POST)) {
                                                            if ($_POST['status'] == 'review') {
                                                                echo "checked";
                                                            }
                                                        } ?>>བསྐྱར་ཞིབ་ངང་སྒུག
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" id="optionsRadios3"
                                                        value="published" <?php if (isset($_POST) & !empty($_POST)) {
                                                            if ($_POST['status'] == 'published') {
                                                                echo "checked";
                                                            }
                                                        } ?>>ཡོངས་ཁྱབ་འགྲེམ་སྤེལ།
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>འབོད་རྟགས་ཨང་།</label>
                                    <input class="form-control" name="callnumber" placeholder="འབོད་རྟགས་ཨང་འགོད་དགོས།"
                                        value="<?php if (isset($_POST['slug'])) {
                                            echo $_POST['callnumber'];
                                        } ?>">
                                </div>
                                <!-- <div class="form-group">
                                    <label>Qr code</label>
                                    <div style="width: 400px; margin: 10px auto; text-align:center">
                                    <input type="text" id="qrText" placeholder="Enter text or URL">
                                    <button id="generateButton">Generate QR Code</button>
                                    <div id="qrcode"></div>
                                    </div>

                                </div> -->
                                <div class="form-group">
                                    <label>ཚད་ལྡན་སྦྲེལ་ཐག་ཡི་གེ།</label>
                                    <input class="form-control" id="slug" name="slug"
                                        placeholder="སྦྲེལ་ཐག་ཡི་གེ་འགོད་དགོས།" value="<?php if (isset($_POST['slug'])) {
                                            echo $_POST['slug'];
                                        } ?>">
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
