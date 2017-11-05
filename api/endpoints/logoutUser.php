<?php 
    require "../../includes/init.inc.php";

    if(verifyJWT($_COOKIE['JWT'])){
        $cookiePath = "/";
        $cookieExp = time()-3600;
        if (isset($_COOKIE['JWT'])) {
            setcookie('JWT', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['JWT']);
        }
        if (isset($_COOKIE['devBio'])) {
            setcookie('devBio', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['devBio']);
        }
        if (isset($_COOKIE['dob'])) {
            setcookie('dob', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['dob']);
        }
        if (isset($_COOKIE['email'])) {
            setcookie('email', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['email']);
        }
        if (isset($_COOKIE['firstName'])) {
            setcookie('firstName', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['firstName']);
        }
        if (isset($_COOKIE['lastName'])) {
            setcookie('lastName', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['lastName']);
        }
        if (isset($_COOKIE['languages'])) {
            setcookie('languages', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['languages']);
        }
        if (isset($_COOKIE['phone'])) {
            setcookie('phone', '', $cookieExp, $cookiePath ); // empty value and old timestamp
            unset($_COOKIE['phone']);
        }
        echo json_encode(array('Success' => 'Successfully logged out')); 
    }else{
        //No permission to log out from current account
        echo json_encode(array('Error' => 'Error in logging out')); 
    }
    
?>