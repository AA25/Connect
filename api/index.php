<?php
    // All requests to the api are first routed to this file

    require "../includes/init.inc.php";
    // Requests from the same server don't have a HTTP_ORIGIN header
    if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
        $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
    }

    try {
        //myAPI object is then created based on the request
        $API = new MyAPI($_SERVER['REQUEST_URI'], $_SERVER['HTTP_ORIGIN']);
        //check to see if the rest url trying to be accessed exists and return the appropriate response
        echo $API->processAPI();
    } catch (Exception $e) {
        echo json_encode(Array('error' => $e->getMessage()));
    }
?>