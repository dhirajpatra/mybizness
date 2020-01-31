<?php
/**
 * business-store: Customizer
 * @package business-store
 * @since 1.0
 */
 
add_action( 'customize_register', 'business_store_customizer_settings' ); 
function business_store_customizer_settings( $wp_customize ) {

// Go pro control
$wp_customize->add_section( 'business_store_lite' , array(
			'title'      	=> __( 'Go Premium Version', 'business-store' ),
			'priority' => 1,
		) );

		$wp_customize->add_setting( 'business_store_lite', array(
			'default'    		=> null,
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( new business_store_pro_Control( $wp_customize, 'business_store_lite', array(
			'label'    => __( 'GO Premium', 'business-store' ),
			'section'  => 'business_store_lite',
			'settings' => 'business_store_lite',
			'priority' => 1,
		) ) );



/*******************
 * Layout options. *
 *******************/

		$wp_customize->add_section( 'layout_section' , array(
			'title'      => __('Layout', 'business-store' ),			
			'description'=> __('Change site layout. Change Single Post display layout, Default is two columns (with sidebar). In pages - use full width template to hide sidebar', 'business-store' ),
		) );
		
		// site layout default / box layout 
		$wp_customize->add_setting( 'business_store_option[box_layout]' , array(
		'default'    => 0,
		'sanitize_callback' => 'business_store_sanitize_checkbox',
		'type'=>'option'
		));

		$wp_customize->add_control('business_store_option[box_layout]' , array(
		'label' => __('Enable box layout mode','business-store' ),
		'description' =>  __('Enable or disable Box layout mode. Default is fluid layout.','business-store' ),
		'section' => 'layout_section',
		'type'=>'checkbox',
		) );
	
		// layout 
		$wp_customize->add_setting( 'business_store_option[layout_section_post_one_column]' , array(
		'default'    => 0,
		'sanitize_callback' => 'business_store_sanitize_checkbox',
		'type'=>'option'
		));

		$wp_customize->add_control('business_store_option[layout_section_post_one_column]' , array(
		'label' => __('One Column Single Post Layout','business-store' ),
		'description' =>  __('Display single post in one column (No Sidebar)','business-store' ),
		'section' => 'layout_section',
		'type'=>'checkbox',
		) );
		
		// sidebar position
		$wp_customize->add_setting( 'business_store_option[blog_sidebar_position]' , array(
		'default'    => 'right',
		'sanitize_callback' => 'sanitize_text_field',
		'type'=>'option'
		));

		$wp_customize->add_control('business_store_option[blog_sidebar_position]' , array(
		'label' => __('Sidebar position','business-store' ),
		'section' => 'layout_section',
		'type'=>'select',
		'choices'=>array(
			'right'=>__('Right Sidebar','business-store' ),
			'left'=>__('Left Sidebar','business-store' ),
		),
		) );				

		
/*****************
 * Theme options.*
*****************/
 
$wp_customize->add_panel( 'theme_options', array(
  'title' => __('Theme Options','business-store' ),
  'description' => __('Theme specific customization options', 'business-store' ), // Include html tags such as <p>.
  'priority' => 2, // Mixed with top-level-section hierarchy.
) );

//template settings
require business_store_TEMPLATE_DIR.'/inc/customizer/header.php';
require business_store_TEMPLATE_DIR.'/inc/customizer/social.php';

// featured areas
require business_store_TEMPLATE_DIR.'/inc/customizer/slider.php';
require business_store_TEMPLATE_DIR.'/inc/customizer/banner.php';

require business_store_TEMPLATE_DIR.'/inc/customizer/fonts.php';
require business_store_TEMPLATE_DIR.'/inc/customizer/footer.php';

			
/****************************
default customizer settings *
*****************************/		

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->selective_refresh->add_partial(
		'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'business_store_customize_partial_blogname',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'business_store_customize_partial_blogdescription',
		)
	);
	

	// home header section enable/disable
	$wp_customize->add_setting( 'business_store_option[home_header_section_disable]' , array(
	'default'    => true,	
	'sanitize_callback' => 'business_store_sanitize_checkbox',
	'type'=>'option'
	));
	
	$wp_customize->add_control('business_store_option[home_header_section_disable]' , array(
	'label' => __('Disable header when front page set to home-page template and product slider enabled.','business-store' ),
	'section' => 'header_image',
	'type'=>'checkbox',
	) );	
	


}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since business-store 1.0
 * @see business_store_customize_register()
 *
 * @return void
 */
function business_store_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since business-store 1.0
 * @see business_store_customize_register()
 *
 * @return void
 */
function business_store_customize_partial_blogdescription() {
	bloginfo( 'description' );
}


/**
 * Return whether we're on a view that supports a one or two column layout.
 */
function business_store_is_view_with_layout_option() {
	// This option is available on all pages. It's also available on archives when there isn't a sidebar.
	return ( is_page() || ( is_archive() && ! is_active_sidebar( 'sidebar-1' ) ) );
}

/**
 * Bind JS handlers to instantly live-preview changes.
 */
function business_store_customize_preview_js() {
	wp_enqueue_script( 'business-store-customize-preview', get_theme_file_uri( '/js/customize-preview.js' ), array( 'customize-preview' ), '1.0', true );
}
add_action( 'customize_preview_init', 'business_store_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function business_store_panels_js() {
	wp_enqueue_script( 'business-store-customize-controls', get_theme_file_uri( '/js/customize-controls.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'business_store_panels_js' );


/**
 * A class to create a dropdown for all categories in your WordPress site
 */
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
	
 class business_store_category_dropdown_custom_control extends WP_Customize_Control
 {
    private $cats = false;

    public function __construct($manager, $id, $args = array(), $business_store_options = array())
    {
        $this->cats = get_categories($business_store_options);

        parent::__construct( $manager, $id, $args );
    }

    /**
     * Render the content of the category dropdown
     * @return HTML
     */
	 
	 public function render_content()
       {
            if(!empty($this->cats))
            {
                ?>
                    <label>
                      <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                      <select <?php $this->link(); ?>>
                           <?php
						        printf('<option value="0" selected="selected" >'.esc_html_e('None','business-store').'</option>');
                                foreach ( $this->cats as $cat )
                                {
                                    printf('<option value="%s" %s>%s</option>', esc_attr( $cat->term_id ), selected( $this->value(), esc_attr( $cat->term_id ), false), esc_attr( $cat->name ) );
                                }
                           ?>
                      </select>
                    </label>
                <?php  
            }
       }
 }
 
/* 
 * check valid list item has been selected 
 */ 
function business_store_sanitize_select( $input, $setting ) {
	
	// Ensure input is a slug.
	$input = sanitize_key( $input );
	
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}



/*
 * business-store sanitize checkbox function
 */ 
function business_store_sanitize_checkbox( $checked ) {
    // Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}



/*
 * business-store get post categories
 */

$business_store_categories = business_store_get_post_categories();

/*
 * business-store get all published pages
 */
$business_store_all_posts = business_store_get_all_posts();

/*
 * business-store get product categories
 */

$business_store_product_categories = business_store_get_product_categories();

function business_store_get_product_categories(){

	$args = array(
			'taxonomy' => 'product_cat',
			'orderby' => 'date',
			'order' => 'ASC',
			'show_count' => 1,
			'pad_counts' => 0,
			'hierarchical' => 0,
			'title_li' => '',
			'hide_empty' => 1,
	);

	$cats = get_categories($args);

	$arr = array();
	$arr['0'] = esc_html__('-Select Category-', 'business-store') ;
	foreach($cats as $cat){
		$arr[$cat->term_id] = $cat->name;
	}
	return $arr;
}

function business_store_get_post_categories(){
	$cats = get_categories();
	$arr = array();
	$arr['0'] = esc_html__('-Select Category-', 'business-store');
	foreach($cats as $cat){
		$arr[$cat->term_id] = $cat->name;
	}
	return $arr;
}

/*
 * business-store get all published pages
 */
 
function business_store_get_all_pages(){

	$args = array(
		'post_type' => 'page',
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'post_status' => 'publish'
	); 

	$pages = get_pages($args);
	$arr = array();
	$arr[''] = '-- None --';
	foreach($pages as $page){
		$arr[$page->ID] = $page->post_title;
	}
	return $arr;
}

/*
 * business-store get all published posts
 */
 
function business_store_get_all_posts(){

	$args = array(
		'post_type' => 'post',
		'sort_order' => 'desc',
		'sort_column' => 'post_title',
		'post_status' => 'publish'
	); 

	$posts = get_posts($args);
	$arr = array();
	$arr[''] = '-- None --';
	foreach($posts as $post){
		$arr[$post->ID] = $post->post_title;
	}
	return $arr;
}

/* label control */
if (class_exists('WP_Customize_Control'))
{
     class business_store_Label_Custom_control extends WP_Customize_Control
     {
          public function render_content()
           {

                ?>
                    <p  class="customize-control-title custom-label-control">
                      <span class="customize-category-select-control" style="text-align:center"><?php echo esc_html( $this->label ); ?></span>                      
                    </p>
                <?php
           }
     }
}




if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'business_store_pro_Control' ) ) :
class business_store_pro_Control extends WP_Customize_Control {

	/**
	* Render the content on the theme customizer page
	*/
	public function render_content() {
		?>
		<label style="overflow: hidden; zoom: 1;">
			<div class="col-md-2 col-sm-6" style="text-align:center;margin-bottom:15px;">					
					<a class="button button-secondary"  href="<?php echo esc_url(business_store_THEME_URL); ?>" target="blank" class="btn pro-btn-success btn"><?php esc_html_e('Upgrade to Premium Version','business-store'); ?> </a>
			</div>
			
			<div class="col-md-4 col-sm-6" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);padding:3px; background-color:#FFF">
				<img  src="<?php echo esc_url(business_store_TEMPLATE_DIR_URI .'/screenshot.jpg'); ?>">
			</div>			
			<div class="col-md-3 col-sm-6" style="font-weight:500;">
				<table class="theme-features" cellspacing="0" align="left">
				<tbody>
				<tr>
				<th scope="col" align="center"><h2><?php esc_html_e('Business Store Pro Version','business-store'); ?></h2></th>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i><b> <?php esc_html_e('* Access to all premium themes with Pro Version','business-store'); ?></b></div></td>
				</tr>						
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('* 12 months customer support, and free updates for each theme purchase for single domain','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('* Woocommerce, YITH wishlist integration.)','business-store'); ?></div></td>
				</tr>			
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('* Cached Widgets (Product Widgets including featured, by category, top sales, product by review, latest products, product slider, featured slider, banner and contact, service, team etc:)', 'business-store'); ?></div></td>
				</tr>
							
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i><?php esc_html_e('*  Service Widget','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  News Widget','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  Unlimited Testimonials/Sliders','business-store'); ?></div></td>
				</tr>

				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  Team Widget','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  Custom Page selection in featured areas on all templates, any page design with default editor, Site orign, Elementor, divi ect: can be added to featured sections.','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  Unlimited Color Schemes','business-store'); ?></div></td>
				</tr>								
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  More footer customizations','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  500 + Google Fonts','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  Translation Ready','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('* All pages and sections customizable (including slider, navigation, banner)','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i><?php esc_html_e('*  Team Widget','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i> <?php esc_html_e('*  Contact Widget','business-store'); ?></div></td>
				</tr>

				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i><?php esc_html_e('*  More accessibility features','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i><?php esc_html_e('*  Sticky navigation','business-store'); ?></div></td>
				</tr>				
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i><?php esc_html_e('*  Site content, Header and footer Width Adjustment','business-store'); ?></div></td>
				</tr>
				<tr>
				<td>
				<div align="left"><i style="color: rgb(38, 191, 38);" class="fa fa-check"></i><?php esc_html_e('*  Drag and Drop page design and Page builder template','business-store'); ?></div></td>
				</tr>										
				</tbody>
			  </table>

			</div>
			
			<br />
			
			<div class="col-md-2 col-sm-6" style="text-align:center">					
					<a class="button button-secondary"   href="<?php echo esc_url(business_store_THEME_AUTHOR_URL); ?>" target="blank" class="btn pro-btn-success btn"><strong><?php esc_html_e(' Go Premium Version or Donate ','business-store'); ?></strong></a>
			</div>

		</label>
		<?php
	}
}
endif;