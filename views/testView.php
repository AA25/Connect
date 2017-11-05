<?php 
    require "../includes/init.inc.php";
    //echo $_COOKIE['JWT'] .PHP_EOL;
    //echo $_COOKIE['type'] .PHP_EOL;
    //setcookie("phone", "", time() - 3600);
    checkSession($_COOKIE['JWT']);
    
?>

