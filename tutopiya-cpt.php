<?php

/*
Plugin Name:       Tutopiya Custom Post Type
Description:       Custom Post Type for Educational Articles/Blogs
Version:           1.0
Author:            Minhaz Irphan Mohamed
Author URI:        https://www.minhazimohamed.com
Requires at least: 5.2
Requires PHP:      7.2
*/

if (!defined('ABSPATH')) {
    exit;
}

function tutopiya_register_cpt()
{
    $labels = array(
        'name' => _x('Educational Articles', 'post type general name'),
        'singular_name' => _x('Educational Article', 'post type singular name'),
        'menu_name' => _x('Educational Articles', 'admin menu'),
        'name_admin_bar' => _x('Educational Article', 'add new on admin bar'),
        'add_new' => _x('Add New', 'book'),
        'add_new_item' => __('Add New Article'),
        'new_item' => __('New Article'),
        'edit_item' => __('Edit Article'),
        'view_item' => __('View Article'),
        'all_item' => __('All Articles'),
        'search_items' => __('Search Articles'),
        'parent_item_colon' => __('Parent Articles:'),
        'not_found' => __('No articles found.'),
        'not_found_in_trash' => __('No articles found in Trash.')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'educational-articles'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierachical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerp', 'comments'),
        'show_in_rest' => true
    );

    register_post_type('educational_articles', $args);
}

add_action('init', 'tutopiya_register_cpt');
