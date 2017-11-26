<?php 
require "../../includes/init.inc.php";
$pdo = get_db();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$headers = apache_request_headers();
if(isset($headers['Authorization'])){
    $requestResponse = json_decode(file_get_contents('php://input'),true);      
    $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
    $verifiedJWT = new Jwt ($tokenInAuth);
    $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
    //This API endpoint should only be accessible if JWT token  is verified and user is a developer
    if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
        updateProjectRequest($pdo, $userVerifiedData, $requestResponse);
    }else{
        echo json_encode(Array('Error' => 'Permission denied'));
    }
}else{
    echo json_encode(Array('Error' => 'No Authorization Header'));
}

function updateProjectRequest($pdo, $userVerifiedData, $requestResponse){
    $pdo->beginTransaction();

    try{
        // Updates the project request of a specific developer to a specific project to accepted or declined.
        // Note that previous rejected requests will be left in the db to later be accessed on the clientside for history purposes, it can be deleted later by user
        // The inner join with businesses table is to ensure that user cannot delete a project request of a different business
        $stepOne = $pdo->prepare("
            update projectRequests inner join businesses on projectRequests.busId = businesses.busId set projectRequests.status = :busResponse 
            where businesses.email = :busEmail and projectRequests.projectId = :projectId and projectRequests.devId = :devId and projectRequests.status = :status;
        ");

        $stepOne->execute([
            'busResponse'   => $requestResponse['busResponse'],
            'projectId'     => $requestResponse['projectId'],
            'busEmail'      => $userVerifiedData['email'],
            'devId'         => $requestResponse['devId'],
            'status'        => 'Pending'
        ]);

        if($requestResponse['busResponse'] == 'Accepted'){
            //If the above query is successful then we need to delete all other pending requests made by that developer as they can only join one project as a time
            //Other accepted and rejected will be left in the db for dev to view client side till the request is deleted by the developer
            $stepTwo = $pdo->prepare("
                delete from projectRequests where devId = :devId and status = :status;
            ");
            $stepTwo->execute([
                'devId'     => $requestResponse['devId'],
                'status'    => 'Pending'
            ]);

            //Then update the currentProject column in the developer table to the project they've been accepted for
            $stepThree = $pdo->prepare("
                update developers set currentProject = :projectId where devId = :devId; 
            ");
            
            $stepThree->execute([
                'projectId' => $requestResponse['projectId'],
                'devId' => $requestResponse['devId']
            ]);

            //Then add the developer as a developer to the project in the projectDevelopers table
            $stepFour = $pdo->prepare("
                insert projectDevelopers (devId, projectId) values (:devId, :projectId)
            ");

            $stepFour->execute([
                'devId' => $requestResponse['devId'],
                'projectId' => $requestResponse['projectId']
            ]);
        }

        //We've got this far without an exception, so commit the changes.
        $pdo->commit();
        echo json_encode(array('Success' => 'Project request updated'));

    }catch(Exception $e){
        echo json_encode(array('Error' => 'Updating response to request failed'));
        //echo $e;
        $pdo->rollBack();
    }
}


?>