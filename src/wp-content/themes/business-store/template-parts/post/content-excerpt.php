<?php
/**
 * Template part for displaying posts with excerpts
 *
 * Used in Search Results and for Recent Posts in Front Page panels.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package business-store
 * @since 1.0

 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php
				
				echo business_store_time_link();
								
				business_store_edit_link();				
				
				?>
				
			</div><!-- .entry-meta -->
		<?php elseif ( 'page' === get_post_type() && get_edit_post_link() ) : ?>
			<div class="entry-meta">
				<span> <?php esc_html_e('by ', 'business-store'); the_author(); ?></span>
				<?php business_store_edit_link(); ?>
				
			</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php
		if ( is_front_page() && ! is_home() ) {

			// The excerpt is being displayed within a front page section, so it's a lower hierarchy than h2.
			the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
		} else {
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->
	
	<?php if( has_post_thumbnail() ): ?>
	<div class="post-thumbnail" >
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('full'); ?>
		</a>
	</div><!-- .post-thumbnail -->
	<?php endif; ?>	
	
	<div class="entry-container">	
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div>

</article><!-- #post-## -->
