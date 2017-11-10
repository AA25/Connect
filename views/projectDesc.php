<? 
    require "../includes/init.inc.php";
    $pdo = get_db();

    //important to tell your browser what we will be sending
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $result = $pdo->prepare("select projectCategory, projectBio, projectBudget, projectDeadline, projectCountry, projectLanguage, projectCurrency, dateEntered, startDate from projects where projectId = :projectId");
    $result->execute([
        'projectId' => $_GET['projectId']
    ]);
    if($result->rowCount() > 0){
        $returnProject = [];
        foreach($result as $row){
            pushProjectDetails($returnProject, $row);
        }
        //echo json_encode($returnProject);
        renderProject();
    }else{
        //echo json_encode(Array('Error' => "Oops, the project you're looking doesn't seem to exist"));
        renderError();
    }

    function pushProjectDetails(&$returnProject, $info){
        array_push($returnProject, 
            Array(
                'projectCategory'   => $info['projectCategory'],
                'projectBio'        => $info['projectBio'],
                'projectCurrency'   => $info['projectCurrency'],
                'projectBudget'     => $info['projectBudget'],
                'projectCountry'    => $info['projectCountry'],
                'projectDeadline'   => $info['projectDeadline'],
                'projectLanguage'   => $info['projectLanguage'],
                'dateEntered'       => $info['dateEntered'],
                'startDate'         => $info['startDate']
            )
        );
    }

    function renderProject(){
        echo "The project view!";
    }

    function renderError(){
        echo "Oops, the project you're looking doesn't seem to exist";
    }
?>