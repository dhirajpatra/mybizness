<?php
/*
 * default settings 
 */
if( !class_exists('business_store_settings') ){
	
	class business_store_settings {
	
		function default_data(){
			return array(			
		
			'widget_posts' => 1,			
			'blog_sidebar_position' => 'right',		
			'home_header_section_disable' => 1,
			'woocommerce_header_cart_hide' => 0,
			
			'header_section_hide_header' => 0,
			'contact_section_address' => '',
			'contact_section_email' => '',
			'contact_section_phone' => '',
			'contact_section_hours' => '',		
			'header_myaccount_link' =>  site_url().'/my-account',
			
			'slider_animation_type' => 'fade', //value not displayed to user
			'slider_cat' => 0,
			'slider_image_height' => 450,
			'slider_button_text' => __("More details",'business-store'),
			'slider_button_url' => "#",
			'slider_speed' => 3000,			
			'slider_max_items' => 5,
			'slider_show' => 1,
			'slider_nav_count' => 12,
		
			'banner_section_background_color' => '#fff',
			'banner_section_page' => '',
			'banner_show' => 1,			

			'layout_section_post_one_column' => 0 ,	
			'box_layout' => 0 ,	
	
			'social_facebook_link' => '',
			'social_twitter_link' => '',
			'social_skype_link' => '',
			'social_pinterest_link' => '',
			'social_open_new_tab' => 1,
						
			'footer_section_background_color' => '#fff',
			'footer_section_bottom_color' => '#fff',
			'footer_foreground_color' => '#000',		
            'footer_section_bottom_text' =>  __('A Theme by Ceylon Themes', 'business-store'),			
					
			);
		}
	}	

}

