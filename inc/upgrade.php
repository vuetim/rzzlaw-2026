<?php
/** Cleanup for installations upgrading from the former optional case-study feature. */
if (!defined('ABSPATH')) exit;

function rzz_is_removed_case_menu_item($item) {
    $object = $item->object ?? get_post_meta($item->ID, '_menu_item_object', true);
    if ($object === 'case_study') return true;
    $url = $item->url ?? get_post_meta($item->ID, '_menu_item_url', true);
    if (!$url) return false;
    $host = wp_parse_url($url, PHP_URL_HOST);
    if ($host && strcasecmp($host, wp_parse_url(home_url('/'), PHP_URL_HOST)) !== 0) return false;
    $path = untrailingslashit(wp_parse_url($url, PHP_URL_PATH) ?: '');
    $removed = untrailingslashit(wp_parse_url(home_url('/case-studies/'), PHP_URL_PATH));
    return $path === $removed || str_starts_with($path, $removed.'/');
}

// Hide stale menu entries immediately, including cached/imported old menus.
add_filter('wp_get_nav_menu_items', function($items) {
    return array_values(array_filter($items, fn($item) => !rzz_is_removed_case_menu_item($item)));
});

// Theme replacement does not trigger after_switch_theme. Run once in the admin.
add_action('admin_init', function() {
    if (!current_user_can('manage_options') || get_stylesheet() !== 'rzzlaw') return;
    if (get_option('rzz_removed_cases_upgrade') === '1.1.0') return;
    $items = get_posts(['post_type'=>'nav_menu_item','post_status'=>'any','numberposts'=>-1]);
    foreach ($items as $item) {
        if (rzz_is_removed_case_menu_item($item)) wp_delete_post($item->ID, true);
        else {
            $object = get_post_meta($item->ID, '_menu_item_object', true);
            $object_id = (int)get_post_meta($item->ID, '_menu_item_object_id', true);
            $page = $object === 'page' ? get_post($object_id) : null;
            if ($page && $page->post_name === 'case-studies') wp_delete_post($item->ID, true);
        }
    }
    $page = get_page_by_path('case-studies');
    if ($page && $page->post_status !== 'trash') wp_trash_post($page->ID);
    flush_rewrite_rules();
    update_option('rzz_removed_cases_upgrade','1.1.0',false);
    wp_cache_flush();
    do_action('litespeed_purge_all');
});

add_action('admin_init',function(){
    if(!current_user_can('manage_options')||get_stylesheet()!=='rzzlaw'||get_option('rzz_contact_hours_upgrade')==='1.2.3')return;
    set_theme_mod('rzz_availability','Monday to Friday, 08:00 - 16:00');
    update_option('rzz_contact_hours_upgrade','1.2.3',false);
    wp_cache_flush();do_action('litespeed_purge_all');
});
