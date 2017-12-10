<?php
    //Returns all the project requests a business has for all of its projects

    $pdo = get_db();

    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            return prepareSelectRequest($pdo, $userVerifiedData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }


    //I need a query where I can pull the project requests to all projects owned by a business
    function prepareSelectRequest($pdo, $userVerifiedData){
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