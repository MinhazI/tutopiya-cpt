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

register_activation_hook(__FILE__, 'tutopiya_activate_plugin');
register_deactivation_hook(__FILE__, 'tutopiya_deactivate_plugin');

function tutopiya_activate_plugin()
{
    tutopiya_register_cpt();
    flush_rewrite_rules();
}

function tutopiya_deactivate_plugin()
{
    flush_rewrite_rules();
}

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
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
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

function tutopiya_add_custom_meta_boxes()
{
    add_meta_box(
        'author_name_meta_box',
        'Author Name',
        'tutopiya_display_author_name_meta_box',
        'educational_article',
        'side',
        'high'
    );

    add_meta_box(
        'publication_date_meta_box',
        'Publication Date',
        'tutopiya_display_publication_date_meta_box',
        'educational_article',
        'side',
        'high'
    );

    add_meta_box(
        'subject_category_meta_box',
        'Subject Category',
        'tutopiya_display_subject_category_meta_box',
        'educational_article',
        'side',
        'high'
    );
}

add_action('add_meta_boxes', 'tutopiya_add_custom_meta_boxes');

function tutopiya_display_author_name_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'tutopiya_nonce');
    $author_name = get_post_meta($post->ID, 'author_name', true);
    echo '<input type="text" name="author_name" value="' . esc_attr($author_name) . '" class="widefat"/>';
}

function tutopiya_display_publication_date_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'tutopiya_nonce');
    $publication_date = get_post_meta($post->ID, 'publication_date', true);
    echo '<input type="date" name="publication_date" value="' . esc_attr($publication_date) . '" class="widefat"/>';
}

function tutopiya_display_subject_category_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'tutopiya_nonce');
    $subject_categories = get_post_meta($post->ID, 'subject_category', true) ?: array();
    $categories = get_terms(array(
        'taxonomy' => 'subject_category',
        'hide_empty' => false,
    ));

?>
    <ul class="categorychecklist form-no-clear">
        <?php foreach ($categories as $category) : ?>
            <li>
                <label class="selectit">
                    <input type="checkbox" name="subject_category[]" value="<?php echo esc_attr($category->term_id); ?>" <?php checked(in_array($category->term_id, $subject_categories)); ?>>
                    <?php echo esc_html($category->name); ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
    <p class="add-new-subject">
        <a href="#" class="link"><?php _e('Add New Subject'); ?></a>
    </p>
    <div class="new-subject-form" style="display:none;">
        <p>
            <label for="new-subject-title"><?php _e('Subject Title:'); ?></label><br>
            <input type="text" id="new-subject-title" name="new_subject_title">
        </p>
        <p>
            <label for="new-subject-parent"><?php _e('Parent Subject:'); ?></label><br>
            <select id="new-subject-parent" name="new_subject_parent">
                <option value=""><?php _e('Parent Subject'); ?></option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <button id="add-new-subject" class="button-primary"><?php _e('Add New Subject'); ?></button>
        </p>
    </div>

    <script>
        jQuery(document).ready(function($) {
            $('.add-new-subject a').click(function(e) {
                e.preventDefault();
                $('.new-subject-form').slideToggle();
            });

            $('#add-new-subject').click(function(e) {
                e.preventDefault();

                var title = $('#new-subject-title').val();
                var parent = $('#new-subject-parent').val();

                if (title === '') {
                    alert('<?php _e('Please enter a subject title.'); ?>');
                    return;
                }

                $.ajax({
                    method: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'tutopiya_add_new_subject',
                        title: title,
                        parent: parent,
                        nonce: '<?php echo wp_create_nonce('tutopiya_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.data);
                        }
                    }
                });
            });
        });
    </script>
<?php
}

function tutopiya_save_meta_boxes($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['tutopiya_nonce']) || !wp_verify_nonce($_POST['tutopiya_nonce'], basename(__FILE__))) return;

    if (isset($_POST['author_name'])) {
        update_post_meta($post_id, 'author_name', sanitize_text_field($_POST['author_name']));
    }
    if (isset($_POST['publication_date'])) {
        update_post_meta($post_id, 'publication_date', sanitize_text_field($_POST['publication_date']));
    }
    if (isset($_POST['subject_category'])) {
        update_post_meta($post_id, 'subject_category', array_map('sanitize_text_field', $_POST['subject_category']));
    }
}

add_action('save_post', 'tutopiya_save_meta_boxes');

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

function tutopiya_enqueue_styles()
{
    wp_enqueue_style('tutopiya-style', plugin_dir_url(__FILE__) . '/styles/style.css');
}

add_action('wp_enqueue_scripts', 'tutopiya_enqueue_styles');

function enqueue_font_awesome()
{
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');
