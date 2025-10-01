<?php

/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>

<?php
// KIỂM TRA NẾU LÀ TRANG CHI TIẾT BÀI VIẾT (SINGLE POST)
if (is_single()) { ?>

	<div class="single-post-wrapper"> <?php // Thẻ div bao bọc để sửa lỗi layout flex 
										?>

		<article <?php post_class('single-post-container'); ?> id="post-<?php the_ID(); ?>">
			<header class="single-post-header">

				<div class="single-post-meta">
					<span class="post-categories"><?php the_category(', '); ?></span>
				</div>

				<h1 class="single-post-title"><?php the_title(); ?></h1>

				<div class="single-post-meta-bottom">
					<span class="post-author">
						Bởi <?php the_author_posts_link(); ?>
					</span>
					<span class="meta-separator">&bull;</span>
					<span class="post-date">
						<?php echo get_the_date(); ?>
					</span>
					<span class="meta-separator">&bull;</span>
					<span class="reading-time">
						<?php echo get_estimated_reading_time(); // ĐÂY LÀ DÒNG THÊM MỚI 
						?>
					</span>
				</div>

			</header>

			<?php if (has_post_thumbnail()) : ?>
				<div class="single-post-featured-image">
					<?php the_post_thumbnail('full'); // Sử dụng ảnh kích thước lớn nhất 
					?>
				</div>
			<?php endif; ?>

			<div class="single-post-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before'   => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__('Page', 'twentytwenty') . '"><span class="label">' . __('Pages:', 'twentytwenty') . '</span>',
						'after'    => '</nav>',
						'link_before' => '<span class="page-number">',
						'link_after'  => '</span>',
					)
				);
				?>
			</div>
			<footer class="single-post-footer">
				<?php
				// PHP để lấy link và tiêu đề bài viết
				$post_url = urlencode(get_permalink());
				$post_title = urlencode(get_the_title());
				?>
				<div class="social-share">
					<h4>Chia sẻ bài viết này:</h4>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" class="share-facebook">Facebook</a>
					<a href="https://twitter.com/intent/tweet?text=<?php echo $post_title; ?>&url=<?php echo $post_url; ?>" target="_blank" class="share-twitter">Twitter</a>
					<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>" target="_blank" class="share-linkedin">LinkedIn</a>
					<a href="mailto:?subject=<?php echo $post_title; ?>&body=Xem bài viết này: <?php echo $post_url; ?>" class="share-email">Email</a>
				</div>
			</footer>

			<?php
			// Hiển thị điều hướng bài viết (Bài mới hơn / cũ hơn)
			get_template_part('template-parts/navigation');

			// Hiển thị khu vực bình luận
			if ((is_single() || is_page()) && (comments_open() || get_comments_number()) && !post_password_required()) {
			?>
				<div class="comments-wrapper section-inner">
					<?php comments_template(); ?>
				</div><?php
					}
						?>

		</article>
	</div> <?php // Thẻ đóng của .single-post-wrapper 
			?>

<?php
	// KIỂM TRA NẾU LÀ TRANG DANH SÁCH BÀI VIẾT (ARCHIVE, INDEX, ETC.)
} else { ?>

	<article class="post">
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php if (has_post_thumbnail()) : ?>
					<?php the_post_thumbnail('medium'); ?>
				<?php endif; ?>
			</a>
		</div>

		<div class="post-main">
			<div class="post-date">
				<div class="day"><?php echo get_the_date('d'); ?></div>
				<span class="month">Tháng <?php echo get_the_date('n'); ?></span>
				<span class="year"><?php echo get_the_date('Y'); ?></span>
			</div>

			<div class="post-content">
				<h2 class="post-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<div class="post-meta">
					<?php the_category(', '); ?>
				</div>
				<div class="post-excerpt">
					<?php echo get_the_excerpt(); ?>
				</div>
			</div>
		</div>
	</article><?php } ?>

