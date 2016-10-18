<?php get_header(); ?>
<div class="container">
<section id="main">
	<div id="primary" class="col-lg-12">
		<div id="content" role="main">
			<div class="row content-block">
				<div class="col-lg-12">
					<h1 class="title-404"><i class="icon-attention"></i><?php _e('Ooops!', 'touchsize');?></h1>
					<div class="nothing-message"><?php _e('Error 404. We didn\'t find anything. Try searching!', 'touchsize');?></div>
					<div class="search-404">
						<?php echo LayoutCompilator::searchbox_element('searchbox'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</div>
<?php get_footer(); ?>
