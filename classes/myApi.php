<?php 
    class MyAPI extends API
    {
    
        public function __construct($request, $origin) {
            parent::__construct($request);
        }
        /**
         * Example of an Endpoint
         */
         protected function project($args) {
            if ($this->method == 'GET') {
                //echo "Project ";
                return include('restEndpoints/getProject.php');
            } else {
                return "Only accepts GET requests";
            }
         }
     }
?>