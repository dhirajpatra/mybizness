<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package business-store

 */

$business_store_default_settings = new business_store_settings();
$business_store_option = wp_parse_args(  get_option( 'business_store_option', array() ) , $business_store_default_settings->default_data());

$business_store_class = '';

$business_store_class = $business_store_class. ' footer-foreground';

?>

<footer id="colophon" role="contentinfo" class="site-footer  <?php echo esc_attr( $business_store_class );?>" >
  <div class="footer-section <?php echo esc_attr( $business_store_class );?>" >
    <div class="container">
	<!--widgets area-->
	<aside class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'business-store' ); ?>">
		<?php
		if ( is_active_sidebar( 'footer-sidebar-1' ) ) {
		?>
			<div class="col-md-3 col-sm-3 footer-widget">
				<?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
			</div>
		<?php
		}
		if ( is_active_sidebar( 'footer-sidebar-2' ) ) {
		?>
			<div class="col-md-3 col-sm-3 footer-widget">
				<?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
			</div>			
		<?php
		}
		if ( is_active_sidebar( 'footer-sidebar-3' ) ) {
		?>
			<div class="col-md-3 col-sm-3 footer-widget">
				<?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
			</div>
		<?php
		}
		if ( is_active_sidebar( 'footer-sidebar-4' ) ) {
		?>
			<div class="col-md-3 col-sm-3 footer-widget">
				<?php dynamic_sidebar( 'footer-sidebar-4' ); ?>
			</div>
        <?php }	?>
	</aside><!-- .widget-area -->

      <div class="col-md-12">
        <center>
          <ul id="footer-social" class="header-social-icon animate fadeInRight" >
            <?php if($business_store_option['social_facebook_link']!=''){?>
            <li><a href="<?php echo esc_url($business_store_option['social_facebook_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="facebook" data-toggle="tooltip" title="<?php esc_attr_e('Facebook','business-store'); ?>"><i class="fa fa-facebook"></i></a></li>
            <?php } ?>
            <?php if($business_store_option['social_twitter_link']!=''){?>
            <li><a href="<?php echo esc_url($business_store_option['social_twitter_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="twitter" data-toggle="tooltip" title="<?php esc_attr_e('Twitter','business-store'); ?>"><i class="fa fa-twitter"></i></a></li>
            <?php } ?>
            <?php if($business_store_option['social_skype_link']!=''){?>
            <li><a href="<?php echo esc_url($business_store_option['social_skype_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="skype" data-toggle="tooltip" title="<?php esc_attr_e('Skype','business-store'); ?>"><i class="fa fa-skype"></i></a></li>
            <?php } ?>
            <?php if($business_store_option['social_pinterest_link']!=''){?>
            <li><a href="<?php echo esc_url($business_store_option['social_pinterest_link']); ?>" target="<?php if($business_store_option['social_open_new_tab']=='1'){echo '_blank';} ?>" class="pinterest" data-toggle="tooltip" title="<?php esc_attr_e('Google-Plus','business-store'); ?>"><i class="fa fa-pinterest"></i></a></li>
            <?php } ?>				
          </ul>
        </center>
      </div>
      <div class="col-md-12 bottom-menu">
        <center>         
		  	<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_id'        => 'footer-menu',
					'container_class' => 'bottom-menu'
				)
			);
			?>
        </center>
      </div>
	  
    </div>
    <!-- .container -->
	
    <!-- bottom footer -->
    <div class="col-md-12 site-info">
      <p align="center" style="color:#fff;" > <a href="<?php echo esc_url(business_store_THEME_AUTHOR_URL); ?>"> <?php echo esc_html($business_store_option['footer_section_bottom_text']); ?> </a> </p>
    </div>
    <!-- end of bottom footer -->	
	
  </div>
  <a id="scroll-btn" href="#" class="scroll-top"><i class="fa fa-angle-up"></i></a>
</footer>
<!-- #colophon -->
<?php 
global $business_store_option;	
if ( class_exists( 'WP_Customize_Control' ) ) {
   $business_store_default_settings = new business_store_settings();
   $business_store_option = wp_parse_args(  get_option( 'business_store_option', array() ) , $business_store_default_settings->default_data());  
}
if($business_store_option['box_layout']){
	// end of wrapper div
	echo '</div>';
}

wp_footer(); 
?>
</body>
</html>