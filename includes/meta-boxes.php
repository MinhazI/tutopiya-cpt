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

    $categories = get_terms(array(
        'taxonomy' => 'subject_category',
        'hide_empty' => false,
    ));

    $post_terms = wp_get_post_terms($post->ID, 'subject_category', array('fields' => 'ids'));

?>

    <ul class="categorychecklist form-no-clear">
        <?php foreach ($categories as $category) : ?>
            <li>
                <label class="selectit">
                    <input type="checkbox" name="subject_category[]" value="<?php echo esc_attr($category->term_id); ?>" <?php checked(in_array($category->term_id, $post_terms)); ?>>
                    <?php echo esc_html($category->name); ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
    <span class="spinner"></span>
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
            <select id="new-subject-parent" name="new_subject-parent">
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
                $(".spinner").addClass("is-active");
                $("#add-new-subject").addClass('disabled');
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
                            console.log("AJAX response", response.data);
                            // Append new category to the list (without refresh)
                            var newCategoryItem = '<li><label class="selectit"><input type="checkbox" name="subject_category[]" value="' + response.data.term_id + '" checked> ' + response.data.name + '</label></li>';
                            $('.categorychecklist').append(newCategoryItem);

                            // Clear form fields and hide the form
                            $('#new-subject-title').val('');
                            $('#new-subject-parent').val('');
                            $('.new-subject-form').slideUp();

                        } else {
                            alert(response.data);
                        }
                        $(".spinner").removeClass("is-active");
                        $("#add-new-subject").removeClass('disabled');
                    }
                });
            });
        });
    </script>
<?php
}


add_action('save_post', 'tutopiya_save_subject_category_meta');

function tutopiya_save_subject_category_meta($post_id)
{
    if (!isset($_POST['tutopiya_nonce']) || !wp_verify_nonce($_POST['tutopiya_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    if (isset($_POST['author_name'])) {
        update_post_meta($post_id, 'author_name', sanitize_text_field($_POST['author_name']));
    }
    if (isset($_POST['publication_date'])) {
        update_post_meta($post_id, 'publication_date', sanitize_text_field($_POST['publication_date']));
    }

    if (isset($_POST['subject_category'])) {
        $subject_categories = array_map('intval', $_POST['subject_category']);
        wp_set_object_terms($post_id, $subject_categories, 'subject_category');
    } else {
        wp_set_object_terms($post_id, array(), 'subject_category'); // Remove all terms if none selected
    }
}
