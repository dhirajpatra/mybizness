<?php
global $business_store_option;
if(!$business_store_option['home_header_section_disable']){
	business_store_sub_header();
} elseif ((!is_front_page() )){ 
	business_store_sub_header();
} elseif ('posts' == get_option( 'show_on_front' )){
	business_store_sub_header();
}
function business_store_sub_header() {
	global $post;
	$url='';
	$homeLink = esc_url( home_url() );
?> 	
	<div class="sub-header" style="background:url('<?php echo esc_url(get_header_image()); ?>');">
	
	<div class="sub-header-inner sectionoverlay">
	<?php
	
	if(is_search()){
		echo '<div class="title">'. esc_html__('Search Results','business-store').'</div>';
	} else if( is_404() ){
		echo '<div class="title">'. esc_html__('Page not Found','business-store').'</div>';
	} else if( is_archive() || is_category() ){
		echo '<div class="title">'. esc_html(get_the_archive_title()).'</div>';
	} else if( is_single() ){
		echo  the_title('<div class="title">', '</div>');
	} else if(is_front_page() || is_home() ){
		echo '<div class="title">'. esc_html(get_bloginfo( 'name' )) .'</div>';
	} else {
	    echo  the_title('<div class="title">', '</div>');
	}

	?>
	</div>
</div><!-- .sub-header -->
<?php 
}