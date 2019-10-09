<?php

function pretty_print($data)
{
    echo '<pre>';
        print_r($data);
    echo '</pre>';
}

function moneyStr($num) {
    $money_formatted_string =  preg_replace("/\B(?=(\d{3})+(?!\d))/", ",", $num);
    return $money_formatted_string;
}

function moneyStrDot($num) {
    $money_formatted_string =  preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $num);
    return $money_formatted_string;
}

function activate_page_css($page_url) {
    
    $ci = get_instance();

    $ci->db->select('plugin_assets.src');
    $ci->db->from('page_plugin');
    $ci->db->join('plugin_assets', 'plugin_assets.plugin_id = page_plugin.plugin_id');
    $ci->db->join('page', 'page.page_id = page_plugin.page_id');
    $ci->db->where('page.url', $page_url);
    $ci->db->where('plugin_assets.type', 'css');

    $active_css_query = $ci->db->get();
    return $active_css_query->result_array();

}

function activate_page_js($page_url) {
    
    $ci = get_instance();

    $ci->db->select('plugin_assets.src');
    $ci->db->from('page_plugin');
    $ci->db->join('plugin_assets', 'plugin_assets.plugin_id = page_plugin.plugin_id');
    $ci->db->join('page', 'page.page_id = page_plugin.page_id');
    $ci->db->where('page.url', $page_url);
    $ci->db->where('plugin_assets.type', 'js');

    $active_css_query = $ci->db->get();
    return $active_css_query->result_array();

}

// vendors/jquery/jquery.min.js
// vendors/popper.js/dist/umd/popper.min.js
// vendors/bootstrap/js/bootstrap.bundle.min.js
// vendors/jquery-easing/jquery.easing.min.js
// vendors/sb-admin-2/js/sb-admin-2.min.js
