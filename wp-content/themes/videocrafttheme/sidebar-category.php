<?php
/**
 * Sidebar widget for front page 
 */
?>
<div class="sidebar">
    <?php
    if (is_active_sidebar('category-widget-area')):
    dynamic_sidebar('category-widget-area');
endif;
    ?>
</div>