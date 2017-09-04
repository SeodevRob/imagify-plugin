/**
 * Mini chart.
 *
 * @param {element} canvas The canvas element.
 */
window.imagify.drawMeAChart = function( canvas ) {
	canvas.each( function() {
		var $this        = jQuery( this ),
			theValue     = parseInt( $this.closest( '.imagify-chart' ).next( '.imagify-chart-value' ).text() ),
			overviewData = [
				{
					value: theValue,
					color: '#00B3D3'
				},
				{
					value: 100 - theValue,
					color: '#D8D8D8'
				}
			];

		new Chart( $this[0].getContext( '2d' ) ).Doughnut( overviewData, { // eslint-disable-line new-cap
			segmentStrokeColor: '#FFF',
			segmentStrokeWidth: 1,
			animateRotate:      true,
			tooltipEvents:      []
		} );
	} );
};

(function($, d, w, undefined) { // eslint-disable-line no-unused-vars, no-shadow, no-shadow-restricted-names

	/**
	 * Toggle slide in custom column.
	 */
	$( '.imagify-datas-details' ).hide();

	$( d ).on( 'click', '.imagify-datas-more-action a', function( e ) {
		var $this = $( this );

		e.preventDefault();

		if ( $this.hasClass( 'is-open' ) ) {
			$( $this.attr( 'href' ) ).slideUp( 300 ).removeClass( 'is-open' );
			$this.removeClass( 'is-open' ).find( '.the-text' ).text( $this.data( 'open' ) );
		} else {
			$( $this.attr( 'href' ) ).slideDown( 300 ).addClass( 'is-open' );
			$this.addClass( 'is-open' ).find( '.the-text' ).text( $this.data( 'close' ) );
		}
	} );

	/**
	 * Process one of these actions: restore, optimize or re-optimize.
	 */
	$( d ).on( 'click', '.button-imagify-restore, .button-imagify-manual-upload, .button-imagify-manual-override-upload', function( e ) {
		var $obj    = $( this ),
			$parent = $obj.parents( '.column-imagify_optimized_file, .compat-field-imagify .field' ),
			href    = $obj.attr( 'href' );

		e.preventDefault();

		if ( ! $parent.length ) {
			$parent = $obj.closest( '.column' );
		}

		$parent.html( '<div class="button"><span class="imagify-spinner"></span>' + $obj.data( 'waiting-label' ) + '</div>' );

		$.get( href.replace( 'admin-post.php', 'admin-ajax.php' ) )
			.done( function( response ){
				$parent.html( response.data );
				$parent.find( '.imagify-datas-more-action a' ).addClass( 'is-open' ).find( '.the-text' ).text( $parent.find( '.imagify-datas-more-action a' ).data( 'close' ) );
				$parent.find( '.imagify-datas-details' ).addClass( 'is-open' );

				w.imagify.drawMeAChart( $parent.find( '.imagify-consumption-chart' ) );
			} );
	} );

	/**
	 * Update the chart in the media modal when a media is selected, and the ones already printed.
	 */
	$( w ).on( 'canvasprinted.imagify', function( e, selector ) {
		var $canvas;

		selector = selector || '.imagify-consumption-chart';
		$canvas  = $( selector );

		w.imagify.drawMeAChart( $canvas );
		$canvas.closest( '.imagify-datas-list' ).siblings( '.imagify-datas-details' ).hide();
	} )
		.trigger( 'canvasprinted.imagify' );

} )(jQuery, document, window);
