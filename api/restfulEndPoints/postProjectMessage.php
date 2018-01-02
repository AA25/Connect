<?php

    //POST new message to the project

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

        if($verifiedJWT->verifyJWT($verifiedJWT->token) && ( ($userVerifiedData['type'] == 'business') ||  ($userVerifiedData['type'] == 'developer'))){
            $projectId = $this->args[0];
            $sentMessage = json_decode($this->file,true);
            return postMessages($pdo, $userVerifiedData, $projectId, $sentMessage);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function postMessages($pdo, $userVerifiedData, $projectId, $sentMessage){
        //A few checks need to be made before posting the message
        //User either needs to be the business owner of the project or has to be developer working on the project
        if(partOfProject($pdo, $userVerifiedData, $projectId)){
            //Creating server validation object to sanitise and valid the data sent in the request
            $validation = new ServerValidation();
            //Second check is server side validation of the message
            $isValid = $validation->sendProjectMessageSanitisation($sentMessage['sentMessage']);
            if(gettype($isValid) == boolean && $isValid == true){
                $pdo->beginTransaction();
                try{
                    $insertMessage = $pdo->prepare("
                        insert into projectMessages (projectId, fromWho, messageTime, sentMessage)
                        values(:thisProject, :sentBy, :currentTime, :sentMessage)
                    ");

                    $insertMessage->execute([
                        'thisProject'   => $projectId,
                        'sentBy'        => $userVerifiedData['firstName'] . ' ' . $userVerifiedData['lastName'],
                        'currentTime'   => date("Y-m-d H:i:s"),
                        'sentMessage'   => $sentMessage['sentMessage']
                    ]);

                    //We've got this far without an exception, so commit the changes.
                    $pdo->commit();
                    return Array('Success' => 'Message was sent successfully');

                }catch(Exception $e){
                    $pdo->rollBack();
                    return Array('Error' => 'Error sending message');
                }

            }else{
                //If isValid is not true it contains a validation error message
                return Array('Error' => $isValid);
            }

        }else{
            return Array('Error' => 'You are not part of this project therefore have no permission to post messages');
        }
    }

    function partOfProject($pdo, $userVerifiedData, $projectId){
        if($userVerifiedData['type'] == 'business'){
            //Select all projects a business owns where projectId matches the requested projectId
            $checkProject = $pdo->prepare("
                select projects.projectId from projects inner join businesses on businesses.busId = projects.businessId 
                where businesses.email = :busEmail and projects.projectId = :requestedProject;
            ");

            $checkProject->execute([
                'busEmail'  => $userVerifiedData['email'],
                'requestedProject'  => $projectId
            ]);

            if($checkProject->rowCount() > 0){
                //The query found the project under the projects the business owns therefore they own it
                return true;
            }else{
                return false;
            }

        }elseif($userVerifiedData['type'] == 'developer'){
            //Check the projectDevelopers table to see if this developer is part of a project where projectid is the requested project
            $checkProject = $pdo->prepare("
                select projectDevelopers.projectId from projectDevelopers inner join developers on developers.devId = projectDevelopers.devId 
                where developers.email = :devEmail and projectDevelopers.projectId = :requestProject;
            ");

            $checkProject->execute([
                'devEmail' => $userVerifiedData['email'],
                'requestProject' => $projectId
            ]);

            if($checkProject->rowCount() > 0){
                //A result was found therefore the developer currently works under that project
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }
?>