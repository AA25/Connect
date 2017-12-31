<?php   //livesearch controller that will return all project name of projects similar to the query string

    require "../../includes/init.inc.php";
    $pdo = get_db();

    //important to tell your browser what we will be sending
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //Find projects that contain the query string
    $projects = $pdo->prepare("select projectName, projectId from projects 
        where projectName like :search"
    );

    $projects->execute([
        'search' => "%".$_GET['q']."%"
    ]);


    if($projects->rowCount() > 0){
        $returnProjects = '';
        foreach($projects as $project){
            $returnProjects = $returnProjects 
                . '<a href="http://localhost:8081/project/'.$project['projectId'].'">' 
                . $project['projectName'] . '</a>' . '<br>';
        }
        echo $returnProjects;
    }else{
        echo 'No Suggestions';
    }
?>