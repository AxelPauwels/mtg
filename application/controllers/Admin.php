<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }

    public function index()
    {
        $data['user'] = $this->authex->getUserInfo();
        $data['title'] = 'textTitle';
        $data['header'] = 'textHeader';
        $data['footer'] = 'Developed by Axel Pauwels';
        $this->load->model('user_model');
        $data['users'] = $this->user_model->getUsers();

        $partials = array('myHeader' => 'main_header', 'myContent' => 'settings', 'myFooter' => 'main_footer');
        $this->template->load('main_master', $partials, $data);
    }

    public function insertDataFromApi($soort)
    {
        $this->load->model('admin_model');

        switch ($soort) {
            case "rarities":
                $this->admin_model->insertData("rarities", array(
                        "Mythic Rare",
                        "Rare",
                        "Uncommon",
                        "Common",
                        "Special"
                    ));
                break;
            case "colors":
                $this->admin_model->insertData("colors", array("White", "Blue", "Black", "Red", "Green"));
                break;
            case "legalities":
                $this->admin_model->insertData("legalities", array("Legal", "Banned", "Restricted"));
                break;
            default:
                $apiRequestUrl = "https://api.magicthegathering.io/v1/" . $soort;

                // api request
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $apiRequestUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                // end api request

                if (json_decode($response, true)) {
                    $response = json_decode($response, true); //because of true, it's in an array
                    // return only the id

                    $this->admin_model->insertData($soort, $response[$soort]);
                }
        }
        redirect('admin/index');
    }
}
