<?php

// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);

// I'll use this file to delete all the files and redirect to correct view-page.php file
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-admin.php');
include('includes/check-subscriber.php');
$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));
$user = $result->fetch(PDO::FETCH_ASSOC); 

if($user['role'] == 'administrator'){
	switch ($_GET['item']) {
		case 'category':
			$table = 'categories';
			$redirect = 'view-categories.php';
			break;
		case 'article':
			$table = 'posts';
			$redirect = 'view-articles.php';
			break;
		case 'page':
			$table = 'pages';
			$redirect = 'view-pages.php';
			break;
		case 'tensum':
				$table = 'tensum';
				$redirect = 'view-tensum.php';
				break;
		case 'organization':
					$table = 'organization';
					$redirect = 'view-organization.php';
					break;
		case 'nechen':
					$table = 'nechen';
					$redirect = 'view-nechen.php';
					break;
		case 'events':
					$table = 'events';
					$redirect = 'view-event.php';
					break;
		case 'user':
			$table = 'users';
			$redirect = 'view-users.php';
			break;
		case 'menu':
				$table = 'menu';
				$redirect = 'view-menu.php';
				break;
		case 'translations':
					$table = 'translations';
					$redirect = 'view-translation.php';
					break;
		case 'widget':
			$table = 'widget';
			$redirect = 'view-widgets.php';
			break;
		case 'contact':
			$table = 'contact_messages';
			$redirect = 'view-contact-massage.php';
			break;
		default:
			$redirect = 'dashboard.php';
			break;
	}
}elseif($user['role'] == 'editor'){
	switch ($_GET['item']) {
		case 'category':
			$table = 'categories';
			$redirect = 'view-categories.php';
			break;
		case 'article':
			$table = 'posts';
			$redirect = 'view-articles.php';
			break;
		case 'page':
			$table = 'pages';
			$redirect = 'view-pages.php';
			break;
		case 'tensum':
				$table = 'tensum';
				$redirect = 'view-tensum.php';
				break;
		case 'organization':
					$table = 'organization';
					$redirect = 'view-organization.php';
					break;
		case 'nechen':
					$table = 'nechen';
					$redirect = 'view-nechen.php';
					break;
			
		case 'menu':
					$table = 'menu';
					$redirect = 'view-menu.php';
					break;
		case 'translations':
						$table = 'translations';
						$redirect = 'view-translation.php';
						break;
		case 'events':
						$table = 'events';
						$redirect = 'view-event.php';
						break;
		case 'contact':
						$table = 'contact_messages';
						$redirect = 'view-contact-massage.php';
						break;
		default:
			$redirect = 'dashboard.php';
			break;
	}
}
try {
	// Start a transaction
	$db->beginTransaction();

	// Fetch the paths of the associated files
	$fetchSql = "SELECT * FROM $table WHERE id = ?";
	$fetchStmt = $db->prepare($fetchSql);
	$fetchStmt->execute([$_GET['id']]);
	$filePaths = $fetchStmt->fetch(PDO::FETCH_ASSOC);

	// Proceed with deletion only if the user is authorized
	if ($user['role'] == 'administrator' || ($user['role'] == 'editor' && $filePaths)) {
			$conditions = "id = ?";
			$params = [$_GET['id']];

			if ($user['role'] == 'editor') {
					// For editors, ensure they can only delete their own posts
					$conditions .= " AND uid = ?";
					$params[] = $_SESSION['id'];
			}

			$DelSql = "DELETE FROM $table WHERE $conditions";
			$result = $db->prepare($DelSql);
			$res = $result->execute($params);

			// Check if the delete operation affected any row
			if ($res && $result->rowCount() > 0) {
					// Attempt to delete associated files
					foreach (['pic', 'sound'] as $fileType) {
							if (!empty($filePaths[$fileType]) && file_exists('../' . $filePaths[$fileType])) {
									unlink('../' . $filePaths[$fileType]);
							}
					}

					// Commit the transaction
					$db->commit();

					header("Location: $redirect");
					exit;
			} else {
					echo "Failed to Delete Record or Record does not exist.";
					// Rollback the transaction in case of failure
					$db->rollBack();
			}
	} else {
			echo "Unauthorized action or no files associated.";
	}
} catch (PDOException $e) {
	// Rollback the transaction in case of an exception
	$db->rollBack();
	echo "Failed to Delete Record: " . $e->getMessage();
}

// Redirect if no deletion occurred
header("Location: $redirect");
exit;

?>
