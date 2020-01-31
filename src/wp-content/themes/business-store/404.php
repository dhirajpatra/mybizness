<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package business-store
 * @since 1.0

 */

get_header(); 

$business_store_content = 'col-sm-8 col-lg-8';
$business_store_sidebar = '';
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	$business_store_content = 'col-sm-12 col-lg-12';
	$business_store_sidebar = 'hide-content';	
}
?>

<div class="container background">
	<div id="primary" class="<?php echo $business_store_content; ?> content-area floateleft">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found text-center">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'business-store' ); ?></h1>
				</header><!-- .page-header -->
				<div class="page-content">
				
				<div class="text-center">
				<i class="fa fa-exclamation-circle page-not-found"></i>
				<span class="page-not-found-text"><?php esc_html_e('404','business-store'); ?></span>
				<h2><?php esc_html_e( 'Search again?', 'business-store' ); ?></h2>
					<div align="center" class='form-404'>
					<?php get_search_form(); ?>
					</div>
				</div>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</main><!-- #main -->	
	</div><!-- #primary -->
	
	<div class="col-md-4 col-sm-4 floatright <?php echo $business_store_sidebar; ?>"> 
		<?php get_sidebar(); ?>
	</div>
		
</div><!-- .container -->

<?php
get_footer();

