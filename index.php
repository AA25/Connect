<?php

    //Routing of all view files url to more restful friendly URL

    //Resource to be accessed
    $endpoint = '';

    //Optional additional descriptors about the endpoint
    $verb = '';
    $verbs = Array();

    //Any additional URI components after the endpoint and verb have been removed
    //eg an int value to mean a particular resource id
    $args = Array();

    //Break the URI into its parts
    $args = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));

    //Remove the initial blank space from the URI parts
    array_shift($args);

    //Will give of the resource endpoint eg /projects
    $endpoint = array_shift($args);

    if (array_key_exists(0, $args) && !is_numeric($args[0])) {
        $verb = array_shift($args);
    }

    if($endpoint == 'home'){

        if($verb == '' && empty($args)){
            include('./views/home.php');
        }elseif($verb == 'marketplace' && empty($args)){
            include('./views/marketplace.php');
        }else{
            noPage();
        }

    }elseif($endpoint == 'project'){

        if(!empty($args[0]) || $verb !== '' ){
            include('./views/projectDesc.php');
        }else{
            noPage();
        }

    }elseif($endpoint == 'business'){

        if($verb == 'info' && !empty($args)){
            include('./views/business.php');
        }else{
            noPage();
        }

    }else{
        noPage();
    }

    // if($endpoint == 'home' && $verb == '' && empty($args)){
    //     include('./views/home.php');
    // }elseif($endpoint == 'home' && $verb == 'marketplace' && empty($args)){
    //     include('./views/marketplace.php');
    // }elseif($endpoint == 'project' && (!empty($args[0]) || $verb !== '' )){
    //     include('./views/projectDesc.php');
    // }else{
    //     echo '404 Page';
    // }

    function noPage(){
        echo '404 Page';
    }
?>