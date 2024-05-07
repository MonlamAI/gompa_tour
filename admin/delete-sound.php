<?php
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Your PHP code here



require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-admin.php');
include('includes/check-subscriber.php');
$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));

$user = $result->fetch(PDO::FETCH_ASSOC); 

if(isset($_GET) & !empty($_GET)){
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
        // Ensure the ID is an integer to prevent SQL injection
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
        // Validate the ID
        if ($id <= 0) {
            throw new Exception("Invalid ID provided.");
        }
    
        // Fetch the sound file path from the database
        $sql = "SELECT sound FROM $table WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($post && !empty($post['sound'])) {
            $filepath = '../' . $post['sound'];
    
            // Check if the file exists before attempting to delete it
            if (file_exists($filepath) && unlink($filepath)) {
                // File deleted successfully, proceed to update the database
                $sql = "UPDATE $table SET sound = NULL, updated = NOW() WHERE id = :id";
                $stmt = $db->prepare($sql);
                $res = $stmt->execute([':id' => $id]);
    
                if ($res) {
                    // Redirect to the specified page after successful operation
                    header("Location: $redirect");
                    exit;
                } else {
                    // Handle the case where the database update fails
                    throw new Exception("Failed to update the database.");
                }
            } else {
                // Handle the case where the file does not exist or cannot be deleted
                throw new Exception("File does not exist or could not be deleted.");
            }
        } else {
            // Handle the case where no sound file is associated with the post
            throw new Exception("No sound file associated with the provided ID.");
        }
    } catch (PDOException $e) {
        // Handle database related errors
        echo "Database error: " . htmlspecialchars($e->getMessage());
    } catch (Exception $e) {
        // Handle general errors
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
    
} else {
    // Handle unauthorized access 
}
}
?>