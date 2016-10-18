<?php
    //customizer for Theme color section

    function cpmmagz_color_customizer( $wp_customize ) {
        $wp_customize->add_section(
            'color_section',
            array(
                'title' =>esc_attr( __('Choose Theme Color', 'cpmmagz' ) ),
                'priority' => 1,
                'panel'    => 'cpm_theme_options_panel',
                )
            );
         $wp_customize->add_setting( 'theme-color'  , array(
            'default'           => 'default',
            'sanitize_callback' => 'esc_attr'
        ) );

        $wp_customize->add_control( 'theme-color', array(
            'label'     => esc_attr( __( 'Choose theme color :', 'cpmmagz' ) ),
            'section'   => 'color_section',
            'settings' => 'theme-color',
            'type'      => 'radio',
            'choices'    => array(
                'default' => esc_attr__( 'Default', 'cpmmagz' ),
                'green' => esc_attr( __( 'Green', 'cpmmagz') ),
                'purple' => esc_attr( __( 'Purple', 'cpmmagz') ),
                'blue' => esc_attr( __( 'Blue', 'cpmmagz') ),
                'red' => esc_attr( __( 'Red', 'cpmmagz') ),
                'turquoise' => esc_attr( __( 'Turquoise', 'cpmmagz') ),
                'aspalt' => esc_attr( __( 'Aspalt', 'cpmmagz') ),
            ),
        ) );
    }
add_action( 'customize_register', 'cpmmagz_color_customizer');
?>