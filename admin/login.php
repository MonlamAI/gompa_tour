<?php
session_abort();
session_start();
require_once ('../includes/init.php');
require_once ('../includes/connect.php');
require_once ('includes/if-loggedin.php');
include ('includes/header.php');

if (isset($_POST) && !empty($_POST)) {
    if (empty($_POST['email'])) {
        $errors[] = 'User Name / E-mail field is Required';
    }
    if (empty($_POST['password'])) {
        $errors[] = 'Password field is Required';
    }

    // CSRF Token Validation
    if (isset($_POST['csrf_token'])) {
        if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {

        } else {
            echo $_POST['csrf_token'];
            echo $_SESSION['csrf_token'];
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
        // Prepare SQL query to check the email id or username in database
        $sql = "SELECT * FROM users WHERE ";
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $sql .= "email=?";
        } else {
            $sql .= "username=?";
        }

        // Prepare and execute the statement
        $stmt = $db->prepare($sql);
        $stmt->execute(array($_POST['email']));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 1) {
            // Compare the password with password hash
            if (password_verify($_POST['password'], $res['password'])) {
                // Regenerate session id
                //session_regenerate_id();
                $_SESSION['login'] = true;
                $_SESSION['id'] = $res['id'];
                $_SESSION['last_login'] = time();
                // Redirect the user to members area/dashboard page
                $domain = $_SERVER['HTTP_HOST'];
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                $currentDir = dirname($_SERVER['REQUEST_URI']);
                header("location: dashboard.php");
                exit;
            } else {
                $errors[] = "User Name / E-Mail & Password Combination not Working";
            }
        } else {
            $errors[] = "User Name / E-Mail Not Valid";
        }
    }
}

// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();
?>
<style>
    body {
        background: #026dc4;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div style="text-align: center; margin-top: 90px;">
                <img src="../vendor/img/logo.png" width="120" alt="">
            </div>

            <div style="margin-top: 20px !important;" class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate('please-sign-in'); ?></h3>
                </div>
                <div class="panel-body">
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
                    <form role="form" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="<?php echo translate('email-or-user-name'); ?>"
                                    name="email" type="text" autofocus value="<?php if (isset($_POST['email'])) {
                                        echo $_POST['email'];
                                    } ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="<?php echo translate('password'); ?>"
                                    name="password" type="password" value="">
                            </div>
                            <input type="submit" class="btn btn-lg btn-success btn-block"
                                value="<?php echo translate('login'); ?>" />
                        </fieldset>
                    </form>
                    <div style="padding: 10px;text-align: right;"><a
                            href="../register.php"><?php echo translate('register'); ?></a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /#wrapper -->
<?php include ('includes/footer.php'); ?>