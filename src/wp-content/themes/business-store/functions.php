<?php
/**
 * business-store functions and definitions
 *
 * @package business-store
 * @since 1.0
 */

/**
 * Theme only works in WordPress 4.8 or later.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('business_store_THEME_NAME','Business Store');
define('business_store_THEME_SLUG','business-store');
define('business_store_THEME_URL','http://www.ceylonthemes.com/product/business-store-pro');
define('business_store_THEME_AUTHOR_URL','http://www.ceylonthemes.com');
define('business_store_THEME_DOC','https://www.ceylonthemes.com/wp-tutorials/business-store-theme-tutorial/');
define('business_store_THEME_REVIEW_URL','https://wordpress.org/support/theme/'.business_store_THEME_SLUG.'/reviews/');
define('business_store_TEMPLATE_DIR',get_template_directory());
define('business_store_TEMPLATE_DIR_URI',get_template_directory_uri());

$business_store_uniqueue_id = 0;
/**
 * Set a constant that holds the theme's minimum supported PHP version.
 */
define( 'business_store_PHP_VERSION', '5.6' );

/**
 * Immediately after theme switch is fired we we want to check php version and
 * revert to previously active theme if version is below our minimum.
 */
add_action( 'after_switch_theme', 'business_store_test_for_min_php' );



/**
 * Switches back to the previous theme if the minimum PHP version is not met.
 */
function business_store_test_for_min_php() {

	// Compare versions.
	if ( version_compare( PHP_VERSION, business_store_PHP_VERSION, '<' ) ) {
		// Site doesn't meet themes min php requirements, add notice...
		add_action( 'admin_notices', 'business_store_php_not_met_notice' );
		// ... and switch back to previous theme.
		switch_theme( get_option( 'theme_switched' ) );
		return false;

	};
}

/**
 * An error notice that can be displayed if the Minimum PHP version is not met.
 */
function business_store_php_not_met_notice() {
	?>
	<div class="notice notice-error is-dismissible" ><p><?php esc_html_e("Can't activate the theme. Business Store Theme requires Minimum PHP version 5.6",'business-store'); ?></p></div>
	<?php
}


/**
* Custom settings for this theme.
*/
require get_parent_theme_file_path( '/inc/settings.php' );
//load settings
$business_store_default_settings = new business_store_settings();
$business_store_option = wp_parse_args(  get_option( 'business_store_option', array() ) , $business_store_default_settings->default_data());

/**
 * Sets up theme defaults and registers support for various WordPress features.
**/
function business_store_setup() {
	/*
	 * Make theme available for translation.
	 */
	
	load_theme_textdomain( 'business-store', get_template_directory() . '/languages'  );
	
	if ( ! isset( $content_width ) ) $content_width = 1600; 

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	*/
	

	$defaults = array(
		'default-color'          => '#fff',
		'default-image'          => '',
		'default-repeat'         => '',
		'default-position-x'     => '',
		'default-attachment'     => '',
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	);
	
	add_theme_support( 'custom-background', $defaults );
	
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	 
	add_theme_support( 'post-thumbnails' );
	
	set_post_thumbnail_size( 200, 200 );

	// This theme uses wp_nav_menu()
	register_nav_menus(
		array(
			'top'    => __( 'Top Menu', 'business-store' ),			
		)
	);
	
	// This theme uses wp_nav_menu()
	register_nav_menus(
		array(
			'footer'    => __( 'Footer Menu', 'business-store' ),			
		)
	);	
	
				
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);


	// Add theme support for Custom Logo.
	add_theme_support(
		'custom-logo', array(
			'width'      => 200,
			'height'     => 200,
			'flex-width' => true,			
		)
	);
	

	$args = array(
		'width'         => 1600,
		'flex-width'    => true,
		'default-image' => business_store_TEMPLATE_DIR_URI.'/images/header.png',
		// Header text
		'uploads'         => true,
		'random-default'  => true,	
		'header-text'     => false,
		
	);
	
	add_theme_support( 'custom-header', 
			apply_filters(
				'business_store_custom_header_args',
				$args
			)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets'     => array(
			// Place three core-defined widgets in the sidebar area.
			'sidebar-1' => array(
			    'search',
				'categories',
				'archives',
			),

			// Add business info widget to the footer 1 area.
			'footer-sidebar-1' => array(
				'text_about',
			),

			// Put widgets in the footer 2 area.
			'footer-sidebar-2' => array(
				'recent-posts',				
			),
			// Putwidgets in the footer 3 area.
			'footer-sidebar-3' => array(
				'categories',				
			),
			// Put widgets in the footer 4 area.
			'footer-sidebar-4' => array(				
				'search',				
			),
											
		),
		
		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus'   => array(
			// Assign a menu to the "top" location.
			'top'    => array(
				'name'  => __( 'Top Menu', 'business-store' ),
				'items' => array(
					'link_home', // "home" page is actually a link in case a static front page is not used.
				),
			),
		),
			// Assign a menu to the "footer" location.
			'footer'    => array(
				'name'  => __( 'Footer Menu', 'business-store' ),
				'items' => array(
					'link_home', // "home" page is actually a link in case a static front page is not used.
				),
			),		
	);


	/**
	 * Filters business-store array of starter content.
	 *
	 * @since business-store 1.1
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'business_store_starter_content', $starter_content );
	 
	add_theme_support( 'starter-content', $starter_content );
}
add_action( 'after_setup_theme', 'business_store_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * 
 * Priority 0 to make it available to lower priority callbacks.
 *
 * $content_width = $GLOBALS['content_width'];
 */


