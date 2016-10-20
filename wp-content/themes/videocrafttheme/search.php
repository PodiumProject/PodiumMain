<?php
/**
 * The template for displaying Search Results pages.
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
         <h1 class="page-title"><span class="arrow"><?php printf(__('Search Results for: %s', 'videocraft'), '' . get_search_query() . ''); ?></span></h1>
      </div>
			<div class="video_cat_list">
			<ul class="fthumbnail">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>               
                <!--Start Post-->
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
                <article id="post-0" class="post no-results not-found">
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <?php _e('Nothing Found', 'videocraft'); ?>
                        </h1>
                    </header>
                    <!-- .entry-header -->
                    <div class="entry-content">
                        <p>
                            <?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'videocraft'); ?>
                        </p>
                        <?php get_search_form(); ?>
                    </div>
                    <!-- .entry-content -->
                </article>
            <?php endif; ?>
			</ul>
			</div>
            <div class="clear"></div>
            <nav id="nav-single"> <span class="nav-previous">
                    <?php next_posts_link(__('&larr; Older posts', 'videocraft')); ?>
                </span> <span class="nav-next">
                    <?php previous_posts_link(__('Newer posts &rarr;', 'videocraft')); ?>
                </span> </nav>	
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