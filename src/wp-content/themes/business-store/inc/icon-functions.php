<?php
/**
 * fontawesome icons related functions and filters
 * @package business-store
 * @since 1.0
 * Return fontawesome markup.
 *
 * @param array $args {
 *     Parameters needed to display an fa.
 *
 *     @type string $icon  Required fa icon filename.
 *     @type string $title Optional fa title.
 *     @type string $desc  Optional fa description.
 * }
 * @return string fo markup.
 */
function business_store_get_fo( $args = array() ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return __( 'Please define default parameters in the form of an array.', 'business-store' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return __( 'Please define an fontawesome icon filename.', 'business-store' );
	}

	// Set defaults.
	$defaults = array(
		'icon'     => '',
		'title'    => '',
		'desc'     => '',
		'fallback' => false,
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true"';

	// Set ARIA.
	$aria_labelledby = '';


	// Begin fontawesome markup.
	$svg = '<span class="fa icon fa-' . esc_attr( $args['icon'] ) . '"' . esc_attr( $aria_hidden ). esc_attr( $aria_labelledby ). ' role="img">';

	$svg .= ' <use href="#icon-' . esc_attr( $args['icon'] ) . '" xlink:href="#icon-' . esc_attr( $args['icon'] ) . '"></use> ';

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] ) {
		$svg .= '<span class="fo-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>'; 
	} 

	$svg .= '</span>'; 

	return $svg;
}


/**
 * Add dropdown icon if menu item has children.
 *
 * @param  string  $title The menu item's title.
 * @param  WP_Post $item  The current menu item.
 * @param  array   $args  An array of wp_nav_menu() arguments.
 * @param  int     $depth Depth of menu item. Used for padding.
 * @return string  $title The menu item's title with dropdown icon.
 */
function business_store_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
	if ( 'top' === $args->theme_location ) {
		foreach ( $item->classes as $value ) {
			if ( 'menu-item-has-children' === $value || 'page_item_has_children' === $value ) {
				$title = $title . business_store_get_fo( array( 'icon' => 'angle-down' ) );
			}
		}
	}

	return $title;
}
add_filter( 'nav_menu_item_title', 'business_store_dropdown_icon_to_menu_link', 10, 4 );

