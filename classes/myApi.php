<?php 
    class MyAPI extends API
    {
    
        public function __construct($request, $origin) {
            parent::__construct($request);
        }

         protected function project($args) {
            if ($this->method == 'GET') {
                return include('restEndpoints/getProject.php');
            } else {
                return Array("Error" => "Only accepts GET requests");
            }
         }
     }
?>