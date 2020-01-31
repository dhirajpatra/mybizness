<?php
/**
 * Custom template tags for this theme
 * @package business-store
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
 
if ( ! function_exists( 'business_store_featured_areas' ) ) : 
/**
 * business-store featured areas
 */
 
function business_store_featured_areas(){
	return  array(
				'slider'=>__('Home Slider', 'business-store'),
				'banner'=>__('Banner', 'business-store'),
				'featured-product'=>__('Featured Products', 'business-store'),
				'onsale-product'=>__('Onsale Products', 'business-store'),
				'shop'=>__('Home Shop', 'business-store'),
				'bestselling-product'=>__('Best Selling Products', 'business-store'),
				'toprated-product'=>__('Top Rated Products', 'business-store'),				
				'service'=>__('Service', 'business-store'),
				'news'=>__('News~Events', 'business-store'),
				'team'=>__('Team', 'business-store'),
				'testimonial'=>__('Testimonial', 'business-store'),
				'none'=>__('-- Disable --', 'business-store')
			);
}

endif;

if ( ! function_exists( 'business_store_color_codes' ) ) : 
/**
 * business-store color codes
 */
 
function business_store_color_codes(){
	return array('#000000','#ffffff','#ED0A70','#e7ad24','#FFD700','#81d742','#0053f9','#8224e3');
}

endif;

if ( ! function_exists( 'business_store_background_style' ) ) : 
/**
 * business-store color codes
 */
 
function business_store_background_style(){
	return array(
					'no-repeat'  => __('No Repeat', 'business-store'),
					'repeat'     => __('Tile', 'business-store'),
					'repeat-x'   => __('Tile Horizontally', 'business-store'),
					'repeat-y'   => __('Tile Vertically', 'business-store'),
				);
}

endif;

/**
 * @package twentyseventeen
 * @sub-package business-store
 * @since 1.0
 */ 
if ( ! function_exists( 'business_store_posted_on' ) ) : 
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function business_store_posted_on() {
		
		$byline = sprintf(
			// Get the author name; wrap it in a link.
			esc_html_x( 'By %s', 'post author', 'business-store' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);		

		// Finally, let's write all of this to the page.
		echo '<span class="posted-on">' . business_store_time_link() . '</span><span class="byline"> ' . $byline . '</span>';
	}
endif;

/**
 * @package twentyseventeen
 * @sub-package business-store
 * @since 1.0
 */ 
if ( ! function_exists( 'business_store_time_link' ) ) :
	/**
	 * Gets a nicely formatted string for the published date.
	 */
	function business_store_time_link() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			get_the_date( DATE_W3C ),
			get_the_date(),
			get_the_modified_date( DATE_W3C ),
			get_the_modified_date()
		);

		$args = array( 'time'=> array('class'=> array(),'datetime'=>array()));
		
		// Wrap the time string in a link, and preface it with 'Posted on'.
		return sprintf(
			/* translators: %s: post date */
			__( '<span class="screen-reader-text">%1$s</span> %2$s', 'business-store'),
			esc_html__('Posted on', 'business-store'),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' .wp_kses($time_string, $args). '</a>'
		);
	}
endif;

/**
 * @package twentyseventeen
 * @sub-package business-store
 * @since 1.0
 */ 
if ( ! function_exists( 'business_store_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function business_store_entry_footer() {

	
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'business-store') );
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'business-store') );
				
		// We don't want to output .entry-footer if it will be empty, so make sure its not.
		if ( ( ( business_store_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {

			echo '<footer class="entry-footer">';

			if ( 'post' === get_post_type() ) {
				
				if ( ( $categories_list && business_store_categorized_blog() ) || $tags_list ) {
					echo '<span class="cat-tags-links">';

					// Make sure there's more than one category before displaying.
					if ( $categories_list && business_store_categorized_blog() ) {
						
						echo '<span class="cat-links">' . business_store_get_fo( array( 'icon' => 'folder-open' ) ) . '<span class="screen-reader-text">' . esc_html__( 'Categories', 'business-store') . '</span>' .$categories_list. '</span>';
					}
					
					if ( $tags_list && ! is_wp_error( $tags_list ) ) {
					
						echo '<span class="tags-links">' . business_store_get_fo( array( 'icon' => 'hashtag' ) ) . '<span class="screen-reader-text">' . esc_html__( 'Tags', 'business-store') . '</span>' .$tags_list. '</span>';
					}

					echo '</span>';
				}
			}


			business_store_edit_link();

			echo '</footer> <!-- .entry-footer -->';
		}
	}
endif;

/**
 * @package twentyseventeen
 * @sub-package business-store
 * @since 1.0
 */ 
if ( ! function_exists( 'business_store_edit_link' ) ) :
	/**
	 * Returns an accessibility-friendly link to edit a post or page.
	 * Helpful when/if the single-page
	 * layout with multiple posts/pages shown gets confusing.
	 */
	function business_store_edit_link() {
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'business-store' ),
				esc_html(get_the_title())
			),
			' <span class="edit-link">',
			'</span>'
		);
	}
