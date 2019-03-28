<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// +----------------------------------------------------------
// | Custom Helper
// +----------------------------------------------------------


function p($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function d($array) {
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
}

function e($array) {
    highlight_string("<?php\n" . var_export($array, true) . ";\n?>");
}