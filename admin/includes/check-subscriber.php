<?php

$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));
$user = $result->fetch(PDO::FETCH_ASSOC);
$domain = $_SERVER['HTTP_HOST'];
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$currentDir = dirname($_SERVER['REQUEST_URI']);
if ($user['role'] == 'subscriber') {
	header("location: $protocol://$domain/index.php");
	exit;
}
?>