<?php
use Dokobit\Gateway\Client;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

/**
 * Write log helper
 * @param mixed $data
 */
function writeLog($data) {
    $path = __DIR__ . '/postback.log';
    if (is_writable($path)) {
        file_put_contents($path, $data . PHP_EOL, FILE_APPEND);
    }
}

/**
 * Generate a file name
 */
function generateFileName($prefix = 'signed') {
    // Save downloaded file
    $name = $prefix . mt_rand() . '.pdf';
    $path = __DIR__ . '/' . $name;

    return $path;
}

/**
 * Read reqeust data, extract params.
 */
$body = file_get_contents('php://input');
$params = json_decode($body, true);

/**
 * Initialize the client
 */
$client = Client::create([
    'apiKey' => CONFIG_ACCESS_TOKEN,
    'sandbox' => true,
]);

/**
 * Write log
 */
$log = '[' . date('Y-m-d H:i:s') . '] ' . PHP_EOL;
$log .= $body . PHP_EOL;
$log .= print_r($params, true);
writeLog($log);

if ($params['action'] == 'signer_signed') {
    // One of the signers has signed document

    // ... you can download signed file
    // or just use as notification...
} elseif ($params['action'] == 'signing_completed') {
    // All signers assigned to signing has signed document

    // Note: The client will automatically append your configured access token
    $url = $params['file'];
    $path = generateFilePath();


    // Download signed file
    writeLog("Downloading the signed document from " . $url);
    writeLog("The file will be saved as " . $path);
    $client->downloadFile($url, $path);
} elseif ($params['action'] == 'signing_archived') {
    // Signing has been archived

    // $accessToken - API access token
    $url = $params['file'];
    $path = generateFilePath('archived');

    // Download signed file
    writeLog("Downloading the archived document from " . $url);
    writeLog("The file will be saved as " . $path);
    $client->downloadFile($url, $path);
} elseif ($params['action'] == 'signing_archive_failed') {
    // Signing archiving failed
}

writeLog('End.');
