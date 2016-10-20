<div class="grid_19 alpha">
          <div class="footer_top">
		  <?php if (is_active_sidebar('first-footer-widget-area')) : ?>
            <?php dynamic_sidebar('first-footer-widget-area'); ?>
        <?php else : ?>
         <?php inkthemes_cat_nav(); ?> 
			 <?php endif; ?>
          </div>
        </div>
        <div class="grid_5 omega">
	  <div class="footer_toplogo">
	 <?php if (is_active_sidebar('second-footer-widget-area')) : ?>
            <?php dynamic_sidebar('second-footer-widget-area'); ?>
        <?php else : ?> 
	  <a href="<?php echo home_url(); ?>" ><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png"></a>
	  <?php endif; ?> 
	  </div>
        </div>