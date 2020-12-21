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
 * Unique user identifier from your system.
 */
$signerUID = 'o880jxigih';
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
 * Phone number. Optional. If provided, will be prefilled in iframe for Mobile ID.
 */
$signer['phone'] = '+37060000666';

/**
 * Personal code. Optional. If provided, will be prefilled for Smart-ID in iframe. Also, will not require entering manually for Mobile ID.
 */
$signer['code'] = '50001018865';

/**
 * Country code. Required for signing with Smart-ID. Can also be used to preselect a country from a list in iframe dropdown for Smart-ID and Mobile ID.
 */
$signer['country_code'] = 'LT';

/**
 * Signing purpose. Availabe options listed in documentation.
 */
$signer['signing_purpose'] = 'signature';

/**
 * Set visible signing options for user. Also adjusts the order of eIDs in iframe. Options: mobile|smartid|stationary|eparaksts_mobile
 */
$signer['signing_options'] = [
	'mobile', 'smartid', 'stationary', 'eparaksts_mobile'
];

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
	print_r($createResponse);
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

