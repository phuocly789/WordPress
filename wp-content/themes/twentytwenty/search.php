<?php

/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
$has_sidebar_13 = is_active_sidebar('sidebar-13');
$has_sidebar_14 = is_active_sidebar('sidebar-14');
$has_sidebar_15 = is_active_sidebar('sidebar-15');
$has_sidebar_9 = is_active_sidebar('sidebar-9');
$has_sidebar_10 = is_active_sidebar('sidebar-10');

?>

<main id="site-content" class="site-main">
    <?php
    $archive_title    = '';
    $archive_subtitle = '';

    if (is_search()) {
        global $wp_query;

        $archive_title = sprintf(
            '%1$s %2$s',
            '<span class="color-accent">' . __('Search:', 'twentytwenty') . '</span>',
            '&ldquo;' . get_search_query() . '&rdquo;'
        );

        if ($wp_query->found_posts) {
            $archive_subtitle = sprintf(
                _n(
                    'We found %s result for your search.',
                    'We found %s results for your search.',
                    $wp_query->found_posts,
                    'twentytwenty'
                ),
                number_format_i18n($wp_query->found_posts)
            );
        } else {
            $archive_subtitle = __('We could not find any results for your search. You can give it another try through the search form below.', 'twentytwenty');
        }
    }

    if ($archive_title || $archive_subtitle) {
    ?>
        <header class="archive-header has-text-align-center header-footer-group">
            <div class="archive-header-inner section-inner medium">
                <?php if ($archive_title) { ?>
                    <h1 class="archive-title"><?php echo wp_kses_post($archive_title); ?></h1>
                <?php } ?>
                <?php if ($archive_subtitle) { ?>
                    <div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post(wpautop($archive_subtitle)); ?></div>
                <?php } ?>
            </div>
        </header>
    <?php
    }
    ?>
    <!-- 3 cột -->
    <div class="search-container">
        <!-- Search Form -->

        <div class="search-results-container">
            <aside class="sidebar-left-search">
                <?php if (is_active_sidebar('sidebar-13')) : ?>
                    <?php dynamic_sidebar('sidebar-13'); ?>
                <?php endif; ?>
            </aside>

            <div class="main-content">
                <?php if (have_posts()) : ?>
                    <div class="post-list">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                                <!-- Ảnh thumbnail -->
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('medium');
                                        } else {
                                            echo '<img src="https://via.placeholder.com/280x180?text=No+Image" alt="No image">';
                                        }
                                        ?>
                                    </a>
                                </div>

                                <!-- Cột ngày -->
                                <div class="post-date">
                                    <span class="day"><?php echo get_the_date('d'); ?></span>
                                    <span class="month"><?php echo strtoupper(get_the_date('M')); ?></span>
                                    <span class="year"><?php echo get_the_date('Y'); ?></span>
                                </div>

                                <!-- Nội dung -->
                                <div class="post-content">
                                    <div class="post-category"><?php the_category(', '); ?></div>
                                    <h2 class="post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="post-meta">
                                        <span class="author">Bởi <?php the_author(); ?></span> |
                                        <span class="comments"><?php comments_number('0 bình luận', '1 bình luận', '% bình luận'); ?></span>
                                    </div>
                                    <div class="post-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                        <!-- Pagination -->
                        <div class="pagination">
                            <?php the_posts_pagination(array(
                                'mid_size' => 2,
                                'prev_text' => __('« Trước'),
                                'next_text' => __('Sau »'),
                            )); ?>
                        </div>
                    </div>
                <?php else : ?>
                    <!-- <p>Không có bài viết nào.</p> -->
                <?php endif; ?>
            </div>

            <div class="sidebar-right-search">
                <?php if (is_active_sidebar('sidebar-14')) : ?>
                    <?php dynamic_sidebar('sidebar-14'); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h4 class="fs-2">Latest News</h4>
                    <ul class="timeline">
                        <?php
                        $latest_posts = new WP_Query(array(
                            'posts_per_page' => 3,
                            'post_status'    => 'publish',
                        ));

                        if ($latest_posts->have_posts()):
                            while ($latest_posts->have_posts()):
                                $latest_posts->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <a href="#" class="float-right"><?php echo get_the_date('j F, Y'); ?></a>
                                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                                </li>
                            <?php endwhile;
                            wp_reset_postdata();
                        else: ?>
                            <li>Không có bài viết mới.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_template_part('template-parts/footer-menus-widgets'); ?>

<?php
get_footer();
