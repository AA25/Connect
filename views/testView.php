<?php 
    require "../includes/classesLoader.inc.php";

    $thisJWT = new Jwt('');
    echo $thisJWT->getToken();
    $thisJWT->setToken(Array('Ade' => 'Akingbade'));
    $test = $thisJWT->getToken();
    echo $test;
?>

