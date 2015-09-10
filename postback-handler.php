<?php
require_once __DIR__ . '/config.php';

/**
 * Read reqeust data, extract params.
 */
$body = file_get_contents('php://input');
$params = json_decode($body, true);

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
 * Download signed file helper
 * @param string $url
 */
function downloadFile($url, $prefix = 'signed') {
    writeLog("Downloading signed file from " . $url);

    // Using curl to download file
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    $error = curl_error($ch);

    // Log errors
    if ($error) {
        writeLog("Error: " . print_r($error, true));
        exit;
    }

    // Save downloaded file
    $name = $prefix . mt_rand() . '.pdf';
    $path = __DIR__ . '/' . $name;
    writeLog("Saving file to " . $path);
    file_put_contents($path, $data);

    curl_close($ch);
}

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

    // $accessToken - API access token
    $url = $params['file'] . '?access_token=' . $accessToken;

    // Download signed file
    downloadFile($url);
} elseif ($params['action'] == 'signing_archived') {
    // Signing has been archived

    // $accessToken - API access token
    $url = $params['file'] . '?access_token=' . $accessToken;

    // Download signed file
    downloadFile($url, 'archived');
} elseif ($params['action'] == 'signing_archive_failed') {
    // Signing archiving failed
    
}

writeLog('End.');
