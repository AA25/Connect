<?php

function get_db()
{
    $host ='127.0.0.1';
    $db = 'connect';
    $userdb = 'connectAdmin';
    $pass = 'connect';

    $dsn = "mysql:host=$host; dbname=$db";
    $pdo = new PDO($dsn, $userdb, $pass);
    
    return $pdo;
};
