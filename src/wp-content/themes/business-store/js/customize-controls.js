/**
 * This Scripts within the customizer controls window control relavent options to user
 */

(function() {
		  
	  
	wp.customize.bind( 'ready', function() {
		
		// Only show the color hue control when there's a custom font scheme.
		wp.customize( 'fontsscheme', function( setting ) {
			wp.customize.control( 'body_fontfamily', function( control ) {
				var visibility = function() {
					if ( 'custom' === setting.get() ) {
						control.container.slideDown( 180 );
					} else {
						control.container.slideUp( 180 );
					}
				};

				visibility();
				setting.bind( visibility );
			});
		});		

		// Detect when the front page sections section is expanded (or closed) so we can adjust the preview accordingly.
		wp.customize.section( 'theme_options', function( section ) {
			section.expanded.bind( function( isExpanding ) {

				// Value of isExpanding will = true if you're entering the section, false if you're leaving it.
				wp.customize.previewer.send( 'section-highlight', { expanded: isExpanding });
			} );
		} );
	});
})( jQuery );
