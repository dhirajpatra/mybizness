<?php
/**
 * Template Name: Blog-Page
 * The template for displaying blog pages
 *
 * @package business-store
 * @since 1.0

 */

get_header();

//get settings
global $business_store_option;	
if ( class_exists( 'WP_Customize_Control' ) ) {
   $business_store_default_settings = new business_store_settings();
   $business_store_option = wp_parse_args(  get_option( 'business_store_option', array() ) , $business_store_default_settings->default_data());  
}
$business_store_content = 'col-sm-8 col-lg-8';
$business_store_sidebar = '';
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	$business_store_content = 'col-sm-12 col-lg-12';
	$business_store_sidebar = 'hide-content';	
}
?>
<div class="container background">

    <div class="row">
		<?php if($business_store_option['blog_sidebar_position']=='left'): ?>
		<div class="col-md-4 col-sm-4 floateleft   <?php echo $business_store_sidebar; ?>" > 
		<?php get_sidebar(); ?>
		</div>
		<?php endif; ?>
	<div id="primary" class="<?php echo $business_store_content; ?>   content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :
		?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				if ( is_archive() ) {
				    
					get_template_part( 'template-parts/post/content', 'excerpt' );
					
				} else {
					
					get_template_part( 'template-parts/post/content', get_post_format() );
					
				}
			endwhile;

			the_posts_pagination(
				array(
				'mid_size' => 0,
					'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'business-store' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'business-store' ) . '</span> <span class="nav-title"><span>' . '<i class="fa fa-arrow-left" aria-hidden="true" ></i>' . '<span class="nav-title nav-margin-left" >'.__( 'View', 'business-store' ).'</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'business-store' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'business-store' ) . '</span> <span class="nav-title">'.__( 'View', 'business-store' ).'<span class="nav-margin-right"></span>' . '<i class="fa fa-arrow-right" aria-hidden="true"></i>'  . '</span>',
					
				)
			);

		else :		
			get_template_part( 'template-parts/post/content', 'none' );
		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php if($business_store_option['blog_sidebar_position']=='right'): ?>
		<div class="col-md-4 col-sm-4 floateright    <?php echo $business_store_sidebar; ?>" > 
		<?php get_sidebar(); ?>
		</div>
	<?php endif; ?>
	</div>
</div><!-- .container -->

<?php
get_footer();