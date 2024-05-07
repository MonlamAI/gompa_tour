<?php
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php');

if(isset($_POST['submit'])) {
    if(!empty($_POST['delete_ids'])) {
        $ids_to_delete = $_POST['delete_ids'];
        $placeholders = implode(',', array_fill(0, count($ids_to_delete), '?'));
        $sql = "DELETE FROM contact_messages WHERE id IN ($placeholders)";
        $stmt = $db->prepare($sql);

        if($stmt->execute($ids_to_delete)) {
            // Success: Redirect or display a success message
            header('Location: view-contact-massage.php?status=success');
            exit;
        } else {
            // Error: Redirect or display an error message
            header('Location: view-contact-massage.php?status=error');
            exit;
        }
    } else {
        // No selection made: Redirect or alert the user
        header('Location: view-contact-massage.php?status=no-selection');
        exit;
    }
}
?>
