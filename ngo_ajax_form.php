<?php

/**
 * Plugin Name: NGO AJAX Form
 * Description: Simple Ajax Form
 * Author: Amos Ngoah
 * Author URI: https://github.com/ngoahamos/ngo_ajax_form
 * Version: 1.0.0
 */

 function ngo_ajax_form() {
    $content = '';
    $content .= "<div class='row'>";
        $content .= "<div class='col-md-12' id='message-box'>";
            
        $content .= "</div>";
        $content .= "<div class='col-md-12'>";
            $content .= "<div class='col-md-12'>";
                $content .= "<div class='form-group'>";
                    $content .= "<label for='email'>Email<span class='text-danger' >*</span></label>";
                    $content .= "<input type='email' class='form-control' id='email' name='email' />";
                $content .= "</div>";
            $content .= "</div>";
        $content .= "</div>";

        $content .= "<div class='col-md-12'>";
            $content .= "<div class='form-group'>";
                $content .= "<button type='button' class='btn btn-primary' id='submit-btn' >Search</button>";
            $content .= "</div>";
        $content .= "</div>";

    $content .= "</div>";

    return $content;
 }

 add_shortcode('ngo_ajax_form', 'ngo_ajax_form');

 function ngo_ajax_form_add_scripts() {
    ?>
       <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="/wp-content/plugins/ngo_ajax_form/js/ngo_ajax_form.js" ></script>



    <?php
 }

 function ngo_ajax_form_add_style() {

   wp_enqueue_style('ngo_ajax_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
 }

 add_action('wp_enqueue_scripts', 'ngo_ajax_form_add_style');
 add_action('wp_footer', 'ngo_ajax_form_add_scripts');

?>