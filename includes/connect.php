<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenvFile = __DIR__ . '/../.env';
if (file_exists($dotenvFile)) {
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

}

$host = getenv('SQL_DB_HOST'); // Fallback to 'sql_db' if the environment variable is not set
$dbname = getenv('SQL_DATABASE_NAME'); // Fallback to 'cta_nekor_data' if the environment variable is not set

$dsn = "mysql:host=$host;dbname=$dbname";
$username = getenv('SQL_USER');
$password = getenv('SQL_PASSWORD');
try {
    // Create a new PDO instance
    $db = new PDO($dsn, $username, $password);
    // Set PDO to throw exceptions on error
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Handle any errors that occur during database connection or operations
    echo 'Connection failed: ' . $e->getMessage();
}


//  $databasePath ='data/cta_nekor_data.db';

// try {
//     // Create a new PDO instance for SQLite
//     $db = new PDO('sqlite:' . $databasePath);

//     // Set PDO to throw exceptions on error
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// } catch (PDOException $e) {
//     // Handle any errors that occur during database connection or operations
//     echo 'Connection failed: ' . $e->getMessage();
// }
?>