<?php
    //Returns all the requests that a developer has sent unless already deleted
    $pdo = get_db();

    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'developer'){
            return prepareSelectRequest($pdo, $userVerifiedData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function prepareSelectRequest($pdo, $userVerifiedData){
        $returnDevReqs = [];

        $result = $pdo->prepare("select projectRequests.projectReqId, projectRequests.projectId, projects.projectName, projectRequests.devMsg, projectRequests.status 
            from ((projectRequests inner join developers on projectRequests.devId = developers.devId)
            inner join projects on projectRequests.projectId = projects.projectId)
            where developers.email = :devEmail
        ");
        $result->execute([
            'devEmail' => $userVerifiedData['email']
        ]);
        if($result->rowCount() > 0){
            foreach($result as $requests){
                pushRequestDetails($returnDevReqs,$requests);
            }
        }else{
            $returnDevReqs = "No requests";
        }
        return Array('Success' => $returnDevReqs);
    }

    function pushRequestDetails(&$returnDevReqs, $requests){
        array_push($returnDevReqs, 
            Array(
                'projectReqId'  => $requests['projectReqId'],
                'projectId'     => $requests['projectId'],
                'projectName'   => $requests['projectName'],
                'devMsg'        => $requests['devMsg'],
                'status'        => $requests['status']
            )
        );
    }
?>