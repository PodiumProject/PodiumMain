<?php
define('WP_USE_THEMES', false);

// Loads the WordPress Environment and Template 
require(dirname(__FILE__) .'/../../../../wp-blog-header.php');

$patterns = array(
	'45degree_fabric',
	'arches',
	'assault',
	'az_subtle',
	'back_pattern',
	'bedge_grunge',
	'beige_paper',
	'bgnoise_lg',
	'billie_holiday',
	'black-Linen',
	'black_denim',
	'black_linen_v2',
	'black_mamba',
	'black_paper',
	'black_scales',
	'blackmamba',
	'blizzard',
	'bright_squares',
	'brillant',
	'broken_noise',
	'carbon_fibre',
	'cardboard_flat',
	'chruch',
	'circles',
	'classy_fabric',
	'clean_textile',
	'concrete_wall',
	'cracks_1',
	'crossed_stripes',
	'crosses',
	'cubes',
	'dark_tire',
	'dark_mosaic',
	'dark_stripes',
	'dark_wall',
	'dark_wood',
	'debut_light',
	'diagmonds',
	'diamond_upholstery',
	'double_lined',
	'fake_brick',
	'first_aid_kit',
	'green_fibers',
	'irongrip',
	'lghtmesh',
	'light_wool',
	'little_triangles',
	'pinstripe',
	'retina_wood',
	'rubber_grip',
	'shattered',
	'striped_lens',
	'subtle_carbon',
	'subtle_dots',
	'subtlenet2',
	'tileable_wood_texture',
	'tiny_grid',
	'wild_oliva',
	'wood_1',
	'worn_dots',
	'zigzag',
);

$directory = get_template_directory() . "/images/patterns";
$filenames = array();
$iterator = new DirectoryIterator($directory);

$counter = 0;
$blocks = array();
$elements = '';
$css = '';

$total = count($patterns);

foreach ($patterns as $pattern) {
	$counter++;
	$elements .= '<li id="pattern_' . $pattern . '">&nbsp;</li>' . "\n";
	$css .= "#pattern_" . $pattern . "{ background: url('".get_template_directory_uri()."/images/patterns/".$pattern.".png') repeat; } \n";

	if ( $counter % 21 === 0 || $counter === $total ) {
		$blocks[] = '<div class="patterns-slide"><ul class="patterns">' . $elements . '</ul></div>';
		$elements = '';
	}
}
?>
<!doctype html>
<html>
<head>
	<style>
			ul.patterns{
				margin: 0;
				padding: 0;
				width: 960px;
			}
			ul.patterns li{
				padding: 0px;
				list-style-type: none;
				width: 100px;
				height: 100px;
				display: inline-block;
				border:5px solid #EEE;
				margin:5px;
			}
			.slider{
				position:relative;
				overflow:hidden;
				width: 960px;
				height:360px;
			}

			.slider .items{
			  width:20000em;
			  position:absolute;
			}

			.items div {
			  float:left;
			}

		<?php echo $css; ?>

		</style>
		<script src="<?php echo get_template_directory_uri(); ?>/admin/js/jquery.tools.min.js"></script>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/scrollable-buttons.css" media="all">
		<script>
		jQuery(document).ready(function($) {
			$('.patterns li').on('click', function(event) {
				event.preventDefault();
				$('.patterns li').css({'border-color':'#EEE'});
				$(this).css({'border-color':'#5476BF'});

				var pattern = $(this).attr('id').replace('pattern_','');
				$("#pattern-demo").css({'background-image':'url(<?php echo get_template_directory_uri(); ?>/images/patterns/'+pattern+'.png)'});
				$("#videotouch-bg-pattern").val(pattern+'.png');
				tb_remove();
			});
			$(".slider").scrollable().navigator();
		});
		
		</script>
</head>
<body>
<div class="slider">
	<div class="navi"></div>
	<div class="items">
		<?php echo implode("\n", $blocks); ?>
	</div>
	</div>
	<a class="prev browse left">&larr;</a>
	<a class="next browse right">&rarr;</a>
</body>
</html>
