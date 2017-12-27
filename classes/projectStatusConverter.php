<?php 
    //This object takes in a project status code and converts it into a string with a meaning
    class ProjectStatusConverter {
        private $projectStatusCode;

        function __construct($projectStatusCode){

            switch($projectStatusCode) {
                case 0:
                    $projectStatusCode = "Recruiting Developers";
                    break;
                case 1:
                    $projectStatusCode = "Pending Start";
                    break;
                case 2:
                    $projectStatusCode = "Discussion Phase";
                    break;
                case 3:
                    $projectStatusCode = "Implementation Phase";
                    break;
                case 4:
                    $projectStatusCode = "Completion Phase";
                    break;
                case 5:
                    $projectStatusCode = "Project Finished";
                    break;
                //For the same of time there will only be 4 phases for now 
                // case 4:
                //     $projectStatusCode = "Reviewing Phase";
                //     break;
                // case 5:
                //     $projectStatusCode = "Live Phase";
                //     break;
                // case 6:
                //     $projectStatusCode = "Project Finished";
                //     break;
            }

            $this->projectStatusCode = $projectStatusCode;
        }

        function getStatus(){
            return $this->projectStatusCode;
        }
    }
    
?>