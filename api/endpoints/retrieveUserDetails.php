<?php 
    //This endpoint requires a jwt to be sent along with the request.
    $verifiedJWT = new Jwt ($_COOKIE['JWT']);
    $userVerifiedData = getDataFromJWT($verifiedJWT->token);  
    if(verifyJWT($verifiedJWT)){
        
    }else{
        
    }
?>