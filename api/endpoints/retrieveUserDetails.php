<?php 
    //This page should only be accessible if JWT is verified and you're a business
    $verifiedJWT = $_COOKIE['JWT'];
    $verifiedJWT = str_replace(' ', '+', $verifiedJWT);
    //echo json_encode(Array('Cookie' => $verifiedJWT));
    $userVerifiedData = getDataFromJWT($verifiedJWT);  
    if(verifyJWT($verifiedJWT)){
        
    }else{
        
    }
?>