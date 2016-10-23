jQuery(document).ready(function($) {
	// Tooltips on the dashboard icons
	$( ".tips, .help_tip" ).tipTip({
		'attribute' : 'data-tip',
		'fadeIn' : 50,
		'fadeOut' : 50,
		'delay' : 200
	});
});

$j = jQuery.noConflict();

$j(document).ready(
	function() {

		$j( '.hide-if-no-js' ).show();

		$j( '#feup_members-add-new-cap' ).click(
			function() {
				$j( 'p.new-cap-holder' ).append( '<input type="text" class="new-cap" name="new-cap[]" value="" size="20" />' );
			}
		);

		$j( 'div.feup_members-role-checkbox input[type="checkbox"]' ).click(
			function() {
				if ( $j( this ).is( ':checked' ) ) {
					$j( this ).next( 'label' ).addClass( 'has-cap' );
				}
				else {
					$j( this ).next( 'label' ).removeClass( 'has-cap' );
				}
			}
		);
	}
);