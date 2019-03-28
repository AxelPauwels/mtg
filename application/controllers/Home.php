<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index()
    {
        $data['user'] = $this->authex->getUserInfo();
        $data['title'] = 'textTitle';
        $data['header'] = 'textHeader';
        $data['footer'] = 'Developed by Axel Pauwels';
        $this->load->model('user_model');
        $data['users'] = $this->user_model->getUsers();
        $this->load->model('cards_model');
        $data['colors'] = $this->cards_model->getAll("colors");
        $data['rarities'] = $this->cards_model->getAll("rarities");
        $data['sets'] = $this->cards_model->getAll("sets");
        $data['formats'] = $this->cards_model->getAll("formats");
        $data['supertypes'] = $this->cards_model->getAll("supertypes");
        $data['types'] = $this->cards_model->getAll("types");
        $data['subtypes'] = $this->cards_model->getAll("subtypes");
        $data['legalities'] = $this->cards_model->getAll("legalities");

        $partials = array('myHeader' => 'main_header', 'myContent' => 'main_menu', 'myFooter' => 'main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function login()
    {
        $user = strtolower($this->input->post('user'));
        $password = sha1($this->input->post('password'));
        if ($this->authex->login($user, $password)) {
            redirect('home/index');
        }
        redirect('home/index');
    }

    public function logout()
    {
        $this->authex->logout();
        redirect('home/index');
    }

    public function addCards()
    {
        $data['user'] = $this->authex->getUserInfo();
        $data['title'] = 'textTitle';
        $data['header'] = 'textHeader';
        $data['footer'] = 'Developed by Axel Pauwels';
        $this->load->model('cards_model');
        $data['lockedFormSettings'] = $this->getLockedSelectionsFromSession();

        $partials = array('myHeader' => 'main_header', 'myContent' => 'cards_addCards', 'myFooter' => 'main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function getLockedSelectionsFromSession()
    {
        $lockedFormSettings = new stdClass();

        // isFoil
        if (!$this->session->flashdata('addCards_lockedFormSettings_isFoil_visibleCheckboxCheckedValue')) {
            $this->session->set_flashdata('addCards_lockedFormSettings_isFoil_visibleCheckboxCheckedValue', false);
        }
        if (!$this->session->flashdata('addCards_lockedFormSettings_isFoil_checkboxCheckedValue')) {
            $this->session->set_flashdata('addCards_lockedFormSettings_isFoil_checkboxCheckedValue', false);
        }
        if (!$this->session->flashdata('addCards_lockedFormSettings_isFoil_iconClassForColor')) {
            $this->session->set_flashdata('addCards_lockedFormSettings_isFoil_iconClassForColor', "");
        }
        $lockedFormSettings->isFoil_visibleCheckboxCheckedValue =
            $this->session->flashdata('addCards_lockedFormSettings_isFoil_visibleCheckboxCheckedValue');
        $lockedFormSettings->isFoil_checkboxCheckedValue =
            $this->session->flashdata('addCards_lockedFormSettings_isFoil_checkboxCheckedValue');
        $lockedFormSettings->isFoil_iconClassForColor =
            $this->session->flashdata('addCards_lockedFormSettings_isFoil_iconClassForColor');

        // isInDeck
        if (!$this->session->flashdata('addCards_lockedFormSettings_isInDeck_visibleCheckboxCheckedValue')) {
            $this->session->set_flashdata('addCards_lockedFormSettings_isInDeck_visibleCheckboxCheckedValue', false);
        }
        if (!$this->session->flashdata('addCards_lockedFormSettings_isInDeck_checkboxCheckedValue')) {
            $this->session->set_flashdata('addCards_lockedFormSettings_isInDeck_checkboxCheckedValue', false);
        }
        if (!$this->session->flashdata('addCards_lockedFormSettings_isInDeck_iconClassForColor')) {
            $this->session->set_flashdata('addCards_lockedFormSettings_isInDeck_iconClassForColor', "");
        }
        $lockedFormSettings->isInDeck_visibleCheckboxCheckedValue =
            $this->session->flashdata('addCards_lockedFormSettings_isInDeck_visibleCheckboxCheckedValue');
        $lockedFormSettings->isInDeck_checkboxCheckedValue =
            $this->session->flashdata('addCards_lockedFormSettings_isInDeck_checkboxCheckedValue');
        $lockedFormSettings->isInDeck_iconClassForColor =
            $this->session->flashdata('addCards_lockedFormSettings_isInDeck_iconClassForColor');

        return $lockedFormSettings;
    }
}
