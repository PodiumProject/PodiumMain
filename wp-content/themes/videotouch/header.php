<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<!-- Viewports for mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<![endif]-->
	<title><?php if( !is_front_page() ) echo esc_attr(get_the_title(get_the_ID())); else echo get_bloginfo('name'); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
  	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

  	<?php
  		if( !function_exists('has_site_icon') || !has_site_icon() ){
  			echo ts_custom_favicon();
  		}
  	?>
  	<?php echo ts_get_logo_google_fonts() ?>
  	<?php echo ts_get_custom_fonts('headings') ?>
  	<?php echo ts_get_custom_fonts('primary_text') ?>
  	<?php echo ts_get_custom_fonts('secondary_text') ?>

  	<?php $facebook = get_option('videotouch_general'); ?>

  	<?php if( isset($facebook['ts_seo']) && $facebook['ts_seo'] == 'y' ) : ?>
		<?php if( is_single() || is_page() ) : ?>
		    <meta property="og:title" content="<?php esc_attr(the_title()) ?>" />
		    <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />
		    <meta property="og:url" content="<?php the_permalink() ?>" />
		    <meta property="og:type" content="article" />
		    <meta property="og:locale" content="en_US" />
		    <meta property="og:description" content="<?php echo get_the_excerpt(); ?>"/>
		    <?php if(  has_post_thumbnail(get_the_ID()) ) : $thum_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
			    <meta property="og:image" content="<?php echo $thum_url; ?>"/>
			<?php else : ?>
				<?php if( fields::get_options_value('videotouch_styles', 'facebook_image') != '' ) : $url = fields::get_options_value('videotouch_styles', 'facebook_image'); ?>
					<meta property="og:image" content="<?php echo $url; ?>"/>
				<?php else : ?>
					<meta property="og:image" content="<?php echo esc_url(get_template_directory_uri() . '/sreenshot.png'); ?>"/>
				<?php endif; ?>
			<?php endif; ?>
		<?php else : ?>
		        <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>"/>
		        <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
		        <meta property="og:url" content="<?php echo home_url() ?>/"/>
		        <meta property="og:type" content="blog"/>
		        <meta property="og:locale" content="en_US"/>
		        <meta property="og:description" content="<?php echo get_bloginfo('name') . get_bloginfo('description'); ?>"/>
		        <?php if( fields::get_options_value('videotouch_styles', 'facebook_image') != '' ) : $url = fields::get_options_value('videotouch_styles', 'facebook_image'); ?>
		        	<meta property="og:image" content="<?php echo $url; ?>"/>
		        <?php else : ?>
					<meta property="og:image" content="<?php echo esc_url(get_template_directory_uri() . '/screenshot.png'); ?>"/>
		        <?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	$theme_styles = get_option('videotouch_styles');
	$custom_body_class = ' videotouch ';
	if($theme_styles['boxed_layout'] == 'Y'){
		$custom_body_class .= 'red-boxed';
	}
	$default_videoplayer = fields::get_options_value('videotouch_single_post','default_videoplayer');

	if ( is_singular() && $default_videoplayer === 'y' )
		echo '<script src="https://content.jwplatform.com/libraries/4r6XfcLg.js"></script>';

	wp_head();
	?>
</head>
<body <?php echo body_class($custom_body_class); ?>>
	<?php if (ts_comment_system() === 'facebook'): ?>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo ts_facebook_app_ID() ?>";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>
	<?php endif ?>

	<?php theme_styles_rewrite(); ?>
	<?php if (ts_preloader()) : ?>
	<div class="ts-page-loading">
		<div class="page-loading-content">
			<div class="page-loading-logo"> <?php echo ts_get_logo(); ?> </div>
			<div class="page-loading-text"><?php _e('Loading...','touchsize'); ?></div>
			<div class="page-loader"></div>
		</div>
	</div>
	<?php endif; ?>
	<div id="ts-loading-preload">
		<div class="preloader-center"></div>
	</div>
	<?php if (ts_enable_sticky_menu()): ?>
		<?php if ( fields::get_options_value('videotouch_general','enable_mega_menu') === 'Y') {
			$sticky_additional_class = ' megaWrapper ';
		} else{
			$sticky_additional_class = '';
		}
		?>
		<div class="ts-behold-menu ts-sticky-menu <?php echo $sticky_additional_class; ?>">
			<div class="container relative">
				<?php
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'menu_class' => 'main-menu sf-menu'
					));

				?>
			</div>
		</div>
	<?php endif ?>
	<div id="wrapper" class="<?php if( $theme_styles['boxed_layout'] == 'Y' ) { echo 'container'; } ?>">
		<header id="header" class="row">
			<div class="col-lg-12">
				<?php echo LayoutCompilator::build_header(); ?>
			</div>
		</header>