<?php
    //Updates the project status of a project to the next stage

    $pdo = get_db();

    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);
        //This API endpoint should only be accessible if JWT token is verified and user is a business
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            $postData = (int)$this->args[0];
            return prepareProceedings($pdo, $userVerifiedData, $postData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function prepareProceedings($pdo, $userVerifiedData, $postData){
        //A few checks are needed before a project can move to the next stage
        //Check1 is to make sure the project is past stage 2 meaning it has been started which inherently also 
        //checks that the business owns this project
        $currentStatus = checkCurrentStatus($pdo, $userVerifiedData, $postData, 2);
        //If the check doesn't return false
        if (gettype($currentStatus) != boolean){
            //Check2 is to ensure that all developers on the project are ready to proceed to the next stage
            $allReady = checkAllReady($pdo, $userVerifiedData, $postData);
            if($allReady){
                //Depending on the current status of the project we route it to different methods
                switch ($currentStatus) {
                    case 2:
                        return proceedProjectStatus($pdo, $userVerifiedData, $postData, $currentStatus);
                        break;
                    case 3:
                        return proceedProjectStatus($pdo, $userVerifiedData, $postData, $currentStatus);
                        break;
                    case 4:
                        //There are more stages that can be seen in projectStatusConverter but with little time left
                        //after 3 phases the project is complete
                        //return endProject($pdo, $userVerifiedData, $postData, $currentStatus);
                        break;
                    default:
                        return Array('Error' => 'Error in proceeding project to the next stage');
                }
            }else{
                return Array('Error' => 'All developers on this project must be ready to proceed');
            }
        }else{
            return Array('Error' => 'Invalid Request due to permission or project not existing');
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
                if($project['projectStatus'] >= $shouldBe){
                    return $project['projectStatus'];
                }
            }
        }else{
            return false;
        }
    }

    function checkAllReady($pdo, $userVerifiedData, $postData){
        //Query to return developers part of this project and check their proceedStatus
        $developers = $pdo->prepare("
            select proceedStatus from projectDevelopers
            where projectId = :requestedProject;
        ");

        $developers->execute([
            'requestedProject' => $postData
        ]);

        if($developers->rowCount() > 0){
            foreach($developers as $developerProceedStatus){
                if($developerProceedStatus['proceedStatus'] == 0){
                    //If any of the proceedStatus for each developer == 0 it means that developer is not ready to proceed
                    return false;
                }
            }
            return true;
        }
    }

    function proceedProjectStatus($pdo, $userVerifiedData, $postData, $currentStatus){
        $pdo->beginTransaction();
        try{

            //Update the projectStatus value in the projects table to the next stage
            $update = $pdo->prepare("
                update projects inner join businesses on businesses.busId = projects.businessId 
                set projects.projectStatus = :updateStatusTo 
                where businesses.email = :userEmail and projects.projectId = :thisProject;
            ");

            $update->execute([
                'updateStatusTo' => $currentStatus+1,
                'userEmail'      => $userVerifiedData['email'],
                'thisProject'    => $postData
            ]);

            //A default message needs to be sent to the project allowing everyone involved know that
            //this action has taken place

            $insertMsg = $pdo->prepare('insert into projectMessages (projectId, fromWho, messageTime, sentMessage) 
            values (:projectId, :sender, :dateTime, :message)');

            $insertMsg->execute([
                'projectId' => $postData,
                'sender'    => 'Connect Admin',
                'dateTime'  => date("Y-m-d H:i:s"),
                'message'   => 'The project has moved stages, well done! A proper default message needs to be included here'
            ]);

            //We've got this far without an exception, so commit the changes.
            $pdo->commit();
            return Array('Success' => 'Successfully updated project status');

        }catch(Exception $e){
            $pdo->rollBack();
            return Array('Error' => 'Error in proceeding project to the next stage');
        }

    }
?>