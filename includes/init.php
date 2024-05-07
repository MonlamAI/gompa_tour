<?php

require_once('connect.php');

// Default language is English
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'bo'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

function translate($key) {
    global $db; // Assuming $pdo is your PDO connection object

    // Prepare a SQL statement with parameters
    $lang = $_SESSION['lang'];
    $stmt = $db->prepare("SELECT `text_{$lang}` AS translation FROM translations WHERE key_name = ?");
    
    // Bind parameters and execute the statement
    $stmt->execute([$key]);

    // Fetch the results
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return the translation or a default message if the translation is not found
    return $row ? $row['translation'] : "[$key not found]";
}
