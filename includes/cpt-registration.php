<?php

function tutopiya_register_cpt()
{
    $labels = array(
        'name'               => _x('Educational Articles', 'post type general name'),
        'singular_name'      => _x('Educational Article', 'post type singular name'),
        'menu_name'          => _x('Educational Articles', 'admin menu'),
        'name_admin_bar'     => _x('Educational Article', 'add new on admin bar'),
        'add_new'            => _x('Add New', 'book'),
        'add_new_item'       => __('Add New Article'),
        'new_item'           => __('New Article'),
        'edit_item'          => __('Edit Article'),
        'view_item'          => __('View Article'),
        'all_items'          => __('All Articles'),
        'search_items'       => __('Search Articles'),
        'parent_item_colon'  => __('Parent Articles:'),
        'not_found'          => __('No articles found.'),
        'not_found_in_trash' => __('No articles found in Trash.')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'educational-article'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail'),
        'show_in_rest'       => true
    );

    register_post_type('educational_article', $args);
}

add_action('init', 'tutopiya_register_cpt');

function tutopiya_register_subject_taxonomy()
{
    $labels = array(
        'name' => _x('Subject Categories', 'taxonomy general name'),
        'singular_name' => _x('Subject Category', 'taxonomy singular name'),
        'search_items' => __('Search Subject Categories'),
        'all_items' => __('All Subject Categories'),
        'parent_item' => __('Parent Subject Category'),
        'parent_item_colon' => __('Parent Subject Category:'),
        'edit_item' => __('Edit Subject Category'),
        'update_item' => __('Update Subject Category'),
        'add_new_item' => __('Add New Subject Category'),
        'new_item_name' => __('New Subject Category Name'),
        'menu_name' => __('Subject Categories'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'subject-category'),
    );

    register_taxonomy('subject_category', array('educational_article'), $args);
}

add_action('init', 'tutopiya_register_subject_taxonomy');
