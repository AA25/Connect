<?php 

// spl_autoload_register('AutoLoader::ClassLoader');

// class AutoLoader
// {
//     public static function ClassLoader($className)
//     {
//         $path = __DIR__.'/classes/';
//         include $path.$className.'.php';
//     }
// }
spl_autoload_register(function($className){
    $path = __DIR__.'/classes/';
    require $path.$className.'.php';
});
  

function get_db()
{
    $host ='127.0.0.1';
    $db = 'connect';
    $userdb = 'connectAdmin';
    $pass = 'connect';

    $dsn = "mysql:host=$host; dbname=$db";
    $pdo = new PDO($dsn, $userdb, $pass, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ));
    
    return $pdo;
};

function checkSession($sentJWT){
    if(isset($sentJWT)) {
        if(verifyJWT($sentJWT)){
            echo 'Valid Auth';
        }else{
            echo 'Invalid Auth';
            //header('HTTP/1.0 401 Unauthorized');
        }
    } else {
        echo "You're not logged in";
    }
}

function verifyJWT($sentJWT){
    $jwtParts = explode(".",$sentJWT);

    $jwtHeaderDec = base64_decode($jwtParts[0]);
    $jwtPayloadDec = base64_decode($jwtParts[1]);

    //We want to decode and encode it again incase values have been changed
    $headerEnc = base64_encode($jwtHeaderDec);
    $payloadEnc = base64_encode($jwtPayloadDec);

    $jwtSig = $jwtParts[2];

    $headerPayload =  $headerEnc . '.' . $payloadEnc;
    //If the signatures match, then that means the JWT sent is valid.
    $secretKey = 'ademola';
    $serverSig = base64_encode(hash_hmac('sha256', $headerPayload, $secretKey, true));
    // echo $jwtSig .PHP_EOL;
    // echo $serverSig .PHP_EOL;
    if($jwtSig == $serverSig){
        return true;
    }else{
        return false;
    }
} 
  

date_default_timezone_set('Europe/London');

// $headers = getallheaders();
    // if($authHeader = $headers['Authorization']){
    //     //Bearer eyJhbGciOiAiSFMyNTYiLCJ
    //     list($sentJWT) = sscanf($authHeader, "Bearer %s");
    //     if(verifyJWT($sentJWT)){
    //         echo 'Valid Auth';
    //     }else{
    //         echo 'Invalid';
    //         //header('HTTP/1.0 401 Unauthorized');
    //     }
    // }else{
    //     //No authorzation sent
    //     echo 'No auth';
    // }