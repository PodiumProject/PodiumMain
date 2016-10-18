<?php
	//customizer for Highlight section
function cpmmagz_highlight_section_customizer( $wp_customize ) {

      $wp_customize->add_setting(
         'highlight_title',
         array(
            'default' => 'Highlights',
            'sanitize_callback' => 'esc_attr',

            )
      );

    $wp_customize->add_control(
     'highlight_title',
     array(
        'label' => esc_attr( __( 'Highlight title', 'cpmmagz' ) ),
        'section' => 'showcase_single_highlight',
        'type' => 'text',
        'priority' => 32
        )
     );

    /*showcase single post*/
    $wp_customize->add_section( 'showcase_single_highlight' , array(
        'title'      => esc_attr( __( 'Hightlight Section ', 'cpmmagz' ) ),
        'panel'    => 'cpm_theme_options_panel',
        'priority'   => 5,
        ));
            // Add color scheme setting and control.
    $wp_customize->add_setting( 'showcase-single-category'  , array(
        'default'           => '',
        'sanitize_callback' => 'esc_attr'
        ) );

    $wp_customize->add_control( new WP_Customize_Category_Control(
       $wp_customize,'showcase-single-category' , array(
        'label'    => esc_attr( __( 'Select Category', 'cpmmagz' ) ),
        'section'  => 'showcase_single_highlight',
        'type'     => 'select',
        ) ));
}
add_action( 'customize_register', 'cpmmagz_highlight_section_customizer');
?>