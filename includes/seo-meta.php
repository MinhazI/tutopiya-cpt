<?php

function add_seo_meta_tags()
{
    if (is_singular('educational_article')) {
        global $post;
        $excerpt = get_the_excerpt($post->ID);
        $title = get_the_title($post->ID);
        $url = get_permalink($post->ID);
        $thumbnail = get_the_post_thumbnail_url($post->ID, 'full');

        echo '<meta name="description" content="' . esc_attr($excerpt) . '" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url($thumbnail) . '" />' . "\n";
    }
}

add_action('wp_head', 'add_seo_meta_tags');
