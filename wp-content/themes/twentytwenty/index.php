<?php
get_header();
?>

<main id="site-content" class="site-main">

	<?php if (is_search()): ?>
		<!-- ==================== TRANG TÌM KIẾM ==================== -->
		<div class="layout-wrapper-search">
			<!-- Search Header -->
			<?php if (is_search()): ?>
				<div class="search-header" style="margin: 20px 0; text-align: center;">
					<h2>
						Search: "<span><?php echo get_search_query(); ?></span>"
					</h2>
					<p style="color: #666;">We could not find any results for your search. You can try it again through the
						form below.</p>
					<div class="search-wrapper">
						<div class="search-form-wrapper">
							<!-- Bootstrap-inspired search form (adapted from snippet) -->
							<form class="card card-sm search-form-custom" role="search" method="get"
								action="<?php echo home_url('/'); ?>">
								<div class="card-body row no-gutters align-items-center">
									<div class="col-auto">
										<i class="fas fa-search h4 text-body"></i>
									</div>
									<!--end of col-->
									<div class="col">
										<input class="form-control form-control-lg form-control-borderless" type="search"
											name="s" placeholder="Search topics or keywords"
											value="<?php echo get_search_query(); ?>">
									</div>
									<!--end of col-->
									<div class="col-auto">
										<button class="btn btn-lg btn-success" type="submit">Search</button>
									</div>
									<!--end of col-->
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<!-- Search Header -->

			<div class="search-content">
				<!-- CỘT TRÁI 13 -->
				<aside class="sidebar-left">
					<?php if (is_active_sidebar('sidebar-13')): ?>
						<div class="widget-categories">
							<?php dynamic_sidebar('sidebar-13'); ?>
						</div>
					<?php endif; ?>
				</aside>
				<!-- KẾT QUẢ TÌM KIẾM (5) -->
				<div class="post-list">
					<?php if (have_posts()): ?>
						<?php while (have_posts()):
							the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
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

								<div class="post-content">
									<h2 class="post-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>
									<div class="post-excerpt">
										<?php the_excerpt(); ?>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
						<div class="pagination">
							<?php the_posts_pagination([
								'mid_size' => 2,
								'prev_text' => __('« Trước'),
								'next_text' => __('Sau »'),
							]); ?>
						</div>
					<?php else: ?>
						<p style="text-align:center;color:#666;">Không tìm thấy kết quả nào.</p>
					<?php endif; ?>
				</div>
				<!-- CỘT PHẢI 14 -->
				<aside class="sidebar-right">
					<?php if (is_active_sidebar('sidebar-14')): ?>
						<div class="widget-categories">
							<?php dynamic_sidebar('sidebar-14'); ?>
						</div>
					<?php endif; ?>
				</aside>
			</div>

			<!-- PHẦN DƯỚI 15 -->
			<div class="search-footer">
				<?php if (is_active_sidebar('sidebar-15')): ?>
					<div class="widget-categories">
						<?php dynamic_sidebar('sidebar-15'); ?>
					</div>
				<?php endif; ?>
			</div>

		</div> <!-- end layout-wrapper -->

	<?php else: ?>
		<!-- ==================== TRANG HOME (mặc định) ==================== -->
		<div class="layout-wrapper">
			<!-- CỘT TRÁI: Archive -->
			<aside class="sidebar-left">
				<?php if (is_active_sidebar('sidebar-11')): ?>
					<div class="widget-categories">
						<?php dynamic_sidebar('sidebar-11'); ?>
					</div>
				<?php endif; ?>
			</aside>

			<!-- DANH SÁCH BÀI VIẾT -->
			<div class="post-list">
				<?php if (have_posts()): ?>
					<?php while (have_posts()):
						the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
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

							<div class="post-content">
								<h2 class="post-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="post-excerpt">
									<?php the_excerpt(); ?>
								</div>
							</div>
						</article>
					<?php endwhile; ?>

					<div class="pagination">
						<?php the_posts_pagination([
							'mid_size' => 2,
							'prev_text' => __('« Trước'),
							'next_text' => __('Sau »'),
						]); ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- CỘT PHẢI: Comments -->
			<aside class="sidebar-left">
				<?php if (is_active_sidebar('sidebar-12')): ?>
					<div class="widget-categories">
						<?php dynamic_sidebar('sidebar-12'); ?>
					</div>
				<?php endif; ?>
			</aside>
		</div>
	<?php endif; ?>

</main>

<?php get_template_part('template-parts/footer-menus-widgets'); ?>

<?php
get_footer();
?>