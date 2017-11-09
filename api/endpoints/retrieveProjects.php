<?php
require "../../includes/init.inc.php";
    $pdo = get_db();

    //important to tell your browser what we will be sending
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //TODO Use limit x,y to return y amount from x+1
    //echo json_encode(Array('returnAmount' => $_GET['returnAmount'], 'returnFrom' => $_GET['returnFrom']));

    $result = $pdo->prepare("
        select projectId, projectCategory, projectBio, projectBudget, projectCountry, projectCurrency from projects order by dateEntered desc limit :returnFrom, :returnAmount
    ");
    // $pdo->bindParam(':returnFrom', (int)$_GET['returnFrom'], PDO::PARAM_INT);
    // $pdo->bindParam(':returnAmount', (int)$_GET['returnAmount'], PDO::PARAM_INT);
    $result->execute([
        'returnFrom' => (int)$_GET['returnFrom'],
        'returnAmount' => (int)$_GET['returnAmount']
    ]);
    if($result->rowCount() > 0){
        $returnProjects = ['Projects' => []];
        foreach($result as $info){
            //array_push($returnProjects['Projects'], $info);
            pushProjectDetails($returnProjects['Projects'], $info);
        }
        echo json_encode($returnProjects);
    }else{
        echo json_encode(Array('Error' => 'No results available'));
    }

    function pushProjectDetails(&$returnProjects, $info){

        // $returnProjects['projectCategory']  = $info['projectCategory'];
        // $returnProjects['projectBio']       = $info['projectBio'];
        // $returnProjects['projectBudget']    = $info['projectBudget'];
        // $returnProjects['projectCountry']   = $info['projectCountry'];
        // $returnProjects['projectCurrency']  = $info['projectCurrency'];
        array_push($returnProjects, 
            Array(
                'projectId'         => $info['projectId'],
                'projectCategory'   => $info['projectCategory'],
                'projectBio'        => $info['projectBio'],
                'projectBudget'     => $info['projectBudget'],
                'projectCountry'    => $info['projectCountry'],
                'projectCurrency'   => $info['projectCurrency']
            )
        // $info['projectCategory'], $info['projectBio'], $info['projectBudget'], $info['projectCountry'], $info['projectCurrency']
        );
    }
?>