<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib.php';

/**
 * 
 * PARAMETERS
 * 
 */

/**
 * File name of document you want to sign.
 */
$file['name'] = 'test.pdf'; 

/**
 * HTTP URL where the file is stored.
 * Documents Gateway will download the file from given resource URL. Ensure that file URL is accessible from internet.
 * Base64 encoded file[content] could be used instead of file[url].
 */
$file['url'] = 'https://gateway-sandbox.dokobit.com/sc/test.pdf';

/**
 * SHA256 file hash. SHA1 is supported, but not recommended.
 */
$file['digest'] = '1d879e6248e4dc553b286fc5f81dc52bf841ead031287228548551a70cf06560';


/**
 * 
 * MAKING API REQUESTS
 * 
 */

/**
 * Upload file
 */
$action = 'file/upload';
$uploadResponse = request(getApiUrlByAction($action), [
    'file' => $file
], REQUEST_POST);

if ($uploadResponse['status'] != 'ok') {
    echo "File could not be uploaded.
Please ensure that file URL is accessible from the internet." . PHP_EOL;
    exit;
}

/**
 * Check file status
 */
$action = 'file/upload/status/' . $uploadResponse['token'];
$statusResponse = '';
while ($statusResponse === '' || $statusResponse['status'] == 'pending') {
    $statusResponse = request(getApiUrlByAction($action), [
        'token' => $uploadResponse['token']
    ], REQUEST_GET);
    sleep(2);
}

if (empty($statusResponse) || $statusResponse['status'] != 'uploaded') {
    echo "Documents Gateway could not download the file.
Please ensure that file URL is accessible from the internet." 
        . PHP_EOL;
    exit;
}

/**
 * Success. Signing can be created using file token.
 */
echo "File has been successfully uploaded." . PHP_EOL;
echo "File token is: " . PHP_EOL . PHP_EOL;
echo "     "  . $uploadResponse['token'] . PHP_EOL . PHP_EOL;
echo "Use this token to create signing." . PHP_EOL;
