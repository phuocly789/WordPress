<?php
/**
 * Displays the next and previous post navigation in single posts
 * with custom list style (date + title like design reference).
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$next_post = get_next_post();
$prev_post = get_previous_post();

if ($next_post || $prev_post):
	?>

	<nav class="pagination-single section-inner" aria-label="<?php esc_attr_e('Post', 'twentytwenty'); ?>">
		<hr class="styled-separator is-style-wide" aria-hidden="true" />

		<div class="pagination-single-inner list-style">

			<?php if ($prev_post): ?>
				<a class="pagination-item" href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
					<div class="post-nav-date">
						<div class="gr-day-year">
							<span class="day border-day"><?php echo get_the_date('d', $prev_post->ID); ?></span>
							<span class="month"><?php echo get_the_date('m', $prev_post->ID); ?></span>
						</div>
						<span class="year"><?php echo get_the_date('y', $prev_post->ID); ?></span>
					</div>
					<span class="title"><?php echo wp_kses_post(get_the_title($prev_post->ID)); ?></span>
				</a>
			<?php endif; ?>

			<?php if ($next_post): ?>
				<a class="pagination-item" href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
					<div class="post-nav-date">
						<div class="gr-day-year">
							<span class="day border-day"><?php echo get_the_date('d', $next_post->ID); ?></span>
							<span class="month"><?php echo get_the_date('m', $next_post->ID); ?></span>
						</div>
						<span class="year"><?php echo get_the_date('y', $next_post->ID); ?></span>
					</div>
					<span class="title"><?php echo wp_kses_post(get_the_title($next_post->ID)); ?></span>
				</a>
			<?php endif; ?>

		</div><!-- .pagination-single-inner -->

		<hr class="styled-separator is-style-wide" aria-hidden="true" />
	</nav>

<?php endif; ?>