<?php

//Automatically load classes that are needed
spl_autoload_register(function($className){
    $path = __DIR__.'/../classes/';
    require $path.$className.'.php';
});

//Database connector
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
        }
    } else {
        echo "You're not logged in";
    }
}

date_default_timezone_set('Europe/London');