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
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'developer'){
            prepareSelectRequest($pdo, $userVerifiedData);
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'Please log in to view this page'));
    }

    function prepareSelectRequest($pdo, $userVerifiedData){
        $returnDevReqs = ['Success' => []];

        $result = $pdo->prepare("select projectId, status from projectRequests inner join developers on projectRequests.devId = developers.devId where developers.email = :devEmail");
        $result->execute([
            'devEmail' => $userVerifiedData['email']
        ]);
        if($result->rowCount() > 0){
            foreach($result as $requests){
                pushRequestDetails($returnDevReqs['Success'],$requests);
            }
        }
        echo json_encode($returnDevReqs);
    }

    function pushRequestDetails(&$returnDevReqs, $requests){
        array_push($returnDevReqs, 
            Array(
                'projectId'     => $requests['projectId'],
                'status'        => $requests['status']
            )
        );
    }
?>