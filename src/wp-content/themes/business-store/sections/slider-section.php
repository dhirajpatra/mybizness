<?php
/**
 * Displays home slider
 * @package business-store
 * @since 1.0
 */
business_store_slider();
function business_store_slider(){

global $business_store_option;	
if ( class_exists( 'WP_Customize_Control' ) ) {
   $business_store_settings = new business_store_settings();
   $business_store_option = wp_parse_args(  get_option( 'business_store_option', array() ) , $business_store_settings->default_data());  
}
?>
<section id="slider-section">
	<div class="svc-section-body section-padding" >
	<div class="container">
		<div class="row">
			<div class="col-md-3">
			<?php business_store_product_navigation(esc_html__('Product Categories','business-store'), $business_store_option['slider_nav_count']); ?>
			</div>
			
			<div class="col-md-9">
<?php
//set query args to show specified amount or show all posts from particular category. 
$count = 0;
$args = array ( 'post_type' => 'product','posts_per_page'=> $business_store_option['slider_max_items'], 'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'terms' => $business_store_option['slider_cat'],
					'operator' => 'IN',
					)
				));

if($business_store_option['slider_cat']=='0') {
$args = array ( 'post_type' => 'product','posts_per_page'=> $business_store_option['slider_max_items']);
}
				
$loop = new WP_Query($args);
$count = $loop->post_count;


?>

<div id="main_Carousel" class="carousel slide <?php if( $business_store_option['slider_animation_type']=='fade' ){ echo 'carousel-' . esc_attr( $business_store_option['slider_animation_type'] ); } ?>" data-ride="carousel"  data-interval="<?php echo absint( $business_store_option['slider_speed']); ?>">
	<div class="no-z-index">
	<?php if($count>1): ?>
	  <ol class="carousel-indicators">
		<?php 
				$j = 0;			
				for ($j = 0; $j < $count; $j++):							
		?>
		<li data-target="#main_Carousel" data-slide-to="<?php echo absint($j); ?>" class="<?php if($j==0){ echo 'active'; }  ?>"></li>
		<?php								
				endfor;
		?>
	  </ol>
	 <?php endif; ?>
    </div>

  <div class="carousel-inner" role="listbox">
    <?php 
		  $i = 0;
		  while( $loop->have_posts() ) : $loop->the_post();
		  global $product;		  
			
    ?>
    <div class="item <?php if($i==0){ echo 'active'; } $i++; ?> "> 
	<?php 
	$thumb_id = $url = $my_title = '';
	$alt = '';
	if( has_post_thumbnail() ):
		$thumb_id = get_post_thumbnail_id(get_the_ID());	
		$url = esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full'));
		if(!$url) {
			$url = business_store_TEMPLATE_DIR_URI.'/images/no-image.png';
		}
		$my_title = esc_html(get_the_title());
		$post_link = get_permalink( get_the_ID() );
		$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
		$price = $product->get_price_html();
	endif;
	?>
	<a href="<?php echo esc_url($post_link) ?>" >
	<img src="<?php echo esc_url($url); ?>" style="max-height:<?php echo absint($business_store_option['slider_image_height']); ?>px; width:100%" alt="<?php echo esc_attr($alt); ?>" ></img>	     
	</a>
	<?php
	echo '<div class="pro-slider-caption on-left">';
		echo '<div class="caption-heading">';
			echo '<h3 class="cap-title"><a href="'.'#'.'">'.esc_html($my_title).'</a></h3>';
		echo '</div>';		
		echo '<div class="price">'.wp_kses_post($price).'</div>';
	echo '</div>';		 
	?>
    </div>
    <?php
		endwhile;
		wp_reset_postdata(); 
	?>
</div>
	<?php if($count>1): ?>
			<ul class="carousel-navigation">
				<li><a class="carousel-prev" href="#main_Carousel" data-slide="prev"></a></li>
				<li><a class="carousel-next" href="#main_Carousel" data-slide="next"></a></li>
			</ul>
	<?php endif; ?> 

  </div>
  </div>
  </div>
  
  
 </div>
</div>
</section>

<?php
}