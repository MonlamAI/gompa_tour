
<?php

$csvFile = fopen('2024.csv', 'r');
if (!$csvFile) {
    die("Failed to open the CSV file.");
}

$calendar = array(); // Corrected variable name
// Skip header line if your CSV has headers
$headers = fgetcsv($csvFile);

while ($row = fgetcsv($csvFile)) {
    $calendar[] = array_combine($headers, $row);
}

echo json_encode($calendar, JSON_UNESCAPED_UNICODE);

fclose($csvFile);

?>