endif;

/**
 * @package twentyseventeen
 * @sub-package business-store
 * @since 1.0
 * Returns true if a blog has more than 1 category.
 * @return bool
 */
function business_store_categorized_blog() {
	$category_count = get_transient( 'business_store_categories' );

	if ( false === $category_count ) {
		// Create an array of all the categories that are attached to posts.
		$categories = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$category_count = count( $categories );

		set_transient( 'business_store_categories', $category_count );
	}

	// Allow viewing case of 0 or 1 categories in post preview.
	if ( is_preview() ) {
		return true;
	}

	return $category_count > 1;
}

/**
 * @package twentyseventeen
 * @sub-package business-store
 * @since 1.0
 * Flush out the transients used in business_store_categorized_blog.
 */
function business_store_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'business_store_categories' );
}
add_action( 'edit_category', 'business_store_category_transient_flusher' );
add_action( 'save_post', 'business_store_category_transient_flusher' );


/* 
 * check valid font has been selected 
 */
function business_store_sanitize_font_family( $value ) {
    if ( array_key_exists($value, business_store_font_family()) )  {   
    	return $value;
	} else {
		return "Times New Roman, Sans Serif";
	}
}

function business_store_font_family(){

	$google_fonts = array(  "Times New Roman" => "Times New Roman, Sans Serif",
							"Open sans" => "Open sans",
							"Oswald" => "Oswald",
							"Lora" => "Lora",
						);
						
	return ($google_fonts);
}


/*********************** woocommerce functions *****************************/

/**
 * Woocommerce Custom add to cart button
 *
 */
