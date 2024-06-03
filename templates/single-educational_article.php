<?php
get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail('large');
                } ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <span class="author">By <?php echo esc_html(get_post_meta(get_the_ID(), 'author_name', true)); ?></span>
                    <span class="date"><?php echo esc_html(get_post_meta(get_the_ID(), 'publication_date', true)); ?></span>
                    <span class="category"><?php echo esc_html(get_post_meta(get_the_ID(), 'subject_category', true)); ?></span>
                </div>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <footer class="entry-footer">
                <?php comments_template(); ?>
            </footer>
        </article>
<?php endwhile;
endif;

get_footer();
?>