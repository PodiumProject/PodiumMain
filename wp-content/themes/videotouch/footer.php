
		<footer class="" role="contentinfo" data-role="footer" data-fullscreen="true">
			<?php echo LayoutCompilator::build_footer(); ?>
		</footer>
	</div>
<?php 	$videotouch_general = get_option('videotouch_general');
		if( isset($videotouch_general['enable_facebook_box']) && $videotouch_general['enable_facebook_box'] == 'Y' ){
			tsIncludeScripts(array('bootstrap'));
?>
			<div class="ts-fb-modal modal fade" id="fbpageModal" tabindex="-1" role="dialog" aria-labelledby="fbpageModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php _e('Close','touchsize') ?></span></button>
							<h4 class="modal-title" id="fbpageModalLabel"><?php _e('Like our facebook page','touchsize')?></h4>
						</div>
						<div class="modal-body">
							<div class="fb-page" data-href="https://facebook.com/<?php echo strip_tags($videotouch_general['facebook_name']); ?>" data-width="500" data-height="350" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true">
								<div class="fb-xfbml-parse-ignore">
									<blockquote cite="https://www.facebook.com/<?php echo strip_tags($videotouch_general['facebook_name']); ?>">
										<a href="https://www.facebook.com/<?php echo strip_tags($videotouch_general['facebook_name']); ?>">Facebook</a>
									</blockquote>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close','touchsize'); ?></button>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				jQuery(window).ready(function(){
					(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));

				});
				jQuery(window).load(function() {
    				fb_likeus_modal(5);
    			});
			</script>

<?php } ?>

<?php echo ts_tracking_code(); ?>
<?php wp_footer(); ?>
</body>
</html>
