	<?php
	$vid = get_post_meta($post->ID, '_video_url', true);
	$upload_image = get_post_meta($post->ID, '_meta_image', true);
    if (!empty($vid)){
	$parts = parse_url($vid);
	$host = $parts['host'];
	if (empty($host)){
		echo 'Unrecognized host';
	} else { 
		$urll=$vid;
		if (strpos($urll, "dailymotion.com")) {
		$dailyid = strtok(basename($urll), '_');
		?>
			<a href="<?php the_permalink() ?>"><img src="http://www.dailymotion.com/thumbnail/video/<?php echo $dailyid?>" width="100px" height="80px" /></a>			
		<?php }
if (strpos($urll, "metacafe.com")) {
	if(preg_match("/((http:\/\/)?(www\.)?metacafe\.com)(\/watch\/)(\d+)(.*)/i", $urll, $match))
		{
			$metaid=$match[5];		
	  ?>  
	<a href="<?php the_permalink() ?>"><img src="http://www.metacafe.com/thumb/<?php echo $metaid; ?>.jpg" width="100px" height="80px" /></a>
							<?php
			} 
		}		
	if (strpos($urll, "vimeo.com")) {
	
$video_id=explode('vimeo.com/', $urll);
$video_id=$video_id[1];
$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));
$vimeourl=$hash[0]['thumbnail_large'];
?>
<a href="<?php the_permalink() ?>"><img src="<?php echo $vimeourl ; ?>" width='100' height='80'/></a>
<?php
		}
	if (strpos($urll, "youtube.com")) {
$query_string = parse_url($urll, PHP_URL_QUERY);
parse_str($query_string, $pieces);
$yurl= $pieces['v'];
?>
<a href="<?php the_permalink() ?>"><img src="http://img.youtube.com/vi/<?php echo $yurl; ?>/mqdefault.jpg" /></a>
<?php 
echo $upload_image; 
}
}
}
if ($upload_image) {
?>
<a href="<?php the_permalink() ?>"><img src="<?php echo $upload_image; ?>" />
<?php
}
?>