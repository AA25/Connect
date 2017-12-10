<?php 
    require "../../includes/init.inc.php";
    $pdo = get_db();

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        //Getting the token sent
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        //Creating a token object from the token sent
        $verifiedJWT = new Jwt ($tokenInAuth);
        //Getting data out from the sent token object
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        //If the token passes verification then we know the data it contains is also valid and true
        if($verifiedJWT->verifyJWT($verifiedJWT->token)){
            prepareDeleteRequest($pdo, $userVerifiedData);
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'Please log in to view this page'));
    }

    function prepareDeleteRequest($pdo, $userVerifiedData){
        if($userVerifiedData['type'] == 'developer'){
            $delete = $pdo->prepare("delete projectRequests from projectRequests 
                inner join developers on projectRequests.devId = developers.devId 
                where developers.email = :devEmail and projectReqId = :projectReqId
            ");

            $delete->execute([
                'devEmail'  => $userVerifiedData['email'],
                'projectReqId' => $_GET['projectReqId']
            ]);
            
            if($delete->rowCount() > 0){
                echo json_encode(Array('Success' => 'Request deleted'));
            }else{
                echo json_encode(Array('Error' => 'Project to delete was not found'));
            }

        }elseif($userVerifiedData['type'] == 'business'){
            echo 'businesses wants to delete a request';
        }
    }
?>