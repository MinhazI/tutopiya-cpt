<?php

function add_schema_markup()
{
    if (is_singular('educational_article')) {
        global $post;
        $schema = array(
            '@context' => "http://schema.org",
            '@type' => "Article",
            'mainEntityOfPage' => get_permalink($post->ID),
            'headline' => get_the_title($post->ID),
            'datePublished' => get_post_meta($post->ID, 'publication_date', true),
            'author' => array(
                '@type' => 'Person',
                'name' => get_post_meta($post->ID, 'author_name', true)
            ),
            'image' => get_the_post_thumbnail_url($post->ID, 'full')
        );
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}

add_action('wp_head', 'add_schema_markup');
