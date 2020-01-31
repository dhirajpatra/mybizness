<?php
/*
 * Display product categories
 */
if (!class_exists('WooCommerce')) return;

class shopping_mart_product_category_widget extends WC_Widget {

		function __construct() {
		
				$this->widget_cssclass    = 'woocommerce product_category_widget';
				$this->widget_description = __( 'Display product categories as colums.', 'shopping-mart' );
				$this->widget_id          = 'shopping_mart_product_category_widget';
				$this->widget_name        = __( 'THEME:Product Categories', 'shopping-mart' );
		
				parent::__construct();
				
		}

		// Creating widget front-end
		public function widget($args, $instance) {
						
				$category_id = (!empty($instance['category_id'])) ? absint($instance['category_id']) : '';
				$display_sub_cat = (!empty($instance['display_sub_cat'])) ? esc_html($instance['display_sub_cat']) : true;
				$display_slider = (!empty($instance['display_slider'])) ? esc_html($instance['display_slider']) : false;
				$colums = (!empty($instance['colums'])) ? esc_html($instance['colums']) : "col-md-3 col-sm-3 col-lg-3 col-xs-6";
				$image_height = (!empty($instance['image_height'])) ? absint($instance['image_height']) : 200;
				$slider_speed = (!empty($instance['slider_speed'])) ? absint($instance['slider_speed']) : 4000;

				$operator = 'IN';
				if (!$display_sub_cat) {
						$operator = 'AND';
				}
				
				$product_args = array();
				
				if( $category_id ) {
					$product_args = array(
							'post_type' => 'product',
							'post_status' => 'publish',
							'posts_per_page' => 8,
							'tax_query' => array(
									array(
											'taxonomy' => 'product_cat',
											'terms' => $category_id,
											'operator' => $operator,
									)
							)
					);				
				
				} else {
					$product_args = array(	'post_type' => 'product',    
											'post_status' => 'publish',
    										'posts_per_page' => 8 );			
				}
				
				echo $args['before_widget'];

				if($display_slider) {
					shopping_mart_product_slider($product_args, $image_height, $colums, $slider_speed);
				} else {
					shopping_mart_list_products($product_args, $image_height ,$colums);
				}

				echo $args['after_widget'];
							
		}

		public function form($instance) {
				$category_id = (!empty($instance['category_id'])) ? ($instance['category_id']) : '';
				$display_sub_cat = (!empty($instance['display_sub_cat'])) ? (($instance['display_sub_cat'])) : true;
				$display_slider = (!empty($instance['display_slider'])) ? ($instance['display_slider']) : false;
				$colums = (!empty($instance['colums'])) ? ($instance['colums']) : "col-md-3 col-sm-3 col-lg-3 col-xs-6";
				$image_height = (!empty($instance['image_height'])) ? ($instance['image_height']) : 200;
				
				$slider_speed = (!empty($instance['slider_speed'])) ? ($instance['slider_speed']) : 4000;
				
				// Widget admin form
				
				$product_colums = array(
						"col-md-4 col-sm-4 col-lg-4 col-xs-6" => 3,
						"col-md-3 col-sm-3 col-lg-3 col-xs-6" => 4,
						"col-sm-2" => 5,
						"col-md-2 col-sm-2 col-lg-2 col-xs-6" => 6,
						
				);

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
				
				global $shopping_mart_allowed_html;

				$categories = get_categories($args);
				$category_list = '';
				if (0 == $category_id) {
						$category_list = $category_list . '<option value="0" Selected=selected>' . __('Any', 'shopping-mart') . '</option>';
				}
				else {
						$category_list = $category_list . '<option value="0">' . __('Any', 'shopping-mart') . '</option>';
				}
				foreach ($categories as $cat) {
						$selected = '';
						if (($cat->term_id) == $category_id) {
								$selected = 'Selected=selected';
						}
						$category_list = $category_list . '<option value="' . esc_attr($cat->term_id) . '" ' . $selected . ' >' . esc_html($cat->name) . '</option>';
				}
				?>
				
				<p>
				<label for="<?php echo esc_attr($this->get_field_id('category_id')); ?>"><?php esc_html_e('Select Product Category:', 'shopping-mart'); ?></label> 
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id('category_id')); ?>" name="<?php echo esc_attr($this->get_field_name('category_id')); ?>" type="text">
				<?php echo wp_kses($category_list, $shopping_mart_allowed_html); ?>
				</select>
				</p>
			
