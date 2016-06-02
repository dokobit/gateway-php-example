<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib.php';

/**
 * 
 * PARAMETERS
 * 
 */

$signers = [];
$files = [];

/**
 * 
 * CHANGE THIS
 * 
 * File token provided by file upload response.
 */

$file['token'] = '5582ce8dd5f83a50edb61f4bbdce166b752dbd3d3c434fb2de1ab';

array_push($files, $file); // For 'pdf' type only one file is supported.

/**
 * Signed document format. Check documentation for all available options.
 */
$type = 'pdf';

/**
 * Signing name. Will be displayed as the main title.
 */
$signingName = 'Agreement';

/**
 * Signer's unique identifier - personal code.
 */
$signerUID = '51001091072';
$signer['id'] = $signerUID;

/**
 * Name
 */
$signer['name'] = 'Tester';

/**
 * Surname
 */
$signer['surname'] = 'Surname';

/**
 * Phone number. Optional.
 */
$signer['phone'] = '+37260000007';

/**
 * Personal code. Optional.
 */
$signer['code'] = '51001091072';

/**
 * Signing purpose. Availabe options listed in documentation.
 */
$signer['signing_purpose'] = 'signature';

array_push($signers, $signer); // Add as many signers as you need.


/**
 * 
 * MAKING API REQUESTS
 * 
 */

/**
 * Create signing
 */
$action = 'signing/create';
$createResponse = request(getApiUrlByAction($action), [
    'type' => $type,
    'name' => $signingName,
    'signers' => $signers,
    'files' => $files,
    'postback_url' => $postbackUrl,
], REQUEST_POST);

if ($createResponse['status'] != 'ok') {
    echo "Signing could not be created." . PHP_EOL;
    exit;
}

/**
 * Important!
 * Signing URL formation.
 */
$signingUrl = trim($apiUrl, '/') . "/signing/" . $createResponse['token'] . '?access_token=' . $createResponse['signers'][$signerUID];

echo "Signing successfully created." . PHP_EOL;
echo "View and sign it here: " . PHP_EOL . PHP_EOL;
echo $signingUrl . PHP_EOL . PHP_EOL;

echo "Signing url formation: " . trim($apiUrl, '/') . "/signing/<SIGNING_TOKEN>?access_token=<SIGNER_ACCESS_TOKEN>" . PHP_EOL;
echo "SIGNING_TOKEN: token received with 'signing/create' API call response." . PHP_EOL;
echo "SIGNER_ACCESS_TOKEN: token received with 'signing/create' API call response as parameter 'signers'.
Signers represented as associative array where key is signer's unique identifier - personal code." . PHP_EOL;

