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
                                    <span class="byline"><i class="fas fa-user"></i> By <?php echo esc_html(get_post_meta(get_the_ID(), 'author_name', true)); ?></span>
                                    <span class="posted-on"><i class="fas fa-calendar-alt"></i> <?php echo esc_html(get_post_meta(get_the_ID(), 'publication_date', true)); ?></span>
                                    <span class="cat-links">
                                        <i class="fas fa-folder"></i>
                                        <?php $categories = get_terms(array(
                                            'taxonomy' => 'subject_category',
                                            'hide_empty' => false,
                                        ));
                                        foreach ($categories as $category) :
                                            var_dump($category);
                                            echo esc_html($category->name . ', ');
                                        endforeach;
                                        ?></span>
                                </div>

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