				<p>
				<input class="checkbox" type="checkbox" <?php if ($display_sub_cat) { echo " checked ";} ?> id="<?php echo esc_attr($this->get_field_id('display_sub_cat')); ?>" name="<?php echo esc_attr($this->get_field_name('display_sub_cat')); ?>" />
				<label for="<?php echo esc_attr($this->get_field_id('display_sub_cat')); ?>"><?php esc_html_e('Process Sub Categories (Operator IN)', 'shopping-mart'); ?></label> 
				</p>
				
				<p>
				<label for="<?php echo esc_attr($this->get_field_id('image_height')); ?>"><?php esc_html_e('Image Height (px):', 'shopping-mart'); ?></label> 
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('image_height')); ?>" name="<?php echo esc_attr($this->get_field_name('image_height')); ?>" type="number" value="<?php echo absint($image_height); ?>" />
				</p>				
				
				<p>
				<label for="<?php echo esc_attr($this->get_field_id('colums')); ?>"><?php esc_html_e('Number of colums:', 'shopping-mart'); ?></label> 
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id('colums')); ?>" name="<?php echo esc_attr($this->get_field_name('colums')); ?>" type="text">
				<?php
				foreach ($product_colums as $key => $value) {
						if ($key == $colums) {
								//product colum $key and $value are from variable
								echo '<option value="' . esc_attr($key) . '" Selected=selected>' . esc_html($value) . '</option>';
						}
						else {
								echo '<option value="' . esc_attr($key) . '" >' . esc_html($value) . '</option>';
						}
				}
				?>
				</select>
				</p>
				
				<p>
				<input class="checkbox" type="checkbox" <?php if ($display_slider) { echo " checked ";} ?> id="<?php echo esc_attr($this->get_field_id('display_slider')); ?>" name="<?php echo esc_attr($this->get_field_name('display_slider')); ?>" />
				<label for="<?php echo esc_attr($this->get_field_id('display_slider')); ?>"><?php esc_html_e('Display Products as a Carousal', 'shopping-mart'); ?></label> 
				</p>
				
				<p>
				<label for="<?php echo esc_attr($this->get_field_id('slider_speed')); ?>"><?php esc_html_e('Slider Speed (ms):', 'shopping-mart'); ?></label> 
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('slider_speed')); ?>" name="<?php echo esc_attr($this->get_field_name('slider_speed')); ?>" type="number" value="<?php echo absint($slider_speed); ?>" />
				</p>
				
				<hr style="color:aliceblue" />
				<p style="text-align:center">
				<?php echo esc_html__('Unlimited products, WooCommerce products by feature, on sales, featured products, category meta, Top sales (slider and grid widgets) and 15+ premium widgets Go Pro version', 'shopping-mart'); ?>
				</p>
		
				<?php
		}

		public function update($new_instance, $old_instance) {
				$instance = array();
				$instance['category_id'] = (!empty($new_instance['category_id'])) ? esc_html($new_instance['category_id']) : '';
				$instance['display_sub_cat'] = (!empty($new_instance['display_sub_cat'])) ? ($new_instance['display_sub_cat']) : '';
				$instance['display_slider'] = (!empty($new_instance['display_slider'])) ? esc_html($new_instance['display_slider']) : '';
				$instance['colums'] = (!empty($new_instance['colums'])) ? esc_html($new_instance['colums']) : '';
				$instance['image_height'] = (!empty($new_instance['image_height'])) ? strip_tags($new_instance['image_height'])  : '' ;		
				$instance['slider_speed'] = (!empty($new_instance['slider_speed'])) ? strip_tags($new_instance['slider_speed'])  : '' ; 
				
				return $instance;
		}
}


function shopping_mart_product_category_widget() {
		register_widget('shopping_mart_product_category_widget');
}
add_action('widgets_init', 'shopping_mart_product_category_widget');