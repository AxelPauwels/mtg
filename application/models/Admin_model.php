<?php

class Admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insertData($soort, $array) {
        if ($soort == "sets") {
            foreach ($array as $item) {
                $set = new stdClass();
                $set->name = $item["name"];
                $set->code = $item["code"];
                $set->type = $item["type"];
                $set->border = $item["border"];
                $set->releaseDate = $item["releaseDate"];
                $this->db->insert($soort, $set);
            }
        }
        else {
            foreach ($array as $item) {
                $data = new stdClass();
                $data->name = $item;
                $this->db->insert($soort, $data);
            }
        }
    }

}

?>