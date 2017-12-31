<?php
    //Toggle the proceedStatus value in the projectDevelopers table for a developer

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

        if($verifiedJWT->verifyJWT($verifiedJWT->token) && ( $userVerifiedData['type'] == 'developer')){
            return toggleProceedStatus($pdo, $userVerifiedData, $projectId);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function toggleProceedStatus($pdo, $userVerifiedData){
        //First a check needs to be made to see if this developer is part of a project 
        //as only developers part of a project will be in the projectDevelopers table
        $proceedStatus = isPartOfProject($pdo, $userVerifiedData);

        if(!is_numeric($proceedStatus)){
            return Array('Error' => 'Developer is not part of a project');
        }else{
            //Here we toggle the value as it can only be 0 (false) or 1 (true)
            if($proceedStatus == 1){
                $toggle = 0;
            }elseif($proceedStatus == 0){
                $toggle = 1;
            }

            $pdo->beginTransaction();
            try{

                $update = $pdo->prepare("
                    update projectDevelopers inner join developers on projectDevelopers.devId = developers.devId 
                    set projectDevelopers.proceedStatus = :updateStatus where developers.email = :userEmail;
                ");

                $update->execute([
                    'updateStatus'  => $toggle,
                    'userEmail'     => $userVerifiedData['email']
                ]);

                //We've got this far without an exception, so commit the changes.
                $pdo->commit();
                return Array('Success' => 'Successfully updated ready status');

            }catch(Exception $e){
                $pdo->rollBack();
                return Array('Error' => 'Error updating ready status');
            }
        }
    }

    function isPartOfProject($pdo, $userVerifiedData){
        $result = $pdo->prepare("
            select projectDevelopers.proceedStatus from projectDevelopers inner join developers on developers.devId = projectDevelopers.devId where developers.email = :devEmail
        ");

        $result->execute([
            'devEmail' => $userVerifiedData['email']
        ]);

        if($result->rowCount() > 0){
            //A result was found therefore the user is part of a project
            foreach($result as $proceedStatus){
                return $proceedStatus['proceedStatus'];
                break;
            }
        }else{
            return false;
        }
    }
?>