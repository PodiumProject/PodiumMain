<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
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
          <h1 class="page_title"><span class="arrow">
                    <?php if (is_day()) : ?>
                        <?php printf(__('Daily Archives: %s', 'videocraft'), get_the_date()); ?>
                    <?php elseif (is_month()) : ?>
                        <?php printf(__('Monthly Archives: %s', 'videocraft'), get_the_date('F Y')); ?>
                    <?php elseif (is_year()) : ?>
                        <?php printf(__('Yearly Archives: %s', 'videocraft'), get_the_date('Y')); ?>
                    <?php else : ?>
                        <?php _e('Blog Archives', 'videocraft'); ?>
                    <?php endif; ?>
                </span></h1> 		
      </div>
           <div class="video_cat_list">
			<ul class="fthumbnail">
                   <?php
                /* Since we called the_post() above, we need to
                 * rewind the loop back to the beginning that way
                 * we can run the loop properly, in full.
                 */
                rewind_posts();
                /* Run the loop for the archives page to output the posts.
                 * If you want to overload this in a child theme then include a file
                 * called loop-archives.php and that will be used instead.
                 */
                ?>
<?php // The Query
query_posts( array( 'posts_per_page' => 50,'post_type' => 'video_listing') );
while (have_posts()) :the_post();
		?>
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
                <?php
                endwhile;
				// Reset Post Data
                wp_reset_postdata();
                ?>   
				</ul>
			    </div>
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