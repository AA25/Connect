<?php

    // Once business accepts or deletes the request,
    //an update of that project request in the database is required

    $pdo = get_db();
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $requestResponse = json_decode($this->file,true);      
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        //This API endpoint should only be accessible if JWT token  is verified and user is a developer
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            return updateProjectRequest($pdo, $userVerifiedData, $requestResponse);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'No Authorization Header');
    }

    function updateProjectRequest($pdo, $userVerifiedData, $requestResponse){
        $pdo->beginTransaction();
        try{
            // Once business accepts or deletes the request, an update of that project request in the database is required
            // Note that previous rejected requests will be left in the db to later be accessed on the clientside for history purposes, it can be deleted later by user
            // The inner join with businesses table is to ensure that user cannot delete a project request of a different business
            // Also making sure that the project they are accepting the request is in stage 0 or 1 as thats the only stages devs can join a project
            $stepOne = $pdo->prepare("
                update projectRequests inner join businesses on projectRequests.busId = businesses.busId 
                inner join projects on projectRequests.projectId = projects.projectId  
                set projectRequests.status = :busResponse 
                where businesses.email = :busEmail and projectRequests.projectId = :projectId 
                and projectRequests.devId = :devId and projectRequests.status = :status and (projects.projectStatus = 0 or projects.projectStatus = 1);
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

                //Then we want to increment the projects status/stage from 0 to 1
                // 0 is the recruiting stage while 1 is Pending start stage
                // A project only needs 1 developers to be started which is why we move to stage 1 after accepting a developer
                $stepFive = $pdo->prepare("
                    update projects set projectStatus = :status where projectId = :projectId
                ");
                $stepFive->execute([
                    'status' => 1,
                    'projectId' => $requestResponse['projectId']
                ]);
            }

            //We've got this far without an exception, so commit the changes.
            $pdo->commit();
            return Array('Success' => 'Project request updated');

        }catch(Exception $e){
            $pdo->rollBack();
            return Array('Error' => 'Updating response to request failed');
        }
    }


?>