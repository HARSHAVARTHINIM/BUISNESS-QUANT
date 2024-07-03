<?php

if (!defined('ABSPATH')) {
    exit; 
}

define('CSV_FILTER_DATATABLES_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CSV_FILTER_DATATABLES_PLUGIN_URL', plugin_dir_url(__FILE__));

function csv_filter_datatables_enqueue_scripts() {
    wp_enqueue_style('csv-filter-datatables-style', CSV_FILTER_DATATABLES_PLUGIN_URL . 'assets/css/styles.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js', array('jquery'), null, true);
    wp_enqueue_script('csv-filter-datatables-script', CSV_FILTER_DATATABLES_PLUGIN_URL . 'assets/js/scripts.js', array('jquery', 'datatables'), null, true);
}

add_action('wp_enqueue_scripts', 'csv_filter_datatables_enqueue_scripts');

require_once CSV_FILTER_DATATABLES_PLUGIN_DIR . 'includes/class-csv-filter-datatables.php';
require_once CSV_FILTER_DATATABLES_PLUGIN_DIR . 'includes/csv-data.php';

function csv_filter_datatables_shortcode() {
    ob_start();
    CSV_Filter_DataTables::render();
    return ob_get_clean();
}

add_shortcode('csv_filter_datatables', 'csv_filter_datatables_shortcode');
?>
