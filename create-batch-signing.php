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

$file['token'] = '45f943fcc30399e2ec4ea7d3473c5c52105ea3e2';

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
 * Phone number. Optional.
 */
$signer['phone'] = '+37060000666';

/**
 * Personal code. Optional.
 */
$signer['code'] = '50001018865';

/**
 * Signing purpose. Availabe options listed in documentation.
 */
$signer['signing_purpose'] = 'signature';

/**
 * Set visible signing options for user.
 * Batch signing is only available for SmartCard eID.
 * Other options can be displayed in iframe. However, it is recomended not to confuse user and display only the available signing eID.
 */
$signer['signing_options'] = [
	'stationary'
];

array_push($signers, $signer); // Add as many signers as you need.


/**
 * 
 * MAKING API REQUESTS
 * 
 */

/**
 * Create multiple signings
 */
$action = 'signing/create';

$signings = [];
for ($i = 1 ; $i <= 2; $i++) {
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

	$signings[] = [
		'token' => $createResponse['token'],
        'signer_token' => $createResponse['signers']['o880jxigih'],
	];
}

$action = 'signing/createbatch';
$batchSigningCreateResponse = request(getApiUrlByAction($action), [
	'signings' => $signings
]);


/**
 * Important!
 * Signing URL formation.
 */
$batchSigningUrl = trim($apiUrl, '/') . "/signing/batch/" . $batchSigningCreateResponse['token'];
$sequentialSigningUrl = trim($apiUrl, '/') . "/signing/sequence/" . $batchSigningCreateResponse['token'];

echo "Batch signing successfully created." . PHP_EOL;
echo "View and sign it here: " . PHP_EOL . PHP_EOL;
echo $batchSigningUrl . PHP_EOL . PHP_EOL . PHP_EOL;

echo "Sequential signing successfully created as well." . PHP_EOL;
echo "View and sign it here: " . PHP_EOL . PHP_EOL;
echo $sequentialSigningUrl . PHP_EOL . PHP_EOL;

echo "Signing url formation: " . trim($apiUrl, '/') . "/signing/batch/<BATCH_SIGNING_TOKEN> or " . trim($apiUrl, '/') . "/signing/sequence/<BATCH_SIGNING_TOKEN>"  . PHP_EOL;
echo "SIGNING_TOKEN: token received with 'signing/createbatch' API call response." . PHP_EOL;

