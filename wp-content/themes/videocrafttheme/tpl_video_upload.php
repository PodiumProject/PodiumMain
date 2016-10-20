<?php
/**
* Template Name: Submit Video
* 
* @package VideoCast
* since 1.0
*  
*/
get_header();
?>
<div class="page_container">
<div class="container_24">
<div class="grid_24">
<div class="page-content">
	<div class="grid_18 alpha">
		<div class="content-bar">
		  <div class="page-heading">
        <h1><span class="arrow"><?php the_title(); ?></span></h1>		
      </div>
		<div class="upload-page">
		<?php		
			if (is_user_logged_in()) {	
			videocast_submit_video();
				?>
			<?php
			} else {			
				//call login form
                    videocast_login_form();
                    //call registration form
					$redirect = inkthemes_get_option('user_redirect');
                    videocast_register_form($redirect);
					wp_footer();
			}				
			?>
		</div>
		</div>
	</div>
	<div class="grid_6 omega">
<?php get_sidebar(); ?>
</div>
</div>
</div>
<div class="clear"></div>
</div>
</div>

<?php get_footer(); ?>