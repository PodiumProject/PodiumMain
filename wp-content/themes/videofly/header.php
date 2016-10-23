<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<!-- Viewports for mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
  	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
  	<?php $typography = vdf_get_custom_fonts_css(); echo vdf_var_sanitize($typography['links']); ?>

  	<?php
  		if ( ! function_exists('has_site_icon') ) {
  			echo vdf_custom_favicon();
  		}
  	?>

	<?php
	if( !is_singular() || is_front_page() || is_home() ){
		if ( fields::get_options_value('videofly_styles', 'facebook_image') !== '' ) {
			$url = fields::get_options_value('videofly_styles', 'facebook_image');
			echo '<meta property="og:image" content="'.$url.'"/>';
		}
	}else{
		$url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
		echo '<meta property="og:image" content="'.$url.'"/>';
	}
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	$theme_styles = get_option('videofly_styles');
	$theme_general  = get_option('videofly_general');
	$custom_body_class = ' videofly ';

	if($theme_styles['boxed_layout'] == 'Y'){
		$custom_body_class .= ' ts-boxed-layout ';
	}

	if ( isset( $theme_styles['bordered_widgets'] ) && $theme_styles['bordered_widgets'] == 'Y' ) {
		$custom_body_class .= ' ts-bordered-widgets';
	}
	// Check if the image background is set
	if ( $theme_styles['theme_custom_bg'] == 'image' && $theme_styles['bg_image'] != '' ) {
		$custom_body_class .= ' ts-has-image-background ';
	}

	$animsitionMenu = '';
	if( isset($theme_styles['effect_in_general']) && ($theme_styles['effect_in_general'] !== 'none' || $theme_styles['effect_out_general'] !== 'none') ){
	    $custom_body_class .= ' animsition ';
	}
	if( isset($theme_general['enable_imagesloaded']) && $theme_general['enable_imagesloaded'] == 'Y' ) {
		$custom_body_class .= ' ts-imagesloaded-enabled ';
	}

	wp_head();
	?>
</head>
<body <?php echo body_class($custom_body_class); ?>>
	<?php if (vdf_comment_system() === 'facebook'): ?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo vdf_facebook_app_ID() ?>";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>
	<?php endif ?>

	<?php vdf_theme_styles_rewrite(); ?>
	<?php if (vdf_preloader()) : ?>
		<div class="ts-page-loading">
			<div class="gate_top"></div>
			<div class="gate_bottom"></div>
		</div>
	<?php endif; ?>
	<div id="ts-loading-preload">
		<div class="preloader-center"></div>
		<span><?php esc_html_e('Loading posts...','videofly'); ?></span>
	</div>
	<?php if (vdf_enable_sticky_menu()): ?>
		<?php if ( fields::get_options_value('videofly_general','enable_mega_menu') === 'Y') {
			$sticky_additional_class = ' megaWrapper ';
		} else{
			$sticky_additional_class = '';
		}
		?>
		<div class="ts-behold-menu ts-sticky-menu <?php echo strip_tags($sticky_additional_class); ?>">
			<div class="container relative">
				<?php
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'menu_class' => 'main-menu'
					));

				?>
			</div>
		</div>
	<?php endif ?>
	<?php
		// Set the header to show elements for all pages
		$vdf_header_display = true;

		$vdf_shown = get_post_meta( get_the_ID(), 'ts_header_and_footer', true);
		$disable_header = (isset($vdf_shown['disable_header'])) ? $vdf_shown['disable_header'] : 0;

		if (is_singular() && is_page() && $disable_header === 1) {
			$vdf_header_display = false;
		}

		$header_position = fields::get_options_value('videofly_styles', 'header_position');
		$header_position = (isset($header_position) && ($header_position == 'left' || $header_position == 'right' || $header_position == 'top')) ? $header_position : 'top';
	?>
	<div id="wrapper" class="<?php if( $theme_styles['boxed_layout'] == 'Y' ) { echo 'container'; } ?>" data-header-align="<?php echo vdf_var_sanitize($header_position); ?>">
		<?php
			if ( $vdf_header_display === true ) :
		?>
		<header id="header" class="row">
			<div class="col-lg-12">
				<?php echo LayoutCompilator::build_header(); ?>
			</div>
		</header>
		<?php endif; ?>