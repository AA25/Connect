<?php 
    //This object takes in a project status code and returns the default admin message that should be 
    //sent to the projects message board
    class DefaultMessage {
        private $defaultMessage;

        function __construct($projectStatusCode){

            switch($projectStatusCode) {
                case 1:
                    $defaultMessage = "The project has been started, well done! A proper default message needs to be included here";
                    break;
                case 2:
                    $defaultMessage = "The project has moved from the Discussion Phase to the Implementation Phase, well done! A proper default message needs to be included here";
                    break;
                case 3:
                    $defaultMessage = "The project has moved from the Implementation Phase to the Completion Phase, well done on successful completing the project!
                    <br> You can take the time now and do some reviewing of the project and do some finalisation. <br>
                    Once the business owner and all developers are ready to end the project, please press the 'proceed' button.
                    <br> Please note that once the project has ended this message board will not be accessible anymore and all developers will be able to join new projects";
                    break;
                //For the same of time there will only be 4 phases for now 
                // case 4:
                //     $defaultMessage = "Reviewing Phase";
                //     break;
                // case 5:
                //     $defaultMessage = "Live Phase";
                //     break;
                // case 6:
                //     $defaultMessage = "Project Finished";
                //     break;
            }

            $this->defaultMessage = $defaultMessage;
        }

        function getDefaultMsg(){
            return $this->defaultMessage;
        }
    }
    
?>