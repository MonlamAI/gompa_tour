<?php 
session_start();

// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

require_once('includes/connect.php');
include('admin/includes/if-loggedin.php');
include('includes/header.php'); 
if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations


    if(empty($_POST['uname'])){ $errors[] = 'User Name field is Required';}else{
        // check username is unique with db query
        $sql = "SELECT * FROM users WHERE username=?";
        $result = $db->prepare($sql);
        $result->execute(array($_POST['uname']));
        $count = $result->rowCount();
        if($count == 1){
            $errors[] = "User Name already exists in database";
        }
    }
    if(empty($_POST['email'])){ $errors[] = 'E-mail field is Required';}else{
        // check email is unique with db query
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $db->prepare($sql);
        $result->execute(array($_POST['email']));
        $count = $result->rowCount();
        if($count == 1){
            $errors[] = "E-mail already exists in database";
        }
    }
    if(empty($_POST['password'])){ $errors[] = 'Password field is Required';}else{
        if(empty($_POST['passwordr'])){ $errors[] = 'Repeat Password field is Required';}else{
            // compare both password, if they match. generate the password hash
            if($_POST['password'] == $_POST['passwordr']){
                // create password hash
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }else{
                // error message
                $errors[] = 'Both Passwords Should Match';
            }
        }
    }

    // Stronger Password Validation
    if (empty($_POST['password'])) {
        $errors[] = 'Password field is Required';
    } else if (empty($_POST['passwordr'])) {
        $errors[] = 'Repeat Password field is Required';
    } else {
        // compare both passwords, if they match, validate the strength of the password
        if ($_POST['password'] == $_POST['passwordr']) {
            $password = $_POST['password'];
            // Regular expression for password validation
            $passwordPolicy = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d]).{8,}$/';
            if (preg_match($passwordPolicy, $password)) {
                // create password hash
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            } else {
                // error message if password does not meet the policy
                $errors[] = 'Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one digit, and one special character.';
            }
        } else {
            // error message
            $errors[] = 'Both Passwords Should Match';
        }
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
    }
    // password will be password hash
    // Insert values into users table
    if(empty($errors)){
        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'subscriber')";
        $result = $db->prepare($sql);
        $values = array(':username'     => $_POST['uname'],
                        ':email'        => $_POST['email'],
                        ':password'     => $pass_hash,
                        );
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if($res){
            $messages[] = 'User Registered';
            header("location: admin/index.php");
        }
    }
}
// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();
?>
<style>
    body{
        background: #026dc4;
    }
    
</style>
<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4">
        <div style="text-align: center; margin-top: 90px;">
                <img src="vendor/img/logo.png" width="120" alt="">
            </div>
            <div class="card my-4">
                <h3 class="card-header">Please Register</h3>
                <div class="card-body">
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
                    <form role="form" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" style="border: 1px solid #e3dddd;" placeholder="User Name" name="uname" type="text" autofocus value="<?php if(isset($_POST['uname'])){ echo $_POST['uname']; } ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" style="border: 1px solid #e3dddd;" placeholder="E-mail" name="email" type="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" style="border: 1px solid #e3dddd;" placeholder="Password" name="password" type="password" >
                            </div>
                            <div class="form-group">
                                <input class="form-control" style="border: 1px solid #e3dddd;" placeholder="Repeat Password" name="passwordr" type="password">
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" class="btn btn-lg btn-success btn-block" value="Register" />
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>