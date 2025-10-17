<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<main id="site-content" class="site-main">

	<div class="post-list">

		<?php if (have_posts()): ?>
			<?php while (have_posts()):
				the_post(); ?>

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


		<?php else: ?>
			<p>Không có bài viết nào.</p>
		<?php endif; ?>

	</div>

</main>


<?php get_template_part('template-parts/footer-menus-widgets'); ?>

<?php
get_footer();
