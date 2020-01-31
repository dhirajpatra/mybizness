<?php
/**
 * Displays top navigation
 *
 * @package business-store
 * @since 1.0

 */

?>
<?php if ( has_nav_menu( 'top' ) ) : ?>
<div class="navigation-top">
<nav id="site-navigation" class="main-navigation navigation-font-size" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'business-store' ); ?>">
	<button class="menu-toggle" aria-controls="top-menu" aria-expanded="false">
		<?php
		echo business_store_get_fo( array( 'icon' => 'bars' ) );
		echo business_store_get_fo( array( 'icon' => 'close' ) );
		esc_html_e( 'Menu', 'business-store' );
		?>
	</button>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'top',
			'menu_id'        => 'top-menu',
		)
	);
	?>

</nav><!-- #site-navigation -->

</div>	  

<!-- .navigation-top -->
<?php endif; ?>
