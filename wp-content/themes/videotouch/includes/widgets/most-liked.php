<?php
    class widget_most_liked extends WP_Widget {

        function widget_most_liked() {
            $widget_ops = array( 'classname' => 'widget_most_liked' , 'description' => __( " Get posts that have the most likes." , 'touchsize' ) );
            parent::__construct( 'widget_touchsize_most_liked' , __( "Most liked posts" , 'touchsize' ) , $widget_ops );
        }

        function widget( $args , $instance ) {

            /* prints the widget*/
            extract($args, EXTR_SKIP);

            $by_time = (isset($instance['by_time']) && ($instance['by_time'] === 't' || $instance['by_time'] === 'm' || $instance['by_time'] === 'w')) ? $instance['by_time'] : 't';
            
            if( isset( $instance['title'] ) ){
                $title = $instance['title'];
            }else{
                $title = '';
            }

			if( isset( $instance['nr_posts'] ) && is_numeric($instance['nr_posts']) ){
                $nr_posts = $instance['nr_posts'];
            }else{
                $nr_posts = 5;
            }
			
						
			if(isset($instance['customPost']) ){
				$custompost		= $instance['customPost'];
			}else{
				$custompost		= array();
			}
			
			
			if(isset($instance['taxonomy']) ){
				$taxonomy		= $instance['taxonomy'];
			}else{
				$taxonomy		= array();
			}
			
			if(isset($instance['taxonomies']) ){
				$taxonomies		= $instance['taxonomies'];
			}else{
				$taxonomies		= array();
			}
			
            echo $before_widget;
		?>
			
			
		<?php
            if( !empty($title) ){
				echo $before_title . $title . $after_title;
			}	
		
            $args = array(
				'post_type' => $custompost,
				'posts_per_page' =>$nr_posts,
				
			);

			/*iterate through each taxonomy, and if the value equals to -1, then remove that calue from array to not influence the query*/			
			foreach ($taxonomies as $key => $value) {
				if( (int)$value === -1 ){
					unset($taxonomies[$key]);
				}
			}
			
			if(sizeof($taxonomies)){
				$args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy[0],
						'field' => 'slug',
						'terms' => $taxonomies
					)
				);
			}
			$args['meta_key'] = '_touchsize_likes';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			
			if( $by_time === 'w' ) $args['w'] = date('W');
			if( $by_time === 'm' ) $args['monthnum'] = date('m');

			$recent = new WP_Query( $args );

            if( is_array($recent->posts) && !empty($recent->posts) ){
                ?><ul class="widget-items"><?php
                foreach($recent->posts as $post)  {
					if( get_post_thumbnail_id($post -> ID) ){
								$post_img = wp_get_attachment_image(get_post_thumbnail_id($post -> ID) , 'thumb' , '');
								$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
								$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
								$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
								
							}else{
								$post_img = '<img src="' . get_template_directory_uri() . '/images/noimage.jpg" alt="" />';
								$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
								$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
								$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
							}

					?>
                    <li>
						<article class="row">
							<div class="col-lg-4 col-sm-4">
                                <a <?php echo $cnt_a3; ?>><?php echo $post_img; ?></a>
                            </div>
                            <div class="col-lg-8 col-sm-8">
                                <h6 class="">
                                	<a <?php echo $cnt_a1; ?>>
									<?php
										echo $post -> post_title;
									?>
									</a>
								</h6>
								<div class="widget-meta">
									<ul>
										<?php if ( $post->comment_status == 'open' ) { ?>
											<li class="red-comments">
                                                <a <?php echo $cnt_a2; ?>>
                                                	<i class="icon-comments"></i>
                                                	<span class="comments-count">
                                                    	<?php echo $post -> comment_count . ' '; ?> 
                                                	</span>
                                                </a>
											</li>
										<?php } ?>
	                                        <?php touchsize_likes($post->ID, '<li>', '</li>'); ?>
	                                        <li>
	                                        	<i class="icon-views"></i> <?php ts_get_views($post->ID, true); ?>
	                                        </li>
									</ul>
								</div>
                            </div>
						</article>
                    </li>
        <?php

                }
                ?></ul><?php
            }
            
            wp_reset_query();
            echo $after_widget;
		}
		
		
        function update( $new_instance, $old_instance) {

            /*save the widget*/
            $instance = $old_instance;
// 			print_r($old_instance);
			
            $instance['title']              = strip_tags( $new_instance['title'] );
			$instance['nr_posts']        	= strip_tags( $new_instance['nr_posts'] );
			$instance['by_time']        	= strip_tags( $new_instance['by_time'] );
			
			
			$instance['customPost'] = array();
			foreach($new_instance['customPost'] as $cust_post){
				if($cust_post != ''){
					$instance['customPost'][] = $cust_post;
				}	
			}
			
			$instance['taxonomy'] = array(); 
			foreach($new_instance['taxonomy'] as $taxonomy){
				if($taxonomy != ''){
					$instance['taxonomy'][] = $taxonomy;
				}else{
					$instance['taxonomy'][] = '';
				}
			}
			
			$instance['taxonomies'] = array(); 
			foreach($new_instance['taxonomies'] as $taxonomies){
				if($taxonomies != ''){
					$instance['taxonomies'][] = $taxonomies;
				}else{
					$instance['taxonomies'][] = '';
				}
			}

			return $instance;
        }

        function form($instance) {

            /* widget form in backend */
            $instance   = wp_parse_args( (array) $instance, array( 'title' => '', 'by_time' =>'', 'nr_posts' => '',  'customPost' => array(),'taxonomy' => array(), 'taxonomies' => array() ) );
            $title      = strip_tags( $instance['title'] );
			$nr_posts   = strip_tags( $instance['nr_posts'] );
			$by_time    = strip_tags( $instance['by_time'] );
			
			$custompost = (isset($instance['customPost'])) ? $instance['customPost'] : array();
			$taxonomy   = (isset($instance['taxonomy'])) ? $instance['taxonomy'] : array();
			$taxonomies = (isset($instance['taxonomies'])) ? $instance['taxonomies'] : array();		
						
			$args = array('exclude_from_search' => false);
			$post_types = array('video', 'post');
			
    ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','touchsize') ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
			
			<div class="c_post">
			<?php if(sizeof($custompost)){ $counter = 0;
					foreach($custompost as $c_p){ ?>
			<div>
				<p>
					<label ><?php _e('Select post type','touchsize') ?>: 
						<a href="javascript:void(0)" onclick="jQuery(this).parent().parent().parent().remove();" style="float:right"><?php _e("remove",'touchsize'); ?></a>
						<select class="widefat post_type" onchange="get_taxonomy(jQuery(this))" name="<?php echo $this->get_field_name( 'customPost'  ); ?>[]" >
							<option value=''><?php _e('Select item','touchsize'); ?></option>	
							<?php foreach($post_types as $custom_post) { ?>
								<option value='<?php echo $custom_post; ?>' <?php if($c_p == $custom_post ){ echo 'selected="selected"'; } ?> ><?php echo $custom_post; ?></option>	
							<?php } ?>		 
						</select>
						
					</label>
				</p>
				<?php 
					$custom_posts_taxonomies = array(
							'post' => array('category' => __('Category','touchsize'), 
											'post_tag' => __('Post tag','touchsize') ,
										),
							
							'video' => array('videos_categories' => __('Video category','touchsize'), 
											 'post_tag' => __('Video tag','touchsize') ,
										),
						);
					
					if(isset($custom_posts_taxonomies[$c_p])){ ?>
					<div class="taxonomy"> 
					<?php 
						if($c_p == 'page'){ ?>	
						<p style="display:none"> 
							<label ><?php _e('Select post taxonomy','touchsize') ?>: 
								<select class="widefat" style="display:none" name="<?php  echo $this->get_field_name('taxonomy'); ?>[]" >
									<option value="__"><?php _e('Select taxonomy','touchsize') ?></option>
								</select>
							</label>	
						</p>
							
					<?php }else{ ?>		
						<p> 
							<label ><?php _e('Select post taxonomy','touchsize') ?>: 
								<select class="widefat " name="<?php  echo $this->get_field_name( 'taxonomy'  ); ?>[]" onchange="javascript:get_terms(jQuery(this))">
									<option value="__"><?php _e('Select taxonomy','touchsize') ?></option>
									<?php  
										if(isset($custom_posts_taxonomies[$c_p])){
											foreach ($custom_posts_taxonomies[$c_p] as $key => $value) { ?>
											<option value="<?php echo $key ?>" <?php if ( isset($taxonomy[$counter]) && $key == $taxonomy[$counter]){echo 'selected="selected" '; } ?> ><?php echo $value ?></option>
									<?php			
											}
										}
									?>
									
								</select>	
							</label>
						</p>	
					<?php		
						
						}	
					
					?>
						
					</div>
				<?php } ?>
				<div class="taxonomies">
					<?php
						if( count($taxonomy) > 0 && isset($taxonomy[$counter])  && $taxonomy[$counter] != '__' && $taxonomy[$counter] != -1 ){
							$terms = get_terms($taxonomy[$counter] , array( 'hide_empty' => false ));
						?>
						<p> 
							<label ><?php _e('Select post terms','touchsize') ?>: 
								<select class="widefat multiple-select" name="<?php  echo $this->get_field_name( 'taxonomies'  ); ?>[]" multiple>
									<?php foreach($terms as $term) { ?>
										<option value="<?php echo $term->slug; ?>" <?php if(isset($taxonomies[$counter]) && in_array($term->slug, $taxonomies) ) { echo 'selected="selected"'; }?> > <?php echo $term->name; ?> </option>
									<?php } ?>
								</select>
							</label>
						</p>
					<?php }else{ ?>
						<input class="hidden" name="<?php echo $this -> get_field_name( 'taxonomies' ); ?>[]" value="-1">
					<?php } ?>
				</div>
			</div>
			<?php 
					$counter++;
				} /*EOF foreach*/
			
			}else{ ?>
				<div>
					<p>
						<label ><?php _e("Select post type",'touchsize') ?>:
							<a href="javascript:void(0)" onclick="jQuery(this).parent().parent().parent().remove();" style="float:right"><?php _e("remove",'touchsize'); ?></a>
							<select class="widefat post_type" onchange="get_taxonomy(jQuery(this))" name="<?php echo $this->get_field_name( "customPost"  ); ?>[]" >
								<option value=''  ><?php _e("Select item",'touchsize'); ?></option>
							<?php foreach($post_types as $custom_post) {  
							
							?>
								<option value="<?php echo $custom_post; ?>"  ><?php echo $custom_post; ?></option>	
							<?php 
								 /*EOF if*/
							} /*EOF foreach*/ ?>
							</select>
							
						</label>
					</p>
					<div class="taxonomy">
					</div>
					<div class="taxonomies">
					</div>
				</div>
			<?php } /*EOF if*/ ?>
			</div>
			
			<p>
                <label for="<?php echo $this->get_field_id('nr_posts'); ?>"><?php _e( 'Number of posts' , 'touchsize' ) ?></label>:
                <input class="widefat digit" id="<?php echo $this->get_field_id('nr_posts'); ?>" name="<?php echo $this->get_field_name('nr_posts'); ?>" type="text" value="<?php echo esc_attr( $nr_posts ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_name('by_time'); ?>"><?php _e( 'Period' , 'touchsize' ) ?></label>:
                <select name="<?php echo $this->get_field_name('by_time'); ?>">
                	<option value="t" <?php selected($by_time, 't'); ?>><?php _e( 'All time' , 'touchsize' ) ?></option>
                	<option value="m" <?php selected($by_time, 'm'); ?>><?php _e( 'Last month' , 'touchsize' ) ?></option>
                	<option value="w" <?php selected($by_time, 'w'); ?>><?php _e( 'Last week' , 'touchsize' ) ?></option>
                </select>
            </p>
					
			<script type="text/javascript">
				function fix__i__( obj ){
					var n = jQuery( obj ).parents( '.widget' ).find( 'input.multi_number' ).val();
					if( n.length && n!='' && n.length > 0 ){
						jQuery( obj ).parents( '.widget-content' ).find( 'select, input, textarea' ).each( function( index, element ) {
							var id = jQuery( element ).attr( 'id' );
							var name = jQuery( element ).attr( 'name' );
							if( id && id.length && id.length > 0 ){
								jQuery( element ).attr( 'id' , id.replace( '__i__' , n ) );
							}
							if( name && name.length && name.length > 0 ){
								jQuery( element ).attr( 'name' , name.replace( '__i__' , n ) );
							}
						});
					}
				}

				function get_taxonomy( obj ) { 
						var  this_widget = '<?php  echo $this->get_field_name( 'taxonomy'  ); ?>[]';
						jQuery.ajax({
							url: ajaxurl,
							data: '&action=get_taxonomies&custom_post_type='+obj.val()+'&this_widget='+this_widget,
							type: 'POST',
							cache: false,
							success: function (data) { 
								obj.parent().parent().parent().find('div.taxonomy').html(data);
								obj.parent().parent().parent().find('div.taxonomies').html('<input class="hidden" name="<?php echo $this -> get_field_name( 'taxonomies' ); ?>[]" value="-1">');
								fix__i__( obj );
							},
							error: function (xhr) {

							}
						});
				}

				function get_terms( obj ) {
					var this_widget = '<?php echo $this -> get_field_name( 'taxonomies' ); ?>[]';
					jQuery.ajax({
							url: ajaxurl,
							data: '&action=get_terms&taxonomy='+obj.val()+'&this_widget='+this_widget,
							type: 'POST',
							cache: false,
							success: function (data) { 
								obj.parent().parent().parent().parent().find('div.taxonomies').html(data);
								fix__i__( obj );
							},
							error: function (xhr) {
								
							}
						});
				}
			</script>
    <?php
        }
		
		function get_terms(){
			if( isset( $_POST[ 'taxonomy'] ) && isset( $_POST[ 'this_widget' ] ) && $_POST[ 'taxonomy' ] != '__' && $_POST[ 'taxonomy' ] != -1 ){
				$terms = get_terms( $_POST[ 'taxonomy' ] , array( 'hide_empty' => false ) );
				?>
				<p> 
					<label ><?php _e('Select post terms','touchsize') ?>: 
						<select class="widefat multiple-select" name="<?php  echo $_POST[ 'this_widget' ]; ?>" multiple>
							<?php foreach( $terms as $term ) { ?>
								<option value="<?php echo $term->slug; ?>" > <?php echo $term->name; ?> </option>
							<?php } ?>
						</select>
					</label>
				</p>
			<?php
			}else{
			?>
				<input class="hidden" name="<?php echo $_POST[ 'this_widget' ]; ?>" value="-1">
		<?php
			}
			die();
		}

		function get_taxonomies(){

			if( isset($_POST['custom_post_type']) && $_POST['custom_post_type'] !== '' ){

				$custom_posts_taxonomies = array(
					'post' => array('category' => __('Category','touchsize'), 
									'post_tag' => __('Post tag','touchsize') ,
								),
					
					'video' => array('videos_categories' => __('Video Category','touchsize'), 
									 'video_tag' => __('Video tag','touchsize') ,
								),
				);
				
				if( $_POST['custom_post_type'] !== 'page' && isset($custom_posts_taxonomies[$_POST['custom_post_type']]) ){ ?> 
					<p> 
						<label ><?php _e('Select post taxonomy','touchsize') ?>: 
							<select class="widefat " name="<?php  echo $_POST['this_widget']; ?>" onchange="javascript:get_terms(jQuery(this));">
								<option value="__"><?php _e('Select taxonomy','touchsize') ?></option>
								<?php  
									if(isset($custom_posts_taxonomies[$_POST['custom_post_type']])){
										foreach ($custom_posts_taxonomies[$_POST['custom_post_type']] as $key => $value) { ?>
											<option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php											
										}
									}
								?>
							</select>	
						</label>
					</p>
			<?php	
				}
			}
			die();		
		}
		
    }
	function register_most_liked_widget() {
	    register_widget( 'widget_most_liked' );
	}
	add_action( 'widgets_init', 'register_most_liked_widget' );

?>