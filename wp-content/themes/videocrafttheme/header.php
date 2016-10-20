<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <title>
            <?php wp_title('&#124;', true, 'right'); ?><?php bloginfo('name'); ?>
        </title> 
        <?php if (is_front_page()) { ?>
            <?php if (inkthemes_get_option('inkthemes_keyword') != '') { ?>
                <meta name="keywords" content="<?php echo inkthemes_get_option('inkthemes_keyword'); ?>" />
            <?php
            } else {
                
            }
            ?>
            <?php if (inkthemes_get_option('inkthemes_description') != '') { ?>
                <meta name="description" content="<?php echo inkthemes_get_option('inkthemes_description'); ?>" />
            <?php
            } else {
                
            }
            ?>
            <?php if (inkthemes_get_option('inkthemes_author') != '') { ?>
                <meta name="author" content="<?php echo inkthemes_get_option('inkthemes_author'); ?>" />
            <?php
            } else {
                
            }
            ?>
<?php } ?>		
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" /> 
<?php wp_head(); ?>
<!--[if IE 6]>
<style>
.feature-post .feature-box {
margin-right:10px;}
}
</style>
<![endif]-->
<!--[if IE 7]>
<style>
.content ul.fthumbnail li span.videobox span.views {
margin-top: -3px;
}
</style>
<![endif]-->
<!--[if IE 8]>
<style>
.content ul.fthumbnail li span.videobox span.views {
margin-top: -3px;
}
</style>
<![endif]-->
<!--[if lte IE 9]>
		<style>
.content ul.fthumbnail li span.videobox span.views {
margin-top: -3px;
}
		</style>
		<![endif]-->
    </head>
    <body>
	<div class="top_strip">
            <div class="container_24">
                <div class="grid_24">
				   <div class="grid_14 alpha">
                            <div class="menu_container">
                                <div class="menu_bar">
                                    <div id="MainNav">       
		<?php inkthemes_nav(); ?> 
                    </div>
                                </div>
                            </div>
                        </div>
					<div class="grid_10 omega">
                    <div class="menu">
                            <?php do_action('videocast_auth_menu'); ?>
                    </div>   
                </div>
				</div>
            </div>
        </div>
        <div class="top_wrapper">
            <div class="container_24">
                <div class="grid_24">
                    <div class="header">
                        <div class="grid_5 alpha">
                            <div class="logo">
                                <a href="<?php echo home_url(); ?>"><img src="<?php if (inkthemes_get_option('inkthemes_logo') != '') { ?><?php echo inkthemes_get_option('inkthemes_logo'); ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" alt="logo"/></a>
                            </div>
                        </div>
						 <div class="grid_13">
                            <div class="banner">
							 <?php if ( inkthemes_get_option('inkthemes_header_banner') !='' ) {  ?>
          <p><?php echo stripslashes(inkthemes_get_option('inkthemes_header_banner')); ?></p>
		   <?php } else { ?>
                  <?php } ?>
							</div>
							</div>
                        <div class="grid_6 omega">
                            <div class="search">
<?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>