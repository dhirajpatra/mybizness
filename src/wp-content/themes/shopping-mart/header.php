<?php
/**
 * The header
 * @package business-mart
 * @since 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php 
wp_head();
//get settings array 
global $business_store_option;	
if ( class_exists( 'WP_Customize_Control' ) ) {
   $business_store_default_settings = new business_store_settings();
   $business_store_option = wp_parse_args(  get_option( 'business_store_option', array() ) , $business_store_default_settings->default_data());  
}
?>
</head>
<body <?php body_class(); ?> >
<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<!-- The Search Modal Dialog -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span id="search-close" class="close">&times;</span>
	<br/> <br/>
    <?php get_template_part( 'searchform'); ?>
	<br/> 
  </div>
</div><!-- end search model-->

<div id="page" class="site">

<?php 
if($business_store_option['box_layout']){
  echo '<div class="wrap-box">';
}

?>

<a class="skip-link screen-reader-text" href="#content">
<?php esc_html_e( 'Skip to content', 'shopping-mart' ); ?>
</a>
<header id="masthead" class="site-header site-header-background" role="banner" >

	<!-- start of mini header -->
	<?php if(!$business_store_option['header_section_hide_header']): ?>	      
			<div class="mini-header">
				<div class="container vertical-center">
					
						<div id="mini-header-contacts" class="col-md-8 col-sm-8 lr-clear-padding" >
						 
							<ul class="contact-list-top">
							<?php if($business_store_option['contact_section_phone']!=''): ?>					  
								<li><i class="fa fa-phone"></i><span class="contact-margin"><?php echo esc_html($business_store_option['contact_section_phone']); ?></span></li>
							<?php endif; ?>
							<?php if($business_store_option['contact_section_email']!=''): ?>
								<li class="contact-margin border-left "><i class="fa fa-envelope" ></i><a class="header-email" href="<?php echo esc_url( 'mailto:'.$business_store_option['contact_section_email'] ); ?>"><span class="contact-margin"><?php echo esc_html($business_store_option['contact_section_email']); ?></span></a></li>
							<?php endif; ?>
							<?php if($business_store_option['contact_section_address']!=''): ?>
								<li class="contact-margin border-left google-map-link"><i class="fa fa-map" ></i><span class="contact-margin"><?php echo esc_html($business_store_option['contact_section_address']); ?></span></li>
							<?php endif; ?>
							<?php if($business_store_option['contact_section_hours']!=''): ?>
								<li class="contact-margin border-left work-hours"><i class="fa fa-clock-o" ></i><span class="contact-margin"><?php echo esc_html($business_store_option['contact_section_hours']); ?></span></li>
							<?php endif; ?>														
							</ul>
						 
						</div>
						<div class="col-md-4 col-sm-4 lr-clear-padding">			
							<ul class="mimi-header-social-icon pull-right animate fadeInRight" >
							    <?php echo '<li class="login-register"><i class="fa fa-user-circle"></i>&nbsp;<a href="'.esc_url($business_store_option['header_myaccount_link']).'" >'.esc_html__('My Account', 'shopping-mart').'</a>  &nbsp;'; ?>							
								<?php if($business_store_option['social_facebook_link']!=''){?> <li><a href="<?php echo esc_url($business_store_option['social_facebook_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="facebook" data-toggle="tooltip" title="<?php esc_attr_e('Facebook','shopping-mart'); ?>"><i class="fa fa-facebook"></i></a></li><?php } ?>
								<?php if($business_store_option['social_twitter_link']!=''){?> <li><a href="<?php echo esc_url($business_store_option['social_twitter_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="twitter" data-toggle="tooltip" title="<?php esc_attr_e('Twitter','shopping-mart'); ?>"><i class="fa fa-twitter"></i></a></li><?php } ?>
								<?php if($business_store_option['social_skype_link']!=''){?> <li><a href="<?php echo esc_url($business_store_option['social_skype_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="skype" data-toggle="tooltip" title="<?php esc_attr_e('Skype','shopping-mart'); ?>"><i class="fa fa-skype"></i></a></li><?php } ?>
								<?php if($business_store_option['social_pinterest_link']!=''){?> <li><a href="<?php echo esc_url($business_store_option['social_pinterest_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="pinterest" data-toggle="tooltip" title="<?php esc_attr_e('Pinterest','shopping-mart'); ?>"><i class="fa fa-pinterest"></i></a></li><?php } ?>
							</ul>
						</div>	
					
				</div>	
			</div>
		<?php endif; ?>		
	 <!-- .end of contacts mini header -->

<!--start of site branding search-->
<div class="container ">
	<div class="vertical-center">
	
		<div class="col-md-4 col-sm-4 col-xs-12 site-branding" >
		
		  <?php if ( has_custom_logo() ) : ?>
		  	<?php the_custom_logo(); ?>
		  <?php endif; ?>
		  
		  <div class="site-branding-text">
			<?php if ( is_front_page() ) : ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			  <?php bloginfo( 'name' ); ?>
			  </a></h1>
			<?php else : ?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			  <?php bloginfo( 'name' ); ?>
			  </a></p>
			<?php endif; ?>
			<?php $business_store_description = get_bloginfo( 'description', 'display' ); if ( $business_store_description || is_customize_preview() ) : ?>
			<p class="site-description"><?php echo esc_html($business_store_description); ?></p>
			<?php endif; ?>
		  </div>
		</div>
		<!-- .end of site-branding -->
		
		<div class="col-sm-8 col-xs-12 vertical-center"><!--  menu, search -->
		<?php if(class_exists( 'WooCommerce' )): ?>
		
		<div class="col-md-7 col-lg-7 col-sm-7 col-xs-12 header-search-form">
			<?php the_widget('business_store_lite_product_search_widget'); ?> 
		</div>
		
		<div class="col-md-5 col-lg-5 col-sm-5 col-xs-12">
				<div id="cart-wishlist-container">
					<table>
					<tr>
					<td>
					  <?php if(class_exists('YITH_WCWL')): ?>
					  <div id="wishlist-top" class="wishlist-top">
						<div class="wishlist-container">
						  <?php business_store_wishlist_count(); ?>
						</div>
					  </div>
					  <?php endif; ?> 
					</td>
					<td>
					  <div id="cart-top" class="cart-top">
						<div class="cart-container">
						  <?php do_action( 'woocommerce_cart_top' ); ?>
						</div>
					  </div>
					</td>
					</tr>
					</table>
				</div>
		 </div>
		
		<?php else: ?>
		<div id="sticky-nav" class="top-menu-layout-2" > <!--start of navigation-->
		  <div class="container">
		  <div class="row vertical-center">
			<!-- start of navigation menu -->
			<div class="navigation-center-align">
			  <?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
			</div>
			<!-- end of navigation menu -->
			</div>
		  </div>
		  <!-- .container -->
		</div>		 
		<?php endif; ?> 
		 
	</div><!-- .menu, search --> 
	
   </div>
</div>
<!-- .end of site-branding, search -->
	 
	  
<?php if(class_exists( 'WooCommerce' )): ?>
<div id="sticky-nav" > <!--start of navigation-->
	<div class="container">
	<div class="row vertical-center">
		<!-- start of navigation menu -->
		<div class="col-sm-12 col-lg-12 col-xs-12">
			<?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
		</div>
		<!-- end of navigation menu -->
	</div>
	</div>
<!-- .container -->
</div>
<?php endif; ?> 

</header><!-- #masthead -->

<?php

if($business_store_option['banner_show'] && $business_store_option['banner_section_page']!='' && is_front_page()){
	get_template_part( 'sections/banner', 'section');
}
if(class_exists( 'WooCommerce' )) {
	if($business_store_option['slider_show'] && is_front_page()){
		get_template_part( 'sections/slider', 'section');
	}
}
?>


<div id="content">

