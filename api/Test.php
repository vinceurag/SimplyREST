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
        $this->load("anothermodel");
        $this->load("jwt");
        $this->load("mail");
    }

    public function get_index() {
        $response = $this->anothermodel->getDummyData();

        $this->sendResponse($response, HTTP_Status::HTTP_OK);
    }


    public function get_nameage($name, $age) {
        echo "Name: {$name} \n Age: {$age}\n";
        $email = $this->mail->sendMail(array("vinceuragvfx@gmail.com", "vince@urag.co"), array("Vince Urag" => "vinceurag@vince.com"), "Test Subject", "Hello! This is the test body.");
        if($email) {
            echo "sent";
        }

    }
}
