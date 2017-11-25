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
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            prepareSelectRequest($pdo, $userVerifiedData);
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'Please log in to view this page'));
    }


    //I need a query where I can pull the project requests to all projects owned by a business
    function prepareSelectRequest($pdo, $userVerifiedData){
        $returnProjectReqs = ['Success' => []];
        $result = $pdo->prepare(
            "select projectReqId, projectId, devMsg, status from projectRequests inner join businesses on projectRequests.busId = businesses.busId where businesses.email = :busEmail and projectRequests.status = 'pending'"
        );
        $result->execute([
            'busEmail' => $userVerifiedData['email']
        ]);
        if($result->rowCount() > 0){
            foreach($result as $requests){
                pushProjectDetails($returnProjectReqs['Success'],$requests);
            }
        }
        echo json_encode($returnProjectReqs);
    }; 

    function pushProjectDetails(&$returnProjectReqs, $requests){
        array_push($returnProjectReqs, 
            Array(
                'projectReqId'  => $requests['projectReqId'],
                'projectId'     => $requests['projectId'],
                'devMsg'        => $requests['devMsg'],
                'status'        => $requests['status']
            )
        );
    }
?>