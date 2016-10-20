<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
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
        <h1><span class="arrow">
            <?php
			printf( __( 'Video Tag Archives: %s', 'VideoCast' ), '' . single_tag_title( '', false ) . '' ); ?>
            </span></h1>
      </div>
		    <div class="video_cat_list">
			<ul class="fthumbnail">
          <?php if (have_posts()) : ?>
                      <!-- Start the Loop. -->
				<?php	
global $cat_name;
$cat_name = @ $GLOBALS['video_cat'];
$limit = 2;
$post_type = 'video_listing';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$wp_query->is_archive = true;
$wp_query->is_home = false;
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