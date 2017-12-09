<?php 
    class MyAPI extends API
    {
    
        public function __construct($request, $origin) {
            parent::__construct($request);
        }

         protected function project($args) {
            if ($this->method == 'GET') {
                if($this->verb == '' && !empty($this->args)){
                    return include('restfulEndPoints/getProject.php');
                }elseif($this->verb == 'requests' && empty($this->args)){
                    //return Array("Error" => "Project reqs to your business");
                    return include('restfulEndpoints/getAllProjectReqsToBusinesses.php');
                }
                return Array("Error" => "Valid verb, Invalid arguement, Argument required or no Arguement required");
            } elseif($this->method == 'POST'){
                if($this->verb == '' && empty($this->args)){
                    return include('restfulEndpoints/postProject.php');
                }elseif($this->verb == 'request'){
                    return include('restfulEndpoints/postProjectRequest.php');
                }else{
                    return Array("Error" => "Endpoint exists but the verb does not or Endpoint does not allow arguements on request type");
                }
            }elseif($this->method == 'DELETE'){
                if($this->verb == 'request'){
                    return include('restfulEndpoints/deleteProjectRequest.php');
                    //return Array("Error" => "Delete");                    
                }else{
                    return Array("Error" => "Endpoint exists but the verb does not or Endpoint does not allow arguements on request type");
                }
            }else {
                return Array("Error" => "Only accepts GET/POST/DELETE requests");
            }
         }
     }
?>