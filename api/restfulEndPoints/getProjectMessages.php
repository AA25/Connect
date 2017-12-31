<?php
    //Get messages sent within the project

    $pdo = get_db();
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        //Getting the token sent
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        //Creating a token object from the token sent
        $verifiedJWT = new Jwt ($tokenInAuth);
        //Getting data out from the sent token object
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);
        //This page should only be accessible if JWT is verified and you're a business or a developer
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && ( ($userVerifiedData['type'] == 'business') ||  ($userVerifiedData['type'] == 'developer'))){
            $projectId = $this->args[0];
            return retrieveMessages($pdo, $userVerifiedData, $projectId);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function retrieveMessages($pdo, $userVerifiedData, $projectId){
        //A check needs to made before retrieving messages
        //User either needs to be the business owner of the project or has to be developer working on the project
        if(partOfProject($pdo, $userVerifiedData, $projectId)){
            $messages = $pdo->prepare("
                select projectMessages.fromWho, projectMessages.messageTime, projectMessages.sentMessage, projects.projectName, projects.projectStatus 
                from projectMessages inner join projects on projects.projectId = projectMessages.projectId
                where projects.projectId = :requestedProject order by messageTime desc
            ");

            $messages->execute([
                'requestedProject' => $projectId
            ]);

            if($messages->rowCount() > 0){
                $returnMessages = Array('Messages' => []);
                foreach($messages as $message){

                    $returnMessages['projectName'] = $message['projectName'];
                    //Convert project current status to its string equivalent
                    $projectStatus = new ProjectStatusConverter($message['projectStatus']);
                    $returnMessages['projectStatus'] = $projectStatus->getStatus();

                    //Push messages into an array that will be returned at the end
                    pushMessages($returnMessages['Messages'], $message);
                }
                return Array('Success' => $returnMessages);
            }else{
                //By default when a project is started an intro message is inserted by default
                //Therefore if there are no messages then the project hasn't been started
                return Array('Error' => 'This project has not been started');
            }

        }else{
            return Array('Error' => 'You are not part of this project therefore have no permission to view messages');
        }
    }

    function pushMessages(&$returnMessages, $message){
        array_push($returnMessages,
            Array(
                'fromWho'           => $message['fromWho'],
                'messageTime'       => $message['messageTime'],
                'sentMessage'       => $message['sentMessage']
            )
        );
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