<?php

// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// Include necessary files and start session
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-admin.php');
include('includes/check-subscriber.php');

// Initialize variables to avoid undefined variable errors
$table = '';
$redirect = 'dashboard.php'; // Default redirect

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));
$user = $result->fetch(PDO::FETCH_ASSOC);

// Determine redirection and table based on user role and item
if ($user['role'] == 'administrator' || $user['role'] == 'editor') {
    switch ($_GET['item']) {
        case 'contact':
            $table = 'contact_messages';
            $redirect = 'view-contact-massage.php'; // Corrected spelling
            break;
        // Add more cases as needed
    }
}

try {
    // Validate $_GET['id'] to ensure it's a number
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("Invalid ID.");
    }

    $db->beginTransaction();

    // Conditions and parameters for deletion
    $conditions = "id = ?";
    $params = [$_GET['id']];

    // Additional condition for editors
    if ($user['role'] == 'editor') {
        $conditions .= " AND uid = ?";
        $params[] = $_SESSION['id'];
    }

    // Execute deletion if table is defined
    if (!empty($table)) {
        $DelSql = "DELETE FROM $table WHERE $conditions";
        $result = $db->prepare($DelSql);
        $res = $result->execute($params);

        if ($res) {
            $db->commit();
        } else {
            throw new Exception("Deletion failed.");
        }
    } else {
        throw new Exception("Unauthorized action or invalid parameters.");
    }
} catch (Exception $e) {
    // Rollback the transaction in case of an exception
    $db->rollBack();
    echo "Error: " . $e->getMessage();
    exit; // Prevent further execution if an error occurs
}

// Redirect if deletion occurred
header("Location: $redirect");
exit;

?>
