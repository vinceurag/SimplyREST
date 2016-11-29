<?php
if (!defined('SRKEY')){echo'This file can only be called via the main index.php file, and not directly';exit();}

/**
 * Demo
 *
 * @author Vince Urag
 */
class Users extends SR_Controller {

    public function __construct() {
        parent::__construct();
        $this->load("model");
        $this->load('jwt');
    }

    public function get_index() {
        $auth = json_decode($this->jwt->check(), true);
        $auth['authorization'] = "authorized";
        if($auth['authorization'] == "authorized") {
            $result = $this->model->getUsers();
            var_dump($result);
            // $this->sendResponse($result, HTTP_Status::HTTP_OK);
        } else {
            $this->sendResponse(array("error" => "unauthorized"), HTTP_Status::HTTP_UNAUTHORIZED);
        }

    }

    public function post_edit($id) {
        $myArray = $this->getJsonData();

        $newName = $myArray['name'];
        $newPassword = $myArray['password'];

        if($this->model->editUser($newName, $newPassword, $id)) {
            $this->sendResponse(array("success" => "row updated"), HTTP_Status::HTTP_OK);
        } else {
            $this->sendResponse(array("error" => "row NOT updated"), HTTP_Status::HTTP_NOT_FOUND);
        }

    }

    public function post_index() {
        $myArray = $this->getJsonData();

        $resArray = array("title" => "Hello",
                        "details" => array(
                            "choice 1" => "World",
                            "choice 2" => "Philippines"
                        )
                    );

        if($myArray['success'] == "yes") {
            $this->sendResponse($resArray, 200);
        } else {
            $this->sendResponse(array("title" => "error"), HTTP_Status::HTTP_NOT_FOUND);
        }
    }

    public function post_signin() {
        $myArray = $this->getJsonData();

        $newName = $myArray['name'];
        $newPassword = $myArray['password'];
        $user_id = 1;
        $token_payload = array("name" => $newName, "pass" => $newPassword);

        $token = $this->jwt->generate_token($user_id, $token_payload);

        $response = array(
            'name' => $newName,
            'password' => $newPassword,
            'meta' => [
                    'token' => $token
                ]
        );

        $this->sendResponse($response, HTTP_Status::HTTP_CREATED);
    }
}
