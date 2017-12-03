<?php 

require "../../includes/init.inc.php";
$pdo = get_db();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$headers = apache_request_headers();
if(isset($headers['Authorization'])){
    $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
    $verifiedJWT = new Jwt ($tokenInAuth);
    $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
    //This API endpoint should only be accessible if JWT token  is verified and user is a developer
    if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
        //updateProjectRequest($pdo, $userVerifiedData, $requestResponse);
    }else{
        echo json_encode(Array('Error' => 'Permission denied'));
    }
}else{
    echo json_encode(Array('Error' => 'No Authorization Header'));
}

?>