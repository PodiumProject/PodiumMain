<?php
/**
 * Template Name: Blog
 * @package Cpmmagz
 */

get_header();

?>
<div id="main-wrapper">
    <div id="content" class="site-content">
        <div class="container">
            <div class="row">
                <div class="col l8 s12 m12">
                    <div id="primary" class="content-area">
                        <?php
                             if ( get_query_var('paged') ) {
                                    $paged = get_query_var('paged');
                                }
                                elseif ( get_query_var('page') ) {
                                    $paged = get_query_var('page');
                                }
                                else {
                                   $paged = 1;
                                }
                            $blog_args = array(
                                'post_type' => 'post',
                                'order' => 'DATE',
                                'orderby' => 'DESC',
                                'paged' => $paged
                                );
                                $blog_query = new WP_Query($blog_args);
                                if( $blog_query->have_posts() ):
                                    $arr = array();
                                 while ($blog_query->have_posts()) : $blog_query->the_post();
                                $arr[] = $post->ID;
                                endwhile;
                                endif;
                                wp_reset_postdata();
                        ?>
                            <main class="site-main" id="main" role="main">
                                    <div class="post-header">
                                        <div class="card">
                                            <div class="card-header">
                                                <h1 class="page-title"><span><?php esc_attr_e('BLOG', 'cpmmagz');?></span></h1>
                                            </div>
                                        </div>
                                    </div>
                                     <?php $first_post = get_post($arr[0]); ?>
                                        <div class="card post-card news-big featured-card">
                                            <article  <?php post_class(); ?>>
                                                <div class="featured-media">
                                                     <?php get_template_part('template-parts/content', get_post_format($first_post->ID)); ?>
                                                </div>
                                                <div class="card-desc">
                                                    <div class="card-content card-wrapper">
                                                        <header class="entry-header">
                                                            <?php $cats = cpmmagz_all_taxonomy_name($first_post->ID, 'category');
                                                            $links = cpmmagz_all_taxonomy_link($first_post->ID, 'category');
                                                            foreach( $cats as $cat) {
                                                                cpmmagz_cat_names( $cat , $links ); ?>
                                                                <?php } ?>
                                                                <h2 class="entry-title title-font">
                                                                    <a href="<?php echo esc_url(get_the_permalink($first_post->ID));?>">
                                                                        <?php echo esc_attr(cpmmagz_limit_title(esc_attr( get_the_title($first_post->ID) ), 95)); ?>
                                                                    </a>
                                                                </h2>
                                                            <span class="entry-meta">
                                                               <?php cpmmagz_meta_data($first_post);?>
                                                            </span>
                                                        </header>
                                                        <!-- Entry Header -->
                                                        <div class="entry-content clearfix">
                                                            <p>
                                                            <?php echo esc_attr(cpmmagz_strip_url_content($first_post,42));?>
                                                            </p>
                                                        </div>
                                                        <!-- Entry Content -->
                                                    </div>
                                                    <!-- Card Content -->
                                                </div>
                                                <!-- Card Desc -->
                                            </article>
                                        </div>
                                <?php unset($arr[0]);  ?>
                                    <!-- Post Card -->

                                    <div class="article-wrapper archive-wrapper">
                                         <?php foreach ($arr as $value){
                                                $post = get_post($value); ?>
                                            <div class="card archive-card news-medium hoverable">
                                                <article <?php post_class(); ?>>
                                                    <?php get_template_part('template-parts/content', get_post_format($post->ID)); ?>
                                                    <div class="card-desc valign-wrapper">
                                                        <div class="card-content">
                                                        <h2 class="title-font">
                                                            <a href="<?php the_permalink();?>"><?php the_title(); ?></h2> </a>
                                                            <span class="entry-meta">
                                                                <?php cpmmagz_meta_data($post);?>
                                                            </span>

                                                            <span class="label waves-effect waves-light">
                                                                <?php $category = cpmmagz_display_random_catname($post->ID, 'category'); ?>
                                                                <a href="<?php echo esc_url( $category['link'] )  ;?>" >
                                                                    <?php echo esc_attr( $category['name'] ) ; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </article>
                                            </div>
                                        <?php } ?>
                                    </div><!-- Archive Wrapper -->
                                         <?php
                                             if(function_exists('wp_paginate')) {
                                                 wp_paginate();
                                             }
                                              else{
                                                cpmmagz_navigation_page();
                                             }
                                        ?>
                            </main>
                     </div>
                        <!-- End Primary -->
                </div>
                    <!-- End Col 8 -->
                <div class="col l4 s12 m12 right">
                    <aside id="sidebar" class="sidebar left-side">
                        <?php get_sidebar(); ?>
                    </aside>
                </div><!-- Col 4 Sidebar -->
            </div>
                <!-- End Row -->
        </div>
            <!-- End Container -->
    </div>
        <!-- End  Content-->
</div>
<?php get_footer();?>