<?php
require "../../includes/init.inc.php";

class MyAPI extends API
{

    public function __construct($request, $origin) {
        parent::__construct($request);
    }

    /**
     * Example of an Endpoint
     */
     protected function project() {
        if ($this->method == 'GET') {
            return "Project ";
        } else {
            return "Only accepts GET requests";
        }
     }
 }
?>