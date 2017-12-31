<?php
    //Returns all the requests that a developer has sent unless already deleted
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
        //Query to return all project requests sent by a developer
        $result = $pdo->prepare("select projectRequests.projectReqId, projectRequests.projectId, projects.projectName, projectRequests.devMsg, projectRequests.status 
            from ((projectRequests inner join developers on projectRequests.devId = developers.devId)
            inner join projects on projectRequests.projectId = projects.projectId)
            where developers.email = :devEmail
        ");
        $result->execute([
            'devEmail' => $userVerifiedData['email']
        ]);
        if($result->rowCount() > 0){
            //Push the requests to an array that will be returned at the end
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