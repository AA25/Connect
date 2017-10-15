<?php 
    $header = array('alg' => 'HS256', 'typ' => 'JWT');
    $base64Header = base64_encode(json_encode($header));
    $payload = array('iss' => 'connectServerr', 'name' => 'Ade');
    $base64Payload = base64_encode(json_encode($payload));
    // $base64Header = base64_encode('{"typ":"JWT", "alg":"HS256"}');
    // $base64Payload = base64_encode('{"iss":"connectServerr", "name":"Ade"}');
    $encodedStr = $base64Header.'.'.$base64Payload;
    $sig = hash_hmac('sha256', $encodedStr, 'secret');
    // $base64Sig = base64_encode($sig);
    //echo $sig;
    $jwt = $base64Header.'.'.$base64Payload.'.'.$sig;
    echo $jwt;
?>