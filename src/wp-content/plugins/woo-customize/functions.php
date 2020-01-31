<?php
/**
 * default settings array
 */
function woo_customize_default_options() {
	return array(
		'billing_first_name' => '',
		'billing_last_name' => '',
		'billing_company'=> '',
		'billing_address_1'=> '',
		'billing_address_2' => '',
		'billing_city'=> '',
		'billing_postcode'=> '',
		'billing_country'=> '',
		'billing_state'=> '',
		'billing_phone'=> '',
		'billing_email'=> '',
		'woocommerce_order_notes'=> '',
		
		//free checkout
		'billing_free_checkout'=> '',
		'billing_virtual_checkout'=> '',
		
		//text and colours
		'woo_customize_add_to_cart_text'=> esc_html__('Add to Cart', 'woo-customize'),
		'woo_customize_variable_text'=> esc_html__('Add to Cart', 'woo-customize'),
		'woo_customize_grouped_text'=> esc_html__('Add to Cart', 'woo-customize'),
		'woo_customize_default_color'=>'#96588a',	
	);
}

$settings_array = 	array_merge(get_option( 'woo_billing_page_options', array() ),
					get_option( 'woo_free_page_options', array() ), 
					get_option( 'woo_color_page_options', array() ));
					
$woo_customize_options =  wp_parse_args( $settings_array , woo_customize_default_options());  

//call function after init of woocommerce
add_filter( 'woocommerce_init', 'woo_customize_init');
function woo_customize_init(){
	add_filter( 'woocommerce_checkout_fields' , 'woo_customize_checkout_fields' );
}

function woo_customize_checkout_fields( $fields ) {
if ( function_exists( 'is_checkout' ) && ( is_checkout() || is_ajax() ) ) {
    
	//handle free products
	global $woo_customize_options;
	
	//free checkout fields
	if($woo_customize_options['billing_free_checkout']) {
		if (!WC()->cart->needs_payment() ) {
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
			// Remove the "Additional Info" order notes
			add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
			unset($fields['billing']['billing_first_name']);
			unset($fields['billing']['billing_last_name']);
			unset($fields['billing']['billing_email']);	
			unset($fields['billing']['billing_company']);
			unset($fields['billing']['billing_address_1']);
			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_city']);
			unset($fields['billing']['billing_postcode']);
			unset($fields['billing']['billing_country']);
			unset($fields['billing']['billing_state']);
			unset($fields['billing']['billing_phone']);				
			
			return $fields;
		}
	}
	
	//handle virtual products
	if($woo_customize_options['billing_virtual_checkout']) {		
		$virtual = true;     
		foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			// Check if there are non-virtual products
			if ( ! $cart_item['data']->is_virtual() ) $virtual = false; 
		}     
		if( $virtual ) {
			unset($fields['billing']['billing_company']);
			unset($fields['billing']['billing_address_1']);
			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_city']);
			unset($fields['billing']['billing_postcode']);
			unset($fields['billing']['billing_country']);
			unset($fields['billing']['billing_state']);
			unset($fields['billing']['billing_phone']);
			add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
			
			return $fields;
		}
	}	
	
	//handle normal products
	$billing_options = array (
		'billing_first_name',
		'billing_last_name',
		'billing_company' ,
		'billing_address_1',
		'billing_address_2' ,
		'billing_city',
		'billing_postcode',
		'billing_country',
		'billing_state',
		'billing_phone',
		'billing_email',
	);
		

	foreach($billing_options as $field){		
		$woo_customize_option = $woo_customize_options[$field];
		
		if($woo_customize_option == 'on'){
			unset($fields['billing'][$field]);	
		}
	}
	

	if($woo_customize_options['woocommerce_order_notes'] == 'on'){
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false');
	}
	
	//return modified fields		
	return $fields;
	
 }    
}

/*
 * customize add to cart button
 */
$woo_customize_option = $woo_customize_options['woo_customize_add_to_cart_text'];	
if($woo_customize_option !== 'Add to Cart'){
	add_filter('woocommerce_product_add_to_cart_text', 'woo_customize_cart_button_text', 10, 2);
	//add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_customize_cart_button_text', 10, 2 );
}

