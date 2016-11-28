<?php
if (!defined('SRKEY')){echo'This file can only be called via the main index.php file, and not directly';exit();}

/**
 * Demo
 *
 * @author Vince Urag
 */
class Test extends SR_Controller {

    public function __construct() {
        parent::__construct();
        $this->load_model("anothermodel");
    }

    public function get_index() {
        $response = $this->anothermodel->getDummyData();

        $this->sendResponse($response, HTTP_Status::HTTP_OK);
    }

    public function get_name($a){
        echo $a;
    }

    public function post_index() {
        echo "This is the post_index page";
    }

    public function get_nameage($name, $age) {
        echo "Name: {$name} \n Age: {$age}";
    }
}
