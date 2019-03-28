<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get($id) {
        // geef user-object met opgegeven $id   
        $this->db->where('id', $id);
        $query = $this->db->get('user');
        return $query->row();
    }

    function getUserName_byId($id) {
        $this->db->select('name');
        $this->db->where('id', $id);
        $query = $this->db->get('user');
        return $query->row()->name;
    }

    function getUser($user, $password) {
        // geef user-object met $email en $password EN geactiveerd = 1
        $this->db->where('name', $user);
        $this->db->where('password', $password);
        $query = $this->db->get('user');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        else {
            return null;
        }
    }

    function getUsers() {
        $this->db->select('id, name');
        $query = $this->db->get('user');
        return $query->result();
    }

}

?>