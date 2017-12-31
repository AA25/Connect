<?php
    //Returns all the project requests a business has for all of its projects

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
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            return prepareSelectRequest($pdo, $userVerifiedData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function prepareSelectRequest($pdo, $userVerifiedData){
        //Query that returns all project requests made to any of the projects a business owns
        $returnProjectReqs = ['Success' => []];
        $result = $pdo->prepare(
            "select projectRequests.projectReqId, projectRequests.projectId, projects.projectName, projects.projectCategory, projects.projectBio, developers.devId, developers.firstName, developers.lastName, developers.email, developers.username, projectRequests.status, projectRequests.devMsg
            from (((projectRequests inner join projects on projectRequests.projectId = projects.projectId)
            inner join developers on projectRequests.devId = developers.devId)
            inner join businesses on projectRequests.busId = businesses.busId)
            where businesses.email = :busEmail and projectRequests.status = :status"
        );
        $result->execute([
            'busEmail' => $userVerifiedData['email'],
            'status' => 'pending'
        ]);
        if($result->rowCount() > 0){
            //Push these projects into the array that will be returned at the end
            foreach($result as $requests){
                pushProjectDetails($returnProjectReqs['Success'],$requests);
            }
        }else{
            $returnProjectReqs['Success'] = 'No requests to any of your projects';
        }
        return $returnProjectReqs;
    }; 

    function pushProjectDetails(&$returnProjectReqs, $requests){
        array_push($returnProjectReqs, 
            Array(
                'projectReqId'      => $requests['projectReqId'],
                'projectId'         => $requests['projectId'],
                'projectName'       => $requests['projectName'],
                'projectCategory'   => $requests['projectCategory'],
                'projectBio'        => $requests['projectBio'],
                'devName'           => $requests['firstName'].' '.$requests['lastName'],
                'devEmail'          => $requests['email'],
                'devId'             => $requests['devId'],
                'username'          => $requests['username'],
                'devMsg'            => $requests['devMsg'],
                'status'            => $requests['status']
            )
        );
    }
?>