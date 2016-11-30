<?php

/**
 * Demo model
 *
 * @author Vince Urag
 */
class Model extends SR_Model {


    public function __construct() {
        parent::__construct();
    }

    public function getUsers() {
        // return $this->db->get_row("users", array("id"=>1, "user_name"=>"V"));
        // return $this->db->get_value("users", "password", array("id"=>1,"user_name"=>"V"));
        // return $this->db->has_row("users", array("id"=>1, "user_name"=>"V"));\
        // return $this->db->insert_row("users", array("user_name"=>"from insert", "password"=>"testing"));
        return $this->db->exec("SELECT * FROM users");
    }

    public function editUser($name, $password, $id) {
        return $this->db->update_record("users", array("user_name" => $name, "password" => $password), "id=".$id);
    }
}
