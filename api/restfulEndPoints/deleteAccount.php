<?php

    //Delete account (at the moment only developers can delete accounts)

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
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'developer'){
            return prepareDeleteAccount($pdo, $userVerifiedData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to preform this action');
    }

    function prepareDeleteAccount($pdo, $userVerifiedData){
        //Before deleting a developer we check to see if they are currently part of a project
        $currentProject = noCurrentProject($pdo, $userVerifiedData);
        if($currentProject == false){
            return Array('Error' => 'Account is currently part of a project and connect be deleted yet');
        }

        //Now we delete all current project requests the developer may have
        deleteRequests($pdo, $userVerifiedData);

        //Finally delete account
        $pdo->beginTransaction();
        try{
            $deleteAccount = $pdo->prepare("delete from developers where email = :devEmail");

            $deleteAccount->execute([
                'devEmail' => $userVerifiedData['email']
            ]);

            //We've got this far without an exception, so commit the changes.
            $pdo->commit();
            return Array('Success' => 'The account has been deleted');

        }catch(Exception $e){
            $pdo->rollBack();
            return Array('Error' => 'Error with deleting account');
        }
    }

    function noCurrentProject($pdo, $userVerifiedData){
        //Query to return the current project that a dveveloper may be working on
        $project = $pdo->prepare("
            select currentProject from developers where email = :devEmail
        ");

        $project->execute([
            'devEmail' => $userVerifiedData['email']
        ]);

        if($project->rowCount() > 0){
            //Check to see if there current project is null
            foreach($project as $current){
                if($current['currentProject'] == NULL){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    function deleteRequests($pdo, $userVerifiedData){
        //Query to delete all project requests from this developer
        $deleteReqs = $pdo->prepare("
            delete projectRequests from projectRequests
            inner join developers on projectRequests.devId = developers.devId 
            where developers.email = :devEmail
        ");

        $deleteReqs->execute([
            'devEmail' => $userVerifiedData['email']
        ]);
    }
?>