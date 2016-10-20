<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 */
?>
<?php get_header(); ?>
<div class="heading_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-heading">
        <h1 class="entry-title"><span class="arrow">
                    <?php _e('This is somewhat embarrassing, isn&rsquo;t it?', 'videocraft'); ?>
         </span></h1>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="page_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-content">
        <div class="grid_18 alpha">
		<br/>
           <div class="content-bar">
                <?php the_content(); ?>	
				      <header class="entry-header">
            <h3 style="line-height:26px">
                <?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'videocraft'); ?>
            </h3>
			<br/>
			<img style=" border:1px solid #ccc; padding:10px; border-radius:5px;" src="<?php echo get_template_directory_uri(); ?>/images/error.jpg"/>
			<br/><br/>
            <?php get_search_form();
	    ?>
		<br/>
            <?php the_widget('WP_Widget_Recent_Posts', array('number' => 10), array('widget_id' => '404')); ?>
			<br/>
            <div class="widget">
                <h2 class="widgettitle">
                    <?php _e('Most Used Categories', 'videocraft'); ?>
                </h2>
				<br/>
                <ul>
                    <?php wp_list_categories(array( 'post_type' => 'video_listing', 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10)); ?>
                </ul>
            </div>
			<br/>
            <?php
            /* translators: %1$s: smilie */
            $archive_content = '<p>' . sprintf(__('Try looking in the monthly archives. %1$s', 'videocraft'), convert_smilies(':)')) . '</p><br/>';
            the_widget('WP_Widget_Archives', array('count' => 0, 'dropdown' => 1), array('after_title' => '</h2><br/>' . $archive_content));
            ?>
			<br/>
            <?php the_widget('WP_Widget_Tag_Cloud'); ?>
			<br/>
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