<style>
	/* === CÀI ĐẶT BIẾN TOÀN CỤC (VARIABLES) === */
	:root {
		--font-primary: 'Inter', sans-serif;
		/* Sử dụng Google Font cho đẹp hơn */
		--font-secondary: 'Lora', serif;
		--color-primary: #3b82f6;
		/* Xanh dương */
		--color-primary-dark: #2563eb;
		--color-text-dark: #111827;
		--color-text-medium: #374151;
		--color-text-light: #6b7280;
		--color-border: #e5e7eb;
		--color-background-light: #f9fafb;
		--border-radius: 8px;
		--shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
		--shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
	}

	/* Thêm Google Font (bạn cần thêm link này vào header.php hoặc dùng plugin) */
	@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=Lora:ital,wght@0,400;0,700;1,400&display=swap');

	body {
		font-family: var(--font-primary);
	}

	/* === CSS CHO TRANG DANH SÁCH (ĐÃ CẢI TIẾN) === */
	.post {
		display: flex;
		flex-direction: row;
		border-bottom: 1px solid var(--color-border);
		padding: 24px 0;
		gap: 24px;
		max-width: cover;
		transition: background-color 0.3s ease;
	}

	.post:hover {
		background-color: var(--color-background-light);
	}

	.post-thumbnail a {
		display: block;
		flex-shrink: 0;
		width: 160px;
	}

	.post-thumbnail img {
		width: 100%;
		height: 120px;
		border-radius: var(--border-radius);
		object-fit: cover;
		box-shadow: var(--shadow-sm);
		transition: transform 0.3s ease, box-shadow 0.3s ease;
	}

	.post-thumbnail a:hover img {
		transform: scale(1.05);
		box-shadow: var(--shadow-md);
	}

	.post-main {
		display: flex;
		flex-direction: row;
		gap: 20px;
		flex: 1;
	}

	.post .post-date {
		max-width: 100px;
		text-align: center;
		border-right: 1px solid var(--color-border);
		padding-right: 20px;
		margin-right: 20px;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}

	.post .post-date .day {
		font-size: 40px;
		font-weight: 800;
		color: var(--color-primary);
		line-height: 1;
	}

	.post .post-date .month {
		text-transform: uppercase;
		font-size: 13px;
		color: var(--color-text-light);
		display: block;
		margin-top: 5px;
	}

	.post .post-date .year {
		font-size: 14px;
		color: var(--color-text-medium);
		font-weight: 500;
	}

	.post .post-content {
		flex: 1;
	}

	.post .post-title {
		font-size: 22px;
		font-weight: 700;
		margin: 0 0 10px 0;
	}

	.post .post-title a {
		color: var(--color-text-dark);
		text-decoration: none;
		transition: color 0.2s ease;
	}

	.post .post-title a:hover {
		color: var(--color-primary-dark);
	}

	.post .post-meta {
		font-size: 14px;
		color: var(--color-text-light);
		margin: 0 10px 10px 0;
	}

	.post .post-excerpt {
		font-size: 15px;
		color: var(--color-text-medium);
		line-height: 1.6;
	}

	/* === CSS MỚI CHO TRANG BÀI VIẾT CHI TIẾT (is_single) === */
	.single-post-wrapper {
		width: 100%;
	}

	.single-post-container {
		display: block !important;
		max-width: 800px;
		margin: 3em auto;
		background: #fff;
		padding: 40px;
		border-radius: var(--border-radius);
		box-shadow: var(--shadow-md);
	}

	.single-post-header {
		text-align: center;
		margin-bottom: 30px;
		border-bottom: 1px solid var(--color-border);
		padding-bottom: 30px;
	}

	.single-post-header .post-categories a {
		background-color: #eef2ff;
		color: #4338ca;
		padding: 6px 16px;
		border-radius: 999px;
		font-size: 14px;
		font-weight: 600;
		text-decoration: none;
		transition: all 0.2s ease;
	}

	.single-post-header .post-categories a:hover {
		background-color: #e0e7ff;
		color: #3730a3;
	}

	.single-post-title {
		font-size: 2.8rem;
		font-weight: 800;
		color: var(--color-text-dark);
		margin: 20px 0 15px 0;
		line-height: 1.2;
	}

	.single-post-meta-bottom {
		color: var(--color-text-light);
		font-size: 15px;
	}

	.single-post-meta-bottom a {
		color: var(--color-text-medium);
		text-decoration: none;
		font-weight: 500;
		transition: color 0.2s ease;
	}

	.single-post-meta-bottom a:hover {
		color: var(--color-primary-dark);
	}

	.single-post-meta-bottom .meta-separator {
		margin: 0 8px;
	}

	.single-post-featured-image {
		margin-bottom: 40px;
	}

	.single-post-featured-image img {
		width: 100%;
		height: auto;
		border-radius: var(--border-radius);
		box-shadow: var(--shadow-md);
	}

	.single-post-content {
		font-family: var(--font-secondary);
		/* Font serif cho nội dung dài dễ đọc hơn */
		font-size: 1.125rem;
		line-height: 1.8;
		color: var(--color-text-medium);
	}

	.single-post-content p {
		margin-bottom: 1.5em;
	}

	.single-post-content h2,
	.single-post-content h3,
	.single-post-content h4 {
		margin-top: 2em;
		margin-bottom: 1em;
		color: var(--color-text-dark);
		font-weight: 700;
		font-family: var(--font-primary);
		/* Font chính cho tiêu đề */
	}

	.single-post-content a {
		color: var(--color-primary-dark);
		text-decoration: none;
		border-bottom: 2px solid var(--color-primary);
		transition: all 0.2s ease;
	}

	.single-post-content a:hover {
		background-color: #dbeafe;
	}

	.single-post-content blockquote {
		border-left: 4px solid var(--color-primary);
		padding-left: 20px;
		margin: 2em 0;
		font-style: italic;
		color: var(--color-text-light);
	}

	.single-post-footer {
		margin-top: 40px;
		padding-top: 20px;
		border-top: 1px solid var(--color-border);
	}

	.single-post-tags span {
		font-weight: 700;
		color: var(--color-text-dark);
		margin-right: 10px;
	}

	.single-post-tags a {
		display: inline-block;
		background-color: var(--color-background-light);
		color: var(--color-text-light);
		padding: 5px 12px;
		margin: 4px;
		border-radius: 5px;
		text-decoration: none;
		font-size: 14px;
		border: 1px solid var(--color-border);
		transition: all 0.2s ease;
	}

	.single-post-tags a:hover {
		background-color: var(--color-primary);
		color: #fff;
		border-color: var(--color-primary);
	}

	/* === RESPONSIVE CHO THIẾT BỊ DI ĐỘNG === */
	@media (max-width: 768px) {

		.post,
		.post-main {
			flex-direction: column;
		}

		.post .post-date {
			border-right: 0;
			border-bottom: 1px solid var(--color-border);
			padding: 0 0 15px 0;
			margin: 0 0 15px 0;
			max-width: none;
			text-align: left;
			flex-direction: row;
			align-items: center;
			gap: 15px;
		}

		.post .post-date .day {
			font-size: 28px;
		}

		.post .post-date .month,
		.post .post-date .year {
			margin-top: 0;
		}

		.post-thumbnail a {
			width: 100%;
		}

		.single-post-container {
			padding: 20px;
			margin: 1em auto;
		}

		.single-post-title {
			font-size: 2rem;
		}
	}

	.social-share {
		margin-top: 30px;
	}

	.social-share h4 {
		margin-bottom: 10px;
	}

	.social-share a {
		text-decoration: none;
		color: #fff;
		padding: 8px 15px;
		border-radius: 5px;
		margin-right: 10px;
		font-size: 14px;
		display: inline-block;
		transition: opacity 0.3s ease;
	}

	.social-share a:hover {
		opacity: 0.8;
	}

	.share-facebook {
		background-color: #1877F2;
	}

	.share-twitter {
		background-color: #1DA1F2;
	}

	.share-linkedin {
		background-color: #0A66C2;
	}

	.share-email {
		background-color: #7f8c8d;
	}
</style>