<?php
/*
Plugin Name: Woo Costomize
Contributors: ceylonthemes
Donate link: https://www.ceylonthemes.com
Tags: customize, woocommerce, woocommerce filters, woocommerce shop
Requires at least: 4.0
Tested up to: 5.3
Version: 1.0.1
Stable tag: 1.1
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Description: A simple and easy way to Customize woocommerce, disable unwanted checkout feelds, free checkout, chenge WooCommerce button names and change colour schemes and My Account page.
*/
 
/**
 * custom option and settings
 */
define('woo_customize_DIR_PATH',plugin_dir_path( __FILE__ ));
class WooCustomizeSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $free_options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'wp_enqueue_scripts', array($this ,'woo_customize_load_plugin_css') );
		add_action( 'admin_enqueue_scripts', array($this,'woo_customize_enqueue_color_picker') );
		$this->free_options = get_option('woo_free_page_options');
		$this->color_options = get_option('woo_color_page_options');
		$this->billing_options = get_option('woo_billing_page_options');
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {	
		add_menu_page('Customizer for WooCommerce', 'Woo Customizer', 'administrator', __FILE__,  array( $this, 'create_admin_page' ), 'dashicons-cart' );

    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
		$this->active_tab = isset( $_GET[ 'tab' ] ) ? esc_html(wp_unslash($_GET[ 'tab' ])) : 'billing_options'; 
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Woocommerce Customize Settings','woo-customize'); ?></h1>
			<?php settings_errors(); ?>
			<h2 class="nav-tab-wrapper">
				<a href="?page=woo-customize/woo-customize.php&tab=billing_options" class="nav-tab <?php echo $this->active_tab == 'billing_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('General Product','woo-customize'); ?></a>
				<a href="?page=woo-customize/woo-customize.php&tab=free_options" class="nav-tab <?php echo $this->active_tab == 'free_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Free & Virtual Product','woo-customize'); ?></a>
				<a href="?page=woo-customize/woo-customize.php&tab=color_options" class="nav-tab <?php echo $this->active_tab == 'color_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Colours and Button text','woo-customize'); ?></a>
			</h2>			
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
				if($this->active_tab=='billing_options'){
                	settings_fields( 'woo_billing_page_options' );
                	do_settings_sections( 'woo_billing_page_options' );
				} else if($this->active_tab=='free_options'){
                	settings_fields( 'woo_free_page_options' );
                	do_settings_sections( 'woo_free_page_options' );					
				} else if($this->active_tab=='color_options'){
                	settings_fields( 'woo_color_page_options' );
                	do_settings_sections( 'woo_color_page_options' );					
				}
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
		
		register_setting('woo_billing_page_options', 'woo_billing_page_options', array( $this, 'sanitize' ));
		register_setting('woo_free_page_options', 'woo_free_page_options', array( $this, 'sanitize' ));
		register_setting('woo_color_page_options', 'woo_color_page_options', array( $this, 'sanitize' ));

        add_settings_section(
            'woo_billing_options', // ID
            __('Disable billing fields for Woocommerce products','woo-customize'), // Settings section Title
            array( $this, 'print_section_info' ), // Callback
            'woo_billing_page_options' // Page
        );
		
        add_settings_section(
            'woo_free_options', // ID
            __('Disable billing fields for free and virtual Woocommerce products','woo-customize'), // Settings section Title
            array( $this, 'print_section_info' ), // Callback
            'woo_free_page_options' // Page
        );
				
        add_settings_section(
            'woo_color_options', // ID
            __('Change colour and Button text','woo-customize'), // Settings section Title
            array( $this, 'print_section_info' ), // Callback
            'woo_color_page_options' // Page
        );	

		/*  
		 * billing section
		 */
		add_settings_field(
            'billing_first_name', // ID
            'First Name', // Title 
            array( $this, 'billing_first_name_callback' ), // Callback
            'woo_billing_page_options', // Page
            'woo_billing_options'
        );
		
		add_settings_field(
            'billing_last_name', 
            'Last Name', 
            array( $this, 'billing_last_name_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
			   
 		add_settings_field(
            'billing_company', 
            'Company', 
            array( $this, 'billing_company_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'billing_address_1', 
            'Address 1', 
            array( $this, 'billing_address_1_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'billing_address_2', 
            'Address 2', 
            array( $this, 'billing_address_2_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'billing_city', 
            'City', 
            array( $this, 'billing_city_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'billing_country', 
            'Country', 
            array( $this, 'billing_country_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'billing_postcode', 
            'Post Code', 
            array( $this, 'billing_postcode_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );	
			
 		add_settings_field(
            'billing_state', 
            'State', 
            array( $this, 'billing_state_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'billing_phone', 
            'Phone', 
            array( $this, 'billing_phone_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'billing_email', 
            'Email', 
            array( $this, 'billing_email_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
		
 		add_settings_field(
            'woocommerce_order_notes', 
            'Order Notes', 
            array( $this, 'woocommerce_order_notes_callback' ), 
            'woo_billing_page_options', 
            'woo_billing_options'
        );
			
		/*  
		 * free section
		 */
 		add_settings_field(
            'billing_free_checkout', 
            'Free Products', 
            array( $this, 'billing_free_checkout_callback' ), 
            'woo_free_page_options', 
            'woo_free_options'
        );
		
 		add_settings_field(
            'billing_virtual_checkout', 
            'Virtual Products', 
            array( $this, 'billing_virtual_checkout_callback' ), 
            'woo_free_page_options', 
            'woo_free_options'
        );				
		/*  
		 * color and text section
		 */
 		add_settings_field(
            'woo_customize_default_color', 
            'Default Color', 
            array( $this, 'woo_customize_default_color_callback' ),
            'woo_color_page_options', 
            'woo_color_options'
        );
					
		/* add to cart text */
 		add_settings_field(
            'woo_customize_add_to_cart_text', 
            'Add to Cart text', 
            array( $this, 'woo_customize_add_to_cart_text_callback' ), 
            'woo_color_page_options', 
            'woo_color_options'
        );
		
 		add_settings_field(
            'woo_customize_variable_text', 
            'Button text (Variable Products)', 
            array( $this, 'woo_customize_variable_text_callback' ), 
            'woo_color_page_options', 
            'woo_color_options'
        );
				
 		add_settings_field(
            'woo_customize_grouped_text', 
            'Button text (Grouped Products)', 
            array( $this, 'woo_customize_grouped_text_callback' ), 
            'woo_color_page_options', 
            'woo_color_options'
        );				 
	} /*end of page init*/

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
			
        if( isset( $input['billing_first_name'] ) )
            $new_input['billing_first_name'] = sanitize_text_field( $input['billing_first_name'] );
			
        if( isset( $input['billing_last_name'] ) )
            $new_input['billing_last_name'] = sanitize_text_field( $input['billing_last_name'] );
			
        if( isset( $input['billing_company'] ) )
            $new_input['billing_company'] = sanitize_text_field( $input['billing_company'] );
			
        if( isset( $input['billing_address_1'] ) )
            $new_input['billing_address_1'] = sanitize_text_field( $input['billing_address_1'] );
			
        if( isset( $input['billing_address_2'] ) )
            $new_input['billing_address_2'] = sanitize_text_field( $input['billing_address_2'] );
			
        if( isset( $input['billing_city'] ) )
            $new_input['billing_city'] = sanitize_text_field( $input['billing_city'] );
			
        if( isset( $input['billing_postcode'] ) )
            $new_input['billing_postcode'] = sanitize_text_field( $input['billing_postcode'] );			
			
        if( isset( $input['billing_country'] ) )
            $new_input['billing_country'] = sanitize_text_field( $input['billing_country'] );
			
        if( isset( $input['billing_state'] ) )
            $new_input['billing_state'] = sanitize_text_field( $input['billing_state'] );			
			
        if( isset( $input['billing_phone'] ) )
            $new_input['billing_phone'] = sanitize_text_field( $input['billing_phone'] );	
										
        if( isset( $input['woocommerce_order_notes'] ) )
            $new_input['woocommerce_order_notes'] = sanitize_text_field( $input['woocommerce_order_notes'] );
				
        if( isset( $input['billing_email'] ) )
            $new_input['billing_email'] = sanitize_text_field( $input['billing_email'] );
			
		/* free and virtual */
        if( isset( $input['billing_free_checkout'] ) )
            $new_input['billing_free_checkout'] = sanitize_text_field( $input['billing_free_checkout'] );
			
        if( isset( $input['billing_virtual_checkout'] ) )
            $new_input['billing_virtual_checkout'] = sanitize_text_field( $input['billing_virtual_checkout'] );			
			
		/* colour */
        if( isset( $input['woo_customize_default_color'] ) )
            $new_input['woo_customize_default_color'] = ( $input['woo_customize_default_color'] );
			
		/* add to cart */
        if( isset( $input['woo_customize_add_to_cart_text'] ) )
            $new_input['woo_customize_add_to_cart_text'] = ( $input['woo_customize_add_to_cart_text'] );
			
        if( isset( $input['woo_customize_grouped_text'] ) )		
            $new_input['woo_customize_grouped_text'] = ( $input['woo_customize_grouped_text'] );
					
        if( isset( $input['woo_customize_variable_text'] ) )
            $new_input['woo_customize_variable_text'] = ( $input['woo_customize_variable_text'] );
																		
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        printf('<div class="notice  is-dismissible"><p>%1$s</p><strong><a href="http://www.ceylonthemes.com">%2$s</a></strong><p>&nbsp;</p></div>',
				esc_html('Enter your woocommerce settings such as for general products, free and virtual products, color and button text below.','woo-customize'),
				esc_html('Buy Pro version for Premium support, WooCommerce Themes and stylish My Account and WooCommerce pages.','woo-customize')
			  );
    }

    public function billing_first_name_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_first_name]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_first_name'] )&&($this->billing_options['billing_first_name']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }    

    public function billing_last_name_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_last_name]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_last_name'] )&&($this->billing_options['billing_last_name']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }

    public function billing_company_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_company]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_company'] )&&($this->billing_options['billing_company']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }
	
    public function billing_address_1_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_address_1]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_address_1'] )&&($this->billing_options['billing_address_1']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }
	
    public function billing_address_2_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_address_2]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_address_2'] )&&($this->billing_options['billing_address_2']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }
	
    public function billing_city_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_city]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_city'] )&&($this->billing_options['billing_city']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }
	
    public function billing_postcode_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_postcode]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_postcode'] )&&($this->billing_options['billing_postcode']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }
	
    public function billing_country_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_country]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_country'] )&&($this->billing_options['billing_country']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }

    public function billing_state_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_state]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_state'] )&&($this->billing_options['billing_state']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }

    public function billing_phone_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_phone]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_phone'] )&&($this->billing_options['billing_phone']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }
	
    public function billing_email_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[billing_email]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['billing_email'] )&&($this->billing_options['billing_email']=='on') ? 'checked=checked' : '',
			esc_html('Remove from billing','woo-customize')
        );
    }
	
    public function woocommerce_order_notes_callback()
    {
        printf(
            '<input type="checkbox" id="woo_billing_page_options" name="woo_billing_page_options[woocommerce_order_notes]"  %1$s /><label> %2$s</label>',
            isset( $this->billing_options['woocommerce_order_notes'] )&&($this->billing_options['woocommerce_order_notes']=='on') ? 'checked=checked' : '',
			esc_html('Remove order notes','woo-customize')
        );
    }
	/** 
     * free checkout options
     */
    public function billing_free_checkout_callback()
    {
        printf(
            '<input type="checkbox" id="woo_free_page_options" name="woo_free_page_options[billing_free_checkout]"  %1$s /><label> %2$s</label>',
            isset( $this->free_options['billing_free_checkout'] )&&($this->free_options['billing_free_checkout']=='on') ? 'checked=checked' : '',
			esc_html('Remove all billing fields for free products', 'woo-customize')
        );
    }
	/** 
     * free checkout options
     */
    public function billing_virtual_checkout_callback()
    {
        printf(
            '<input type="checkbox" id="woo_free_page_options" name="woo_free_page_options[billing_virtual_checkout]"  %1$s /><label> %2$s</label>',
            isset( $this->free_options['billing_virtual_checkout'] ) && ($this->free_options['billing_virtual_checkout']=='on') ? 'checked=checked' : '',
			esc_html('Remove billing fields for virtual products','woo-customize')
        );
    }		
	/** 
     * Colour options
     */
    public function woo_customize_default_color_callback()
    {
		$color = isset( $this->color_options['woo_customize_default_color'] ) ? $this->color_options['woo_customize_default_color'] : '#96588a';
        echo  '<input type="text" id="woo_color_page_options"  name="woo_color_page_options[woo_customize_default_color]" value="'.esc_attr($color).'" class="woo-customize-default-color-field" data-default-color="#96588a" />';

    }
	
	/** 
     * Add to cart text
     */
    public function woo_customize_add_to_cart_text_callback()
    {
        printf(
            '<input type="text" id="woo_color_page_options" name="woo_color_page_options[woo_customize_add_to_cart_text]" value="%s" />',
            isset( $this->color_options['woo_customize_add_to_cart_text'] ) ? esc_attr( $this->color_options['woo_customize_add_to_cart_text']) : esc_html__('Add to Cart','woo-customize')
        );
    }
	
	/** 
     * variable text
     */
    public function woo_customize_variable_text_callback()
    {
        printf(
            '<input type="text" id="woo_color_page_options" name="woo_color_page_options[woo_customize_variable_text]" value="%s" />',
            isset( $this->color_options['woo_customize_variable_text'] ) ? esc_attr( $this->color_options['woo_customize_variable_text']) : esc_html__('Add to Cart','woo-customize')
        );
    }
	
	/** 
     * Grouped text
     */
    public function woo_customize_grouped_text_callback()
    {
        printf(
            '<input type="text" id="woo_color_page_options" name="woo_color_page_options[woo_customize_grouped_text]" value="%s" />',
            isset( $this->color_options['woo_customize_grouped_text'] ) ? esc_attr( $this->color_options['woo_customize_grouped_text']) : esc_html__('Add to Cart','woo-customize')
        );
    }
	
	/* 
	 * load css and javascripts
	 */
	public function woo_customize_enqueue_color_picker( $hook_suffix ) {
		// first check that $hook_suffix is appropriate for your admin page
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'woo-customize-script-handle', plugins_url('js/color-selector-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		$plugin_url = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'woo-customize-style', $plugin_url . 'css/style.css' );	
	}
		
} /*end of class*/

if( is_admin() ) {
    $my_settings_page = new WooCustomizeSettingsPage();	
}

require( woo_customize_DIR_PATH . 'functions.php');