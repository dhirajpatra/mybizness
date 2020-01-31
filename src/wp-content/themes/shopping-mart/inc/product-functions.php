<?php

if(!class_exists('Shopping_Mart_Product_Item')) { 
class Shopping_Mart_Product_Item	{
	public $id;
	public $image_url;
	public $link;
	public $title;
	public $content;
	public $alt;
	public $sale;
	public $categories;
	public $price_html;
	public $rating_html;
 }
}

/**
 * Get list of products with buttons based shoping_mart_get_product_args 
 * included product category inside function
 */
function shopping_mart_list_products($args, $image_height, $colums){

	
	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ) :
	$i = 1;
	echo '<div class="col-sm-12">';
	echo '<div class="row multi-columns-row">';
		while ( $loop->have_posts() ) :
			$loop->the_post();
			global $product;
			global $post;
			$offfset_css = ' col-sm-offset-1';
			$prod_id = get_the_ID();
			$thumb_id = get_post_thumbnail_id($prod_id);	
			$url = get_the_post_thumbnail_url($prod_id , 'full');			
			?>
			<div class="<?php if($colums != 'col-sm-2'){ echo esc_attr($colums); } else { echo esc_attr($colums.$offfset_css); $offfset_css=''; } ?>">
			<div class="product-wrapper">
			

				<div class="product-image-wrapper" style="height:<?php echo absint($image_height); ?>px" >
				
					<?php if ($url) : ?>
						<a href="<?php echo esc_url( $url); ?>" title="<?php the_title_attribute(); ?>">
						
						<img src="<?php echo esc_url($url); ?>" style="height:<?php echo absint($image_height); ?>px;box-shadow:unset;" />

						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( get_permalink() ); ?>" >
						<img src="<?php echo esc_attr(business_store_TEMPLATE_DIR_URI.'/images/no-image.png'); ?>" />
						</a>									
					<?php endif; ?>
					
					<?php if ($product->is_on_sale() ) : ?>
						<div class="badge-wrapper"> <span class="onsale"><?php esc_html_e('Sale!', 'shopping-mart') ?></span></div>
					<?php endif; ?>
					<div class="product-rating-wrapper">
						<?php
						$rating = $product->get_average_rating();
						 if($rating > 0){												
							for($r=1; $r<=5; $r++){
								$class = ($r<=$rating)? 'checked':'';
								echo '<span class="fa fa-star '.esc_attr($class).'"></span>';
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
	echo '</div>';
	endif; // end loop

}


/* product slider function */
function shopping_mart_product_slider($args, $image_height, $css_class, $slider_speed, $nav_color = '#3ba0f4' ){

		/* slider colum count is determined by css class */
		if($css_class == "col-md-3 col-sm-3 col-lg-3 col-xs-6"){
			$colums = 4;			
		} else if($css_class == "col-md-4 col-sm-4 col-lg-4 col-xs-6"){
			$colums = 3;
		} else if($css_class == "col-md-2 col-sm-2 col-lg-2 col-xs-6"){
			$colums = 6;
		} else if($css_class == "col-sm-2"){
			$colums = 5;
		}
		
		$loop = new WP_Query($args);
		
		global $shopping_mart_uniqueue_id ;
		$shopping_mart_uniqueue_id += 1;
		$carousal_id = 'hwp-carousal-'.$shopping_mart_uniqueue_id;
		$i = 1;
		$items = array();
		$subitems = array();
		
		while( $loop->have_posts() ) : $loop->the_post();
			global $product;
			global $post;
			
			$prod_id = get_the_ID();
			$thumb_id = get_post_thumbnail_id($prod_id);	
			$url = get_the_post_thumbnail_url($prod_id , 'full');
			$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
			$link = get_permalink();
			$title = get_the_title();
			$content = get_the_content();			

			//product category
			$categories = "";
			if ( function_exists( 'wc_get_product_category_list' ) ) {
				$categories = wc_get_product_category_list( $prod_id );
			} else {
				$categories = $product->get_categories();
			}
			//is on sale
			$sale = ($product->is_on_sale());
			//price
			$price = $product->get_price_html();
			$price_html = '';
			if ( ! empty( $price ) ) {
				$price_html .= '<p>';
				$price_html .= wp_kses(
					$price, array(
						'span' => array(
							'class' => array(),
						),
						'del' => array(),
					)
				);
				$price_html .= '</p>';
			}
			
			$rating = $product->get_average_rating();
			$rating_html = '';
			 if($rating > 0){												
				for($r = 1; $r <= 5; $r++){
					$class = ($r <= $rating)? 'checked':'';
					$rating_html .= '<span class="fa fa-star '.esc_attr($class).'"></span>';
				}
			 }			
				
			$slideritem = new Shopping_Mart_Product_Item(); 
			$slideritem->image_url = $url;
			$slideritem->content = $content;
			$slideritem->title = $title;
			$slideritem->alt = $alt;
			$slideritem->link = $link;
			$slideritem->categories = $categories;
			$slideritem->price_html = $price_html;
			$slideritem->rating_html = $rating_html;
			$slideritem->sale = $sale;
			$slideritem->id = $prod_id;
			
				/* slider colum count is determined by
				 * css class Add sub items = colums */
				 
				if($i % $colums == 0){
					array_push($subitems, $slideritem);
					array_push($items, $subitems);
					$subitems = array();					
				} else {
					array_push($subitems, $slideritem);
				}              
			
			$i++; 
		endwhile;
		wp_reset_postdata();
		/* add remaining items (% not reach multiples of value) */
		if(!empty($subitems)) {array_push($items, $subitems);}
						
		//carousal begin
		echo '<div id="'.esc_attr($carousal_id).'" class="carousel slide" data-ride="carousel" data-interval="'.absint($slider_speed).'" >';
		echo '<div class="carousel-inner product-carousal-inner">';
	
		$active = 'active';
		$item_count = count($items);
		foreach ($items as $slides) {
			//item begin
		   echo '<div class="item '.esc_attr($active).' ">';
		   $active = '';
				//subitem begin
				$offfset_css = ' col-sm-offset-1';	
				foreach ($slides as $slideritem) {
				?>
					<div class="<?php if($colums != 5){ echo esc_attr($css_class); } else { echo esc_attr($css_class.$offfset_css); $offfset_css=''; } ?>" >
						<div class="product-wrapper">

							<div class="product-image-wrapper" style="height:<?php echo absint($image_height); ?>px">
								<?php if ( $slideritem->image_url ) : ?>
									<a href="<?php echo esc_url($slideritem->link); ?>" >
									<img src="<?php echo esc_url($slideritem->image_url); ?>" style="height:<?php echo absint($image_height); ?>px;box-shadow:unset;" />
									</a>
								<?php else : ?>
								<a href="<?php echo esc_url( get_permalink() ); ?>" >
								<img src="<?php echo esc_attr(business_store_TEMPLATE_DIR_URI.'/images/no-image.png'); ?>" />
								</a>									
								<?php endif; ?>
								<?php if ($slideritem->sale) : ?>
									<div class="badge-wrapper"> <span class="onsale"><?php esc_html_e('Sale!', 'shopping-mart') ?></span></div>
								<?php endif; ?>				
							</div>
					
							<div class="product-description">
						
								<a href="<?php echo esc_url($slideritem->link); ?>" ><p class="product-title"><?php echo esc_html($slideritem->title); ?></p></a>
								<span class="price">
									<?php
									global $shoping_mart_allowed_html;
									if ( $slideritem->price_html ) {
										echo wp_kses($slideritem->price_html, $shoping_mart_allowed_html);
									}
									?>					
								</span>
								<div class="product-rating-wrapper">
									<?php 
									global $shoping_mart_allowed_html;
									echo wp_kses($slideritem->rating_html, $shoping_mart_allowed_html); 
									?>
								</div>
							</div> <!--end product description-->
							
							<div class="wc-button-container woocommerce">
								<div>
									<?php business_store_add_to_cart($slideritem->id); ?>
								</div>
							</div>
							
						</div>
					</div>							
				<?php		
				}
				//subitem end
			echo '</div>';
			//item end
		}
		//indicators start
		echo '</div>';
		echo '</div>';
		
		$active = 'active';
		if($item_count>1){	
			echo '<ul class="carousel-navigation ">
				   	<li><a class="carousel-prev" href="#'.esc_attr($carousal_id).'" data-slide="prev" style=background-color:'.esc_attr($nav_color).';></a></li>
				   	<li><a class="carousel-next" href="#'.esc_attr($carousal_id).'" data-slide="next" style=background-color:'.esc_attr($nav_color).';></a></li>
				  </ul>';
		}
		
		//carousal end				
}
