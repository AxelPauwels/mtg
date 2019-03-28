<?php

    // +----------------------------------------------------------
    // | MY url helper
    // +----------------------------------------------------------


    function divAnchor($uri = '', $title = '', $attributes = '') 
    {
        return '<div>' . anchor($uri, $title, $attributes) . '</div>';
    }

    function smallDivAnchor($uri = '', $title = '', $attributes = '') 
    {
        return '<div style="margin-top: 4px">' . anchor($uri, $title, $attributes) . '</div>';
    }
?>
