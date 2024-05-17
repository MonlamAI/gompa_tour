<?php
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here



require_once ('../includes/connect.php');
require_once ('../includes/s3_functions.php');
include ('includes/check-login.php');
include ('includes/check-admin.php');
include ('includes/check-subscriber.php');

$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));

$user = $result->fetch(PDO::FETCH_ASSOC);

if (isset($_GET) & !empty($_GET)) {
    $id = $_GET['id'];

    switch ($_GET['type']) {
        case 'post':
            $table = 'posts';
            $redirect = "edit-article.php?id=$id";

            break;
        case 'page':
            $table = 'pages';
            $redirect = "edit-page.php?id=$id";
            break;
        case 'tensum':
            $table = 'tensum';
            $redirect = "edit-tensum.php?id=$id";
            break;
        case 'events':
            $table = 'events';
            $redirect = "edit-events.php?id=$id";
            break;
        case 'organization':
            $table = 'organization';
            $redirect = "edit-organization.php?id=$id";
            break;

        default:
            $redirect = 'dashboard.php';
            break;
    }

    if ($user['role'] == 'administrator') {
        // Validate $_GET['id'] (add code to ensure it's an expected integer)

        try {
            // Fetch data (Parameterized query)
            $sql = "SELECT * FROM $table WHERE id = ?";
            $result = $db->prepare($sql);
            $result->execute([$_GET['id']]);
            $post = $result->fetch(PDO::FETCH_ASSOC);

            if (deleteFromS3($post['pic'])) {
                // Update database (Parameterized query)
                $sql = "UPDATE $table SET pic = '', updated = NOW() WHERE id = ?";
                $result = $db->prepare($sql);
                $res = $result->execute([$_GET['id']]);

                if ($res) {
                    header("location: $redirect");
                } else {
                    // Handle database update error
                }
            } else {
                // Handle file deletion error
            }
        } catch (PDOException $e) {
            // Handle database errors
        }
    } else {
        // Handle unauthorized access 
    }
}
?>