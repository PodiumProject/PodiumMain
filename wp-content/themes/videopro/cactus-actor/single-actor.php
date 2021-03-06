<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package cactus
 */

get_header();
$videopro_sidebar = ot_get_option('post_sidebar','right');

$videopro_sidebar = apply_filters('videopro_single_actor_sidebar', $videopro_sidebar);

$sidebar_style = 'ct-small';
videopro_global_sidebar_style($sidebar_style);
$videopro_layout = videopro_global_layout();

?>

    <div id="cactus-body-container"> <!--Add class cactus-body-container for single page-->
        <div class="cactus-sidebar-control <?php if($videopro_sidebar == 'right' || $videopro_sidebar == 'both'){?>sb-ct-medium <?php }?>  <?php if($videopro_sidebar != 'full' && $videopro_sidebar != 'right'){?>sb-ct-small <?php }?>">
        <div class="cactus-container <?php if($videopro_layout== 'wide'){ echo 'ct-default';}?>">                        	
            <div class="cactus-row">
            	<?php if($videopro_layout == 'boxed'){?>
                    <div class="open-sidebar-small open-box-menu"><i class="fa fa-bars"></i></div>
                <?php }?>
                <?php if($sidebar!='full'){ get_sidebar('left'); } ?>
                <div class="main-content-col">
                    <div class="main-content-col-body">
						<?php if(function_exists('videopro_breadcrumbs')){
                             videopro_breadcrumbs();
                        }
						
						if(is_active_sidebar('content-top-sidebar')){
							echo '<div class="content-top-sidebar-wrap">';
							dynamic_sidebar( 'content-top-sidebar' );
							echo '</div>';
						} 
						
						if ( have_posts() ) : 
							while ( have_posts() ) : the_post(); ?>
                        <div class="cactus-author-post single-actor">
                        	<?php if(has_post_thumbnail()){ ?>
                            <div class="cactus-author-pic">
                              <div class="img-content">
                                  <?php echo videopro_thumbnail( array(298,298)); ?> 
                              </div>
                            </div>
                            <?php } ?>
                          <div class="cactus-author-content">
                            <div class="author-content"> <h3 class="author-name h1"><?php the_title();?></h3>
                              <?php if(get_post_meta(get_the_ID(), 'actor-pos', true)!=''){?>
                              	<span class="author-position"><?php echo esc_html(get_post_meta(get_the_ID(), 'actor-pos', true));?></span> 
                              <?php }?>
                              <span class="author-body"><?php the_content();?></span>
                              <ul class="social-listing list-inline">
							  
                                  <?php
								  if($email = get_post_meta(get_the_ID(),'envelope',true)){ ?>
									  <li class="email"><a rel="nofollow" href="mailto:<?php echo esc_attr($email); ?>" title="<?php esc_html_e('Email', 'videopro');?>"><i class="fa fa-envelope-o"></i></a></li>
								  <?php }
								  
								  if($facebook = get_post_meta(get_the_ID(),'facebook',true)){ ?>
									  <li class="facebook"><a rel="nofollow" href="<?php echo esc_url($facebook); ?>" title="<?php esc_html_e('Facebook', 'videopro');?>"><i class="fa fa-facebook"></i></a></li>
								  <?php }
								  if($twitter = get_post_meta(get_the_ID(),'twitter',true)){ ?>
									  <li class="twitter"><a rel="nofollow" href="<?php echo esc_url($twitter); ?>" title="<?php esc_html_e('Twitter', 'videopro');?>"><i class="fa fa-twitter"></i></a></li>
								  <?php }
								  if($linkedin = get_post_meta(get_the_ID(),'linkedin',true)){ ?>
									  <li class="linkedin"><a rel="nofollow" href="<?php echo esc_url($linkedin); ?>" title="<?php esc_html_e('Linkedin', 'videopro');?>"><i class="fa fa-linkedin"></i></a></li>
								  <?php }
								  if($tumblr = get_post_meta(get_the_ID(),'tumblr',true)){ ?>
									  <li class="tumblr"><a rel="nofollow" href="<?php echo esc_url($tumblr); ?>" title="<?php esc_html_e('Tumblr', 'videopro');?>"><i class="fa fa-tumblr"></i></a></li>
								  <?php }
								  if($google = get_post_meta(get_the_ID(),'google-plus',true)){ ?>
									 <li class="google-plus"> <a rel="nofollow" href="<?php echo esc_url($google); ?>" title="<?php esc_html_e('Google Plus', 'videopro');?>"><i class="fa fa-google-plus"></i></a></li>
								  <?php }
								  if($pinterest = get_post_meta(get_the_ID(),'pinterest',true)){ ?>
									 <li class="pinterest"> <a rel="nofollow" href="<?php echo esc_url($pinterest); ?>" title="<?php esc_html_e('Pinterest', 'videopro');?>"><i class="fa fa-pinterest"></i></a></li>
								  <?php }
								  if($instagram = get_post_meta(get_the_ID(),'instagram',true)){ ?>
									 <li class="instagram"> <a rel="nofollow" href="<?php echo esc_url($instagram); ?>" title="<?php esc_html_e('Instagram', 'videopro');?>"><i class="fa fa-instagram"></i></a></li>
								  <?php }
								  if($flickr = get_post_meta(get_the_ID(),'flickr',true)){ ?>
									 <li class="flickr"> <a rel="nofollow" href="<?php echo esc_url($flickr); ?>" title="<?php esc_html_e('Flickr', 'videopro');?>"><i class="fa fa-flickr"></i></a></li>
								  <?php }
								  if($youtube = get_post_meta(get_the_ID(),'youtube',true)){ ?>
									 <li class="youtube"> <a rel="nofollow" href="<?php echo esc_url($youtube); ?>" title="<?php esc_html_e('Youtube', 'videopro');?>"><i class="fa fa-youtube"></i></a></li>
								  <?php }
								  if($github = get_post_meta(get_the_ID(),'github',true)){ ?>
									 <li class="github"> <a rel="nofollow" href="<?php echo esc_url($github); ?>" title="<?php esc_html_e('Github', 'videopro');?>"><i class="fa fa-github"></i></a></li>
								  <?php }
								   if($dribbble = get_post_meta(get_the_ID(),'dribbble',true)){ ?>
									 <li class="dribbble"> <a rel="nofollow" href="<?php echo esc_url($dribbble); ?>" title="<?php esc_html_e('Dribbble', 'videopro');?>"><i class="fa fa-dribbble"></i></a></li>
								  <?php }
								  if($vk = get_post_meta(get_the_ID(),'vk',true)){ ?>
									 <li class="vk"> <a rel="nofollow" href="<?php echo esc_url($vk); ?>" title="<?php esc_html_e('vk', 'videopro');?>"><i class="fa fa-vk"></i></a></li>
								  <?php }
								  ?>
                              </ul>
                            </div>
                          </div>
                        </div>
                        
                        <div class="single-divider"></div>
                        <?php 
						$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
						$args = array(
							'post_type' => 'post',
							'paged' => $paged,
							'post_status' => 'publish',
							'ignore_sticky_posts' => 1,
							'orderby' => 'date',
							'order' => 'ASC',
							'post__not_in' => array(get_the_ID()),
							'meta_query' => array(
								array(
									'key' => 'actor_id',
									 'value' => get_the_ID(),
									 'compare' => 'LIKE',
								),
							)
						);

						$the_query = new WP_Query( $args );
						$it = $the_query->post_count;
                        $videopro_wp = videopro_global_wp();
						if($the_query->have_posts()){
							$i = 0;
							?>                  
                            <h1 class="h4 category-title entry-title single-actor"><?php esc_html_e('Videos','videopro');?></h1>
                            <div class="cactus-listing-wrap single-actor">
                                <div class="cactus-listing-config style-2"> <!--addClass: style-1 + (style-2 -> style-n)-->
                                    <div class="cactus-sub-wrap">
										<script type="text/javascript">
                                            var cactus = {"ajaxurl":"<?php echo admin_url( 'admin-ajax.php' );?>","query_vars":<?php echo str_replace('\/', '/', json_encode($args)) ?>,"current_url":"<?php echo esc_url(home_url($videopro_wp->request));?>" }
                                        </script>                                   
                                        <?php
                                        while($the_query->have_posts()){ 
                                            $the_query->the_post();
                                            get_template_part( 'cactus-video/content-video' );
                                        }
                                        wp_reset_postdata();
                                        ?>                                                
                                    </div>
                                    
                                    <?php
                                    
                                    if(is_active_sidebar('content-bottom-sidebar')){
                                        echo '<div class="content-bottom-sidebar-wrap">';
                                            dynamic_sidebar( 'content-bottom-sidebar' );
                                        echo '</div>';
                                    } ?>
                                </div>
                            </div>
                            <?php videopro_paging_nav('.cactus-listing-wrap.single-actor .cactus-sub-wrap','cactus-video/content-video'); ?>
						<?php 
                        
                        
                        } else {
							?>
                            <p><?php esc_html_e('No videos found','videopro');?></p>
                            <?php
						}
                        if($it>0){
                            $videopro_wp_query = $main_query;
                        }
                        ?>
                        
                        <?php endwhile;
						
						else : 
							get_template_part( 'html/loop/content', 'none' );							
						endif; ?>
                        
                        <?php
                        if(ot_get_option('show_comment', 'on') != 'off'){
                            
                            if ( comments_open() ){
                                comments_template();
                            }
						}?>
                    </div>
                </div>
				<?php 
                
				$sidebar_style = 'ct-medium';
				videopro_global_sidebar_style($sidebar_style);
                if($videopro_sidebar != 'full'){ get_sidebar(); } 
				
				?>
        
            </div>
        </div>
        
    </div>                
    
    
</div>
<?php get_footer(); ?>