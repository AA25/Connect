<?php

    //Routing of all restful friendly urls to the correct view file

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
        }else{
            noPage();
        }

    }elseif($endpoint == 'marketplace'){

        if(empty($args) || $verb !== ''){
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

    }elseif($endpoint == 'developer'){

        if($verb == 'info' && !empty($args)){
            include('./views/developer.php');
        }else{
            noPage();
        }

    }elseif($endpoint == 'register'){
        
        if($verb == 'business' && empty($args)){
            //include('./views/registerBusiness.php');
            include(__DIR__.'/views/registerBusiness.php');
        }elseif($verb == 'developer' && empty($args)){
            include('./views/registerDeveloper.php');
        }else{
            noPage();
        }

    }elseif($endpoint == 'dashboard'){

        if($verb == '' && empty($args)){
            include(__DIR__.'/views/dashboard/dashboard.php');
        }elseif($verb == 'register' && $args[0] == 'project'){
            include(__DIR__.'/views/dashboard/registerProject.php');
        }elseif($verb == 'forum' && !empty($args)){
            include(__DIR__.'/views/dashboard/forum.php');
        }else{
            noPage();
        }

    }else{
        noPage();
    }

    function noPage(){
        echo '404 Page';
    }
?>