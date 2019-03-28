<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Authex {

    public function __construct() {
        $CI = &get_instance();
        $CI->load->model('user_model');
    }

    function loggedIn() {
        // gebruiker is aangemeld als sessievariabele user_id bestaat
        $CI = &get_instance();
        if ($CI->session->has_userdata('user_id')) {
            return true;
        }
        else {
            return false;
        }
    }

    function getUserInfo() {
        // geef user-object als gebruiker aangemeld is
        $CI = &get_instance();
        if (!$this->loggedIn()) {
            return null;
        }
        else {
            $id = $CI->session->userdata('user_id');
            return $CI->user_model->get($id);
        }
    }

    function getUserId() {
        // geef user-id als gebruiker aangemeld is
        $CI = &get_instance();
        if (!$this->loggedIn()) {
            return null;
        }
        else {
            $userId = $CI->session->userdata('user_id');
            return $userId;
        }
    }

    function login($user, $password) {
        // gebruiker aanmelden met opgegeven user en wachtwoord
        $CI = &get_instance();
        $user = $CI->user_model->getUser($user, $password);
        if ($user == null) {
            return false;
        }
        else {
            $CI->session->set_userdata('user_id', $user->id);
            return true;
        }
    }

    function logout() {
        // uitloggen, dus sessievariabele wegdoen
        $CI = &get_instance();
        $CI->session->unset_userdata('user_id');
    }
    

}
