<?php

/*****************
 * Custom fonts. *
 *****************/

	$wp_customize->add_section( 'font_section' , array(
		'title'      => __('Fonts', 'business-store' ),
		'description' => __('500+ google fonts. Go Pro version.', 'business-store' ),			
	) );
	
	
	$wp_customize->add_setting(
		'header_fontfamily', array(
			'default'           => 'Oswald',			
			'transport'         => 'refresh',
			'sanitize_callback' => 'business_store_sanitize_font_family',  
		)
	);
	
	$wp_customize->add_control( 'header_fontfamily' , array(
		'label' => __('Headings Font Family','business-store'),
		'section' => 'font_section',
		'type'=>'select',
		'choices'=> business_store_font_family(),
	) );
		
	$wp_customize->add_setting(
		'body_fontfamily', array(
			'default'           => 'Lora',			
			'transport'         => 'refresh',
			'sanitize_callback' => 'business_store_sanitize_font_family',  
		)
	);	

	
	$wp_customize->add_control( 'body_fontfamily' , array(
		'label' => __('Body Font Family', 'business-store'),
		'section' => 'font_section',
		'type'=>'select',
		'choices'=> business_store_font_family(),
	) );
	
