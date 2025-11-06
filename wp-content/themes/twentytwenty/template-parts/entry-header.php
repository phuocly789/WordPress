<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$entry_header_classes = '';

// Thêm class 'single-post-header-wrapper' cho header khi là bài viết chi tiết
if (is_singular()) { // is_singular() sẽ là true cho cả bài viết chi tiết và trang
	$entry_header_classes .= ' header-footer-group single-post-header-wrapper';
}

?>

<header class="entry-header has-text-align-center<?php echo esc_attr($entry_header_classes); ?>">

	<div class="entry-header-inner section-inner medium">

		<?php
		/**
		 * Allow child themes and plugins to filter the display of the categories in the entry header.
		 *
		 * @since Twenty Twenty 1.0
		 *
		 * @param bool Whether to show the categories in header. Default true.
		 */
		$show_categories = apply_filters('twentytwenty_show_categories_in_entry_header', true);

		if (true === $show_categories && has_category()) {
			?>

			<div class="entry-categories">
				<span class="screen-reader-text">
					<?php
					/* translators: Hidden accessibility text. */
					_e('Categories', 'twentytwenty');
					?>
				</span>
				<div class="entry-categories-inner">
					<?php the_category(' '); ?>
				</div><!-- .entry-categories-inner -->
			</div><!-- .entry-categories -->

			<?php
		}

		// Thêm div ngày tháng ở đây
		// Chỉ hiển thị cho bài viết chi tiết (is_single() sẽ là true cho bài viết post type 'post')
		if (is_single()) {
			?>
			<div class="post-date-circle">
				<div class="cotom">
					<span class="day border-day"><?php echo get_the_date('d'); ?></span>
					<span class="year" ><?php echo get_the_date('y'); ?></span>
				</div>
				<span class="month"><?php echo get_the_date('m'); ?></span>
			</div>
			<?php
		}


		if (is_singular()) { // is_singular() cũng là true cho bài viết chi tiết
			the_title('<h1 class="entry-title">', '</h1>');
		} else {
			the_title('<h2 class="entry-title heading-size-1"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');
		}

		$intro_text_width = '';

		if (is_singular()) {
			$intro_text_width = ' small';
		} else {
			$intro_text_width = ' thin';
		}

		if (has_excerpt() && is_singular()) {
			?>

			<div
				class="intro-text section-inner max-percentage<?php echo $intro_text_width; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?>">
				<?php the_excerpt(); ?>
			</div>

			<?php
		}

		// Default to displaying the post meta.
		// Có thể bạn muốn giữ lại cái này hoặc loại bỏ tùy ý.
		// Hiện tại, tôi sẽ giữ nó lại và có thể CSS nó thành ẩn nếu không cần.
		twentytwenty_the_post_meta(get_the_ID(), 'single-top');

		// Thêm đường gạch ngang nếu là bài viết chi tiết
		if (is_single()) {
			echo '<hr class="header-divider">';
		}
		?>

	</div><!-- .entry-header-inner -->

</header><!-- .entry-header -->