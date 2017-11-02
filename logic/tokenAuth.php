<?php
// JWT token structure is like header.payload.signature
// The header of the JWT token
$headerEnc = base64_encode('{"alg": "HS256","typ": "JWT"}');

// The payload of the JWT token
$payloadEnc = base64_encode('{"iss": "connectServer","name": "Ade"}');

// header and payload concat
$headerPayload = $headerEnc . '.' . $payloadEnc;

//Setting the secret key
$secretKey = 'ademola';

// Creating the signature, a hash with the sha256 algorithm and the secret key.
$signature = base64_encode(hash_hmac('sha256', $headerPayload, $secretKey, true));

// Creating the JWT token
$jwtToken = $headerPayload . '.' . $signature;

echo $jwtToken;

// $recToken = 'eyJhbGciOiAiSFMyNTYiLCJ0eXAiOiAiSldUIn0=.eyJpc3MiOiAiY29ubmVjdFNlcnZlciIsIm5hbWUiOiAiQWRlIn0=.ODCoorU4MfMU8HgMETig0kGVTWD6cVdeR2KGgO5md0c=';
// $secretKey = 'ademola';

// $jwtValues = explode('.', $recToken);

// $recSig = $jwtValues[2];

// $recHeaderPayload = $jwtValues[0] . '.' . $jwtValues[1];

// $resultedsignature = base64_encode(hash_hmac('sha256', $recHeaderPayload, $secret_key, true));

// if($resultedsignature == $recSig) {
//     echo "Success";
// }
?> 