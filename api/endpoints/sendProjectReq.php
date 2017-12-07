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

            //Creating server validation object to sanitise and valid the data sent in the request
            $validation = new ServerValidation();
            if($validation->sendRequestSanitisation($projectReqJSON['projectId'],$projectReqJSON['devMsg'])){
                checkDevelopersCurrentProject($pdo, $userVerifiedData, $projectReqJSON);
            }else{
                echo json_encode(Array('Error' => 'Validation Failed'));
            }
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'No Authorization Header'));
    }

    //Need to check that the developer is not already part of a project
    function checkDevelopersCurrentProject($pdo, $userVerifiedData, $projectReqJSON){
        $currentProject = '';
        $check = $pdo->prepare("
            select currentProject from developers where email = :devEmail
        ");

        $check->execute([
            'devEmail'  => $userVerifiedData['email']
        ]);

        if($check->rowCount() > 0){
            foreach($check as $row){
                $currentProject = $row['currentProject'];
            }
        }

        if($currentProject == null){
            checkDuplicateReqs($pdo, $userVerifiedData, $projectReqJSON);
        }else{
            echo json_encode(Array('Error' => 'User already part of a project'));
        }
    }

    //Checking if the user already has a request sent to this project already
    function checkDuplicateReqs($pdo, $userVerifiedData, $projectReqJSON){
        //A query that selects all pending requests made by this particular developer to a specific project
        $result = $pdo->prepare("select * from projectRequests 
            inner join developers on projectRequests.devId = developers.devId 
            where developers.email = :email and projectRequests.projectId = :projectId and projectRequests.status = :status
        ");

        $result->execute([
            'email'     => $userVerifiedData['email'],
            'projectId' => $projectReqJSON['projectId'],
            'status'    => 'Pending'
        ]);

        //If num of rows returned is greater than 0 we know we have a result meaning theres already a pending request for this specific project made by the user
        if($result->rowCount() > 0){
            echo json_encode(Array('Error' => 'Request for this project has already been made'));
        }else{
            //Before inserting the request into the DB a check needs to be done to make sure the project is in stage 0 || 1 
            //As these are the only stages when devs can send a request to a project
            checkProjectStatus($pdo, $userVerifiedData, $projectReqJSON);
        }
    }
    
    function checkProjectStatus($pdo, $userVerifiedData, $projectReqJSON){
        // Retrieve the status of a project a dev is trying to send a request to
        $allowInsert = false;
        $status = $pdo->prepare("select projectStatus from projects where projectId = :projectId");

        $status->execute([
            'projectId' => $projectReqJSON['projectId']
        ]);

        if($status->rowCount() > 0){
            foreach($status as $targetProject){
                if($targetProject['projectStatus'] < 2){
                    insertProjectRequest($pdo, $userVerifiedData, $projectReqJSON);
                }else{
                    echo json_encode(Array('Error' => 'This project is no longer accepting requests'));
                }
            }
        }
    }

    function insertProjectRequest($pdo, $userVerifiedData, $projectReqJSON){
        $devId = getPrimaryKey($pdo,$userVerifiedData);
        $busId = getBusIdFromProjId($pdo,$projectReqJSON['projectId']);
        $pdo->beginTransaction();
        try{
            //Now we insert a new project with the busId 
            $result = $pdo->prepare(
                "insert into
                projectRequests (projectId, devId, devMsg, status, busId)
                values(:projectId, :devId, :devMsg, :status, :busId);"
            );
            $result->execute([
                'projectId' => $projectReqJSON['projectId'],
                'devId' => $devId,
                'devMsg' => $projectReqJSON['devMsg'],
                'status' => 'Pending',
                'busId' => $busId
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
        }
        return $pk;
    }

    function getBusIdFromProjId($pdo,$projectId){
        $busId = "";
        $result = $pdo->prepare("select businessId from projects where projectId = :projectId");
        $result->execute([
            'projectId' => $projectId
        ]);
        if($result->rowCount() > 0){
            foreach($result as $row){
                $busId = $row['businessId'];
            }
        }
        return $busId;
    }

?>