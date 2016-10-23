	<div id="comments">
	<?php if (vdf_comment_system() === 'facebook'): ?>
		<h3 class="comments-title"><?php esc_html_e('Recent comments', 'videofly'); ?></h3>
		<div class="fb-comments" data-href="<?php echo get_permalink( get_the_ID() ); ?>" data-numposts="5"></div>
	<?php else: ?>	

	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php esc_html_e( 'This post is password protected. Enter the password to view any comments.', 'videofly' ); ?></p>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
				printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', vdf_get_comment_count($post->ID), 'videofly' ),
					number_format_i18n( vdf_get_comment_count($post->ID) ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php esc_html_e( 'Comment navigation', 'videofly' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'videofly' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'videofly' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use vdf_touchsize_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define vdf_touchsize_comment() and that will be used instead.
				 * See vdf_touchsize_comment() in videofly/includes/functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'vdf_touchsize_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php esc_html_e( 'Comment navigation', 'videofly' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'videofly' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'videofly' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<?php if ( get_post_type() === 'post' ): ?>
			<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'videofly' ); ?></p>
		<?php endif ?>
	<?php endif; ?>

	<?php comment_form(); ?>
	<?php endif ?>
</div><!-- #comments -->