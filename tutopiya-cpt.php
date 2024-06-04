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

define('TUTOPIYA_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'tutopiya_activate_plugin');
register_deactivation_hook(__FILE__, 'tutopiya_deactivate_plugin');

function tutopiya_activate_plugin()
{
    require_once TUTOPIYA_PLUGIN_DIR . 'includes/cpt-registration.php';
    tutopiya_register_cpt();
    tutopiya_register_subject_taxonomy();
    flush_rewrite_rules();
}

function tutopiya_deactivate_plugin()
{
    flush_rewrite_rules();
}

add_action('init', function () {
    require_once TUTOPIYA_PLUGIN_DIR . 'includes/cpt-registration.php';
    tutopiya_register_cpt();
    tutopiya_register_subject_taxonomy();
    require_once TUTOPIYA_PLUGIN_DIR . 'includes/meta-boxes.php';
    require_once TUTOPIYA_PLUGIN_DIR . 'includes/ajax-handlers.php';
    require_once TUTOPIYA_PLUGIN_DIR . 'includes/schema-meta.php';
    require_once TUTOPIYA_PLUGIN_DIR . 'includes/seo-meta.php';
});

function tutopiya_template_redirect($template)
{
    if (is_singular('educational_article')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/single-educational_article.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}

add_filter('template_include', 'tutopiya_template_redirect', 99);

function tutopiya_enqueue_styles()
{
    wp_enqueue_style('tutopiya-style', plugin_dir_url(__FILE__) . 'css/style.css', array(), '0.1.0');
}

add_action('wp_enqueue_scripts', 'tutopiya_enqueue_styles');

function tutopiya_enqueue_font_awesome()
{
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
}
add_action('wp_enqueue_scripts', 'tutopiya_enqueue_font_awesome');

function tutopiya_enqueue_admin_scripts()
{
    wp_enqueue_script('tutopiya-admin-script', plugin_dir_url(__FILE__) . 'js/admin-script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'tutopiya_enqueue_admin_scripts');

function tutopiya_enqueue_admin_styles()
{
    wp_enqueue_style('tutopiya-admin-style', plugin_dir_url(__FILE__) . 'css/admin-style.css', array(), '1.0');
}
add_action('admin_enqueue_scripts', 'tutopiya_enqueue_admin_styles');
