<?php
session_start();
$uid = $_SESSION['id'];
session_destroy();
require_once ('includes/if-loggedin.php');
?>