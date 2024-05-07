<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the decoded data sent from the client
    $data = $_POST['data'];
    // Process the data (e.g., lookup in database, log it, etc.)
    echo "QR Code Data: " . $data;
    // Further processing...
}
?>