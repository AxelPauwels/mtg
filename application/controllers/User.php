<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('string');
        $this->load->library('email');
    }

//    public function login() {
//        $user = strtolower($this->input->get('user'));
//        $password = sha1($this->input->get('password'));
//        if ($this->authex->login($user, $password)) {
//            redirect('home/index');
//        }
//    }

//        $data['title'] = 'textTitleOK';
//        $data['nobox'] = true;      // geen extra rand rond hoofdmenu
//        $data['user'] = $this->authex->getUserInfo();
//        $data['header'] = 'textHeaderOK';
//        $data['footer'] = 'textFooterOK';

//        $partials = array('myHeader' => 'main_header', 'myContent' => 'main_menu','myFooter' => 'main_footer');
//        $this->template->load('main_master', $partials, $data);

    public function logout()
    {
        $this->authex->logout();
        redirect('home/index');
    }
}
