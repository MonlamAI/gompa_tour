<?php
// // Database connection parameters
// $dsn = 'mysql:host=localhost;dbname=cta_nekor_data';
// $username = 'root';
// $password = 'root';

// try {
//     // Create a new PDO instance
//     $db = new PDO($dsn, $username, $password);

//     // Set PDO to throw exceptions on error
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// } catch (PDOException $e) {
//     // Handle any errors that occur during database connection or operations
//     echo 'Connection failed: ' . $e->getMessage();
// }


 $databasePath ='data/cta_nekor_data.db';

try {
    // Create a new PDO instance for SQLite
    $db = new PDO('sqlite:' . $databasePath);

    // Set PDO to throw exceptions on error
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Handle any errors that occur during database connection or operations
    echo 'Connection failed: ' . $e->getMessage();
}
?>