if ( ! function_exists( 'business_store_add_to_cart' ) ) {
	function business_store_add_to_cart($id = '') {
		
		if(!class_exists( 'WooCommerce' )){return;}
		global $product;
		
		if( $id ) {
			$product = wc_get_product( $id );
		}

		if ( function_exists( 'method_exists' ) && method_exists( $product, 'get_type' ) ) {
			$prod_type = $product->get_type();
		} else {
			$prod_type = $product->product_type;
		}

		if ( function_exists( 'method_exists' ) && method_exists( $product, 'get_stock_status' ) ) {
			$prod_in_stock = $product->get_stock_status();
		} else {
			$prod_in_stock = $product->is_in_stock();
		}

		if ( $product ) {
			$args = array();
			$defaults = array(
				'quantity' => 1,
				'class'    => implode(
					' ', array_filter(
						array('button',
							'product_type_' . $prod_type,
							$product->is_purchasable() && $prod_in_stock ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',)
					)
				),
			);

			$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

			wc_get_template( 'woocommerce/add-to-cart.php', $args );
		}
	}
}

/* when price empty return 0$ */
function business_store_wc_custom_get_price_html( $price, $product ) {
	if(!class_exists( 'WooCommerce' )){return;}
	if ( $product->get_price() == 0 ) {
		if ( $product->is_on_sale() && $product->get_regular_price() ) {
			$regular_price = wc_get_price_to_display( $product, array( 'qty' => 1, 'price' => $product->get_regular_price() ) );
			$price = wc_format_price_range( $regular_price, __( 'Free!', 'business-store' ) );
		} else {
			$price = '<span class="amount">' . __( 'Free!', 'business-store' ) . '</span>';
		}
	}

	return $price;
}

add_filter( 'woocommerce_get_price_html', 'business_store_wc_custom_get_price_html', 10, 2 );

/**
 * Add Cart icon and count to header if WC is active
 */
function business_store_wc_cart_count() {
 
    if ( class_exists('WooCommerce') ) {
	global $woocommerce;
		if($woocommerce && is_object( WC()->cart )) {
		?>
		<a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('Cart View', 'business-store'); ?>"><span class="cart-contents-count"><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span>
		<span class="cart-contents-price"><?php echo wp_kses_post($woocommerce->cart->get_cart_total()); ?></span>
		</a> 
		<?php
		}
	} 
 
}
add_action( 'woocommerce_cart_top', 'business_store_wc_cart_count' );

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 */
function business_store_add_to_cart_fragment( $fragments ) {
	if ( class_exists('WooCommerce') ) {
		global $woocommerce;
		if($woocommerce && is_object( WC()->cart )) {
			ob_start();
			?>
			<div class="">
			<a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('Cart View', 'business-store'); ?>"><span class="cart-contents-count"><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span>
			<span class="cart-contents-price"><?php echo wp_kses_post($woocommerce->cart->get_cart_total()); ?></span>
			</a> 
			<?php
			$cart_fragments['a.cart-contents'] = ob_get_clean();
			return $cart_fragments;
		}
	}	
}
add_filter( 'woocommerce_add_to_cart_fragments', 'business_store_add_to_cart_fragment' );

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 */
function business_store_wishlist_count( ) {
$wishlist_count = 0;
if(function_exists('YITH_WCWL')){
	$wishlist_count = YITH_WCWL()->count_products();
}
?>
<a class="wishlist-contents"  href="<?php echo esc_url(home_url( '/wish-list')); ?>" title="<?php esc_attr_e( 'View your whishlist','business-store' ); ?>">
<span class="wishlist-contents-count"><?php echo absint($wishlist_count); ?></span></a>
<?php
}


/**
 * Set list of products arguments
 * $product_type :- '' = All, featured, best-selling, on-sale, top-rated, price, latest(new arrivals)
 */
function business_store_get_product_args($number_of_products, $product_type, $operator='IN', $order='DESC'){
 
	if(!class_exists( 'WooCommerce' ))	return;
	
	$args = array(	'post_type' => 'product', 
					'post_status' => 'publish', 
					'posts_per_page'=> $number_of_products, 
					'order' => $order
				  );
		
	switch($product_type){
		case 'featured':
			$args['tax_query'] = array(array('taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured'));
			break;		
		
		case 'best-selling':
			$args['meta_key'] = 'total_sales';
			$args['orderby'] = 'meta_value_num';
			break;			
		
		case 'on-sale':
			$args['meta_query']  = WC()->query->get_meta_query();
			$args['post__in']    = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
			break;	
		
		case 'top-rated':
			$args['meta_query']  = WC()->query->get_meta_query();
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_wc_average_rating';
			break;		
		//heigh to low
		case 'price':
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
			break;
		//low to heigh
		case 'price-low':
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
			break;				
		
		case 'latest':
			$args['orderby'] = 'date';
			$args['order']= 'DESC';
			break;	
	}
	
	return $args;

}

/**
 * Get list of products with buttons based business_store_get_product_args 
 * included product category inside function
 */
function business_store_list_products($args, $colums){

	if(!class_exists( 'WooCommerce' ))	return;
	
	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ) :
	$i = 1;
	echo '<div class="row multi-columns-row">';
		while ( $loop->have_posts() ) :
			$loop->the_post();
			global $product;
			global $post;
			$offfset_css = ' col-sm-offset-1';
			?>
			<div class="<?php if($colums != 'col-sm-2'){ echo $colums; } else { echo $colums.$offfset_css; $offfset_css=''; } ?>">
			<div class="product-wrapper">
				<div class="product-image-wrapper" style="max-height:250px" >
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail( 'full', array( 'alt' => esc_html(get_the_title()) )); ?>
						</a>					
					<?php endif; ?>
					<?php if ($product->is_on_sale() ) : ?>
						<div class="badge-wrapper"> <span class="onsale"><?php esc_html_e('Sale!', 'business-store') ?></span></div>
					<?php endif; ?>
					<div class="product-rating-wrapper">
						<?php
						$rating = $product->get_average_rating();
						 if($rating > 0){												
							for($r=1; $r<=5; $r++){
								$class = ($r<=$rating)? 'checked':'';
								echo '<span class="fa fa-star '.$class.'"></span>';
							}
						 }	
						?>
					</div>									
				</div>
				
				<div class="product-description">
				
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><p class="product-title"><?php esc_html(the_title()); ?></p></a>
				
					<span class="price">
						<?php
						$price = $product->get_price_html();
						if ( ! empty( $price ) ) {
							echo '<p>';
							echo wp_kses(
								$price, array(
									'span' => array(
										'class' => array(),
									),
									'del' => array(),
								)
							);
							echo '</p>';
						}
						?>					
					</span>

				</div> <!--end product description-->
				
				<div class="wc-button-container woocommerce">
					<div>
						<?php business_store_add_to_cart(); ?>
					</div>
				</div>
			</div>
		</div>							
	<?php
	$i++;				
	endwhile;
	wp_reset_postdata();
	echo '</div>';
	endif; // end loop

}

