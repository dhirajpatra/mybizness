<?php

/************************
*    Banner Settings      *
*************************/
	
		$wp_customize->add_section( 'banner_section' , array(
			'title'      => __('Banner Section', 'business-store' ),			 
			'description'=> __('Create a banner page with images and links, Multiple banner sections and banner widgets Go Pro Version.', 'business-store' ),
			'panel' => 'theme_options',
		) );
		
		$wp_customize->add_setting( 'business_store_option[banner_show]' , array(
			'default'    => 1,
			'sanitize_callback' => 'business_store_sanitize_checkbox',
			'type'=>'option'
		));
		
		$wp_customize->add_control('business_store_option[banner_show]' , array(
			'label' => __('Display Top Banner','business-store' ),
			'section' => 'banner_section',
			'type'=>'checkbox',
		) );
		
		$wp_customize->selective_refresh->add_partial( 'business_store_option[banner_show]', array(
			'selector' => '#custom-banner-page .banner-section',
		) );					
	
		// background Alpha Color Picker setting
		$wp_customize->add_setting(
			'business_store_option[banner_section_background_color]',
			array(
				'default'     => '#fff',
				'type'        => 'option',				
				'transport'   => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
				
			)
		);
		
		// background Alpha Color Picker control
		$wp_customize->add_control(
			new business_store_Alpha_Color_Control(
				$wp_customize,
				'business_store_option[banner_section_background_color]',
				array(
					'label'         =>  __('Section Background Color','business-store' ),
					'section'       => 'banner_section',
					'settings'      => 'business_store_option[banner_section_background_color]',
					'show_opacity'  => true, // Optional.
					'palette'	=> business_store_color_codes(),
				)
			)
		);				
		
		// page
		$wp_customize->add_setting( 'business_store_option[banner_section_page]' , array(
		'default'    => '',
		'sanitize_callback' => 'business_store_sanitize_select',
		'type'=>'option'
		));

		$wp_customize->add_control('business_store_option[banner_section_page]' , array(
		'label' => __('Select a Page to Display','business-store' ),
		'section' => 'banner_section',
		'type'=>'select',
		'choices'=> business_store_get_all_pages(), 
		) );
				
		
