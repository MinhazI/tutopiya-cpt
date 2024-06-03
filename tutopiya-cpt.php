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

function tutopiya_add_custom_meta_boxes()
{
    add_meta_box(
        'author_name_meta_box',
        'Author Name',
        'tutopiya_display_name_meta_box',
        'educational_article',
        'normal',
        'high'
    );

    add_meta_box(
        'publication_date_meta_box',
        'Publication Date',
        'tutopiya_display_publication_date_meta_box',
        'educational_article',
        'normal',
        'high'
    );

    add_meta_box(
        'subject_category_meta_box',
        'Subject Category',
        'tutopiya_display_subject_category_meta_box',
        'educational_article',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'tutopiya_add_custom_meta_boxes');

function tutopiya_display_author_name_meta_box($post)
{
    $author_name = get_post_meta($post->ID, 'author_name', true);
    echo '<input type="text" name="author_name" value="' . esc_attr($author_name) . '" class="widefat"/>';
}

function tutopiya_display_publication_date_meta_box($post)
{
    $publication_date = get_post_meta($post->ID, 'publication_date', true);
    echo '<input type="date" name="publication_date" value="' . esc_attr($publication_date) . '" class="widefat"/>';
}

function tutopiya_display_subject_category_meta_box($post)
{
    $subject_category = get_post_meta($post->ID, 'subject_category', true);
    $categories = array('Math', 'Science', 'History', 'English');
    echo '<select name="subject_category" class="widefat">';
    foreach ($categories as $category) {
        echo '<option value="' . esc_attr($category) . '""' . selected($subject_category, $category, false) . '>' . esc_html($category) . '</option>';
    }
    echo "</select>";
}

function tutopiya_save_meta_boxes($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['author_name']) || !isset($_POST['publication_date']) || !isset($_POST['subject_category'])) return;

    update_post_meta($post_id, 'author_name', sanitize_text_field($_POST['author_name']));
    update_post_meta($post_id, 'publication_date', sanitize_text_field($_POST['publication_date']));
    update_post_meta($post_id, 'subject_category', sanitize_text_field($_POST['subject_category']));
}

add_action('save_post', 'tutopiya_save_meta_boxes');
