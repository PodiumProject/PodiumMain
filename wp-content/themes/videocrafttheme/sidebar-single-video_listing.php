<?php
/**
 * Sidebar widget for front page 
 */
?>
<div class="sidebar">
    <?php
    if (is_active_sidebar('single-video-widget-area')):
    dynamic_sidebar('single-video-widget-area');
endif;
    ?>
	 <div id="tabs">
		  <div id="tab-4">  
                    <ol>
           <?php //wp_tag_cloud( array( 'taxonomy' => 'video_tag', format => 'text' ) );		?>
                    </ol>
            </div>
            <div id="tab-1" class="widget-post">
                <ul>
                <?php // The Query	
				$functions_path = TEMPLATEPATH . '/functions/';			
				 global $post;
     $vid_tax = get_the_terms( $post->ID, 'video_cat');
	 	if($vid_tax == ''){ 
		$vid_tax = 'none';	
	   $args = array('post_type' => 'video_listing', 'post__not_in' => array($post->ID), 'caller_get_posts' => 1, 'orderby' => 'rand', 'showposts'=>10, 'video_cat' => "$vid_tax" );	
	 } else{
	 foreach ( $vid_tax as $tex_name ) {
        $tax_name = $tex_name->name;
	$args = array('post_type' => 'video_listing', 		'post__not_in' => array($post->ID), 'caller_get_posts' => 1, 'orderby' => 'rand', 'showposts'=>10, 'video_cat' => "$tax_name" );
    }
	}
	$loop = new WP_Query($args);
    while ($loop->have_posts()) :$loop->the_post();
	$vid = get_post_meta($post->ID, '_video_url', true);                    
		?>
     <li>
     <p>
       <?php	
	  require( $functions_path . '/widget/video-front-thumb.php');
		?>
        <h6><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
        <?php
        $tit = the_title('', '', FALSE);
       echo substr($tit, 0, 50);
       if (strlen($tit) > 50)
       echo "...";
       ?>
           </a></h6>                   
                    </p>
                    </li>
                    <?php
                endwhile;
// Reset Post Data
                wp_reset_postdata();
                ?>
                    </ul>
           </div>
        </div> 	