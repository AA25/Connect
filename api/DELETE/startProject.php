<?php 

    require "../../includes/init.inc.php";
    $pdo = get_db();

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        //This API endpoint should only be accessible if JWT token  is verified and user is a developer
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            $postData = json_decode(file_get_contents('php://input'),true);                
            setProjectStatus($pdo, $userVerifiedData, $postData);
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'No Authorization Header'));
    }

    function setProjectStatus($pdo, $userVerifiedData, $postData){
        //A few checks are needed before a project can move from stage 1 to 2 
        //Check1 is to make sure the project is in stage 1
        if (checkCurrentStatus($pdo, $userVerifiedData, $postData, 1)){
            //Check2 is to make sure the project has at least developer assigned to it
            if(checkDevCount($pdo, $userVerifiedData, $postData, 1)){
                //After the checks we then change the projectstatus from 1 to 2
                updateProjectStage($pdo, $userVerifiedData, $postData, 2);
            }else{
                echo json_encode(Array('Error' => 'Insufficient number of developers on this project to start this project'));
            }
        }else{
            echo json_encode(Array('Error' => 'Invalid Request'));
        };
    }

    function checkCurrentStatus($pdo, $userVerifiedData, $postData, $shouldBe){
        $check1 = $pdo->prepare("select projects.projectStatus from projects inner 
        join businesses on projects.businessId = businesses.busId 
        where projects.projectId = :projectId and businesses.email = :email");

        $check1->execute([
            'projectId' => $postData['projectId'],
            'email'     => $userVerifiedData['email']
        ]);

        if($check1->rowCount() > 0){
            foreach($check1 as $project){
                if($project['projectStatus'] == $shouldBe){
                    return true;
                }
            }   
        }else{
            return false;            
        }
    }

    function checkDevCount($pdo, $userVerifiedData, $postData, $devCount){
        $check2 = $pdo->prepare("
            select devId from projectDevelopers where projectId = :projectId
        ");

        $check2->execute([
            'projectId' => $postData['projectId']
        ]);

        if($check2->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function updateProjectStage($pdo, $userVerifiedData, $postData, $updateTo){
        // Update project status of a project owned by a specific project

        $update = $pdo->prepare('update projects inner join businesses on projects.businessId = businesses.busId 
        set projects.projectStatus = :updateTo where businesses.email = :email and projects.projectId = :projectId');

        $update->execute([
            'updateTo'  => $updateTo,
            'email'     => $userVerifiedData['email'],
            'projectId' => $postData['projectId']
        ]);
        
        if($update->rowCount() > 0){
            echo json_encode(Array('Success' => 'Project has been successfully started!'));
            
        }else{
            echo json_encode(Array('Error' => 'Action was not able to be completed'));
        }
    }
?>