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
    //If there is a verbs in the URI push into the verbs array
    // for($i = 0; $i <= sizeof($args); $i++){
    //     echo $i;
    //     var_dump($args); echo '<br>';
    //     if (array_key_exists(0, $args) && !is_numeric($args[0])) {
    //         echo 'true'.'<br>';
    //         array_push($verbs, array_shift($args));
    //     }
    // }
    if (array_key_exists(0, $args) && !is_numeric($args[0])) {
        $verb = array_shift($args);
    }
    
    if($endpoint == 'home' && $verb == '' && empty($args)){
        include('./views/home.php');
    }elseif($endpoint == 'home' && $verb == 'marketplace' && empty($args)){
        include('./views/marketplace.php');
    }elseif($endpoint == 'project'){
        include('./views/projectDesc.php');
    }else{
        echo '404 Page';
    }

?>