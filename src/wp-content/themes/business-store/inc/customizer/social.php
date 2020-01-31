<?php

/******************** 
*   Social options  *
********************/		

		$wp_customize->add_section( 'social_section' , array(
			'title'      => __('Social', 'business-store' ),			
			'description'=> __('Display social icons and links in site header and footer. More social links Go Pro version.', 'business-store' ),
			'panel' => 'theme_options',
		) );
		
		
		$wp_customize->add_setting( 'business_store_option[social_open_new_tab]' , array(
		'default'    => 1,
		'sanitize_callback' => 'business_store_sanitize_checkbox',
		'type'=>'option'
		));
		
		$wp_customize->add_control('business_store_option[social_open_new_tab]' , array(
		'label' => __('Open Social Links in New Tab','business-store' ),
		'section' => 'social_section',
		'type'=>'checkbox',
		) );
		
		$wp_customize->selective_refresh->add_partial( 'business_store_option[social_open_new_tab]', array(
			'selector' => '.mimi-header-social-icon',
		) );


		$wp_customize->add_setting( 'business_store_option[social_facebook_link]' , array(
		'default'    => 'facebook.com',
		'sanitize_callback' => 'esc_url_raw',
		'type'=>'option'
		));
		
		$wp_customize->selective_refresh->add_partial( 'business_store_option[social_facebook_link]', array(
			'selector' => '#footer-social',
		) );
		
		$wp_customize->add_control('business_store_option[social_facebook_link]' , array(
		'label' => __('Facebook Link','business-store' ),
		'section' => 'social_section',
		'type'=>'url',
		) );

		$wp_customize->add_setting( 'business_store_option[social_twitter_link]' , array(
		'default'    => 'twitter.com',
		'sanitize_callback' => 'esc_url_raw',
		'type'=>'option'
		));	
		
		$wp_customize->add_control('business_store_option[social_twitter_link]' , array(
		'label' => __('Twitter Link','business-store' ),
		'section' => 'social_section',
		'type'=>'url',
		) );


		$wp_customize->add_setting( 'business_store_option[social_skype_link]' , array(
		'default'    => 'skype.com',
		'sanitize_callback' => 'esc_url_raw',
		'type'=>'option'
		));	
		
		$wp_customize->add_control('business_store_option[social_skype_link]' , array(
		'label' => __('Skype Link','business-store' ),
		'section' => 'social_section',
		'type'=>'url',
		) );							

		$wp_customize->add_setting( 'business_store_option[social_pinterest_link]' , array(
		'default'    => 'pinterest.com',
		'sanitize_callback' => 'esc_url_raw',
		'type'=>'option'
		));	
		
		$wp_customize->add_control('business_store_option[social_pinterest_link]' , array(
		'label' => __('Pinterest Link','business-store' ),
		'section' => 'social_section',
		'type'=>'url',
		) );
		
		//account link		
		$wp_customize->add_setting( 'business_store_option[header_myaccount_link]' , array(
		'default'    =>  site_url().'/my-account',
		'sanitize_callback' => 'esc_url_raw',
		'type'=>'option'
		));	
		
		$wp_customize->add_control('business_store_option[header_myaccount_link]' , array(
		'label' => __('My Account Link','business-store' ),
		'section' => 'social_section',
		'type'=>'url',
		) );

				
