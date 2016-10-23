<?php
class widget_touchsize_facebook extends WP_Widget
{

  function widget_touchsize_facebook()
  {
    $widget_ops = array('classname' => 'widget_touchsize_facebook', 'description' => 'This is a Facebook like box and posts widget.' );
    parent::__construct('widget_touchsize_facebook', 'Facebook Like & Feed', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
	$pageurl = isset( $instance['pageurl'] ) ? esc_attr( $instance['pageurl'] ) : '';
	$showfaces = isset( $instance['showfaces'] ) ? esc_attr( $instance['showfaces'] ) : '';
	$showstream = isset( $instance['showstream'] ) ? esc_attr( $instance['showstream'] ) : '';
	//$showheader = isset( $instance['showheader'] ) ? esc_attr( $instance['showheader'] ) : '';					
	$likebox_height = isset( $instance['likebox_height'] ) ? esc_attr( $instance['likebox_height'] ) : '';						
?>
  <p>
  <label for="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>">
	 <?php esc_html_e('Title:','videofly');?>  
	  <input class="upcoming" id="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>" size='40' name="<?php echo vdf_var_sanitize($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label>
  </p> 
  <p>
  <label for="<?php echo vdf_var_sanitize($this->get_field_id('pageurl')); ?>">
	  <?php esc_html_e('Page URL:','videofly');?> 
	  <input class="upcoming" id="<?php echo vdf_var_sanitize($this->get_field_id('pageurl')); ?>" size='40' name="<?php echo vdf_var_sanitize($this->get_field_name('pageurl')); ?>" type="text" value="<?php echo esc_attr($pageurl); ?>" />
	<br />
      <small><?php esc_html_e('Please enter your page url. Example: https://www.facebook.com/touchsize','videofly');?>
	</small><br />
  </label>
  </p> 
  <p>
  <label for="<?php echo vdf_var_sanitize($this->get_field_id('showfaces')); ?>">
	  <?php esc_html_e('Show Faces:','videofly');?>
	  <select id="<?php echo vdf_var_sanitize($this->get_field_id('showfaces')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('showfaces')); ?>">
			<option <?php if($showfaces == 'true'){echo 'selected';}?> value="true"><?php esc_html_e('Yes','videofly');?></option>
			<option <?php if($showfaces == 'false'){echo 'selected';}?> value="false"><?php esc_html_e('No','videofly');?></option>
      </select>
  </label>
  </p>  
  <p>
  <label for="<?php echo vdf_var_sanitize($this->get_field_id('showstream')); ?>">
	  <?php esc_html_e('Show Stream:','videofly');?> 
	   <select id="<?php echo vdf_var_sanitize($this->get_field_id('showstream')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('showstream')); ?>" style="width:225px">
			<option <?php if($showstream == 'true'){echo 'selected';}?> value="true"><?php esc_html_e('Yes','videofly');?></option>
			<option <?php if($showstream == 'false'){echo 'selected';}?> value="false"><?php esc_html_e('No','videofly');?></option>
      </select>
  </label>
  </p> 
  
  <p>
  <label for="<?php echo vdf_var_sanitize($this->get_field_id('likebox_height')); ?>">
	  <?php esc_html_e('Like Box Height:','videofly');?>
	  <input class="upcoming" id="<?php echo vdf_var_sanitize($this->get_field_id('likebox_height')); ?>" size='2' name="<?php echo vdf_var_sanitize($this->get_field_name('likebox_height')); ?>" type="text" value="<?php echo esc_attr($likebox_height); ?>" />
  </label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['pageurl'] = $new_instance['pageurl'];
	$instance['showfaces'] = $new_instance['showfaces'];	
	$instance['showstream'] = $new_instance['showstream'];
	$instance['showheader'] = $new_instance['showheader'];	
	$instance['likebox_height'] = $new_instance['likebox_height'];			
    return $instance;
  }
 
	function widget($args, $instance)
	{
		
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$pageurl = empty($instance['pageurl']) ? ' ' : apply_filters('widget_title', $instance['pageurl']);
		$showfaces = empty($instance['showfaces']) ? ' ' : apply_filters('widget_title', $instance['showfaces']);
		$showstream = empty($instance['showstream']) ? ' ' : apply_filters('widget_title', $instance['showstream']);
		$showheader = empty($instance['showheader']) ? ' ' : apply_filters('widget_title', $instance['showheader']);													
		$likebox_height = empty($instance['likebox_height']) ? ' ' : apply_filters('widget_title', $instance['likebox_height']);													
		
		echo vdf_var_sanitize($before_widget);	
		// WIDGET display CODE Start
		if (!empty($title))
			echo vdf_var_sanitize($before_title);
			echo vdf_var_sanitize($title);
			echo vdf_var_sanitize($after_title);
			global $wpdb, $post;?>
			<?php	
			if($likebox_height == ' ' || $likebox_height == ''){$likebox_height = '315';}
			?>         

			<div class="fb-like-box" data-href="<?php echo vdf_var_sanitize($pageurl);?>" data-width="340" data-height="<?php echo vdf_var_sanitize($likebox_height);?>" data-adapt-container-width="true" data-show-faces="<?php echo vdf_var_sanitize($showfaces);?>" data-header="false" data-stream="<?php echo vdf_var_sanitize($showstream);?>" data-show-border="false"></div>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
	<?php echo vdf_var_sanitize($after_widget);
		}
		
	}
add_action( 'widgets_init', create_function('', 'return register_widget("widget_touchsize_facebook");') );?>