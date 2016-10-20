<?php
/**
 * The template for displaying Category pages.
 *
 */
?>
<?php get_header(); ?>
<div class="content_wrapper">
  <div class="container_24">
    <div class="grid_24">
      <div class="frontpage-content">
        <div class="grid_18 alpha">
          <div class="content category">
		    <div class="page-heading">
        <h1 class="page-title"><span class="arrow"><?php printf(__('All Videos In: %s Category' , 'local-business'), '' . single_cat_title('', false) . ''); ?></span></h1>
      </div>
		    <div class="video_cat_list">
			<ul class="fthumbnail">
          <?php if (have_posts()) : ?>
                <?php
                $category_description = category_description();
                if (!empty($category_description))
                    echo '' . $category_description . '';
                /* Run the loop for the category page to output the posts.
                 * If you want to overload this in a child theme then include a file
                 * called loop-category.php and that will be used instead.
                 */
                ?>
                      <!-- Start the Loop. -->
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
          </div>
        </div>
        <div class="grid_6 omega">
          <!--Start Sidebar-->
           <?php get_sidebar('category'); ?>
          <!--End Sidebar-->
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php get_footer(); ?>
