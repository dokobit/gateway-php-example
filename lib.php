<?php

function getApiUrlByAction($action)
{
    global $apiUrl, $accessToken;
    
    return trim($apiUrl, '/') . '/api/' .  $action . '.json?access_token='.$accessToken;
}

const REQUEST_GET = false;
const REQUEST_POST = true;
function request($requestUrl, $fields = [], $post = REQUEST_POST) {
    
    $fields = http_build_query($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $requestUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 180);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $requestHeaders = array(
            'Content-Type: application/x-www-form-urlencoded; ',
            'Content-Length: ' . strlen($fields),
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
    }
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    if ($result ===  null) {
        echo "\nGot NULL Response. Actual response: \n";
        var_dump($response);
        echo curl_error($ch);
    }
    curl_close($ch);
    return $result;
}