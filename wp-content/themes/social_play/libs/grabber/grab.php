<?php

class FW_Grab
{
	var $res = '';
	var $source = '';
	var $apis = array();
	var $api_settings = array();

	function __construct($url = '')
	{
		include_once('config/config.php');
		
		$this->config = $options;

		$this->api_settings = $api_settings;

		$this->data = $data;

		if( !$url ) return;
		
		if( $this->type = $this->get_type( $url ) )
		{
			/** Don't add videos in audio section */
			if(isset($_REQUEST['add_audio']) && $this->config[$this->type]['source'] != 'soundcloud') return;
			elseif(isset($_REQUEST['add_video']) && $this->config[$this->type]['source'] == 'soundcloud') return;
			
			$this->source = $this->config[$this->type]['source'];
			$this->res = $this->get_data(rtrim($url, '/'), $this->type);
		}
	}
	
	/**
	 @param		string		$url	Link to grab the videos
	 @param		string		$type	URL type (video, playlist, channel)
	 @param		interger 	$number	Number of videos to grab
	 */
	function result($source = null, $data = null)
	{
		$data = (!$data) ? $this->res : $data;

		$source = (!$source) ? $this->source : $source;
		
		$array = (array)kvalue($this->data, $source);

		if ( method_exists($this, $source) )	return $this->$source($data, $array);
		else return false;
	}
	
	function youtube($data, $array, $playlist = false )
	{
		$return = array();
	
		$items = kvalue( $data, 'items' );

		if ( count( $items ) )
		{
			foreach( ( array ) $items as $k => $v )
			{
				if ( $v->kind == 'youtube#playlistItem' )
				{
					$return = $this->yt_fetch_playlistItems( $items, $array );
					break;
				} elseif ( $v->kind == 'youtube#playlist' )
				{
					$playlistData = $this->yt_fetch_playlist( $v->id, $array );

					if ( empty( $return ) )
					{
						$return = $playlistData;
					} else
					{
						foreach( ( array ) $playlistData as $video_data )
						{
							$return[] = $video_data;
						}
					}

				} else 
				{
					$snippet = $v->snippet;
					$contentDetails = $v->contentDetails;
					$statistics = $v->statistics;

					foreach( $array as $key => $val )
					{
						if ( $key == 'id' )
						{
							$return[ $k ][ $key ] = $v->id;
						} elseif ( $key == 'thumb' )
						{
							$return[$k][$key] = ( isset( $snippet->thumbnails->{$val} ) ) ? $snippet->thumbnails->{$val}->url : $snippet->thumbnails->default->url;
						} elseif($key == 'source')
						{
							$return[$k][$key] = 'youtube';	
						} elseif( $key == 'duration')
						{
							$return[ $k ][ $key ] = $this->yt_time( $contentDetails->duration );
						} elseif( $key == 'hd' )
						{
							$return[ $k ][ $key ] = ( isset( $contentDetails->definition ) && $contentDetails->definition == 'hd' ) ? true : false;
						}elseif ( in_array( $key, array('views', 'rating') ) )
						{
							$return[$k][$key] = ( isset( $statistics->{$val} ) ) ? $statistics->{$val} : '';
						} else 
						{
							$return[$k][$key] = ( isset( $snippet->{$val} ) ) ? $snippet->{$val} : '';
						}
					}
				}
			}
		}

		return $return;
	}

	function yt_fetch_playlist( $id, &$array )
	{
		$api_key = kvalue( $this->api_settings, 'yt_api' );

		$limit = kvalue( $this->api_settings, 'yt_videos_limit', 50 );

		$response = wp_remote_get( "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults={$limit}&playlistId={$id}&key={$api_key}" );
		
		if ( is_array($response) )
		{
			$body = json_decode( $response['body'] );
			return $this->youtube( $body, $array, true );
		}
	}

