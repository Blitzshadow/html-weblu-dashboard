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
    if (is_page('panel')) {
        wp_enqueue_style('weblu-panel-style', plugin_dir_url(__FILE__) . 'css/style.css');
        wp_enqueue_script('weblu-panel-js', plugin_dir_url(__FILE__) . 'js/custom.js', array('jquery'), null, true);
        // Dodaj kolejne zasoby według potrzeb
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
