<?php

// Register and load the widget
function business_store_lite_latest_posts_widget() {
	register_widget( 'business_store_lite_latest_posts_widget' );
}
add_action( 'widgets_init', 'business_store_lite_latest_posts_widget' );

// Creating the widget 
class business_store_lite_latest_posts_widget extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'business_store_lite_latest_posts_widget', 

// Widget name will appear in UI
__('Advanced Recent Posts', 'business-store'), 

// Widget description
array( 'description' => __( 'Display latest_posts with featured image and Meta', 'business-store' ), ) 
);
}

// Creating widget front-end

public function widget( $args, $instance ) {
$title = ( ! empty( $instance['title'] ) ) ? strip_tags( apply_filters( 'widget_title', $instance['title'] ) ) : __( 'Recent Posts', 'business-store' );
$max_items = ( ! empty( $instance['max_items'] ) ) ? strip_tags( $instance['max_items'] ) : '5';
$hide_title = ( ! empty( $instance['hide_title'] ) ) ? strip_tags( $instance['hide_title'] ) : false;

// before and after widget arguments are defined by themes
echo $args['before_widget'];
if (!$hide_title)
echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];

// This run the code and display the output

business_store_lite_get_latest_posts($max_items);
	
//
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
$title = ( ! empty( $instance['title'] ) ) ? strip_tags( apply_filters( 'widget_title', $instance['title'] ) ) : __( 'Recent Posts', 'business-store' );
$max_items = ( ! empty( $instance['max_items'] ) ) ? strip_tags( $instance['max_items'] ) : '5';
$hide_title = ( ! empty( $instance['hide_title'] ) ) ? strip_tags( $instance['hide_title'] ) : false;
// Widget admin form
?>

<p>
<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:','business-store' ); ?></label> 
<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo esc_attr($this->get_field_id( 'max_items' )); ?>"><?php esc_html_e( 'Number of posts to Show:','business-store'  ); ?></label> 
<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'max_items' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'max_items' )); ?>" type="number" value="<?php echo absint( $max_items ); ?>" />
</p>
<p>
<input class="checkbox" type="checkbox" <?php if($hide_title){echo " checked ";} ?> id="<?php echo esc_attr($this->get_field_id( 'hide_title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'hide_title' )); ?>" />
<label for="<?php echo esc_attr($this->get_field_id( 'hide_title' )); ?>"><?php esc_html_e( 'Hide Widget Title','business-store' ); ?></label> 
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : esc_html_e( 'Advanced Recent Posts','business-store' );
	$instance['max_items'] = ( ! empty( $new_instance['max_items'] ) ) ? absint( $new_instance['max_items'] ) : '5';
	$instance['hide_title'] = ( ! empty( $new_instance['hide_title'] ) ) ? sanitize_text_field( $new_instance['hide_title'] ) : false;
	return $instance;
 }
} // Class latest_posts_list_widget ends here


function business_store_lite_get_latest_posts($max){
 
	echo '<ul class="adv-recent-posts">';
		
		$args = array( 'post_type' => 'post', 'ignore_sticky_posts' => 1 ,  'posts_per_page' =>  absint($max), 'numberposts' => absint($max) , 'orderby' => 'date', 'order' => 'DESC');		 
		$latest_posts_query = new WP_Query($args);
        
		while ($latest_posts_query->have_posts()) : $latest_posts_query->the_post();
		$i=1;
			 
				?>
				<li>
					<table border="0">
					  <tr>
						<td rowspan="2"><a href="<?php echo esc_url(get_the_permalink()); ?>">						
						<?php 
							if ( has_post_thumbnail() ) {
							   the_post_thumbnail();
							}
						 ?>
						 </a></td>
						<td><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo esc_html(get_the_title()) ;?></a></td>
					  </tr>
					  <tr class="no-border">
					     <td class="entry-meta"><?php echo esc_html(get_the_date( 'Y-M-d' )); echo ' &iota; '.esc_html(get_the_author()); ?></td>
					  </tr>
					</table> 
				</li>				     
				<?php				
			 
		$i++;
		endwhile;
		wp_reset_postdata();		
	echo '</ul>';

}
