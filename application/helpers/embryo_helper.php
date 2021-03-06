<?php

function pretty_print($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function moneyStr($num)
{
    $money_formatted_string =  preg_replace("/\B(?=(\d{3})+(?!\d))/", ",", $num);
    return $money_formatted_string;
}

function moneyStrDot($num)
{
    $money_formatted_string =  preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $num);
    return $money_formatted_string;
}

function moneyBadge($num)
{
    $badge_color = 'default';

    switch (true) {
        case $num >= 1000000:
            $badge_color = 'danger';
            break;
        case $num >= 500000:
            $badge_color = 'warning';
            break;
        case $num >= 150000:
            $badge_color = 'info';
            break;
    }

    return $badge_color;
}

function deadlineBadge($status_id)
{
    $badge_color = 'default';

    switch (true) {
        case $status_id == 1:
            $badge_color = 'danger';
            break;
        case $status_id == 2 || $status_id == 3:
            $badge_color = 'warning';
            break;
        case $status_id == 5:
            $badge_color = 'secondary';
            break;
        case $status_id == 6:
            $badge_color = 'info';
            break;
        case $status_id == 8:
            $badge_color = 'primary';
            break;
    }

    return $badge_color;
}

function activate_page_css($page_url)
{

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

function activate_page_js($page_url)
{

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
