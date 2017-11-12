<?php 
    //This page should only be accessible if JWT is verified and you're a business WRONG send a token in the header instead
    $verifiedJWT = new Jwt ($_COOKIE['JWT']);
    $userVerifiedData = getDataFromJWT($verifiedJWT->token);  
    if(verifyJWT($verifiedJWT)){
        
    }else{
        
    }
?>