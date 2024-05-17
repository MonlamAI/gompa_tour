<?php
//session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
	if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
		if (isset($_SESSION['last_login']) && !empty($_SESSION['last_login'])) {
			// Session values are set, continue with your logic
		} else {
			// Handle case when 'last_login' is not set
		}
	} else {
		// Handle case when 'id' is not set
	}
} else {
	// redirect user to login page
	header('location: login.php');
	exit; // Important to stop further execution after redirecting
}


if (isset($_SESSION['id']) & !empty($_SESSION['id'])) {
	$sql = "SELECT * FROM users WHERE id=?";
	$result = $db->prepare($sql);
	$result->execute(array($_SESSION['id']));
	$user = $result->fetch(PDO::FETCH_ASSOC);

	if ($user['role'] == 'subscriber') {
		//echo "role: subscriber";
		// redirect to Blog Home page
		header("location: index.php");
	}
}

?>