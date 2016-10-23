<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class USER_Dashboard {
	function __construct() {
		add_action( 'template_redirect', array(
			 $this,
			'check_access'
		) );
		add_action('init',array($this,'comment_intercept'));
		add_action('init',array($this,'mark_comment_as_read'));

	}

	public function check_access() {
		global $post;
		$task = !empty( $post->post_name ) ? $post->post_name : '';
		if ( $task == 'logout' ) {
			$this->user_secure_logout();
		}
	}

	public function user_secure_logout() {
		if ( is_user_logged_in() ) {
			wp_logout();
			$base_url = get_permalink( FRONTEND_USER()->helper->get_option( 'user-logout-redirect', false ) );
			wp_redirect( $base_url );
			exit;
		}
	}

	function comment_intercept(){

		if( ! isset( $_POST['user_nonce'] ) || ! isset( $_POST['newcomment_body'] ) ) {
			return;
		}

		if ( !wp_verify_nonce($_POST['user_nonce'], 'user_comment_nonce') || $_POST['newcomment_body'] === '' ) {
			return;
		}

		$comment_id = absint( $_POST['cid'] );
		$author_id = absint( $_POST['aid'] );
		$post_id = absint( $_POST['pid'] );
		$content = wp_kses( $_POST['newcomment_body'], user_allowed_html_tags() );

		$user = get_userdata( $author_id );

		update_comment_meta( $comment_id,'user-already-processed', 'frontend_user_pro' );

		$new_id = wp_insert_comment( array(
			'user_id' => $author_id,
			'comment_author_email' => $user->user_email,
			'comment_author' => $user->user_login,
			'comment_parent' => $comment_id,
			'comment_post_ID' => $post_id,
			'comment_content' => $content
		) );
		// This ensures author replies are not shown in the list
		update_comment_meta( $new_id, 'user-already-processed', 'frontend_user_pro' );
	}


	function mark_comment_as_read(){

		if ( ! isset( $_POST['user_nonce'] ) || ! wp_verify_nonce( $_POST['user_nonce'], 'user_ignore_nonce' ) ) {
			return;
		}

		$comment_id = absint( $_POST['cid'] );
		update_comment_meta( $comment_id, 'user-already-processed', 'frontend_user_pro');
	}

	function render_comments_table( $limit ) {
		global $current_user, $wpdb;
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) :
		1;
		$offset = ( $pagenum - 1 ) * $limit;
		$args = array(
			'number' => $limit,
			'offset' => $offset,
			'post_author' => $current_user->ID,
			'post_type' => 'download',
			'status' => 'approve',
			'meta_query' => array(
				array(
					'key' => 'user-already-processed',
					'compare' => 'NOT EXISTS'
				),
			)
		);
		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );

		if ( count( $comments ) == 0 ) {
			echo '<tr><td colspan="4">' . __( 'No Comments Found', 'frontend_user_pro' ) . '</td></tr>';
		}
		foreach ($comments as $comment) {
			$this->render_comments_table_row( $comment );
		}

		$args = array('post_author'  => $current_user->ID,'post_type'    => 'download','status'       => 'approve','author__not_in' => array($current_user->ID),'meta_query'   => array(array('key' => 'user-already-processed','compare' => 'NOT EXISTS',),));
		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );

		if ( count($comments) > 0){
			$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) :
			1;
			$num_of_pages = ceil( count($comments) / $limit );
			$page_links = paginate_links( array('base' => add_query_arg( 'pagenum', '%#%' ),'format' => '','prev_text' => __( '&laquo;', 'aag' ),'next_text' => __( '&raquo;', 'aag' ),'total' => $num_of_pages,'current' => $pagenum) );

			if ( $page_links ) {
				echo '<div class="user-pagination">' . $page_links . '</div>';
			}
		}
	}

	function render_comments_table_row( $comment ) {
		$comment_date = get_comment_date( 'Y/m/d \a\t g:i a', $comment->comment_ID );
		$comment_author_img = get_avatar( $comment->comment_author_email, 32 );
		$purchased = frontend_has_user_purchased( $comment->user_id, $comment->comment_post_ID );
		?>
	<tr>
		<td class="col-author" style="width:25%;">
			<div class="user-author-img"><?php echo $comment_author_img; ?></div>
			<span id="user-comment-author"><?php echo $comment->comment_author; ?></span>
			<br /><br />
			<?php
			if ($purchased){
				echo '<div class="user-light-green">'.__('Has Purchased','frontend_user_pro').'</div>';
			} else {
				echo '<div class="user-light-red">'.__('Has Not Purchased','frontend_user_pro').'</div>';
			}

			?>
			<span id="user-comment-date"><?php echo $comment_date; ?>&nbsp;&nbsp;&nbsp;</span><br />
			<span id="user-product-name">
				<b><?php echo FRONTEND_USER()->users->get_product_constant_name( $plural = false, $uppercase = true ) . ': '; ?></b>
				<a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) ); ?>"><?php echo get_the_title( $comment->comment_post_ID ); ?></a>&nbsp;&nbsp;&nbsp;
			</span><br />
			<span id="user-view-comment">
				<a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID ); ?>"><?php _e( 'View Comment','frontend_user_pro' ); ?></a>
			</span>
		</td>
		<td class="col-content" style="width:70%;">
			<div class="user-comments-content"><?php echo $comment->comment_content; ?></div>
			<hr/>
			<div id="<?php echo $comment->comment_post_ID; ?>" class="user-user-comment-respond-form">
				<span><?php _e( 'Respond:', 'frontend_user_pro' ); ?></span><br/>
				<table>
					<tr>
						<form id="user_comments-form" action="" method="post">
							<input type="hidden" name="cid" value="<?php echo $comment->comment_ID; ?>">
							<input type="hidden" name="pid" value="<?php echo $comment->comment_post_ID; ?>">
							<input type="hidden" name="aid" value="<?php echo get_current_user_id(); ?>">
							<?php wp_nonce_field('user_comment_nonce', 'user_nonce'); ?>
							<textarea class="user-cmt-body" name="newcomment_body" cols="50" rows="8"></textarea>
							<button class="user-cmt-submit-form button" type="submit"><?php  _e( 'Post Response', 'frontend_user_pro' ); ?></button>
						</form>
						<form id="user_ignore-form" action="" method="post">
							<input type="hidden" name="cid" value="<?php echo $comment->comment_ID; ?>">
							<?php wp_nonce_field('user_ignore_nonce', 'user_nonce'); ?>
							<button class="user-ignore button" type="submit"><?php _e( 'Mark as Read', 'frontend_user_pro' ); ?></button>
						</form>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<?php
	}

}