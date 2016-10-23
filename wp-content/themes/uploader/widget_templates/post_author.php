<?php defined('ABSPATH') OR die('restricted access');

$author_id = ( ! empty( $instance['author'] ) ) ? $instance['author'] : 0;

if ( ! intval( $author_id ) ) {
    return;
}
$widget_title = exc_kv( $instance, 'title' );?>

<!-- user detail -->
<div class="sidebar-block">

    <div class="user-detail">

        <figure class="user-thumb"> <?php exc_get_avatar( 64, 'thumbnail', $author_id, false, array( 'height' => '' ) ); ?> </figure>

        <span><?php _e('Uploaded by', 'exc-uploader-theme');?></span>

        <h3>
            <?php
            printf( '<a href="%1$s" rel="author">%2$s</a>',
                    esc_url( get_author_posts_url( $author_id ) ),
                    exc_get_user_name( $author_id, true )
                 );?>
        </h3>

        <?php if ( exc_get_follower( $author_id, get_current_user_id() ) ) :?>
            <a href="#" class="btn btn-primary btn-xs exc-follow-author" data-id="<?php echo esc_attr( $author_id );?>" role="button">
                <i class="fa fa-plus"></i>
                <span class="exc-followers-info-<?php echo esc_attr( $author_id );?>"><?php _e('Unfollow', 'exc-uploader-theme');?></span>
            </a>
        <?php else :?>
            <a href="#" class="btn btn btn-primary btn-xs exc-follow-author" data-id="<?php echo esc_attr( $author_id );?>" role="button">
                <i class="fa fa-plus"></i>
                <span class="exc-followers-info-<?php echo esc_attr( $author_id );?>"><?php _e('Follow', 'exc-uploader-theme');?></span>
            </a>

        <?php endif;?>

        <div class="subscription-form exc-follower-form-<?php echo esc_attr( $author_id );?>"></div>
    </div>
    <!-- Entry stats -->
    <ul class="post-stats clearfix">
        <li>
            <i class="fa fa-thumbs-up"></i> <span class="exc-votes-count-<?php the_ID();?>"><?php echo exc_get_votes();?></span>
        </li>

        <li>
            <i class="fa fa-eye"></i><?php echo exc_get_views();?>
        </li>

        <li>
            <i class="fa fa-comments"></i>
            <?php echo get_comments_number();?>
        </li>
    </ul>
</div>