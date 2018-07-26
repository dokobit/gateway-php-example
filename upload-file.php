<?php
use Dokobit\Gateway\Client;
use Dokobit\Gateway\Query\File\Upload;

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
 * Path of the file to be uploaded.
 */
const FILE_PATH = 'test.pdf';

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
$request = new Upload(FILE_PATH);

try {
    $response = $client->get($request);
} catch (\RuntimeException $e) {
    echo "Unable to upload the file." . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    exit;
}

/**
 * Success. Signing can be created using file token.
 */
echo "File has been successfully uploaded." . PHP_EOL;
echo "File token is: " . PHP_EOL . PHP_EOL;
echo "     "  . $response->getToken() . PHP_EOL . PHP_EOL;
echo "Use this token to create signing." . PHP_EOL;
