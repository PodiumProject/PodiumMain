<?php
/**
 * Dynamic sidebars initialization
 */
function ts_init_dynamic_sidebars()
{
	$sidebars = ts_get_sidebars();
	
	if( $sidebars ) {

        foreach( $sidebars as $id => $sidebar ) {
     
            register_sidebar(array(
            	'id'			=> $id,
				'name'          => $sidebar,
				'before_widget' => '<aside id="%1$s" class="widget ts_widget %2$s"><div class="widget-content">',
				'after_widget'  => '</div></aside>',
				'before_title'  => '<h6 class="widget-title ts_sidebar_title">',
				'after_title'   => '</h6><div class="widget-delimiter"></div>',
            ));

        }
    }
}

add_action('widgets_init', 'ts_init_dynamic_sidebars');

function ts_add_sidebar(){

	$response = array(
		'success' => 0,
		'message' => 'Error',
		'sidebar' => 
			array(
				'id' => 0,
				'name' => ''
			)
		);

	$sidebars = ts_get_sidebars();
	$name     = trim( str_replace( array( "\n","\r","\t" ), '', $_POST['ts_sidebar_name'] ) );
	$name     = preg_replace( "/\s+/", " ", $name );

	if ( $sidebars ) {
		foreach ( $sidebars as $id => $sidebar ) {
			if ( strcmp( $sidebar, $name ) == 0 ) {
				$response['message'] = 'Sidebar already exists, please use a different name.';
				die( json_encode( $response ) );
			}
		}
	}

	$id              = 'ts-sidebar-'. uniqid();
	$sidebars[ $id ] = $name;

	ts_update_sidebars( $sidebars );

	$response['success']         = 1;
	$response['sidebar']['id']   = $id;
	$response['sidebar']['name'] = $name;

	die( json_encode( $response ) );
}

add_action('wp_ajax_ts_add_sidebar', 'ts_add_sidebar');

/**
 * Remove sidebar function
 * @return string
 */
function ts_remove_sidebar()
{
	$sidebar_id = $_POST['ts_sidebar_id'];

	// 0 - error, 1 - removed, 2 - sidebar does not exist
	$response = array(
		'result' => 0,
		'element_id' => ''
	);
	$sidebars = ts_get_sidebars();
	//unset( $sidebars[ 'css' ] );
	if ( array_key_exists( $sidebar_id, $sidebars ) ) {	
		unset( $sidebars[ $sidebar_id ] );
		ts_update_sidebars( $sidebars );
		$response['result']     = 1;
		$response['element_id'] = $sidebar_id;
	} else {
		$response['result'] = 2;
	}

	die( json_encode( $response ) );
}

add_action('wp_ajax_ts_remove_sidebar', 'ts_remove_sidebar');

function ts_update_sidebars( $sidebars = array() )
{
	$sidebars = update_option( 'videofly_sidebars', $sidebars );
}

/**
 * Get sidebars as array
 * @return array
 */
function ts_get_sidebars()
{
	$sidebars = get_option('videofly_sidebars');
	
	return ( ! isset( $sidebars ) ) ? array() : $sidebars;
}

/**
 * Get sidebars as drop-down menu
 * @return string
 */
function ts_sidebars_drop_down($sidebar_id = '', $element_id = '', $name = 'sidebars[]')
{
	$sidebars = ts_get_sidebars();
	$options  = '<option value="0">' . esc_html__(' -- select sidebar -- ', 'videofly'). '</option>';
	$options  .= '<option value="main" ' . selected( $sidebar_id, 'main', false ) . ' >' . esc_html__('Main Sidebar', 'videofly') . '</option>';
	$id       = '';

	if ($element_id != '') {
		$id = 'id="'.$element_id.'"';
	}

	$sidebars_start = '<select id="ts_sidebar_sidebars" name="'.$name.'" '.$id.'>';
	$sidebars_end   = '</select>';
	
	if ( $sidebars ) {
		foreach ( $sidebars as $id => $sidebar_name ) {
			$selected = ( $id === $sidebar_id ) ? 'selected="selected" ': '';
			$options .= '<option value="' . $id . '" ' . $selected . '>' . $sidebar_name . '</option>';
		}
	}

	return $sidebars_start . $options . $sidebars_end;
}

function ts_get_sidebar($name="")
{
	if ( ! is_singular() ) {
		if ( $name != "" ){
			dynamic_sidebar($name);
		} else {
			dynamic_sidebar();
		}
		return;
	}
}
?>
