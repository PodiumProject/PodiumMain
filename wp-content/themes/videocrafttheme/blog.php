<?php
/*
  Template Name: Blog Page
 */
?>
<?php get_header(); ?>
<div class="page_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-content">
        <div class="grid_18 alpha">
          <div class="content-bar">
		  <div class="page-heading">
        <h1><?php the_title(); ?></h1>
      </div>
		  <?php
                        $limit = get_option('posts_per_page');
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        query_posts('showposts=' . $limit . '&paged=' . $paged);
                        $wp_query->is_archive = true;
                        $wp_query->is_home = false;
                        ?>
              <!-- Start the Loop. -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 <!--post start-->
            <div class="post">
              <h1 class="post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
              <div class="post_content">
			  <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                         <?php inkthemes_get_thumbnail(675, 272); ?>
                    <?php } else { ?>
                        <?php inkthemes_get_image(675, 232); ?> 
                        <?php
                    }
                    ?>
             <?php the_excerpt(); ?>
                <a class="more" href="<?php the_permalink() ?>">More &rarr;</a> </div>
              <ul class="post_meta">
                <li class="post_date">&nbsp;&nbsp;<?php echo get_the_time('M, d, Y') ?></li>
                <li class="posted_by">&nbsp;&nbsp;<span>Author:&nbsp;</span><?php the_author_posts_link(); ?></li>
                <li class="post_category">&nbsp;&nbsp;<span>Categories:&nbsp;</span><?php the_category(', '); ?></li>
                <li class="post_comment"><?php comments_popup_link('No Comments.', '1 Comment.', '% Comments.'); ?></li>
              </ul>
            </div>
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