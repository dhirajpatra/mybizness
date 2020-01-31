<?php

business_store_banner();
function business_store_banner(){
// $business_store_option array declared in functions.php
global $business_store_option;	
if ( class_exists( 'WP_Customize_Control' ) ) {
   $business_store_default_settings = new business_store_settings();
   $business_store_option = wp_parse_args(  get_option( 'business_store_option', array() ) , $business_store_default_settings->default_data());  
}

?>

<section id="custom-banner-page" style="background: <?php echo esc_attr( $business_store_option['banner_section_background_color'] ); ?>;">
  <div class="svc-section-body" >
    <div class="text-center banner-section">

          <?php 
		  if($business_store_option['banner_section_page']!=''){
				// turn on the read more tags in pages	
				$business_store_page = absint($business_store_option['banner_section_page']);
				$business_store_args = array( 'post_type' => 'page','ignore_sticky_posts' => 1 , 'post__in' => array($business_store_page) );
				$business_store_result = new WP_Query($business_store_args);
				while ( $business_store_result->have_posts() ) :
					$business_store_result->the_post();
					the_content();
				endwhile; // End of the loop.
				wp_reset_postdata(); 
		   }
		  ?>

    </div>
  </div>
</section>

<?php
}

