<?php

class CSV_Data {
    public static function get_data() {
        $file = CSV_FILTER_DATATABLES_PLUGIN_DIR . 'data/Sample-Data-Screener.csv';
        $data = array_map('str_getcsv', file($file));
        return $data;
    }
}
