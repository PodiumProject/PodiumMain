<?php

/****************************************************************************/
/*                 Footer Setting for the site                             */
/****************************************************************************/
	function cpmmagz_footer_section_customizer( $wp_customize ) {

	// Footer Settings
	$wp_customize->add_section(
		'footer_section_bottom',
		array(
			'title' =>esc_attr( __('Footer Settings for the Site', 'cpmmagz' ) ),
			'description' => 'This is a section for footer options for the site',
			'priority' => 9,
			'panel'    => 'cpm_theme_options_panel',
		)
	);
	$wp_customize->add_setting(
		'footer_copyright',
		array(
			'default' => esc_attr( __('Code Themes. All Rights Reserved.', 'cpmmagz' ) ),
           		'sanitize_callback' => 'esc_attr'
		)
	);
	$wp_customize->add_control(
		'footer_copyright',
		array(
			'label' => esc_attr( __('Site Copyright', 'cpmmagz' ) ),
			'section' => 'footer_section_bottom',
			'type' => 'text',
			'priority' => 1
		)
	);

}
add_action( 'customize_register', 'cpmmagz_footer_section_customizer');
?>