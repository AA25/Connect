<?php 
    class MyAPI extends API
    {
    
        public function __construct($request, $origin) {
            parent::__construct($request);
        }

         protected function project($args) {
            if ($this->method == 'GET') {
                return include('restfulEndPoints/getProject.php');
            } elseif($this->method == 'POST'){
                if($this->verb == '' && empty($this->args)){
                    return include('restfulEndpoints/postProject.php');
                }elseif($this->verb == 'request'){
                    return include('restfulEndpoints/postProjectRequest.php');
                }else{
                    return Array("Error" => "Endpoint may exists but the verb does not or Endpoint does not allow arguements");
                }
            }else {
                return Array("Error" => "Only accepts GET/POST requests");
            }
         }
     }
?>