<?php 
    require "../includes/classesLoader.inc.php";

    $thisJWT = new Jwt('');
    echo $thisJWT->getToken();
?>

