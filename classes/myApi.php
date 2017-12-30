<?php
    class MyAPI extends API
    {

        //Use the API class contructor to break down the request e.g. DELETE /api/project/delete/13
        public function __construct($request, $origin) {
            parent::__construct($request);
        }

        //Everything below is just routing the request based on its the the method, endpoint,
        //verbs and arguements if a route is found it calls the appropriate php file to handle
        //the requests and return a result. If route is not found and error is provided

        //The functions below are the REST API's endpoints
         protected function projects($args){
            if ($this->method == 'GET') {
                if($this->verb == 'from' && is_numeric($this->args[0]) && is_numeric($this->args[1])){
                    //return Array("Error" => "Testing url");
                    return include('restfulEndPoints/getAllProjects.php');
                }elseif($this->verb == '' && empty($this->args)){
                    return Array("TODO" => "Return all projects");
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");
            }else {
                return Array("Error" => "Endpoint only accepts GET requests");
            }
         }

         protected function business($args){
            if ($this->method == 'POST') {

                if($this->verb == 'register' && empty($this->args)){
                    //return Array("Error" => "Testing business register");
                    return include('restfulEndPoints/postBusiness.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }elseif($this->method == 'GET'){

                if($this->verb == 'info' && !empty($this->args[0])) {
                    //return Array("Error" => "Testing business info retrieval");
                    return include('restfulEndpoints/getBusiness.php');
                }elseif($this->verb == 'projects' && empty($this->args)){
                    //return Array("Error" => "business projects");
                    return include('restfulEndpoints/getYourBusinessProjects.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }else {

                return Array("Error" => "Endpoint only accepts POST/GET requests");

            }
         }

         protected function developer($args){
            if ($this->method == 'POST') {

                if($this->verb == 'register' && empty($this->args)){
                    //return Array("Error" => "Testing developer register");
                    return include('restfulEndPoints/postDeveloper.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }elseif($this->method == 'GET'){

                if($this->verb == 'info' && !empty($this->args[0])) {
                    //return Array("Error" => "Testing developer info retrieval");
                    return include('restfulEndpoints/getDeveloper.php');
                }elseif($this->verb == 'project' && empty($this->args)){
                    //return Array("Error" => "Developers current project");
                    return include('restfulEndpoints/getCurrentDeveloperProject.php');
                }elseif($this->verb == 'requests' && empty($this->args)){
                    //return Array("Error" => "Developers sent requests");
                    return include('restfulEndpoints/getAllProjectRequestsSent.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }elseif($this->method == 'PUT'){

                if($this->verb == 'toggleProceedStatus' && empty($this->args)){
                    return include('restfulEndpoints/updateProceedStatus.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }else {

                return Array("Error" => "Endpoint only accepts POST/GET requests");

            }
         }

         protected function project($args) {
            if ($this->method == 'GET') {

                if($this->verb == '' && !empty($this->args)){
                    return include('restfulEndPoints/getProject.php');
                }elseif($this->verb == 'requests' && empty($this->args)){
                    //return Array("Error" => "Project reqs to your business");
                    return include('restfulEndpoints/getAllProjectReqsToBusinesses.php');
                }elseif($this->verb == 'developers'){
                    return include('restfulEndpoints/getDevelopersPerProject.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            } elseif($this->method == 'POST'){

                if($this->verb == '' && empty($this->args)){
                    return include('restfulEndpoints/postProject.php');
                }elseif($this->verb == 'request'){
                    return include('restfulEndpoints/postProjectRequest.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }elseif($this->method == 'DELETE'){

                if($this->verb == 'request'){
                    return include('restfulEndpoints/deleteProjectRequest.php');
                    //return Array("Info" => "Delete request");
                }elseif($this->verb == 'delete' && !empty($this->args[0])){
                    return include('restfulEndpoints/deleteProject.php');
                    //return Array("Info" => "Delete project");
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }elseif ($this->method == 'PUT') {

                if($this->verb == 'requests' && empty($this->args)){
                    return include('restfulEndpoints/updateProjectRequest.php');
                }elseif($this->verb == 'start' && !empty($this->args)){
                    //return Array("Error" => "You wanna start project ".$this->args[0]);
                    return include('restfulEndpoints/updateStartProject.php');
                }elseif($this->verb == 'proceedStage' && !empty($this->args)){
                    //return Array("Test" => "Testing");
                    return include('restfulEndpoints/updateProjectStatus.php');
                }
                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");

            }else {

                return Array("Error" => "Endpoint only accepts GET/POST/DELETE/PUT requests");

            }
         }

         protected function forum($args){
            if ($this->method == 'GET') {

                if($this->verb == '' && !empty($this->args)){
                    //return Array("Error" => "Forum get endpoint");
                    return include('restfulEndPoints/getProjectMessages.php');
                }elseif($this->verb == 'developers' && !empty($this->args)){
                    return include('restfulEndpoints/getDevelopersOnProject.php');
                }

                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");                

            }elseif($this->method == 'POST') {

                if($this->verb == '' && !empty($this->args)){
                    return include('restfulEndpoints/postProjectMessage.php');
                }

                return Array("Error" => "Invalid verb, Invalid arguement, Argument required or no Arguement required");                

            }else {

                return Array("Error" => "Endpoint only accepts GET|POST requests");

            }
         }
     }

?>