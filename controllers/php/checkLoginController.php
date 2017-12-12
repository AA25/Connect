<?php
    require "./includes/init.inc.php";        
    // Controller that used to check if a user is logged in or not
    //This is done by checking to see if the token cookies exists and if it does to ensure its a valid token if so then the user is still logged in
    function checkLogin(){
        if(isset($_COOKIE['JWT'])) {

            //Check to see if the cookie is valid
            $tokenToVerify = new Jwt ($_COOKIE['JWT']);
            $userVerifiedData = $tokenToVerify->getDataFromJWT($tokenToVerify->token);  

            if($tokenToVerify->verifyJWT($tokenToVerify->token)){
                //echo 'token exists and valid';
                return $userVerifiedData;
            }else{
                //echo 'token isnt valid';
                return false;
            }
        }else{
            //echo 'no token';
            return false;
        }
    }
    $loginStatus = checkLogin();
?>