/*
 * Product navigation by categories up to 3 sub categories
 */

function business_store_product_navigation($widget_title, $max_items = 50){
  
  if(!class_exists( 'WooCommerce' ))	return;

  $args = array(
         'taxonomy'     => 'product_cat',
         'orderby'      => 'date',
		 'order'      	=> 'ASC',
         'show_count'   => 1,
         'pad_counts'   => 0,
         'hierarchical' => 1,
         'title_li'     => '',
         'hide_empty'   => 1,
  );
 $all_categories = get_categories( $args );
 $cat_count = 1;
 echo '<div class="product-navigation">'; 	
	 echo '<ul>';
	 if($widget_title){
	 	echo '<li class="navigation-name"><a href="#">'.esc_html($widget_title).'</a></li>';
	 }
	 foreach ($all_categories as $cat) {
		 if($cat_count > $max_items){
			break;
		 }
		 $cat_count++;
		
			if($cat->category_parent == 0) {
				$category_id = $cat->term_id; 
				$args2 = array(
						'taxonomy'     => 'product_cat',
						'child_of'     => 0,
						'parent'       => $category_id,
						'orderby'      => 'name',
						'show_count'   => 1,
						'pad_counts'   => 0,
						'hierarchical' => 1,
						'title_li'     => '',
						'hide_empty'   => 0,
				);
				$sub_cats = get_categories( $args2 );
				
				if($sub_cats) {
				echo '<li class="has-sub"> <a href="'.esc_url(get_term_link($cat->slug, 'product_cat')).'">'.esc_html($cat->name).' ('.absint($cat->count).')</a>';
				echo '<ul>';
					foreach($sub_cats as $sub_category) {
						$sub_category_id = $sub_category->term_id;
						$args3 = array(
								'taxonomy'     => 'product_cat',
								'child_of'     => 0,
								'parent'       => $sub_category_id,
								'orderby'      => 'name',
								'show_count'   => 1,
								'pad_counts'   => 0,
								'hierarchical' => 1,
								'title_li'     => '',
								'hide_empty'   => 0,
						);
						$sub_sub_cats = get_categories( $args3 );
						if($sub_sub_cats) {
						echo '<li class="has-sub"> <a href="'.esc_url(get_term_link($sub_category->slug, 'product_cat')).'">'.esc_html($sub_category->name).' ('.absint($sub_category->count).')</a>';
							echo '<ul>';
								foreach($sub_sub_cats as $sub_sub_cat) {
									echo '<li> <a href="'.esc_url(get_term_link($sub_sub_cat->slug, 'product_cat')).'">'.esc_html($sub_sub_cat->name).' ('.absint($cat->count).')</a>';
								}
							echo '</ul>';						
						} else {
						echo '<li> <a href="'.esc_url(get_term_link($sub_category->slug, 'product_cat')) .'">'.esc_html($sub_category->name).' ('.absint($cat->count).')</a>';
						}
					}
				echo '</ul>'; 
				} else {
					echo '<li> <a href="'.esc_url(get_term_link($cat->slug, 'product_cat')).'">'.esc_html($cat->name).' ('.absint($cat->count).')</a>';
				}
			}		      
	 } /* end for each */
	 echo '</ul>';
 echo '</div>';

} /* end category function */

