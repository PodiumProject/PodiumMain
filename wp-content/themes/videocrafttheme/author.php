<?php
/**
 * The template for displaying Author pages.
 *
 */
?>
<?php get_header(); ?>
<div class="page_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-content">
        <div class="grid_18 alpha">
            <div class="content">
			<div class="page-heading">
		<?php if (have_posts()) : the_post(); ?>
		<h1 class="page-title"><?php printf(__('Author Archives: %s', 'golden_eagle'), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url(get_the_author_meta('ID')) . "' title='" . esc_attr(get_the_author()) . "' rel='me'>" . get_the_author() . "</a></span>"); ?></h1>
      </div>
              <div class="video_cat_list">
			<ul class="fthumbnail">
               <?php
                /* Queue the first post, that way we know who
                 * the author is when we try to get their name,
                 * URL, description, avatar, etc.
                 *
                 * We reset this later so we can run the loop
                 * properly with a call to rewind_posts().
                 */
                if (have_posts())
                    the_post();
                ?>
                <?php
// If a user has filled out their description, show a bio on their entries.
                if (get_the_author_meta('description')) :
                    ?>
                    <div id="entry-author-info">
                        <div id="author-avatar"> <?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('inkthemes_author_bio_avatar_size', 60)); ?> </div>
                        <!-- #author-avatar -->
                        <div id="author-description">
                            <h2><?php printf(__('About %s', 'videocraft'), get_the_author()); ?></h2>
                            <?php the_author_meta('description'); ?>
                        </div>
                        <!-- #author-description	-->
                    </div>
                    <!-- #entry-author-info -->
                <?php endif; ?>
                <?php
                /* Since we called the_post() above, we need to
                 * rewind the loop back to the beginning that way
                 * we can run the loop properly, in full.
                 */
                rewind_posts();
                /* Run the loop for the author archive page to output the authors posts
                 * If you want to overload this in a child theme then include a file
                 * called loop-author.php and that will be used instead.
                 */                
                ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<!--post start-->
				<li><span class="videobox" >							
				<span class="author"><?php $auth = the_author('', '', FALSE);
				echo substr($auth, 0, 14);
                                if (strlen($auth) > 14)
                                    echo "...";
				?>
				</span>
				<span class="views"><?php echo getPostViews(get_the_ID()); ?></span>
                  <?php	require ('video-front-thumb.php');  ?>
				  <h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                                <?php
                                $tit = the_title('', '', FALSE);
                                echo substr($tit, 0, 50);
                                if (strlen($tit) > 50)
                                    echo "...";
                                ?>
                            </a></h6>						
                  </span> </li>	
            <!--End Post-->
			<?php endwhile;
else: ?>
    <div class="post">
        <p>
            <?php _e('Sorry, no posts matched your criteria.', 'videocraft'); ?>
        </p>
    </div>
<?php endif; ?>
<!--End Loop-->
                <div class="clear"></div>
               <?php inkthemes_pagination(); ?>
            <?php endif; ?>
                <?php
				   wp_reset_query();
				// Reset Post Data
                wp_reset_postdata();
                ?>
				 </ul>
			    </div>
                <div class="clear"></div>
          </div>
        </div>
        <div class="grid_6 omega">
          <!--Start Sidebar-->
          <?php get_sidebar(); ?>
           <!--End Sidebar-->
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
  <?php get_footer(); ?>