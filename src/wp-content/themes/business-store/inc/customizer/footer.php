<?php

/*********************** 
* Footer customization *
***********************/

		$wp_customize->add_section( 'footer_section' , array(
			'title'      => __('Customize Footer', 'business-store' ),			
			'description'=> __('Customize footer. Add widgets Go Customizer -> Widgets. Background image, background color, Footer bottom link and background color customization Go Pro version.', 'business-store' ),
		    'panel' => 'theme_options',
		) );
		
		// footer section bottom background text
		$wp_customize->add_setting( 'business_store_option[footer_section_bottom_text]' , array(
		'default'    => __('A Theme by Ceylon Themes','business-store' ),
		'sanitize_callback' => 'sanitize_text_field',
		'type'=>'option'
		));	
		
		$wp_customize->add_control('business_store_option[footer_section_bottom_text]' , array(
		'label' => __('Footer Bottom Text','business-store' ),
		'section' => 'footer_section',
		'type'=>'text',
		) );
		
		$wp_customize->selective_refresh->add_partial( 'business_store_option[footer_section_bottom_text]', array(
			'selector' => '.site-info',
		) );			
