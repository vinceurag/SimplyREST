<?php

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

        $this->sendResponse($response, 200);
    }

    public function get_name($a){
        echo $a;
    }

    public function post_index() {
        echo "This is the post_index page";
    }
}
