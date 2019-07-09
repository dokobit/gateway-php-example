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
 * Gateway API will download the file from given resource URL.
 * Ensure that file URL is accessible from internet.
 */
$file['url'] = 'https://developers.dokobit.com/sc/test.pdf';

/**
 * SHA1 digest of file content.
 */
$file['digest'] = 'a50edb61f4bbdce166b752dbd3d3c434fb2de1ab';


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
    echo "Gateway API could not download the file.
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