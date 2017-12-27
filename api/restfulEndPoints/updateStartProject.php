<?php
    //Updates the current status/stage of a project from 1 -> 2 meaning 'Discussion Phase'

    $pdo = get_db();

    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);
        //This API endpoint should only be accessible if JWT token is verified and user is a business
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            $postData = (int)$this->args[0];
            return setProjectStatus($pdo, $userVerifiedData, $postData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function setProjectStatus($pdo, $userVerifiedData, $postData){
        //A few checks are needed before a project can move from stage 1 to 2
        //Check1 is to make sure the project is in stage 1
        if (checkCurrentStatus($pdo, $userVerifiedData, $postData, 1)){
            //Check2 is to make sure the project has at least developer assigned to it
            if(checkDevCount($pdo, $userVerifiedData, $postData, 1)){
                //After the checks we then change the projectstatus from 1 to 2
                return updateProjectStage($pdo, $userVerifiedData, $postData, 2);
            }else{
                return Array('Error' => 'Insufficient number of developers on this project to start this project');
            }
        }else{
            return Array('Error' => 'Invalid Request');
        };
    }

    function checkCurrentStatus($pdo, $userVerifiedData, $postData, $shouldBe){
        $check1 = $pdo->prepare("select projects.projectStatus from projects inner 
        join businesses on projects.businessId = businesses.busId 
        where projects.projectId = :projectId and businesses.email = :email");

        $check1->execute([
            'projectId' => $postData,
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
            'projectId' => $postData
        ]);

        if($check2->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function createProjectMessage($pdo, $postData){
        $AdminMsg = new DefaultMessage(1);
        $AdminMsg = $AdminMsg->getDefaultMsg();

        $pdo->beginTransaction();
        try{
            $insertMsg = $pdo->prepare('insert into projectMessages (projectId, fromWho, messageTime, sentMessage) 
            values (:projectId, :sender, :dateTime, :message)');

            $insertMsg->execute([
                'projectId' => $postData,
                'sender'    => 'Connect Admin',
                'dateTime'  => date("Y-m-d H:i:s"),
                'message'   => $AdminMsg
            ]);

            //We've got this far without an exception, so commit the changes.
            $pdo->commit();
        }catch(Exception $e){
            $pdo->rollBack();
        }
    }

    function updateProjectStage($pdo, $userVerifiedData, $postData, $updateTo){

        // Update project status of a project owned by a specific project
        $update = $pdo->prepare('update projects inner join businesses on projects.businessId = businesses.busId 
        set projects.projectStatus = :updateTo where businesses.email = :email and projects.projectId = :projectId');

        $update->execute([
            'updateTo'  => $updateTo,
            'email'     => $userVerifiedData['email'],
            'projectId' => $postData
        ]);
        
        if($update->rowCount() > 0){
            createProjectMessage($pdo, $postData);
            return Array('Success' => 'Project has been successfully started!');
            
        }else{
            return Array('Error' => 'Action was not able to be completed');
        }
    }
?>