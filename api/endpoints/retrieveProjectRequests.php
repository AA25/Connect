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
            "select projectRequests.projectReqId, projectRequests.projectId, projects.projectName, projects.projectCategory, projects.projectBio, developers.devId, developers.firstName, developers.lastName, developers.email, projectRequests.status, projectRequests.devMsg 
            from (((projectRequests inner join projects on projectRequests.projectId = projects.projectId) 
            inner join developers on projectRequests.devId = developers.devId) 
            inner join businesses on projectRequests.busId = businesses.busId) 
            where businesses.email = :busEmail and projectRequests.status = :status"
        );
        $result->execute([
            'busEmail' => $userVerifiedData['email'],
            'status' => 'pending'
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
                'projectReqId'      => $requests['projectReqId'],
                'projectId'         => $requests['projectId'],
                'projectName'       => $requests['projectName'],
                'projectCategory'   => $requests['projectCategory'],
                'projectBio'        => $requests['projectBio'],
                'devName'           => $requests['firstName'].' '.$requests['lastName'],
                'devEmail'          => $requests['email'],
                'devId'             => $requests['devId'],
                'devMsg'            => $requests['devMsg'],
                'status'            => $requests['status']
            )
        );
    }
?>