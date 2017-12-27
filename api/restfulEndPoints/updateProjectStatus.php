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
            //Depending on the current status of the project we route it to different methods
            switch ($currentStatus) {
                case 2:
                    return proceedProjectStatus($pdo, $userVerifiedData, $postData, $currentStatus);
                    break;
                case 3:
                    //There are more stages that can be seen in projectStatusConverter but with little time left
                    //after phase 3 the project is complete
                    break;
                default:
                    return Array('Error' => 'Error in proceeding project to the next stage');
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
                if($project['projectStatus'] >= $shouldBe){
                    return $project['projectStatus'];
                }
            }
        }else{
            return false;
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

            // $insertMsg = $pdo->prepare('insert into projectMessages (projectId, fromWho, messageTime, sentMessage) 
            // values (:projectId, :sender, :dateTime, :message)');

            // $insertMsg->execute([
            //     'projectId' => $postData,
            //     'sender'    => 'Connect Admin',
            //     'dateTime'  => date("Y-m-d H:i:s"),
            //     'message'   => 'The project has moved stages, well done! A proper default message needs to be included here'
            // ]);

            //We've got this far without an exception, so commit the changes.
            $pdo->commit();
            return Array('Success' => 'Successfully updated project status');

        }catch(Exception $e){
            $pdo->rollBack();
            return Array('Error' => 'Error in proceeding project to the next stage');
        }

    }
?>