/* change cart button text */
function woo_customize_cart_button_text($button_text, $product) {
	global $woo_customize_options;
	$variable = $woo_customize_options['woo_customize_variable_text'];
	$grouped = $woo_customize_options['woo_customize_grouped_text'];
	if ( $product->is_type( 'simple' ) )
		$button_text = $woo_customize_options['woo_customize_add_to_cart_text'];		
	if ( $product->is_type( 'variable' ) )
		$button_text = $variable;
	if ( $product->is_type( 'grouped' ) )
		$button_text = $grouped;
	/*if ( $product->is_type( 'external' ) )
		$button_text = __('buy external', 'woocommerce');*/

	return $button_text;
}

/* Add color scheme */	
$woo_customize_option = $woo_customize_options['woo_customize_default_color'];

if($woo_customize_option !== '#96588a'){
	add_filter( 'body_class', 'woo_customize_body_classes' );
	add_action( 'wp_head', 'woo_customize_colors_css_container' );
}

/**
 * Adds custom classes to the array of body classes.
 */
function woo_customize_body_classes( $classes ) {
	$classes[] = 'woo-custom-colours';
	return $classes;
}

/**
 * Return rgb value of a $hex - hexadecimal color value with given $a - alpha value
 * Ex:- business_wp_rgba('#11ffee',15) // return rgba(17,255,238,15)
**/
 
function woo_customize_rgba($hex,$a){
 
	$r = hexdec(substr($hex,1,2));
	$g = hexdec(substr($hex,3,2));
	$b = hexdec(substr($hex,5,2));
	$result = 'rgba('.$r.','.$g.','.$b.','.$a.')';
	
	return $result;
}

/* 
 * check woocommerce active 
 */
function woo_customize_woocommerce_active(){
	return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

/* 
 * check virtualproducts  only in the cart
 */
function woo_customize_cart_only_virtual() {
if(!function_exists(WC())){return false;}     
    $only_virtual = true;     
    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Check if there are non-virtual products
        if ( ! $cart_item['data']->is_virtual() ) $only_virtual = false; 
    }	
	return $only_virtual;
}

/**
 * Display custom color CSS.
 */
