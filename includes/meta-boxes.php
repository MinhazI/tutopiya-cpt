<?php

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
    if (empty($author_name)) {
        $author_name = get_the_author_meta('display_name', $post->post_author);
    }

    echo '<input type="text" name="author_name" value="' . esc_attr($author_name) . '" class="tutopiya-meta-field"/>';
}

// function tutopiya_display_publication_date_meta_box($post)
// {
//     wp_nonce_field(basename(__FILE__), 'tutopiya_nonce');
//     $publication_date = get_post_meta($post->ID, 'publication_date', true);
//     if (empty($publication_date)) {
//         $publication_date = get_the_date('Y-m-d', $post);
//     }

//     echo '<input type="date" name="publication_date" value="' . esc_attr($publication_date) . '"/>';
// }

function tutopiya_display_publication_date_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'tutopiya_nonce');
    $publication_date = get_post_meta($post->ID, 'publication_date', true);

    if (empty($publication_date)) {
        $publication_date = get_the_time('Y-m-d', $post);
    }

    echo '<input type="date" name="publication_date" value="' . esc_attr($publication_date) . '" class="tutopiya-meta-field"/>';
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
                    <input required type="checkbox" name="subject_category[]" class="tutopiya-meta-field" value="<?php echo esc_attr($category->term_id); ?>" <?php checked(in_array($category->term_id, $subject_categories)); ?>>
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
            <input required type="text" id="new-subject-title" name="new_subject_title" class="tutopiya-meta-field">
        </p>
        <p>
            <label for="new-subject-parent"><?php _e('Parent Subject:'); ?></label><br>
            <select required id="new-subject-parent" name="new_subject_parent">
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
        $subject_category = array_map('intval', $_POST['subject_category']); // Ensure IDs are integers
        update_post_meta($post_id, 'subject_category', $subject_category);
    }
}

add_action('save_post', 'tutopiya_save_meta_boxes');
