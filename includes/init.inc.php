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

date_default_timezone_set('Europe/London');

