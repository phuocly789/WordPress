<?php
/**
 * Template for displaying single post content
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$class = '';
if (!is_single()) {
	$class = 'danh-sach';
}
$has_sidebar_9 = is_active_sidebar('sidebar-9'); // Categories
$has_sidebar_10 = is_active_sidebar('sidebar-10'); // Recent post
?>

<main id="site-content" class="site-main">

	<!-- BỐ CỤC 3 CỘT -->
	<div class="layout-wrapper">

		<!-- CỘT TRÁI: Categories -->
		<aside class="sidebar-left">
			<?php if (is_active_sidebar('sidebar-9')): ?>
				<div class="widget-categories">
					<?php dynamic_sidebar('sidebar-9'); ?>
				</div>
			<?php else: ?>
				<div class="widget-categories">
					<h2 class="widget-title">Categories</h2>
					<ul><?php wp_list_categories(['title_li' => '']); ?></ul>
				</div>
			<?php endif; ?>
		</aside>


		<!-- GIỮA: NỘI DUNG CHI TIẾT -->
		<article <?php post_class($class); ?> id="post-<?php the_ID(); ?>">

			<?php
			get_template_part('template-parts/entry-header');
			get_template_part('template-parts/featured-image');
			?>

			<div class="post-inner">
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>

			<!-- Prev - Next Post -->
			<div class="post-navigation-wrapper">
				<?php get_template_part('template-parts/navigation'); ?>
			</div>

			<!-- Comments -->
			<?php if ((comments_open() || get_comments_number()) && !post_password_required()): ?>
				<div class="comments-wrapper section-inner">
					<?php comments_template(); ?>
				</div>
			<?php endif; ?>

		</article>

		<!-- CỘT PHẢI: Recent post -->
		<aside class="sidebar-right">
			<?php if (is_active_sidebar('sidebar-10')): ?>
				<div class="widget-recent">
					<?php dynamic_sidebar('sidebar-10'); ?>
				</div>
			<?php else: ?>
				<div class="widget-recent">
					<h2 class="widget-title">TIN TỨC MỚI</h2>
					<ul class="recent-custom-list">
						<?php
						$recent_posts_query = new WP_Query([
							'posts_per_page' => 3,
							'post_status' => 'publish',
						]);

						if ($recent_posts_query->have_posts()):
							while ($recent_posts_query->have_posts()):
								$recent_posts_query->the_post();
								?>
								<li class="recent-item">
									<div class="date-box">
										<div class="day-year">
											<span class="day"><?php echo get_the_date('d'); ?></span>
											<sup class="year"><?php echo get_the_date('y'); ?></sup>
										</div>
										<div class="month"><?php echo get_the_date('m'); ?></div>
									</div>


									<div class="title-box">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</div>
								</li>
								<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</ul>

					<div class="view-all">
						<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">XEM TẤT CẢ TIN TỨC</a>
					</div>
				</div>
			<?php endif; ?>


		</aside>


	</div> <!-- layout-wrapper -->

</main>