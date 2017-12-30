<?php 
    require "../../includes/init.inc.php";

    //important to tell your browser what we will be sending
    // header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $token = new Jwt ($_COOKIE['JWT']);

    //Verify the users token
    if($token->verifyJWT($token->token)){
        //Then simply unset the cookie
        $cookiePath = "/";
        $cookieExp = time()-3600;
        if (isset($_COOKIE['JWT'])) {
            setcookie('JWT', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['JWT']);
        }
        echo json_encode(array('Success' => 'Successfully logged out')); 
    }else{
        //No permission to log out from current account
        echo json_encode(array('Error' => 'Error in logging out')); 
    }
    
?>