function woo_customize_colors_css_container() {
 global $woo_customize_options;
 $color = $woo_customize_options['woo_customize_default_color'];
?>
	<style type="text/css" id="woo-custom-colours" >
	.woo-custom-colours .woocommerce #respond input#submit, 
	.woo-custom-colours.woocommerce-page a.button, 
	.woo-custom-colours .woocommerce a.button,
	.woo-custom-colours .woocommerce button.button, 
	.woo-custom-colours .woocommerce input.button,
	.woo-custom-colours button.button.alt,
	.woocommerce span.onsale {
	   background-color: <?php echo esc_attr($color); ?> ;
	   color: #FFF;
	}
	
	.woo-custom-colours .woocommerce #respond input#submit.disabled, 
	.woo-custom-colours .woocommerce #respond input#submit:disabled, 
	.woo-custom-colours .woocommerce #respond input#submit[disabled]:disabled, 
	.woo-custom-colours .woocommerce a.button.disabled,
	.woo-custom-colours .woocommerce a.button:disabled, 
	.woo-custom-colours .woocommerce a.button[disabled]:disabled, 
	.woo-custom-colours .woocommerce button.button.disabled, 
	.woo-custom-colours .woocommerce button.button:disabled, 
	.woo-custom-colours .woocommerce button.button[disabled]:disabled, 
	.woo-custom-colours .woocommerce input.button.disabled, 
	.woo-custom-colours .woocommerce input.button:disabled, 
	.woo-custom-colours .woocommerce input.button[disabled]:disabled {
	   background-color: <?php echo esc_attr(woo_customize_rgba($color,0.7)); ?> ;
	   color:#FFF;
	   
	}
	
	.woocommerce .woocommerce-breadcrumb a {
		color: <?php echo esc_attr(esc_attr($color)); ?> ;
	}
	
	.woo-custom-colours .woocommerce form.checkout_coupon, 
	.woo-custom-colours .woocommerce form.login, 
	.woo-custom-colours .woocommerce form.register {
		border-color: <?php echo esc_attr(esc_attr($color)); ?> ;
	} 
	

	.woo-custom-colours nav.woocommerce-MyAccount-navigation ul li.is-active {
		background-color: <?php echo esc_attr(esc_attr($color)); ?> ;
	}

	/* .woo-custom-colours .woocommerce input[type="text"],
	.woo-custom-colours .woocommerce input[type="email"],
	.woo-custom-colours .woocommerce input[type="url"],
	.woo-custom-colours .woocommerce input[type="password"],
	.woo-custom-colours .woocommerce input[type="search"],
	.woo-custom-colours .woocommerce input[type="number"],
	.woo-custom-colours .woocommerce input[type="tel"],
	.woo-custom-colours .woocommerce input[type="range"],
	.woo-custom-colours .woocommerce input[type="date"],
	.woo-custom-colours .woocommerce input[type="month"],
	.woo-custom-colours .woocommerce input[type="week"],
	.woo-custom-colours .woocommerce input[type="time"],
	.woo-custom-colours .woocommerce input[type="datetime"],
	.woo-custom-colours .woocommerce input[type="datetime-local"],
	.woo-custom-colours .woocommerce input[type="color"],
	.woo-custom-colours .woocommerce input[type="checkbox"],
	.woo-custom-colours .woocommerce select,
	.woo-custom-colours .woocommerce .select2-container--default .select2-selection--single,
	.woo-custom-colours .woocommerce textarea {
		border:1px solid <?php echo esc_attr($color); ?> ; 
	} 
	
	.woo-custom-colours .woocommerce fieldset {
		 border-color: <?php echo esc_attr(esc_attr($color)); ?> ;
	}
		
	.woo-custom-colours .woocommerce legend {
		 border-color: <?php echo esc_attr(esc_attr($color)); ?> ;
	}	
	
	*/
	
	.woo-custom-colours .select2-container--default .select2-selection--single .select2-selection__arrow b {
		border-color:<?php echo esc_attr(esc_attr($color)); ?> transparent  transparent; 
	}
	.woo-custom-colours input[type="number"] {
		border-color:<?php echo esc_attr(woo_customize_rgba($color,0.6)); ?> ;		
	}
	
	.woo-custom-colours input[type="text"]:focus,
	.woo-custom-colours input[type="email"]:focus,
	.woo-custom-colours input[type="url"]:focus,
	.woo-custom-colours input[type="password"]:focus,
	.woo-custom-colours input[type="search"]:focus,
	.woo-custom-colours input[type="number"]:focus,
	.woo-custom-colours input[type="tel"]:focus,
	.woo-custom-colours input[type="range"]:focus,
	.woo-custom-colours input[type="date"]:focus,
	.woo-custom-colours input[type="month"]:focus,
	.woo-custom-colours input[type="week"]:focus,
	.woo-custom-colours input[type="time"]:focus,
	.woo-custom-colours input[type="datetime"]:focus,
	.woo-custom-colours input[type="datetime-local"]:focus,
	.woo-custom-colours input[type="color"]:focus,
	.woo-custom-colours input[type="checkbox"]:focus,
	.woo-custom-colours textarea:focus {	
		border-color:<?php echo esc_attr(woo_customize_rgba($color,0.6)); ?> ;
	}		
	
	.woo-custom-colours .woocommerce #respond input#submit:hover, 
	.woo-custom-colours .woocommerce a.button:hover, 
	.woo-custom-colours.woocommerce-page a.button:hover,
	.woo-custom-colours .woocommerce a.button.alt:hover,
	.woo-custom-colours .woocommerce button.button:hover, 
	.woo-custom-colours .woocommerce input.button:hover,
	.woo-custom-colours .woocommerce button.button.alt:hover,
	.woo-custom-colours button.button.alt:hover {
		background:<?php echo esc_attr(woo_customize_rgba($color,0.2)); ?>;
		transition: background-color 0.4s ease-in-out;
		color:#000;
	}
	
	nav.woocommerce-MyAccount-navigation ul {
	  list-style-type: none;
	  padding-left: 0;
	  max-width:200px;
	  font-size: 17px;
	  line-height: 26px;
	  box-shadow: 2px 5px 7px rgba(0,0,0,.16);
	  -webkit-box-shadow: 2px 5px 7px rgba(0,0,0,.16);
	  margin-left:0px;  
	}
	
	nav.woocommerce-MyAccount-navigation ul li {
	  padding: 8px 20px;
	  background-color: #FFFFFF;
	  border-bottom: 1px solid rgba(0,0,0,0.05);
	}
	
	.woocommerce-MyAccount-navigation  ul li a {
		text-decoration:none;
	}
			
	</style>
<?php
}