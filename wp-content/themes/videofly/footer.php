		<footer id="footer" role="contentinfo" data-role="footer" data-fullscreen="true">
			<?php echo LayoutCompilator::build_footer(); ?>
		</footer>
	</div>
<?php 	$videofly_general = get_option('videofly_general');
		if( isset($videofly_general['enable_facebook_box']) && $videofly_general['enable_facebook_box'] == 'Y' ){
			tsIncludeScripts(array('bootstrap'));
?>		
			<div class="ts-fb-modal modal fade" id="fbpageModal" tabindex="-1" role="dialog" aria-labelledby="fbpageModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php esc_html_e('Close','videofly') ?></span></button>
							<h4 class="modal-title" id="fbpageModalLabel"><?php esc_html_e('Like our facebook page','videofly'); ?></h4>
						</div>
						<div class="modal-body">
							<div class="fb-page" data-href="https://facebook.com/<?php echo strip_tags($videofly_general['facebook_name']); ?>" data-width="<?php echo (wp_is_mobile() ? '300' : '500') ?>" data-height="350" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true">
								<div class="fb-xfbml-parse-ignore">
									<blockquote cite="https://www.facebook.com/<?php echo strip_tags($videofly_general['facebook_name']); ?>">
										<a href="https://www.facebook.com/<?php echo strip_tags($videofly_general['facebook_name']); ?>">Facebook</a>
									</blockquote>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close','videofly'); ?></button>
						</div>
					</div>
				</div>
			</div>

			<div id="fb-root"></div>
			<script>
				(function(d, s, id) {
				  	var js, fjs = d.getElementsByTagName(s)[0];
				  	if (d.getElementById(id)) return;
				  	js = d.createElement(s); js.id = id;
				  	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
				  	fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>
<?php } ?>

<?php 
$scroll_to_top = get_option( 'videofly_styles' );
$scroll_to_top = (isset($scroll_to_top['scroll_to_top']) && ($scroll_to_top['scroll_to_top'] == 'y' || $scroll_to_top['scroll_to_top'] == 'n')) ? $scroll_to_top['scroll_to_top'] : 'y';
?>

<?php if( $scroll_to_top == 'y' ) : ?>
	<button id="ts-back-to-top"><i class="icon-up"></i><span><?php esc_html_e('Back to top', 'videofly'); ?></span></button>
<?php endif; ?>

<?php echo ts_custom_javascript_code(); ?>
<?php wp_footer(); ?>
</body>
</html>
