<?php
$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));
$user = $result->fetch(PDO::FETCH_ASSOC);

if (($user['role'] == 'editor') || ($user['role'] == 'subscriber')) {
	// Get current domain name
	$domain = $_SERVER['HTTP_HOST'];
	// Redirect to dashboard using current domain
	$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
	$currentDir = dirname($_SERVER['REQUEST_URI']);
	header("location: dashboard.php");
} elseif ($user['role'] == 'administrator') {
	// do nothing
}
?>