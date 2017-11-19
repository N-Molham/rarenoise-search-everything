/**
 * Created by Nabeel on 2016-02-02.
 */
(function ( w, $, undefined ) {
	$( function () {

		// setup button click
		$( '#rnse_indexes_setup' ).on( 'click', function ( e ) {
			// set loading status
			var $this = $( e.currentTarget ).prop( 'disabled', true ).append( '<span class="spinner" style="visibility: visible;"></span>' );

			$.post( ajaxurl, { 'action': 'setup_search_fulltext' }, function ( response ) {
				alert( response.data );
			}, 'json' ).always( function ( $button ) {
				return function () {
					// clear loading status
					$button.prop( 'disabled', false ).find( '.spinner' ).remove();
				};
			}( $this ) );
		} );

	} );
})( window, jQuery );