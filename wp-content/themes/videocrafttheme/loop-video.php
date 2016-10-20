<?php
/**
 * Main loop for displaying Video
 *
 * @package VideoCast
 * @author InkThemes
 *
 */
?>
<?php
$limit = get_option('posts_per_page');
$post_type = 'video_listing';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
query_posts("post_type=$post_type&showposts=$limit&paged=$paged");
$wp_query->is_archive = true;
$wp_query->is_home = false;
?>
<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
<!--post start-->
<div class="post">
  <h1 class="post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
    <?php the_title(); ?>
    </a></h1>
  <div class="post_content video">
  <div class="video_container_loop">
          <div class="video_player_container_loop">
            <div class="video_player_loop">
	 <?php
$meta_video_url = get_post_meta($post->ID, '_video_url', true);	
$name = get_post_meta($post->ID, '_own_url', true);
if (!empty($meta_video_url)){
	$parts = parse_url($meta_video_url);
	$host = $parts['host'];
	if (empty($host)){
		echo 'Unrecognized host';
	} else { 
		$urll=$meta_video_url;
		if (strpos($urll, "dailymotion.com")) {
		$dailyid = strtok(basename($urll), '_');
		?>
    <iframe src="http://www.dailymotion.com/embed/video/<?php echo $dailyid; ?>" type="application/x-shockwave-flash" width="705" height="400" allowfullscreen="true"></iframe>
  <?php }
if (strpos($urll, "metacafe.com")) {
	if(preg_match("/((http:\/\/)?(www\.)?metacafe\.com)(\/watch\/)(\d+)(.*)/i", $urll, $match))
		{
			$metaid=$match[5];		
	  ?>
  <embed class="metacafe" src="http://www.metacafe.com/fplayer/<?php echo $metaid; ?>/meta.swf" width="705" height="400" allowfullscreen="true" wmode="transparent"  pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
  <?php
	} 
		}
if (strpos($urll, "vimeo.com")) {
$video_id=explode('vimeo.com/', $urll);
$video_id=$video_id[1];	
?>
<iframe src="http://player.vimeo.com/video/<?php echo $video_id; ?>"  frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen="true" allowscriptaccess="always" width="705" height="400"></iframe> 
  <?php
$date=$video['upload_date'];
$thumbvimeo = $video['thumb_large'];  //vimeo
}	
	?>
		<?php
	if (strpos($urll, "youtube.com")) {
//youtube coding....
$html="<a href=".$urll."</a></p>";
$regex="/v\=([\-\w]+)/";
preg_match_all($regex,$html,$out);
$out[1]=array_unique($out[1]);
foreach($out[1] as $youtube){
$youtube;
}
?>
<iframe title="YouTube video" src="http://www.youtube.com/embed/<?php echo $youtube; ?>" frameborder="0" allowfullscreen></iframe> 
<?php
$thumbyou = $video['thumb_1'];  //youtube 
echo "<a href=\"\"><img src=\"$thumbyou\" alt=\"\" /></a>";
}
	}	
}
//  Uploaded Video
 		$upload_dir = wp_upload_dir();
		$baseurl= $upload_dir['baseurl'];
		$upload_video = get_post_meta($post->ID, '_meta_video', true);
		$upload_image = get_post_meta($post->ID, '_meta_image', true);
	            if($upload_video){
				$imageurl = $upload_image;
				$videourl = $upload_video;
				?>		
		<div id="container<?php echo $count; ?>">Loading the player ... </div></br>	  	
    <script type="text/javascript">	
jwplayer("container<?php echo $count; ?>").setup({
flashplayer: "<?php echo get_template_directory_uri().'/js/player.swf' ?>",
height: 400,
width: 705,
image: "<?php echo $imageurl ?>",
levels: [
{ file: "<?php echo $videourl ?>"}, // H.264 version
{ file: "<?php echo $videourl ?>" }, // WebM version
{ file: "<?php echo $videourl ?>" } // Ogg Theroa version
]
});
</script>   
<?php  
$count++;  
}
?> 
</div>
</div>
</div>
    <?php the_excerpt(); ?>
    <a class="more" href="<?php the_permalink() ?>">More &rarr;</a> </div>
  <ul class="post_meta">
    <li class="post_date">&nbsp;&nbsp;<?php echo get_the_time('M, d, Y') ?></li>
    <li class="posted_by">&nbsp;&nbsp;<span>Author:&nbsp;</span>
      <?php the_author_posts_link(); ?>
    </li>
    <li class="post_category">&nbsp;&nbsp;<span>Categories:&nbsp;</span>
      <?php 
	  global $post;
		echo get_the_term_list( $post->ID, 'video_cat', '', ' ', '' ); 
	  ?>
    </li>
    <li class="post_comment">
      <?php comments_popup_link('No Comments.', '1 Comment.', '% Comments.'); ?>
    </li>
  </ul>
  <div class="clear"></div>
</div>
<!--End Post-->
<?php endwhile;
else: ?>
<div class="post">
  <p>
    <?php _e('Sorry, no posts matched your criteria.', 'videocraft'); ?>
  </p>
</div>
<?php endif; 
wp_reset_query();
?>
<!--End Loop-->
