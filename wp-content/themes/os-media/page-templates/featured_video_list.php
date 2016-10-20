<?php
/**
 * Template Name: Featured video list
 *
 * @package os_media
 * @since OS media 0.1
 * 
 */
?>

<?php get_header(); ?>

<div id="main-content" class="main-content">
  <div id="primary" class="content-area">

    <?php
    $mypost = array( 
      'post_type' => 'osmedia_cpt', 
      'showposts' => 15, 
      'paged' => $paged /*, 'category_name' => Featured */
    );
    $loop = new WP_Query( $mypost );
    ?>

    <div id="featured-content" class="featured-content">
      <div class="featured-content-inner">
          <?php /* The loop */ ?>
          <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
          
                <?php
                if (has_post_thumbnail( $post->ID )) {
                    $array_img_cover_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
                    $img_cover_url = $array_img_cover_url[0];
                }else{    
                    // try load image from video folder
                    $img_cover_url = '';
                    $options =      get_option('OSmedia_settings'); 
                    $local_path =   OStheme_path_sanitize($options['OSmedia_path']); // not implemented yet 
                    $url =          OStheme_path_sanitize($options['OSmedia_url']); 
                    $s3_url =       $options['OSmedia_s3server'] . $options['OSmedia_s3bucket'] . '/' . $options['OSmedia_s3dir'];

                    $fileurl =      get_post_meta($post->ID, 'OSmedia_fileurl', true);
                    $file_img =     get_post_meta($post->ID, 'OSmedia_file', true);
                    $file_mp4 =     get_post_meta($post->ID, 'OSmedia_mp4', true);
                    $file_webm =    get_post_meta($post->ID, 'OSmedia_webm', true);
                    $file_ogg =     get_post_meta($post->ID, 'OSmedia_ogg', true);
                    // img campi on-fly
                    if ( substr($file_ogg, 0, 7) == 'http://' || substr($file_ogg, 0, 8) == 'https://' ) {
                        $fileurl = OStheme_path_sanitize(dirname($file_ogg));
                        $file_img = basename($file_ogg, ".ogg");
                    }elseif ( substr($file_webm, 0, 7) == 'http://' || substr($file_webm, 0, 8) == 'https://' ) {
                        $fileurl = OStheme_path_sanitize(dirname($file_webm));
                        $file_img = basename($file_webm, ".webm");
                    }elseif ( substr($file_mp4, 0, 7) == 'http://' || substr($file_mp4, 0, 8) == 'https://' ) {
                        $fileurl = OStheme_path_sanitize(dirname($file_mp4));
                        $file_img = basename($file_mp4, ".mp4");
                    }
                    // img campi selettore file (prevalgono)
                    if( $fileurl == 1 ) {    
                        $fileurl = $url;
                    }elseif( $fileurl == 2 ||  !$fileurl || $fileurl == '' ) {
                        $fileurl = $url;
                    }elseif( $fileurl == 3 ) {
                        $fileurl = $s3_url;
                    }
                    //  || $file_webm != '' || $file_ogg != ''
                    
                    if ( $file_img && $file_img != '' && $fileurl != '' ) 
                      $img_cover_url = $fileurl . $file_img . ".jpg"; 
                }
                ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
              <a class="post-thumbnail" href="<?php the_permalink(); ?>">
                <?php if(isset($img_cover_url)) echo '<img src="'.$img_cover_url.'" />'; ?>
                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/OS-frame-play.png" />
              </a>

              <header class="entry-header">
                <div class="entry-meta">
                  <span class="cat-links"><?php get_the_terms( $post->ID, 'osmedia_tax' ) ?></span>
                
                </div><!-- .entry-meta -->
                <?php //endif; ?>

                <?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h1>' ); ?>
                <?php the_excerpt( '<span class="entry_exc">','</span>' ); ?>
                
              </header><!-- .entry-header -->
            </article><!-- #post-## -->
                  
          <?php endwhile ?>
          <?php wp_reset_query(); ?>

      </div><!-- .featured-content-inner -->
    </div><!-- #featured-content .featured-content -->

    <div id="primary" class="content-area" style="margin-top:20px">
      <div id="content" class="site-content" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

          <article>

              <?php the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' ); ?>
              
              <div class="entry-content">
                <?php the_content();?>
              </div>
          
          </article>

        <?php endwhile; ?> 

      </div>  
    </div>

  </div><!-- .main-content -->  
</div><!-- .primary -->  

<?php get_sidebar(); ?>
<?php get_footer(); ?>