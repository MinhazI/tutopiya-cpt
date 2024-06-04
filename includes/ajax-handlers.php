<?php

function tutopiya_add_new_subject_callback()
{
    check_ajax_referer('tutopiya_nonce', 'nonce');

    if (!current_user_can('manage_categories')) {
        wp_send_json_error(__('You do not have permission to add categories.'));
    }

    $title = sanitize_text_field($_POST['title']);
    $parent = isset($_POST['parent']) ? (int)$_POST['parent'] : 0;

    if (term_exists($title, 'subject_category')) {
        wp_send_json_error(__('Subject category already exists.'));
    }

    $result = wp_insert_term($title, 'subject_category', array('parent' => $parent));

    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    }

    wp_send_json_success();
}

add_action('wp_ajax_tutopiya_add_new_subject', 'tutopiya_add_new_subject_callback');
