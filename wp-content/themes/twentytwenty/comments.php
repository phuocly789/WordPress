<?php
/**
 * Template for displaying comments and comment form
 * in the Twenty Twenty theme (customized version)
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

/*
 * Nếu bài viết được bảo vệ bằng mật khẩu và người dùng chưa nhập mật khẩu,
 * thì không hiển thị bình luận.
 */
if ( post_password_required() ) {
	return;
}

if ( have_comments() ) :
	?>

	<div class="comments" id="comments">

		<?php
		$comments_number = get_comments_number();
		?>

		<div class="comments-header section-inner small max-percentage">
			<h2 class="comment-reply-title">
				<?php
				if ( ! have_comments() ) {
					_e( 'Leave a comment', 'twentytwenty' );
				} elseif ( 1 === (int) $comments_number ) {
					/* translators: %s: Post title. */
					printf( _x( 'One reply on &ldquo;%s&rdquo;', 'comments title', 'twentytwenty' ), get_the_title() );
				} else {
					printf(
						/* translators: 1: Number of comments, 2: Post title. */
						_nx(
							'%1$s reply on &ldquo;%2$s&rdquo;',
							'%1$s replies on &ldquo;%2$s&rdquo;',
							$comments_number,
							'comments title',
							'twentytwenty'
						),
						number_format_i18n( $comments_number ),
						get_the_title()
					);
				}
				?>
			</h2>
		</div><!-- .comments-header -->

		<div class="comments-inner section-inner thin max-percentage">

			<?php
			wp_list_comments(
				array(
					'walker'      => new TwentyTwenty_Walker_Comment(),
					'avatar_size' => 120,
					'style'       => 'div',
				)
			);

			$comment_pagination = paginate_comments_links(
				array(
					'echo'      => false,
					'end_size'  => 0,
					'mid_size'  => 0,
					'next_text' => __( 'Newer Comments', 'twentytwenty' ) . ' <span aria-hidden="true">&rarr;</span>',
					'prev_text' => '<span aria-hidden="true">&larr;</span> ' . __( 'Older Comments', 'twentytwenty' ),
				)
			);

			if ( $comment_pagination ) :
				$pagination_classes = '';

				// Nếu chỉ có nút "Next", thêm class để CSS dễ xử lý.
				if ( false === strpos( $comment_pagination, 'prev page-numbers' ) ) {
					$pagination_classes = ' only-next';
				}
				?>

				<nav class="comments-pagination pagination<?php echo esc_attr( $pagination_classes ); ?>" aria-label="<?php esc_attr_e( 'Comments', 'twentytwenty' ); ?>">
					<?php echo wp_kses_post( $comment_pagination ); ?>
				</nav>

			<?php endif; ?>

		</div><!-- .comments-inner -->

	</div><!-- .comments -->

<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<div class="card shadow-sm border-0 mt-4 mb-5">
		<div class="card-header bg-white">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link active" href="#"><?php esc_html_e( 'Make a Post', 'twentytwenty' ); ?></a>
				</li>
			</ul>
		</div>

		<div class="card-body">
			<form action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>" method="post" class="post-form">
				<div class="form-group mb-3">
					<textarea id="comment" name="comment" class="form-control" rows="3" placeholder="<?php esc_attr_e( 'What are you thinking...', 'twentytwenty' ); ?>" required></textarea>
				</div>
				<div class="text-right">
					<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Share', 'twentytwenty' ); ?></button>
				</div>

				<?php
				comment_id_fields();
				wp_nonce_field( 'comment_form_action', 'comment_form_nonce' );
				do_action( 'comment_form', get_the_ID() );
				?>
			</form>
		</div>
	</div>
<?php else : ?>
	<p class="no-comments text-muted"><?php esc_html_e( 'Comments are closed.', 'twentytwenty' ); ?></p>
<?php endif; ?>
