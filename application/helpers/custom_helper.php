<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// +----------------------------------------------------------
// | Custom Helper
// +----------------------------------------------------------


function p($array){
    ?> <pre><?php print_r($array); ?></pre> <?php
}

function d($array){
    ?> <pre><?php var_dump($array); ?></pre> <?php
}