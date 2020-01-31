<?php
/**
 * Template Name:Left-Sidebar
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package business-store
 * @since 1.0

 */

get_header(); 
$business_store_content = 'col-sm-8 col-lg-8';
$business_store_sidebar = '';
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	$business_store_content = 'col-sm-12 col-lg-12';
	$business_store_sidebar = 'hide-content';	
}	
?>


<div class="container">
  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <div class="row">
        <div class="col-md-4 col-sm-4 floateleft <?php echo $business_store_sidebar; ?>">
          <?php get_sidebar(); ?>
        </div>
        <div class="<?php echo $business_store_content; ?>  content-area">
          <?php
			while ( have_posts() ) :
				the_post();
            ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >

            <div class="entry-content">
              <?php
							the_content();
				
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . __( 'Pages:', 'business-store' ),
									'after'  => '</div>',
								)
							);
						?>
            </div>
            <!-- .entry-content -->
          </article>
          <!-- #post-## -->
          <?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
        </div>
      </div>
    </main>
    <!-- #main -->
  </div>
  <!-- #primary -->
</div>
<!-- .container -->
<?php
get_footer();
