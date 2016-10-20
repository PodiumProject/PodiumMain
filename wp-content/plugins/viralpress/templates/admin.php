<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
 * @modified v1.1
*/
defined( 'ABSPATH' ) || exit;
$vp_instance = $attributes[ 'vp_instance' ];
$vp_instance->plugin_update_message();

$tab = @$_REQUEST['tab'];
if( !in_array( $tab, array( 'general', 'social', 'captcha', 'modules', 'editor', 'demo', 'gif', 'embed' ) ) ) {
	$tab = 'general';	
}

if ( isset( $_REQUEST['install_demo'] ) ) {
	$ins = get_option( 'vp-demo-installed', -1 );
	$ok = 1; 
	if( $ins == 1 || $ins == -1 ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Menu and categories already installed', 'viralpress' ).'</p></div>';
	}
	else if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp_install_demo' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->register_categories();
		$vp_instance->load_page_definitions();
		$vp_instance->create_menus();	
		echo '<div class="updated"><p>'.__( 'Menu and categories successfully installed. Please activate ViralPress login and menu using the form below.', 'viralpress' ).'</p></div>';
	}	
}	
else if ( isset( $_REQUEST['install_menu'] ) ) {
	$ok = 1;
	if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'install_menu' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->load_page_definitions();
		$vp_instance->create_menus();	
		echo '<div class="updated"><p>'.__( 'Menu successfully installed. Please activate ViralPress login and menu using the form below.', 'viralpress' ).'</p></div>';
	}
}
else if ( isset( $_REQUEST['delete_menu'] ) ) {
	$ok = 1;
	if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'delete_menu' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->load_page_definitions();
		$vp_instance->delete_menus();	
		echo '<div class="updated"><p>'.__( 'Menu successfully deleted.', 'viralpress' ).'</p></div>';
	}
}
else if ( isset( $_REQUEST['install_cat'] ) ) {
	$ok = 1;
	if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'install_cat' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->register_categories();
		echo '<div class="updated"><p>'.__( 'Categories successfully installed.', 'viralpress' ).'</p></div>';
	}
}

