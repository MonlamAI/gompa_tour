<?php
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// Function to upload file to Amazon S3

// Instantiate an S3 client


function uploadToS3($keyName, $filePath)
{
    $bucketName = 'gompa-tour';

    $credentials = [
        'key' => getenv('AWS_ACCESS_KEY'),
        'secret' => getenv('AWS_SECRET_KEY'),
    ];
    $s3Client = new S3Client([
        'version' => 'latest',
        'region' => 'ap-south-1',
        'credentials' => $credentials
    ]);

    try {
        $result = $s3Client->putObject([
            'Bucket' => $bucketName,
            'Key' => $keyName,
            'Body' => fopen($filePath, 'rb'),
        ]);

        return $result['ObjectURL']; // Return the URL of the uploaded file
    } catch (S3Exception $e) {
        return "Error uploading file: " . $e->getMessage();
    }
}
