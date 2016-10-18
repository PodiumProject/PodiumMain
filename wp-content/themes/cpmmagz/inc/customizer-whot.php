<?php
	//customizer for Highlight section

	function cpmmagz_whot_section_customizer( $wp_customize ) {
			/*showcase single post*/
            	$wp_customize->add_section( 'showcase_whot_category' , array(
            	            'title'      => esc_html( __( 'What\'s hot Section', 'cpmmagz' ) ),
            		        'priority'   => 6,
                            'panel'    => 'cpm_theme_options_panel',
            	    ) );

            	$wp_customize->add_setting(
            		'hot_title',
            		array(
            			'default' => 'What\'s hot?',
            		 	'sanitize_callback' => 'esc_attr'
            		)
            	);
            	$wp_customize->add_control(
            		'hot_title',
            		array(
            			'label' => esc_attr( __( 'What\'s hot title', 'cpmmagz' ) ),
            			'section' => 'showcase_whot_category',
            			'type' => 'text',
            			'priority' => 35           		)
            	);

                    $wp_customize->add_setting( 'showcase-whot-category'  , array(
                        'default'           => '',
                        'sanitize_callback' => 'esc_attr'
                        ) );

                    $wp_customize->add_control(  new WP_Customize_Category_Control(
                    $wp_customize,'showcase-whot-category' , array(
                        'label'    => esc_attr( __( 'Select Category', 'cpmmagz' ) ),
                        'section'  => 'showcase_whot_category',
                        'type'     => 'select',
                    )
                    ));

}
	add_action( 'customize_register', 'cpmmagz_whot_section_customizer');
?>