	function yt_fetch_playlistItems( $items, &$array )
	{
		$videoIDs = array();

		foreach( ( array ) $items as $item )
		{
			$videoIDs[] = $item->snippet->resourceId->videoId;
		}

		// Limit the maximum
		// @TODO: add pagination support to fetch all videos from playlist
		$limit = kvalue( $this->api_settings, 'yt_videos_limit', 50 );
		$videoIDs = array_chunk( $videoIDs, $limit );
		
		$data = array();
		
		$api_key = kvalue( $this->api_settings, 'yt_api' );

		foreach( $videoIDs as $ids )
		{
			$ids = implode(',', $ids);
			$api_url = "https://www.googleapis.com/youtube/v3/videos?id={$ids}&part=snippet,contentDetails,statistics&key={$api_key}";

			$response = wp_remote_get( $api_url );

			if ( is_array( $response ) )
			{
				$body = json_decode( $response['body'] );

				if ( empty( $data ) )
				{
					$data = $body;
				} else 
				{
					foreach ( $body->items as $item )
					{
						$data->items[] = $item;
					}
				}
			}

			break;
		}

		return $this->youtube( $data, $array );
	}

	function yt_time( $video_time )
	{
	    if ( preg_match_all('/(\d+)/', $video_time, $parts) )
	    {
	    	$time = array('seconds', 'mintues', 'hours');
	    	$yt_time = array_reverse( $parts[0] );

	    	$the_time = array();

	    	$time_str = '';
	    	foreach( $yt_time as $k => $v )
	    	{
	    		$the_time[ $time[ $k ] ] = ( strlen( $v ) > 1 ) ? $v : 0 . $v;
	    	}

	    	$the_time = array_reverse( $the_time );
	    	$the_time = array_merge( array('hours' => '00', 'mintues' => '00', 'seconds' => '00'), $the_time);
	    	
	    	return strtotime( implode(':', $the_time) ) - strtotime('TODAY');
	    }
	}

	function vimeo($data, $array)
	{
		$return = array();
		foreach( $data as $k => $v )
		{
			foreach($array as $key => $val )
			{
				if($key == 'source') $return[$k][$key] = 'vimeo';
				else $return[$k][$key] = (isset($v->{$val})) ? $v->{$val} : '';
			}
		}
		
		return $return;
	}
	
	function ustream($data, $array)
	{
		$return = array();
		$results = kvalue($data, 'results');
		if( is_array( $results ) )
		{
			foreach( (array)kvalue($data, 'results') as $k => $v )
			{
				foreach($array as $key => $val )
				{
					if( $key == 'thumb' ) $return[$k][$key] = kvalue( kvalue( $v, $val), 'medium');
					else $return[$k][$key] = kvalue( $v, $val, $val );
				}
			}
		}
		else
		{
			foreach($array as $key => $val )
			{
				if( $key == 'thumb' ) $return[0][$key] = kvalue( kvalue( $results, $val), 'medium');
				else $return[0][$key] = kvalue( $results, $val, $val );
			}
		}

		return $return;
	}
	
	function soundcloud($data, $array)
	{
		$return = array();
		if(is_array( $data ) )
		{
			foreach( $data as $k => $v )
			{
				foreach($array as $key => $val )
				{
					$return[$k][$key] = (isset($v->{$val})) ? $v->{$val} : '';
					
					if($key == 'thumb')
					{
						$return[$k][$key] = ($return[$k]['thumb']) ? $return[$k]['thumb'] : $v->{$val}->user->avatar_url;
						$return[$k][$key] = str_replace('-large.', '-t500x500.', $return[$k][$key]);
					}elseif($key == 'source') $return[$k][$key] = 'soundcloud';
				}
			}
			
		}else
		{
			foreach($array as $key => $val)
			{
				$return[0][$key] = (isset($data->{$val})) ? $data->{$val} : '';
				
				if($key == 'thumb')
				{
					$return[0][$key] = ($return[0]['thumb']) ? $return[0]['thumb'] : $data->user->avatar_url;
					$return[0][$key] = str_replace('-large.', '-t500x500.', $return[0][$key]);
				}
				elseif($key == 'source') $return[0][$key] = 'soundcloud';
			}
		}

		return $return;
	}
	
