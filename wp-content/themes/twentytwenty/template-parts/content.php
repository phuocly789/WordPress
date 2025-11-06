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
			<?php endif; ?>


		</aside>


	</div> <!-- layout-wrapper -->

</main>