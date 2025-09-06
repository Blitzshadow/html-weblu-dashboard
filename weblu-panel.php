<?php
/*
Plugin Name: Weblu Panel
Description: Wyświetla dashboard klienta na stronie /panel tylko dla zalogowanych użytkowników.
Version: 1.0
Author: Blitzshadow
*/

// Shortcode do panelu
add_shortcode('weblu_panel', 'weblu_panel_shortcode');
function weblu_panel_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Musisz być zalogowany, aby zobaczyć panel.</p>';
    }
    ob_start();
    // Wczytaj główny plik HTML dashboardu
    include dirname(__FILE__) . '/index.html';
    return ob_get_clean();
}

// Rejestracja i ładowanie zasobów
add_action('wp_enqueue_scripts', 'weblu_panel_enqueue_assets');
function weblu_panel_enqueue_assets() {
    if (get_query_var('weblu_panel')) {
    // CSS główny
    wp_enqueue_style('weblu-panel-style', plugin_dir_url(__FILE__) . '../css/style.css');
    wp_enqueue_style('weblu-panel-bootstrap', plugin_dir_url(__FILE__) . '../css/plugins/bootstrap.min.css');
    wp_enqueue_style('weblu-panel-datepicker', plugin_dir_url(__FILE__) . '../css/plugins/bootstrap-datepicker.css');
    // Dodaj inne style pluginów według potrzeb

    // JS główny
    wp_enqueue_script('weblu-panel-jquery', plugin_dir_url(__FILE__) . '../js/jquery-3.3.1.min.js', array(), null, true);
    wp_enqueue_script('weblu-panel-plugins', plugin_dir_url(__FILE__) . '../js/plugins-jquery.js', array('weblu-panel-jquery'), null, true);
    wp_enqueue_script('weblu-panel-chart', plugin_dir_url(__FILE__) . '../js/chart-init.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-calendar', plugin_dir_url(__FILE__) . '../js/calendar.init.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-sparkline', plugin_dir_url(__FILE__) . '../js/sparkline.init.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-morris', plugin_dir_url(__FILE__) . '../js/morris.init.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-datepicker-js', plugin_dir_url(__FILE__) . '../js/datepicker.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-sweetalert2', plugin_dir_url(__FILE__) . '../js/sweetalert2.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-toastr', plugin_dir_url(__FILE__) . '../js/toastr.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-validation', plugin_dir_url(__FILE__) . '../js/validation.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-lobilist', plugin_dir_url(__FILE__) . '../js/lobilist.js', array('weblu-panel-plugins'), null, true);
    wp_enqueue_script('weblu-panel-custom', plugin_dir_url(__FILE__) . '../js/custom.js', array('weblu-panel-plugins'), null, true);
    // Dodaj inne skrypty pluginów według potrzeb
    }
}

// Dodanie strony /panel
add_action('init', function() {
    add_rewrite_rule('^panel/?$', 'index.php?weblu_panel=1', 'top');
});
add_filter('query_vars', function($vars) {
    $vars[] = 'weblu_panel';
    return $vars;
});
add_action('template_redirect', function() {
    if (get_query_var('weblu_panel')) {
        echo do_shortcode('[weblu_panel]');
        exit;
    }
});
