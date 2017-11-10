<?php 
    spl_autoload_register(function($className){
        $path = __DIR__.'/classes/';
        require $path.$className.'.php';
    });
?>