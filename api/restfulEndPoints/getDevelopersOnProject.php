<?php

    //Get Developers on the current project

    $pdo = get_db();
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);

        //This action should only be accessible if JWT is verified and you're a business or a developer
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && ( ($userVerifiedData['type'] == 'business') ||  ($userVerifiedData['type'] == 'developer'))){
            $projectId = $this->args[0];
            return retrieveDevelopers($pdo, $userVerifiedData, $projectId);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function retrieveDevelopers($pdo, $userVerifiedData, $projectId){
        //A check needs to made before retrieving developers
        //User either needs to be the business owner of the project or has to be developer working on the project
        if(partOfProject($pdo, $userVerifiedData, $projectId)){
            //Query to return developers part of this project
            $developers = $pdo->prepare("
                select developers.firstName, developers.lastName, developers.username, projectDevelopers.proceedStatus
                from projectDevelopers inner join developers on projectDevelopers.devId = developers.devId 
                where projectDevelopers.projectId = :requestedProject;
            ");

            $developers->execute([
                'requestedProject' => $projectId
            ]);

            if($developers->rowCount() > 0){
                $returnDevelopers = Array('Developers' => []);
                foreach($developers as $developer){
                    pushDeveloper($returnDevelopers['Developers'], $developer);
                }
                return Array('Success' => $returnDevelopers);
            }else{
                return Array('Success' => []);
            }

        }else{
            return Array('Error' => 'You are not part of this project therefore have no permission to view developer list');
        }
    }

    function pushDeveloper(&$returnDevelopers, $developer){
        array_push($returnDevelopers,
            Array(
                'name'              => $developer['firstName'].' '.$developer['lastName'],
                'proceedStatus'     => $developer['proceedStatus'],
                'username'          => $developer['username']
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