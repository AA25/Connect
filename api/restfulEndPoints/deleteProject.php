<?php
        //Delete a project

        $pdo = get_db();
        $headers = apache_request_headers();
        if(isset($headers['Authorization'])){
            //Getting the token sent
            $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
            //Creating a token object from the token sent
            $verifiedJWT = new Jwt ($tokenInAuth);
            //Getting data out from the sent token object
            $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
            //If the token passes verification then we know the data it contains is also valid and true
            //This action can only be preformed by businesses also 
            if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
                $deleteProject = $this->args[0];
                return prepareDeleteProject($pdo, $userVerifiedData, $deleteProject);
            }else{
                return Array('Error' => 'Permission denied');
            }
        }else{
            return Array('Error' => 'Please log in to preform this action');
        }

        function prepareDeleteProject($pdo, $userVerifiedData, $deleteProject){
            // A few checks need to be made first. That the business owns the project requested to be deleted
            //And that the project has not been started
            $check = checkOwnershipAndState($pdo, $userVerifiedData, $deleteProject);
            if($check){
                $pdo->beginTransaction();
                try{
                    //Checks have been passed, no we delete the project
                    $delete = $pdo->prepare("
                        delete from projects where projectId = :deleteProject
                    ");

                    $delete->execute([
                        'deleteProject' => $deleteProject
                    ]);

                    //Once the project is deleted we need to remove all developers who may have been
                    //accepted to this project before it started
                    $deassign1 = $pdo->prepare("
                        delete from projectDevelopers where projectId = :deleteProject
                    ");

                    $deassign1->execute([
                        'deleteProject' => $deleteProject
                    ]);

                    //Now set those developers currentProject back to null
                    $deassign2 = $pdo->prepare("
                        update developers set currentProject = NULL where currentProject = :deletedProject
                    ");

                    $deassign2->execute([
                        'deletedProject' => $deleteProject
                    ]);

                    //We also delete all current requests to that project
                    $deleteReq = $pdo->prepare("
                        delete from projectRequests where projectId = :deletedProject
                    ");

                    $deleteReq->execute([
                        'deletedProject' => $deleteProject
                    ]);

                    //We've got this far without an exception, so commit the changes.
                    $pdo->commit();
                    return Array('Success' => 'The project has been successfully deleted');

                }catch(Exception $e){
                    $pdo->rollBack();
                    return Array('Error' => 'Error with deleting projects');
                }
            }else{
                return Array('Error' => 'No permission or project has already been started');
            }
        }

        function checkOwnershipAndState($pdo, $userVerifiedData, $deleteProject){
            $check1 = $pdo->prepare("
                select projects.projectStatus from projects
                inner join businesses on businesses.busId = projects.businessId 
                where businesses.email  = :userEmail and projects.projectId = :deleteProject"
            );

            $check1->execute([
                'userEmail' => $userVerifiedData['email'],
                'deleteProject' => $deleteProject
            ]);

            //If a result was found then this user owns the project
            if($check1->rowCount() > 0){
                foreach($check1 as $projectStatus){
                    //If the projectStatus is less than 2 then we know the project hasnt been started
                    if($projectStatus['projectStatus'] < 2){
                        return true;
                    }
                }
            }
            return false;
        }
?> 