<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package cactus
 */
$videopro_sidebar_style = videopro_global_sidebar_style();
?>
<!--Sidebar-->
<div class="cactus-sidebar <?php echo esc_attr($videopro_sidebar_style);?>">
        <div class="cactus-sidebar-content">
		<?php 
        if(is_active_sidebar('right-sidebar')){
            dynamic_sidebar( 'right-sidebar' );
        }else{ ?>
        
            <aside id="search" class="widget widget_search module widget-col">
                <div class="widget-inner">
                    <h2 class="widget-title h4"><?php esc_html_e('Search','videopro') ?></h2>
                    <?php get_search_form(); ?>
                </div>
            </aside>
        
        <?php } // end sidebar widget area ?>  
    </div>  
</div>
<!--Sidebar-->