<?php
    global $wpdb, $rating_table_name;
	$rating_table_name = $wpdb->prefix . 'post_ratings';
  	$post_id = $_POST["rating_postid"];
	$rating_val = $_POST["rating_rating"];
	$rating_ip = getenv("REMOTE_ADDR");;
	$rate_userid = $_POST["rating_userid"];
    
    $wpdb->insert(
        $rating_table_name, array(
        'rating_postid' => $post_id,
        'rating_rating' => $rating_val,
        'rating_ip' => $rating_ip,
        'rating_userid' => $rate_userid
        )
   
	?>	    	
