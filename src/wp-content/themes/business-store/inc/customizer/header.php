<?php
/********************
*  contact Settings *
*********************/

		$wp_customize->add_section( 'header_section' , array(
			'title'      => __('Header', 'business-store' ),			
			'description'=>  __('Add contact details to be displayed in top header and contact section and My Account Link', 'business-store' ),
			'panel' => 'theme_options',
		) );
		
		$wp_customize->selective_refresh->add_partial( 'business_store_option[contact_section_phone]', array(
			'selector' => '.contact-list-top',
		) );	
			
		// contact section header show / hide
		$wp_customize->add_setting( 'business_store_option[header_section_hide_header]' , array(
		'default'    => false,
		'sanitize_callback' => 'business_store_sanitize_checkbox',
		'type'=>'option'
		));

		$wp_customize->add_control('business_store_option[header_section_hide_header]' , array(
		'label' => __('Hide Mini Header','business-store' ),
		'section' => 'header_section',
		'type'=>'checkbox',
		) );
		
		// phone
		$wp_customize->add_setting( 'business_store_option[contact_section_phone]' , array(
		'default'    => '(0+)123456789',
		'sanitize_callback' => 'sanitize_text_field',
		'type'=>'option'
		));

		$wp_customize->add_control('business_store_option[contact_section_phone]' , array(
		'label' => __('Phone:','business-store' ),
		'section' => 'header_section',
		'type'=>'text',
		) );
		
		
		// address
		$wp_customize->add_setting( 'business_store_option[contact_section_address]' , array(
		'default'    => __('Address','business-store' ),
		'sanitize_callback' => 'sanitize_text_field',
		'type'=>'option'
		));

		$wp_customize->add_control('business_store_option[contact_section_address]' , array(
		'label' => __('Address','business-store' ),
		'section' => 'header_section',
		'type'=>'text',
		) );

		// email
		$wp_customize->add_setting( 'business_store_option[contact_section_email]' , array(
		'default'    => __('email','business-store' ),
		'sanitize_callback' => 'sanitize_email',
		'type'=>'option'
		));		
		
		$wp_customize->add_control('business_store_option[contact_section_email]' , array(
		'label' => __('Email:','business-store' ),
		'section' => 'header_section',
		'type'=>'text',
		) );
		
		// Work  Hours:
		$wp_customize->add_setting( 'business_store_option[contact_section_hours]' , array(
		'default'    =>  __('Work Hours','business-store' ),
		'sanitize_callback' => 'sanitize_text_field',
		'type'=>'option'
		));		
		
		$wp_customize->add_control('business_store_option[contact_section_hours]' , array(
		'label' => __('Work Hours:','business-store' ),
		'section' => 'header_section',
		'type'=>'text',
		) );
				
	
	