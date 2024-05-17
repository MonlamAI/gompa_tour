<?php 
$sql = "SELECT * FROM users WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_SESSION['id']));
$user = $result->fetch(PDO::FETCH_ASSOC); 

if(($user['role'] == 'editor') || ($user['role'] == 'subscriber')){
	header("location: https://gompatour.com/admin/dashboard.php");
}elseif($user['role'] == 'administrator'){
	// do nothing
}
 ?>