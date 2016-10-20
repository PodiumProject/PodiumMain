<div class="footer_wrapper">
  <div class="container_24">
    <div class="grid_24">
      <div class="footer_topwrapper">
        <?php
        /* A sidebar in the footer? Yep. You can can customize
         * your footer with four columns of widgets.
          */
         get_sidebar('footer');
         ?>
      </div>
    </div>
	    <div class="clear"></div>
    <div class="container_24">
    <div class="grid_24">
      <div class="footer_bottom">
        <div class="grid_10 alpha">
         <div class="social_logo">
          <ul class="fsocialicon">
		  <?php if (inkthemes_get_option('inkthemes_twitter') != '') { ?>
            <li><a href="<?php echo inkthemes_get_option('inkthemes_twitter'); ?>"><span><img src="<?php echo get_template_directory_uri(); ?>/images/twitter.png" class="twittericon">Twitter</span></a></li>
			<?php } ?>  
			 <?php if (inkthemes_get_option('inkthemes_facebook') != '') { ?>
            <li><img src="<?php echo get_template_directory_uri(); ?>/images/facebook.png" class="facebookicon"><a href="<?php echo inkthemes_get_option('inkthemes_facebook'); ?>">Facebook</a></li>
			<?php } ?>  
					   <?php if (inkthemes_get_option('inkthemes_rss') != '') { ?>
            <li><img src="<?php echo get_template_directory_uri(); ?>/images/rss.png" class="newslettericon"><a href="<?php echo inkthemes_get_option('inkthemes_rss'); ?>">RSS Feed</a></li>
				<?php } ?>
          </ul>
          </div>
        </div>
        <div class="grid_14 omega">
          <div class="copyright">
          <div class="copyrightinfo">
		  <div class="copyrightinfo">
               <?php if (inkthemes_get_option('inkthemes_footertext') != '') { ?>
			<?php echo inkthemes_get_option('inkthemes_footertext'); ?>
		<?php } else { ?>
		  VideoCraft Theme by InkThemes.com
		  <?php } ?>
		  </div>
        </div>
        </div>
      </div>
    </div>
  </div>
      <div class="clear"></div>
  </div>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
