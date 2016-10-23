	<div id="comments">
	<?php if (ts_comment_system() === 'facebook' && comments_open(get_the_ID())): ?>
		<h3 class="comments-title"><?php _e('Recent comments', 'touchsize'); ?></h3>
		<div class="fb-comments" data-href="<?php echo get_permalink( get_the_ID() ); ?>" data-numposts="5"></div>
	<?php else: ?>

	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'touchsize' ); ?></p>
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
				printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', ts_get_comment_count($post->ID), 'touchsize' ),
					number_format_i18n( ts_get_comment_count($post->ID) ), '<span>' . esc_attr(get_the_title()) . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'touchsize' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'touchsize' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'touchsize' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use touchsize_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define touchsize_comment() and that will be used instead.
				 * See touchsize_comment() in videotouch/includes/functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'touchsize_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'touchsize' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'touchsize' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'touchsize' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<?php if ( get_post_type() === 'post' ): ?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'touchsize' ); ?></p>
		<?php endif ?>
	<?php endif; ?>

	<?php comment_form(); ?>
	<?php endif ?>
</div><!-- #comments -->
