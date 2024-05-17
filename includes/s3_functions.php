<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenvFile = __DIR__ . '/../.env';
if (file_exists($dotenvFile)) {
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();
}
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;



function uploadToS3($keyName, $filePath)
{
    $bucketName = 'gompa-tour';
    $accesskey = getenv('AWS_ACCESS_KEY');
    $secret = getenv('AWS_SECRET_KEY');
    $credentials = array(
        'key' => $accesskey,
        'secret' => $secret,
    );
    $s3Client = S3Client::factory(
        array(
            'credentials' => $credentials,
            'region' => 'ap-south-1'
        )
    );
    try {
        $result = $s3Client->putObject([
            'Bucket' => $bucketName,
            'Key' => $keyName,
            'Body' => fopen($filePath, 'rb'),
        ]);

        return $result['ObjectURL'];
    } catch (S3Exception $e) {
        return "Error uploading file: " . $e->getMessage();
    }
}