/**
 * Register custom fonts.
 */
if(!function_exists('business_store_fonts_url')) {

	function business_store_fonts_url() {
		$fonts_url = '';
	
		/*
		 * Translators: If there are characters in your language that are not
		 * supported by "Open Sans", sans-serif;, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$typography = _x( 'on', 'Open Sans font: on or off', 'business-store' );
	
		if ( 'off' !== $typography ) {
			$font_families = array();
			
			$font_families[] = get_theme_mod('header_fontfamily','Oswald').':300,400,500';
			$font_families[] = get_theme_mod('body_fontfamily','Lora').':300,400,500';
			
	 
			$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
			);
			
			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			
		}
	   
		return esc_url( $fonts_url );
	}
}
/**
 * Display custom font CSS.
 */
function business_store_fonts_css_container() {   

	require( get_parent_theme_file_path( '/inc/custom-fonts.php' ) );

?>
	<style type="text/css" id="custom-fonts" >
		<?php echo business_store_custom_fonts_css(); ?>
	</style>
<?php
}
add_action( 'wp_head', 'business_store_fonts_css_container' );

/**
 * Add preconnect for Google Fonts.
 *
 * @since business-store 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function business_store_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'business-store-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'business_store_resource_hints', 10, 2 );

/**
* display notice 
**/
if(!function_exists('business_store_general_admin_notice')) {
function business_store_general_admin_notice(){

         $msg = sprintf('<div data-dismissible="disable-done-notice-forever" class="notice notice-info is-dismissible" >
             	<p> %1$s %2$s <span style="color:#FFFF00">&#9733;</span><span style="color:#FFFF00">&#9733;</span><span style="color:#FFFF00">&#9733;</span><span style="color:#FFFF00">&#9733;</span><span style="color:#FFFF00">&#9733;</span> %3$s <span style="color:red">&hearts;</span>  %4$s <a href=%5$s target="_blank"  style="text-decoration: none; margin-left:10px;" class="button button-primary"> %6$s </a>
			 	<a href=%7$s target="_blank"  style="text-decoration: none; margin-left:10px;" class="button">%8$s</a>
			 	<a href="?business_store_notice_dismissed" target="_self"  style="text-decoration: none; margin-left:10px;" class="button button-secondary">%9$s</a>
			 	</p><p><strong>%10$s</strong></p></div>',
				esc_html__(' If you like Business Store ','business-store'),
				esc_html__(' theme, please leave us a ','business-store'),
				esc_html__(' Rating ','business-store'),
				esc_html__(' Huge thanks in advance. ','business-store'),
				esc_url(business_store_THEME_REVIEW_URL),
				esc_html__('Rate','business-store'),
				esc_url(business_store_THEME_DOC),	
				esc_html__('Theme Tutorial - What is new?','business-store'),
				esc_html__('Dismiss','business-store'), 
				esc_html__('Enable | Disable WooCommerce Slider, Navigation and Banner :- Appearance -> Customize -> Theme Options', 'business-store'));				

		 echo wp_kses_post($msg);
		 
	}
}

//show, hide notice, update_option('business_store_notice',1);
if ( isset( $_GET['business_store_notice_dismissed'] ) ){
	update_option('business_store_notice', 7);
}
$business_store_notice = get_option('business_store_notice', 0);
if($business_store_notice != 7){
	add_action('admin_notices', 'business_store_general_admin_notice');
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function business_store_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Main Sidebar', 'business-store' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar on blog posts, archives and pages.', 'business-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	
	register_sidebar(
		array(
			'name'          => __( 'Woocommerce Sidebar', 'business-store' ),
			'id'            => 'sidebar-woocommerce',
			'description'   => __( 'Add widgets here to appear in your woocommerce pages.', 'business-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);	

	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'business-store' ),
			'id'            => 'footer-sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'business-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'business-store' ),
			'id'            => 'footer-sidebar-2',
			'description'   => __( 'Add widgets here to appear in your footer.', 'business-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => __( 'Footer 3', 'business-store' ),
			'id'            => 'footer-sidebar-3',
			'description'   => __( 'Add widgets here to appear in your footer.', 'business-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);	
	
	register_sidebar(
		array(
			'name'          => __( 'Footer 4', 'business-store' ),
			'id'            => 'footer-sidebar-4',
			'description'   => __( 'Add widgets here to appear in your footer.', 'business-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	

	/* blog section sidebar */
	register_sidebar(
		array(
			'name'          => __( 'Home Blog', 'business-store' ),
			'id'            => 'home-blog-1',
			'description'   => __( 'Add widgets here to appear in Home Blog section.', 'business-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'business_store_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since business-store 1.0
 *
 * @param string $link Link to single post/page.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function business_store_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf(
		'<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'business-store' ), esc_html(get_the_title( get_the_ID() )) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'business_store_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since business-store 1.0
 */
function business_store_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'business_store_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function business_store_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n",  esc_url(get_bloginfo( 'pingback_url' )) );
	}
}
add_action( 'wp_head', 'business_store_pingback_header' );


/**
 * Enqueue scripts and styles.
 */
function business_store_scripts() {

	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'business-store-fonts', business_store_fonts_url(), array(), null );

	wp_enqueue_style( 'boostrap', get_theme_file_uri( '/css/bootstrap.css' ), array(), '3.3.6'); 
	
	// Theme stylesheet.
	wp_enqueue_style( 'business-store-style', get_stylesheet_uri() );	

	//fonsawesome
	wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/fonts/font-awesome/css/font-awesome.css' ), array(), '4.7');
	
	// Load the html5 shiv.
	wp_enqueue_script( 'html5', get_theme_file_uri( '/js/html5.js' ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'business-store-skip-link-focus-fix', get_theme_file_uri( '/js/skip-link-focus-fix.js' ), array(), '1.0', true );

	wp_enqueue_script( 'boostrap', get_theme_file_uri( '/js/bootstrap.min.js' ), array( 'jquery' ), '3.3.7', true);
		
	wp_enqueue_script( 'business-store-scroll-top', get_theme_file_uri( '/js/scrollTop.js' ), array( 'jquery' ), '2.1.2', false);
	
	$business_store_l10n = array(
		'quote' => business_store_get_fo( array( 'icon' => 'quote-right' ) ),
	);

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script( 'business-store-navigation', get_theme_file_uri( '/js/navigation.js' ), array( 'jquery' ), '1.0', true );
		$business_store_l10n['expand']   = __( 'Expand child menu', 'business-store' );
		$business_store_l10n['collapse'] = __( 'Collapse child menu', 'business-store' );
		$business_store_l10n['icon']     = business_store_get_fo(
			array(
				'icon'     => 'angle-down',
				'fallback' => true,
			)
		);
	}

	wp_localize_script( 'business-store-skip-link-focus-fix', 'businessStoreScreenReaderText', $business_store_l10n );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'business_store_scripts' );



/**
 * Filter the `sizes` value in the header image markup.
 *
 * @package twentyseventeen
 * @sub-package business-store
 * @since business-store 1.0
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function business_store_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'business_store_header_image_tag', 10, 3 );


/**
 * Return rgb value of a $hex - hexadecimal color value with given $a - alpha value
 * Ex:- business_store_rgba('#11ffee',15) // return rgba(17,255,238,15)
 *
 * @since business-store 1.0 
**/
 
function business_store_rgba($hex,$a){
 
	$r = hexdec(substr($hex,1,2));
	$g = hexdec(substr($hex,3,2));
	$b = hexdec(substr($hex,5,2));
	$result = 'rgba('.$r.','.$g.','.$b.','.$a.')';
	
	return $result;
}

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since business-store 1.0
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function business_store_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'business_store_widget_tag_cloud_args' );

/**
 * Custom template tags for this theme.
*/
require get_parent_theme_file_path( '/inc/template-tags.php' );

/* load default data, default settings are stored in template-tags.php */


/**
* Additional features to allow styling of the templates.
*/
require business_store_TEMPLATE_DIR.'/inc/template-functions.php';

if ( class_exists( 'WP_Customize_Control' ) ) {

	// Inlcude the Alpha Color Picker control file.
	require business_store_TEMPLATE_DIR.'/inc/color-picker/alpha-color-picker.php';
 
}

/**
 * TGM plugin.
 */
require business_store_TEMPLATE_DIR.'/inc/plugin-activation.php';

/**
 * fontawesome icons functions and filters.
 */
require business_store_TEMPLATE_DIR.'/inc/icon-functions.php';

/**
 * Customizer additions.
 */
 
require business_store_TEMPLATE_DIR.'/inc/customizer.php';
 

/**
 * This function adds some styles to the WordPress Customizer
 */
function business_store_customizer_styles() { ?>
	<style>
		.custom-label-control {
			border-left: 3px solid #f9d303;
			padding: 5px 5px 5px 10px;
			background-color: #fff;
			box-shadow: 0 2px 2px #d5d5d5;
		}
	</style>
	<?php
}
add_action( 'customize_controls_print_styles', 'business_store_customizer_styles', 999 );

add_filter('wp_nav_menu_items', 'business_store_add_search_form_to_menu', 10, 2);
function business_store_add_search_form_to_menu($items, $args) {
  // If this isn't the main navbar menu, do nothing
  if(  !($args->theme_location == 'top') )
    return $items;
  // add edd cart icon
    if (function_exists('edd_get_checkout_uri')) {
 
        // Add cart icon
        $items = $items . '<li id="cart-menu-item"><a class="dashicons-before dashicons-cart edd-cart-menu" href="' . esc_url(edd_get_checkout_uri()). '">';
        if ( $qty = edd_get_cart_quantity() ) {
 
            $items = $items . '(<span id="header-cart" class="edd-cart-quantity">' . absint(edd_get_cart_quantity()) . '</span>)';            
        } 
		$items = $items .' </a></li>';
    }  
  // On main menu: put styling around search and append it to the menu items
  return $items . '<li style="color:#eee;" class="my-nav-menu-search"><a id="myBtn" href="#"><i class="fa fa-search" style="color:#eee; font-size:18px;"></i>
  </a></li>';
}


/* 
 * Display template by name. Available template sections are as follows, 
 * slider, news,portfolio, questions,service, skills, stats, team, testimonials, woocommerce, callout
 */
function business_store_featured_area($args){
       get_template_part( '/sections/'.$args, 'section' );
}

/**
 * Add woocommerce theme support
 */

add_action( 'after_setup_theme', 'business_store_woocommerce_support' );
function business_store_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );	
}

/* hide shop page title */
function business_store_hide_shop_title()
{
    if( !is_shop() ) // is_shop is the conditional tag
        return true;
}
add_filter( 'woocommerce_show_page_title', 'business_store_hide_shop_title' );


/* Load widgets */
if($business_store_option['widget_posts']){
	require  business_store_TEMPLATE_DIR.'/inc/widget-posts.php';
}

add_action( 'after_setup_theme', 'business_store_search_widget' );
function business_store_search_widget(){
		require  business_store_TEMPLATE_DIR.'/inc/widget-search.php';
}

/**
 * Change the breadcrumb separator
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'business_store_breadcrumb_delimiter' );
function business_store_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' &raquo; ';
	return $defaults;
}