	function dailymotion($data, $array)
	{
		$return = array();

		if( is_array( kvalue( $data, 'list') ) )
		{
			foreach( kvalue( $data, 'list') as $k => $v )
			{
				foreach($array as $key => $val )
				{
					if( $key == 'tags' ) $return[$k][$key] = (is_array( kvalue( $v, $val))) ? implode(', ', kvalue($v, $val)) : kvalue($v, $val);
					elseif($key == 'source') $return[$k][$key] = 'dailymotion';
					else $return[$k][$key] = (isset($v->{$val})) ? $v->{$val} : '';
				}
			}
		}
		else
		{
			foreach($array as $key => $val )
			{
				if( $key == 'tags' ) $return[0][$key] = (is_array( kvalue( $data, $val))) ? implode(', ', kvalue($data, $val)) : kvalue($data, $val);
				elseif($key == 'source') $return[0][$key] = 'dailymotion';
				else $return[0][$key] = (isset($data->{$val})) ? $data->{$val} : '';
			}
		}
		
		return $return;
	}
	
	function blip($data, $array)
	{
		$return = array();
		
		$post = kvalue( kvalue( $data, '0'), 'Post');

		if( ! $post )
		{
			foreach( $data as $k => $v )
			{
				foreach($array as $key => $val )
				{
					if( $key == 'tags' && is_array ( kvalue($v, $val) ) ) {
						$tags = '';
						foreach( (array)kvalue($v, $val) as $tag)
							$tags .= kvalue( $tag, 'name').', ';
						
						$return[$k][$key] = $tags;
					}
					elseif( $key == 'duration' ) $return[$k][$key] = kvalue( kvalue($v, 'media'), $val);
					else $return[$k][$key] = kvalue( $v, $val, $val );
				}
			}
		}
		else
		{
			foreach($array as $key => $val )
			{
				if( $key == 'tags' && is_array ( kvalue($post, $val) ) ) {
					$tags = '';
					foreach( (array)kvalue($post, $val) as $tag)
						$tags .= kvalue( $tag, 'name').', ';
					
					$return[0][$key] = $tags;
				}
				elseif( $key == 'duration' ) $return[0][$key] = kvalue( kvalue($post, 'media'), $val);
				else $return[0][$key] = kvalue( $post, $val, $val );
			}
		}

		return $return;
	}
	
	function metacafe($data, $array)
	{
		$return = array();
		
		$post = kvalue( kvalue( $data, '0'), 'Post');
		$item = kvalue( kvalue($data, 'channel'), 'item');

		if( $item )
		{
			$item = is_array( $item ) ? $item : array($item);
			foreach( $item as $k => $v )
			{
				$v = (array)$v;
				foreach($array as $key => $val )
				{
					$return[$k][$key] = kvalue( $v, $val, $val );
				}
			}
		}
		else return false;

		return $return;
	}
	
	function get_id($url, $type)
	{
		if( isset($this->config[$type]) )
		{
			preg_match($this->config[$type]['regex'], $url, $matches);

			if( isset($matches[1]) ) return $matches[1];
			else return false;
		}
	}
	
	function fetch_links($id, $type)
	{
		if( isset($this->config[$type]) ) return str_replace('{id}', $id, $this->config[$type]['link']);
		else return false;		
	}
	
	function get_data($url, $type)
	{
		@set_time_limit( 120 );

		$id = $this->get_id($url, $type);
		$link = '';

		if($id) $link = $this->fetch_links($id, $type);
		else return false;

		$data = wp_remote_get( $link );

		if ( is_array( $data ) )
		{
			$data = $data['body'];
		}

		return $this->json_decodes( $data );
	}
	
	function get_type($url)
	{
		foreach($this->config as $k => $v )
		{
			preg_match($v['type'], $url, $matches);
			
			if( $matches ) return $k;
		}

		return false;
	}
	
	function json_decodes($str)
	{
		$data = json_decode( (string)$str);
		if( !$data )
		{
			$replace = str_replace( "blip_ws_results([[{", "[{", $str, $replaced_count );
            if($replaced_count > 0) {
                $replace = str_replace( "]);", "", $replace );
            }
            else
            {
                $replace = str_replace( "blip_ws_results([{", "[{", $replace, $replaced_count );
                $replace = str_replace( "]);", "]", $replace );
            }

			$data = json_decode( (string)$replace, true);
			if( !$data ) $data = simplexml_load_string($str);
		}
		
		return $data;
	}
}