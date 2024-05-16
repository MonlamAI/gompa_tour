<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cta_nekor_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$events = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    echo json_encode($events, JSON_UNESCAPED_UNICODE);
} else {
    echo "0 results";
}
$conn->close();



?>
