<?php 
    require "../../includes/init.inc.php";
    $pdo = get_db();

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $projectReqJSON = json_decode(file_get_contents('php://input'),true);        
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        //This API endpoint should only be accessible if JWT token  is verified and user is a developer
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'developer'){
            checkDuplicateReqs($pdo, $userVerifiedData, $projectReqJSON);
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'No Authorization Header'));
    }

    function checkDuplicateReqs($pdo, $userVerifiedData, $projectReqJSON){
        //Retrieve businessId from the business trying to add a new project
        $result = $pdo->prepare("select * from projectRequests inner join developers on projectRequests.devId = developers.devId where developers.email = :email and projectRequests.projectId = :projectId");
        $result->execute([
            'email' => $userVerifiedData['email'],
            'projectId' => $projectReqJSON['projectId']
        ]);
        //If num of rows returned is greater than 0 we know we have a result meaning a request for this project has already been made
        if($result->rowCount() > 0){
            echo json_encode(Array('Error' => 'Request for this project has already been made'));
        }else{
            //echo json_encode(Array('Error' => $projectReqJSON['projectId']));
            insertProjectRequest($pdo, $userVerifiedData, $projectReqJSON);
        }
    }

    function insertProjectRequest($pdo, $userVerifiedData, $projectReqJSON){
        $devId = getPrimaryKey($pdo,$userVerifiedData);
        echo gettype($projectReqJSON['devMsg']);
        $pdo->beginTransaction();
        try{
            //Now we insert a new project with the busId 
            $result = $pdo->prepare(
                "insert into
                projectRequests (projectId, devId, devMsg, status)
                values(:projectId, :devId, :devMsg, :status);"
            );
            $result->execute([
                'projectId' => $projectReqJSON['projectId'],
                'devId' => $devId,
                'devMsg' => $projectReqJSON['devMsg'],
                'status' => 'Pending',
            ]);

            //We've got this far without an exception, so commit the changes.
            echo json_encode(array('Success' => 'Project request sent successfully'));
            $pdo->commit();
        }catch(Exception $e){
            echo json_encode(Array('Error' => 'Error with making project request'));
            $pdo->rollBack();
        }
    }

    function getPrimaryKey($pdo,$userVerifiedData){
        $pk = '';
        $result = $pdo->prepare("select devId from developers where email = :email");
        $result->execute([
            'email' => $userVerifiedData['email']
        ]);
        if($result->rowCount() > 0){
            foreach($result as $row){
                $pk = $row['devId'];
            }
        }else{
            //echo json_encode(Array('Error' => $projectReqJSON['projectId']));
            insertProjectRequest($pdo, $userVerifiedData, $projectReqJSON);
        }
        return $pk;
    }

?>