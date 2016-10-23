<?php defined( 'ABSPATH' ) or die( 'restricted access.' ); ?>

<!DOCTYPE html>
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->

<!--[!(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>

    <!-- meta information -->
    <meta charset="<?php echo esc_attr( get_bloginfo('charset') );?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

    <link rel="profile" href="//gmpg.org/xfn/11">
    <link rel="pingback" href="<?php echo esc_url( get_bloginfo('pingback_url') );?>">
    
    <?php wp_head(); ?>
</head>

<?php
$header_settings = (array) get_option( 'mf_header_settings' );
$general_settings = (array) get_option( 'mf_general_settings' );
$is_boxed_view = ( exc_kv( $general_settings, 'structure' ) == 'boxed-layout' ) ? true : false;
$auto_grid_adjustment = ( 'on' == exc_kv( $general_settings, 'auto_grid_adjustment' ) ) ? 'auto-grid-adjustment' : '';

$body_class = ( $is_boxed_view ) ? 'boxed-layout' : $auto_grid_adjustment; ?>

<body <?php body_class( $body_class );?>>

<div class="wrapper <?php echo ( $is_boxed_view ) ? 'container' : '';?>">
        <header class="exc-header-3">
        <!-- Notifications -->
        <?php get_template_part('modules/notifications');?>

        <div class="header-bottom">
        
            <!-- Logo Bar -->
            <div class="logo-bar">
                <div class="<?php mf_container_class();?>">
                    <div class="navbar-header">
                        
                        <!-- Logo -->
                        <a href="<?php echo esc_url( home_url('/') );?>" class="logo">
                            <img src="<?php echo esc_url( exc_kv( $header_settings, 'logo', get_template_directory_uri() . '/images/logo.png' ) );?>" alt="<?php echo esc_attr( get_bloginfo('name') );?>">
                        </a>

                        <ul class="menu-right login">
                            <li>
                                <?php if ( exc_get_layout( 'slider' ) != 'with_uploader' && exc_kv( get_option('mf_uploader_settings'), 'status' ) ) : ?>
                                    <?php exc_mf_media_upload( array( 'template' => 'modules/upload_button' ) );?>
                                <?php endif;?>
                            </li>
                            
                            <li>
                                <!-- Member Controls -->
                                <?php if ( exc_kv( $header_settings, 'header_member_status' ) == 'on' ) :?>
                                    <?php get_template_part('modules/user_controls');?>
                                <?php endif;?>
                            </li>
                        </ul>

                        <!-- Responsive Navigation Btn -->
                        <button type="button" data-toggle="collapse" data-target="#navbar-collapse" class="navbar-toggle">
                            <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Main Menu -->
                    <nav class="main-nav navbar-collapse collapse" id="navbar-collapse">
                        <?php if ( exc_kv( $header_settings, 'hcl_menu_status' ) == 'on' ) :?>
                            <?php wp_nav_menu(
                                array(
                                    'theme_location'    =>  'main-menu',
                                    'container'         =>  'ul',
                                    )
                                );?>
                        <?php endif;?>

                        <?php if ( exc_kv( $header_settings, 'hcl_contact_status' ) == 'on' ) :?>
                        <ul class="exc-contact-info">
                            <?php if ( $email_addr = exc_kv( $header_settings, 'hcl_contact_email_addr' ) ) :?>
                                <li><i class="fa fa-envelope-o"></i><?php echo $email_addr;?></li>
                            <?php endif;?>

                            <?php if ( $phone_no = exc_kv( $header_settings, 'hcl_contact_phone' ) ) :?>
                                <li><i class="fa fa-phone"></i><?php echo $phone_no;?></li>
                            <?php endif;?>
                        </ul>
                        <?php endif;?>

                    </nav>

                </div>
            </div>

            <!-- Uploader -->
            <?php   
            $slider = exc_get_layout( 'slider' );
            $revslider_id = exc_get_layout( 'revslider_id' );

            if ( $slider == 'with_uploader' ) :?>
                <?php if ( exc_kv( get_option('mf_uploader_settings'), 'status' ) == 'on' ) :?>
                    <?php exc_mf_media_upload();?>
                <?php endif;?>

            <?php elseif ( $slider == 'revslider' && class_exists( 'RevSliderOutput' )
                            && $revslider_id ): 
                RevSliderOutput::putSlider( $revslider_id ); ?>
            <?php endif;?>
        </div>

    </header>