$ins = get_option( 'vp-demo-installed', -1 );
?>
<div id="wpbody-content">
    <div id="welcome-panel" class="welcome-panel">
        <div class="welcome-panel-content">
            <h2><?php _e( 'Welcome to ViralPress!', 'viralpress' )?></h2>
            <p class="about-description"><?php _e( 'Turn your website into a viral content sharing platform!', 'viralpress' )?></p>
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column" style="width:25% !important">
                    <h3><?php _e( 'Get Started', 'viralpress' )?></h3>
                    <?php if( $ins == 0 ):?>
                    <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'vp_install_demo', '_nonce' );?>"> 
                    <button class="button button-primary button-hero">
                        <?php _e( 'Install Categories & Menu', 'viralpress' )?>
                    </button>
                    <input type="hidden" name="install_demo" value="1" />
                    </form>
                    <?php else:?>
                    <a class="button button-primary button-hero" href="#vp-config">
                        <?php _e( 'Configure ViralPress', 'viralpress' )?>
                    </a>
                    <?php endif;?>
                    <ul>
                        <li>
                            <a href="options-permalink.php" class="welcome-icon welcome-write-blog">
                                <?php _e( 'Update permalink structure', 'viralpress' )?>
                            </a>
                        </li>
                        <li>
                            <div class="welcome-icon welcome-widgets-menus">
                                <?php _e( 'Update', 'viralpress' )?> 
                                <a href="widgets.php"><?php _e( 'widgets', 'viralpress' )?></a>
                                <?php _e( 'or', 'viralpress' )?> <a href="nav-menus.php"><?php _e( 'menus', 'viralpress' )?>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="welcome-panel-column" style="width:25% !important">
                    <h3><?php _e( 'Next Steps', 'viralpress' )?></h3>
                    <?php if( $ins == 0 ):?>
                    <a class="button button-secondary button-hero" href="#vp-config">
                        <?php _e( 'Configure ViralPress', 'viralpress' )?>
                    </a>
					<?php else:?>
                    <a class="button button-secondary button-hero" href="https://drive.google.com/file/d/0B34QQcRSxhm2Sm9rOVE2MW9MWDQ/view" target="_blank">
                        <?php _e( 'Read the documentation', 'viralpress' )?>
                    </a>
					<?php endif;?>
                    <ul>
                        <li>
                            <a href="<?php echo home_url( '/create/' ).'?type=news'?>" class="welcome-icon welcome-write-blog">
                                <?php _e( 'Write your first viral post', 'viralpress' )?>
                            </a>
                        </li>
                        <li><a href="options-discussion.php" class="welcome-icon welcome-comments">Turn comments on or off</a></li>
                    </ul>
                </div>
                <div class="welcome-panel-column" style="width:25% !important">
                    <h3><?php _e( 'More Actions', 'viralpress' )?></h3>
                    <?php if( $ins == 0 ) : ?>
                    <a class="button button-primary button-hero" href="https://drive.google.com/file/d/0B34QQcRSxhm2Sm9rOVE2MW9MWDQ/view" target="_blank">
                        <?php _e( 'Read the documentation', 'viralpress' )?>
                    </a>
               		<?php else:?>
                    <a class="button button-primary button-hero" href="http://codecanyon.net/user/inspireddev/portfolio?ref=inspireddev" target="_blank">
                        <?php _e( 'Visit the item page', 'viralpress' )?>
                    </a>
                    <?php endif;?>
                     <ul>
                        <li><a href="<?php echo home_url( '/' )?>" class="welcome-icon welcome-view-site"><?php _e( 'View your site', 'viralpress' )?></a></li>
                        <li><a href="theme.php" class="welcome-icon welcome-view-site"><?php _e( 'Update theme', 'viralpress' )?></a></li>
                    </ul>
                </div>
                <div class="welcome-panel-column" style="width:25% !important">
                    <h3><?php _e( 'Link to viral editor', 'viralpress' )?></h3>
                    <br/>    
                    <div style="float:left">                
                     	<ul>
                        <li><a href="<?php echo home_url( '/create?type=news' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create news', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=list' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create list', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=poll' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create poll', 'viralpress' )?></a></li>
                    	</ul>
                    </div>
                    <div>
                    	<ul>                          
                        <li><a href="<?php echo home_url( '/create?type=quiz' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create quiz', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=video' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create video', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=gallery' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create gallery', 'viralpress' )?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrap" id="vp-config">
    	<?php if ( vp_check_license() == - 1 ) :?>
        <div class="error">
        	<p>
            	<?php _e( sprintf( 'ViralPress license is not activated yet. Activate it %s here %s', '<a href="' . admin_url( 'admin.php?page=viralpress-update' ) . '">', '</a>' ), 'viralpress' );?>
            </p>
        </div>
        <?php endif;?>
        <br/>
        <h1><?php _e( 'Viralpress Configurations', 'viralpress' )?></h1>
        <?php
        if( !empty($_POST['vp_save_config']) ) {
          
            if ( empty( $_REQUEST['_nonce'] ) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp-admin-action-'.get_current_user_id() ) ) {
                echo '<div class="error"><p>'. __( 'Failed to validate request. Please try again' , 'viralpress' ). '</p></div>';
            }
            else {
				if( $tab == 'general' ) {
					$auto_publish = @(int)$_POST['auto_publish'];
					update_option( 'vp-auto-publish-post', $auto_publish );
					
					$custom_profiles = @(int)$_POST['custom_profiles'];	
					update_option( 'vp-custom-profiles', $custom_profiles );
					
					$show_menu = @(int)$_POST['show_menu'];	
					update_option( 'vp-show-menu', $show_menu );
					
					$show_menu_on = @$_POST['show_menu_on'];	
					if( !in_array( $show_menu_on, array( 'both', 'primary', 'secondary' ) ) ) $show_menu_on = 'both';
					update_option( 'vp-show-menu-on', $show_menu_on );
					
					$block_admin = @(int)$_POST['block_admin'];	
					update_option( 'vp-block-admin', $block_admin );
					
					$block_edits = @(int)$_POST['block_edits'];	
					update_option( 'vp-block-edits', $block_edits );
					
					$use_category = @(int)$_POST['use_category'];	
					update_option( 'vp-use-category', $use_category );	
					
					$show_reactions = @(int)$_POST['show_reactions'];	
               	 	update_option( 'vp-show-reactions', $show_reactions );
					
					$show_like_dislike = @(int)$_POST['show_like_dislike'];	
               	 	update_option( 'vp-show-like-dislike', $show_like_dislike );
					
					$anon_votes = @(int)$_POST['anon_votes'];	
					update_option( 'vp-anon-votes', $anon_votes );
					
					$share_quiz_force = @(int)$_POST['share_quiz_force'];	
					update_option( 'vp-share-quiz-force', $share_quiz_force );
					
					$hotlink_image = @(int)$_POST['hotlink_image'];	
					update_option( 'vp-hotlink-image', $hotlink_image );
				}
				
				else if( $tab == 'social' ) {
					$fb_app_id = esc_html( $_POST['fb_app_id'] );
					update_option( 'vp-fb-app-id', $fb_app_id );
					
					$google_api_key = esc_html( $_POST['google_api_key'] );
					update_option( 'vp-google-api-key', $google_api_key );
					
					$google_oauth_id = esc_html( $_POST['google_oauth_id'] );
					update_option( 'vp-google-oauth-id', $google_oauth_id );
                
					$fb_comments = @(int)$_POST['fb_comments'];	
					update_option( 'vp-show-fb-comments', $fb_comments );
					
					$share_buttons = @(int)$_POST['share_buttons'];	
                	update_option( 'vp-share-buttons', $share_buttons );
					
					$comments_per_list = @(int)$_POST['comments_per_list'];	
                	update_option( 'vp-comments-per-list', $comments_per_list );
				}
				
				else if( $tab == 'editor' ) {
				
					$only_admin = @(int)$_POST['only_admin'];	
                	update_option( 'vp-only-admin', $only_admin );
					
					$allow_copy = @(int)$_POST['allow_copy'];	
					update_option( 'vp-allow-copy', $allow_copy );
					
					$allow_open_list = @(int)$_POST['allow_open_list'];	
					update_option( 'vp-allow-open-list', $allow_open_list );
				
				}
				
				else if( $tab == 'captcha' ) {				
					$recap_key = @$_POST['recap_key'];	
					update_option( 'vp-recap-key', $recap_key );
					
					$recap_secret = @$_POST['recap_secret'];	
					update_option( 'vp-recap-secret', $recap_secret );
					
					$recap_login = @(int)$_POST['recap_login'];	
					update_option( 'vp-recap-login', $recap_login );
					
					$recap_post = @(int)$_POST['recap_post'];	
					update_option( 'vp-recap-post', $recap_post );
				}
				
				else if( $tab == 'modules' ) {		
					$list_enabled = @(int)$_POST['list_enabled'];	
                	update_option( 'vp-allow-list', $list_enabled );
					
					$quiz_enabled = @(int)$_POST['quiz_enabled'];	
                	update_option( 'vp-allow-quiz', $quiz_enabled );
					
					$poll_enabled = @(int)$_POST['poll_enabled'];	
                	update_option( 'vp-allow-poll', $poll_enabled );
					
					$disable_login = @(int)$_POST['disable_login'];	
					update_option( 'vp-disable-login', !$disable_login );
					
					$vp_bp = @(int)$_POST['vp_bp'];	
					update_option( 'vp-bp-int', $vp_bp );
					
					$vp_mycred = @(int)$_POST['vp_mycred'];	
					update_option( 'vp-mycred-int', $vp_mycred );
					
					$self_video = @(int)$_POST['self_video'];	
					update_option( 'vp-self-video', $self_video );
					
					$self_audio = @(int)$_POST['self_audio'];	
					update_option( 'vp-self-audio', $self_audio );
				}
				
				else if( $tab == 'embed' ) {
					$embeds = @$_POST['allowed_embeds'];
					update_option( 'vp-allowed-embeds', $embeds );	
				}
				
				else if( $tab == 'gif' ) {
					$gifs = $_POST['react_gif']['url'];
					if( empty( $gifs ) ) {
						update_option( 'vp-react-gifs', '' );	
					}
					else {
						$gifs_new = array();
						foreach( $gifs as $i => $b ) {
							if( !empty( $b ) ) {
								$arr = array();
								$arr['url']	= esc_url( $b, array( 'http', 'https' ) );;
								$arr['caption']	= esc_html( $_POST['react_gif']['caption'][$i] );
								$arr['static']	= esc_url( $_POST['react_gif']['static'][$i], array( 'http', 'https' ) );
								
								$gifs_new[] = $arr;
							}	
						}
						update_option( 'vp-react-gifs', json_encode( $gifs_new ) );	
					}
				};
							
				if( !empty( $use_category ) ) {
					if( get_option( 'vp-type-cat-installed', -1 ) == - 1 ) {
						$vp_instance->register_type_categories();	
					}	
				}
				
                $vp_instance->load_settings();
                
                echo '<div class="updated"><p>'.__( 'Settings saved', 'viralpress' ).'</p></div>';
            }
        }
		
        $settings = $vp_instance->settings;
    ?>
    
    	<h2 class="nav-tab-wrapper">
            <a href="?page=viralpress&tab=general" class="nav-tab <?php echo $tab == 'general' ? 'nav-tab-active' : ''?>"><?php _e( 'General settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=social" class="nav-tab <?php echo $tab == 'social' ? 'nav-tab-active' : ''?>"><?php _e( 'Social settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=captcha" class="nav-tab <?php echo $tab == 'captcha' ? 'nav-tab-active' : ''?>"><?php _e( 'Captcha settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=editor" class="nav-tab <?php echo $tab == 'editor' ? 'nav-tab-active' : ''?>"><?php _e( 'Editor settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=modules" class="nav-tab <?php echo $tab == 'modules' ? 'nav-tab-active' : ''?>"><?php _e( 'Module settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=demo" class="nav-tab <?php echo $tab == 'demo' ? 'nav-tab-active' : ''?>"><?php _e( 'Manage category & menu', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=gif" class="nav-tab <?php echo $tab == 'gif' ? 'nav-tab-active' : ''?>"><?php _e( 'Gif Reactions', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=embed" class="nav-tab <?php echo $tab == 'embed' ? 'nav-tab-active' : ''?>"><?php _e( 'Embed settings', 'viralpress' ) ?></a>
        </h2>
        
        <div class="clear"></div><div class="clear"></div>
        
        <?php
		if( $tab == 'demo' ) {
		?>
        <h2><?php _e( 'Install menu', 'viralpress' )?></h2>
        <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'install_menu', '_nonce' );?>"> 
            <button class="button button-primary button-hero" onclick="return confirm('<?php _e( 'WARNING: If you already have menu installed, performing this action will make duplicate entries on menu. Please proceed with caution. NOTE: This action will take few minutes to complete.', 'viralpress' )?>');">
                <?php _e( 'Install Menu', 'viralpress' )?>
            </button>
            <input type="hidden" name="install_menu" value="1"/>
        </form>
        <h2><?php _e( 'Install categories', 'viralpress' )?></h2>
        <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'install_cat', '_nonce' );?>"> 
            <button class="button button-secondary button-hero">
                <?php _e( 'Install Categories', 'viralpress' )?>
            </button>
            <input type="hidden" name="install_cat" value="1"/>
        </form>
        <h2><?php _e( 'Delete menu', 'viralpress' )?></h2>
        <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'delete_menu', '_nonce' );?>"> 
            <button class="button button-primary button-hero" onclick="return confirm('<?php _e( 'WARNING: Are you sure to delete ViralPress menu? NOTE: This action will take few minutes to complete.', 'viralpress' )?>');">
                <?php _e( 'Delete Menu', 'viralpress' )?>
            </button>
            <input type="hidden" name="delete_menu" value="1"/>
        </form>
        <br/><br/>
        <div><?php _e( 'Note: To reinstall the menu, delete first and then install again.', 'viralpress' )?></div>
        <?php		
		}else{
		?>
        
        <form method="post">
            <?php wp_nonce_field( 'vp-admin-action-'.get_current_user_id(), '_nonce' ); ?>
            <input type="hidden" name="vp_save_config" value="1"/>
            <table class="form-table">
            	<?php if( $tab == 'general' ) : ?> 
                <tr>
                    <th scope="row">
                        <label for="auto_publish"><?php _e( 'Publication', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="auto_publish" id="auto_publish" value="1" <?php if( $settings['auto_publish'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="auto_publish"><?php _e( 'Auto publish user submitted posts', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_reactions"><?php _e( 'Post Reactions', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_reactions" id="show_reactions" value="1" <?php if( $settings['show_reactions'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="show_reactions"><?php _e( 'Show reaction buttons under each post', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_like_dislike"><?php _e( 'Use like/dislike buttons', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_like_dislike" id="show_like_dislike" value="1" <?php if( $settings['show_like_dislike'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="show_like_dislike"><?php _e( 'Show likes/dislikes buttons instead of up/down votes for individual list items', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="custom_profiles"><?php _e( 'Custom Author Profiles', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="custom_profiles" id="custom_profiles" value="1" <?php if( $settings['custom_profiles'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="custom_profiles"><?php _e( 'Replace default author page with custom author page of ViralPress', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_menu"><?php _e( 'Show ViralPress Menu', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_menu" id="show_menu" value="1" <?php if( $settings['show_menu'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="show_menu"><?php _e( 'This will replace your wordpress menu with viralpress menu', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="block_admin"><?php _e( 'Block Admin Panel Access', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="block_admin" id="block_admin" value="1" <?php if( $settings['block_admin'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="block_admin"><?php _e( 'Block contributors from admin panel. Highly recommended to keep this option enabled.', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="block_edits"><?php _e( 'Disallow approved post edits', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="block_edits" id="block_edits" value="1" <?php if( $settings['block_edits'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="block_edits"><?php _e( 'Block contributors from editing or deleting approved posts', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="use_category"><?php _e( 'Save Viral Posts into Categories', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="use_category" id="use_category" value="1" <?php if( $settings['use_category'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="use_category"><?php _e( 'If checked each type of viral posts will be saved into a category with the same name as their type', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_menu_on"><?php _e( 'Show menu on', 'viralpress' )?></label>
                    </th>
                    <td>
                        <select id="show_menu_on" name="show_menu_on">
                        	<option value="both" <?php if( $settings['show_menu_on'] == 'both' ) echo "selected='selected'"?>>Both</option>
                            <option value="primary" <?php if( $settings['show_menu_on'] == 'primary' ) echo "selected='selected'"?>>Primary</option>
                            <option value="secondary" <?php if( $settings['show_menu_on'] == 'secondary' ) echo "selected='selected'"?>>Secondary</option>
                        </select>&nbsp;
                        <label for="show_menu_on"><?php _e( 'Choose on which menu viralpress menu will be active. If you choose secondary menu your theme must support it', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="anon_votes"><?php _e( 'Allow anonymous poll votes and reactions', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="anon_votes" id="anon_votes" value="1" <?php if( $settings['anon_votes'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="anon_votes"><?php _e( 'If checked guests can cast votes on polls and react to posts', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="share_quiz_force"><?php _e( 'Share quiz before seeing result', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="share_quiz_force" id="share_quiz_force" value="1" <?php if( $settings['share_quiz_force'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="share_quiz_force"><?php _e( 'If checked users must share quiz to view their result. Only facebook share available on this mode', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="hotlink_image"><?php _e( 'Allow hotlinking in editor', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="hotlink_image" id="hotlink_image" value="1" <?php if( $settings['hotlink_image'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="hotlink_image"><?php _e( 'If checked external images added from link will be hotlinked directly without downloading to your server', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'social' ) : ?>
                <tr>
                    <th scope="row">
                        <label for="fb_app_id"><?php _e( 'Facebook App Id', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['fb_app_id']?>" id="fb_app_id" name="fb_app_id" class="regular-text"> 
                        <p class="description">Must be filled to enable facebook login.</p>   
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="google_api_key"><?php _e( 'Google Api Key', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['google_api_key']?>" id="google_api_key" name="google_api_key" class="regular-text">
                        <p class="description"><?php _e( 'Must be filled to enable google login.<br/>Make sure to enable google plus api in your app.', 'viralpress' )?></p>
                   </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="google_oauth_id"><?php _e( 'Google OAuth Id', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['google_oauth_id']?>" id="google_oauth_id" name="google_oauth_id" class="regular-text">
                        <p class="description"><?php _e( 'Must be filled to enable google login.<br/>Make sure to enable google plus api in your app.', 'viralpress' )?></p>
                    </td>
                </tr>
				<tr>
                    <th scope="row">
                        <label for="fb_comments"><?php _e( 'Facebook Comments', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="fb_comments" id="fb_comments" value="1" <?php if( $settings['fb_comments'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="fb_comments"><?php _e( 'Add facebook comments plugin with each post', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="comments_per_list"><?php _e( 'Display seperate facebook comments per list', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="comments_per_list" id="comments_per_list" value="1" <?php if( $settings['comments_per_list'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="comments_per_list"><?php _e( 'If checked each list will display seperate facebook comments box. Exception: polls and quizzes', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="share_buttons"><?php _e( 'Share Buttons', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="share_buttons" id="share_buttons" value="1" <?php if( $settings['share_buttons'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="share_buttons"><?php _e( 'Show social share buttons under each post', 'viralpress' )?></label>
                    </td>
                </tr>
				<?php endif;?>
                 
                <?php if( $tab == 'captcha' ) : ?>
                <tr>
                    <th scope="row">
                        <label for="recap_key"><?php _e( 'Recaptcha Public Key', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['recap_key']?>" id="recap_key" name="recap_key" class="regular-text"> 
                        <p class="description"><?php _e( 'Must be filled to enable captcha protection.', 'viralpress' )?></p>   
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="recap_secret"><?php _e( 'Recaptcha Secret', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['recap_secret']?>" id="recap_secret" name="recap_secret" class="regular-text"> 
                        <p class="description"><?php _e( 'Must be filled to enable captcha protection.', 'viralpress' )?></p>   
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="recap_login"><?php _e( 'Captcha on signup', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="recap_login" id="recap_login" value="1" <?php if( $settings['recap_login'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="recap_login"><?php _e( 'If checked captcha challenge will be shown on signup', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="recap_post"><?php _e( 'Captcha on editor', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="recap_post" id="recap_post" value="1" <?php if( $settings['recap_post'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="recap_post"><?php _e( 'If checked user must solve captcha before submitting new post', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                
                <?php if( $tab == 'editor' ):?>
                <tr>
                    <th scope="row">
                        <label for="only_admin"><?php _e( 'Allow only admins', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="only_admin" id="only_admin" value="1" <?php if( $settings['only_admin'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="only_admin"><?php _e( 'If checked ViralPress editor can only be accessed by admins and editors', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="allow_copy"><?php _e( 'Allow copying viral posts by other users', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="allow_copy" id="allow_copy" value="1" <?php if( $settings['allow_copy'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="allow_copy"><?php _e( 'If checked any user can copy others list and save on their own name when the list owner checks the option to allow copy.', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="allow_open_list"><?php _e( 'Allow open list', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="allow_open_list" id="allow_open_list" value="1" <?php if( $settings['allow_open_list'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="allow_open_list"><?php _e( 'If checked any user can contribute to list that has been submitted as open list. Submitted lists will be queued for approval from admins. Exception: polls and quizzes', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'modules' ) : ?> 
                <tr>
                    <th scope="row">
                        <label for="list_enabled"><?php _e( 'List enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="list_enabled" id="list_enabled" value="1" <?php if( $settings['list_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="list_enabled"><?php _e( 'Indicates whether list item submission is enabled or disabled. List includes image, news, audio, video and embed', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="quiz_enabled"><?php _e( 'Quiz enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="quiz_enabled" id="quiz_enabled" value="1" <?php if( $settings['quiz_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="quiz_enabled"><?php _e( 'Indicates whether quiz submission is enabled or disabled', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="poll_enabled"><?php _e( 'Poll enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="poll_enabled" id="poll_enabled" value="1" <?php if( $settings['poll_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="poll_enabled"><?php _e( 'Indicates whether poll submission is enabled or disabled', 'viralpress' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="disable_login"><?php _e( 'ViralPress login system', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="disable_login" id="disable_login" value="1" <?php if( !$settings['disable_login'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="disable_login"><?php _e( 'Disable if you want to use your existing login system. ViralPress login, logout, registration and recovery pages will not work.', 'viralpress' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="vp_bp"><?php _e( 'BuddyPress Integration', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="vp_bp" id="vp_bp" value="1" <?php if( $settings['vp_bp'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="vp_bp"><?php _e( 'Uncheck to disable ViralPress notifications, post tabs from BuddyPress.', 'viralpress' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="vp_mycred"><?php _e( 'myCRED Integration', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="vp_mycred" id="vp_mycred" value="1" <?php if( $settings['vp_mycred'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="vp_mycred"><?php _e( 'Uncheck to disable ViralPress hooks for myCRED.', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="self_video"><?php _e( 'Self hosted video', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="self_video" id="self_video" value="1" <?php if( $settings['self_video'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="self_video"><?php _e( 'Uncheck to disable self hosted video uploads', 'viralpress' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="self_audio"><?php _e( 'Self hosted audio', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="self_audio" id="self_audio" value="1" <?php if( $settings['self_audio'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="self_audio"><?php _e( 'Uncheck to disable self hosted audio uploads', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'gif' ) : ?>
                <tr>
                    <th scope="row">
                        <label for="disable_login"><?php _e( 'Gif Reactions', 'viralpress' )?></label>
                    </th>
                    <td>
                    	<div class="gifs">
                        <?php
						if( !empty( $vp_instance->settings['react_gifs'] ) ) {
							$gifs = json_decode( $vp_instance->settings['react_gifs'], true );
							$gg = array();
							foreach( $gifs as $g ) {
								echo '<div class="gif_row">
								'.__( 'GIF URL:', 'viralpress' ).'<input type="text" name="react_gif[url][]" value="'.$g['url'].'"/>
								'.__( 'Caption:', 'viralpress' ).'<input type="text" name="react_gif[caption][]" value="'.$g['caption'].'"/>
								'.__( 'Static Image URL:', 'viralpress' ).'<input type="text" name="react_gif[static][]" value="'.$g['static'].'"/>
								<a href="javascript:void(0)" onclick="jQuery(this).parents(\'.gif_row:first\').remove()">'.__( 'Remove', 'viralpress' ).'</a>
								<br/></div>
								';	
							}
						}
						?>
                        </div>
                        <br/>
                        <a href="javascript:void(0)" class="add_new_gif"><?php _e( 'Add new+', 'viralpress' )?></a>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'embed' ) : ?>
                
                <tr>
                    <th scope="row">
                        <label for="disable_login"><?php _e( 'Allowed external embeds', 'viralpress' )?></label>
                    </th>
                    <td>
                        <textarea rows="10" cols="50" type="checkbox" name="allowed_embeds" id="allowed_embeds"><?php echo @$settings['allowed_embeds']?></textarea>&nbsp;
                        <br/>
                        <label for="allowed_embeds"><?php _e( 'Put additional domain names you want to allow to be embeded. <br/> Only domain name. No URL please. Seperate them by comma <br/>Popular sites like youtube, facebook, bbc, twitter, youtube etc. are already allowed by default.', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
            </table> 
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'viralpress' )?>"  /></p>   	
        </form>
        <?php }?>
    </div>
</div>
<script>
$ = jQuery;
$('.add_new_gif').click( function(){
	h = '<div class="gif_row">'+
		'<?php _e( 'GIF URL:', 'viralpress' )?><input type="text" name="react_gif[url][]" value=""/>&nbsp;'+
		'<?php _e( 'Caption:', 'viralpress' )?><input type="text" name="react_gif[caption][]" value=""/>&nbsp;'+
		'<?php _e( 'Static Image URL:', 'viralpress' )?><input type="text" name="react_gif[static][]" value=""/>&nbsp;'+
		'<a href="javascript:void(0)" onclick="jQuery(this).parents(\'.gif_row:first\').remove()"><?php _e( 'Remove', 'viralpress' )?></a>'+
		'<br/></div>';	
	$('.gifs').append(h);
});
</script>