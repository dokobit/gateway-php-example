<?php
use Dokobit\Gateway\Client;
use Dokobit\Gateway\Query\Signing\Create;
use Dokobit\Gateway\Query\Signing\CreateBatch;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

/**
 * BEGIN PARAMETERS
 */

/**
 *
 * CHANGE THIS
 *
 * File token provided by file upload response.
 */
const FILE_TOKEN = 'c6661f5282870647e2cce56d2767287066c4964d';

/**
 * Signed document format. Check documentation for all available options.
 */
const DOCUMENT_TYPE = 'pdf';

/**
 * Signing name. Will be displayed as the main title.
 */
const DOCUMENT_NAME = 'Agreement';

/**
 * Signer's unique identifier - personal code.
 */
const SIGNER_ID = 'test_signer';

/**
 * Name
 */
const SIGNER_NAME = 'Tester';

/**
 * Surname
 */
const SIGNER_SURNAME = 'Surname';

/**
 * Phone number. Optional.
 */
const SIGNER_PHONE = '+37260000007';

/**
 * Personal code. Optional.
 */
const SIGNER_CODE = '51001091072';

/**
 * Signing purpose. Availabe options listed in documentation.
 */
const SIGNER_SIGNING_PURPOSE = 'signature';

/**
 * END PARAMETERS
 */

$files = [
    [
        'token' => FILE_TOKEN,
    ],
]; // For 'pdf' type only one file is supported.

$signers = [
    [
        'id' => SIGNER_ID,
        'name' => SIGNER_NAME,
        'surname' => SIGNER_SURNAME,
        'phone' => SIGNER_PHONE,
        'code' => SIGNER_CODE,
        'signing_purpose' => SIGNER_SIGNING_PURPOSE,
    ],
    // Add as many signers as you need.
];

/**
 * Initialize the client
 */
$client = Client::create([
    'apiKey' => CONFIG_ACCESS_TOKEN,
    'sandbox' => true,
]);

/**
 * Create multiple signings
 */
$action = 'signing/create';

$signings = [];
for ($i = 1; $i <= 2; $i++) {
    $createRequest = new Create(
        DOCUMENT_TYPE,
        DOCUMENT_NAME,
        $files,
        $signers,
        CONFIG_POSTBACK_URL
    );

    try {
        $createResponse = $client->get($createRequest);
    } catch (\RuntimeException $e) {
        echo "Signing could not be created." . PHP_EOL;
        echo $e->getMessage() . PHP_EOL;
        exit;
    }

    $signings[] = [
        'token' => $createResponse->getToken(),
        'signer_token' => $createResponse->getSigners()[SIGNER_ID],
    ];
}

$createBatchRequest = new CreateBatch($signings);
$createBatchResponse = $client->get($createBatchRequest);

/**
 * Important!
 * Signing URL formation.
 */
$batchSigningUrl = $client->getBatchSigningUrl($createBatchResponse->getToken());
$sequentialSigningUrl = $client->getSequenceSigningUrl($createBatchResponse->getToken());

echo "Batch signing successfully created." . PHP_EOL;
echo "View and sign it here: " . PHP_EOL . PHP_EOL;
echo $batchSigningUrl . PHP_EOL . PHP_EOL . PHP_EOL;

echo "Sequential signing successfully created as well." . PHP_EOL;
echo "View and sign it here: " . PHP_EOL . PHP_EOL;
echo $sequentialSigningUrl . PHP_EOL . PHP_EOL;

echo "Signing url formation: " . trim($client->getBaseUrl(), '/') . "/signing/batch/<BATCH_SIGNING_TOKEN> or " . trim($client->getBaseUrl(), '/') . "/signing/sequence/<BATCH_SIGNING_TOKEN>"  . PHP_EOL;
echo "SIGNING_TOKEN: token received with 'signing/createbatch' API call response." . PHP_EOL;
