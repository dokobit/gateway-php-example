<?php
use Dokobit\Gateway\Client;
use Dokobit\Gateway\Query\File\UploadFromUrl;
use Dokobit\Gateway\Query\File\UploadStatus;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

/**
 * BEGIN PARAMETERS
 */

/**
 * File name of document you want to sign.
 */
const FILE_NAME = 'test.pdf';

/**
 * URL of the file to be uploaded.
 */
const FILE_URL = 'https://developers.isign.io/sc/test.pdf';

/**
 * SHA1 digest of file content.
 */
const FILE_DIGEST = 'a50edb61f4bbdce166b752dbd3d3c434fb2de1ab';

/**
 * END PARAMETERS
 */

/**
 * Initialize the client
 */
$client = Client::create([
    'apiKey' => CONFIG_ACCESS_TOKEN,
    'sandbox' => true,
]);

/**
 * Upload file
 */
$uploadRequest = new UploadFromUrl(FILE_URL, FILE_DIGEST);

try {
    $uploadResponse = $client->get($uploadRequest);
} catch(\RuntimeException $e) {
    echo "Unable to upload the file." . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    exit;
}

/**
 * Check file status
 */
$statusRequest = new UploadStatus($uploadResponse->getToken());

try {
    while (true) {
        $statusResponse = $client->get($statusRequest);

        if ($statusResponse->getStatus() !== 'pending') {
            break;
        }
        echo "Waiting for file upload to finish..." . PHP_EOL;
        sleep(2);
    }
} catch (\RuntimeException $e) {
    echo "Unable to check file status." . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    exit;
}

if ($statusResponse->getStatus() !== 'uploaded') {
    echo "Gateway API could not download the file." . PHP_EOL;
    echo "Please ensure that file URL is accessible from the internet." . PHP_EOL;
    exit;
}

/**
 * Success. Signing can be created using file token.
 */
echo "File has been successfully uploaded." . PHP_EOL;
echo "File token is: " . PHP_EOL . PHP_EOL;
echo "     "  . $uploadResponse->getToken() . PHP_EOL . PHP_EOL;
echo "Use this token to create signing." . PHP_EOL;
