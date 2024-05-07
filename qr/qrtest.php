<?php
// Include the phpqrcode library

require_once '../vendor/phpqrcode/qrlib.php';

// The data you want to encode in the QR code
$data = 'http://localhost/Blog-PHP/tensum.php?url=the-seventeen-pandits-and-siddhas-of-the-noble-land-of-india'; // Your data here, Unicode characters supported

// The path where the QR code will be saved


$filePath = '../media/qrcodes/lama.png';

// Generating QR code. You can also directly output the QR code to the browser by specifying null as the second parameter.
QRcode::png($data, $filePath, QR_ECLEVEL_L, 10, 2);



// Optionally, display the generated QR code in an HTML image tag
echo '<img src="'.htmlspecialchars($filePath).'" alt="QR Code"/>';
?>
