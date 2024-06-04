<?php
get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="tutopiya-educational-single-article">
            <div class="content-area">
                <main id="main" class="site-main" role="main">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <div class="entry-header-inner">
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                                <div class="entry-meta">
                                    <span class="byline">
                                        <i class="fas fa-user"></i>
                                        <?php
                                        $author_name = get_post_meta(get_the_ID(), 'author_name', true);
                                        $author_posts_link = get_author_posts_url(get_the_author_meta('ID'));
                                        if ($author_posts_link) :
                                        ?>
                                            <a href="<?php echo esc_url($author_posts_link); ?>" class="meta-links"><?php echo esc_html($author_name); ?></a>
                                        <?php else : ?>
                                            <?php echo esc_html($author_name); ?>
                                        <?php endif; ?>
                                    </span>

                                    <span class="posted-on">
                                        <i class="fas fa-calendar-alt"></i>
                                        <a href="<?php echo esc_url(get_permalink()); ?>" class="meta-links"><?php echo esc_html(get_post_meta(get_the_ID(), 'publication_date', true)); ?></a>
                                    </span>

                                    <span class="cat-links">
                                        <i class="fas fa-folder"></i>
                                        <?php
                                        $categories = get_the_terms(get_the_ID(), 'subject_category');

                                        if ($categories && !is_wp_error($categories)) {
                                            $categories_count = count($categories);
                                            $last_index = $categories_count - 1;

                                            foreach ($categories as $index => $category) { ?>
                                                <span><a href="<?php echo esc_url(get_term_link($category)); ?>" class="meta-links"><?php echo esc_html($category->name); ?></a><?php
                                                                                                                                                                                if ($index < $last_index) {
                                                                                                                                                                                    echo ","; // Comma without leading space
                                                                                                                                                                                }
                                                                                                                                                                                ?></span><?php
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                            ?>
                                    </span>


                                    <?php if (has_post_thumbnail()) : ?>
                                        <figure class="post-thumbnail">
                                            <?php the_post_thumbnail(); ?>
                                        </figure>
                                    <?php endif; ?>
                                </div>
                        </header>

                        <div class="post-inner">
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </article>
                </main>
                <?php get_sidebar(); ?>
            </div>
        </div>
<?php endwhile;
endif;

get_footer();
?>