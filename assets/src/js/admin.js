/**
 * Created by Nabeel on 2016-02-02.
 */
(function ( w, $, undefined ) {
	$( function () {

		// setup button click
		$( '#rnse_indexes_setup' ).on( 'click', function ( e ) {
			// set loading status
			var $this = $( e.currentTarget ).trigger( 'is-loading' );

			$.post( ajaxurl, { 'action': 'setup_search_fulltext' }, function ( response ) {
				alert( response.data );
			}, 'json' ).always( function ( $button ) {
				return function () {
					// clear loading status
					$button.trigger( 'loading-done' );
				};
			}( $this ) );
		} );
		
		// setup button click
		$( '#rnse_clear_cache' ).on( 'click', function ( e ) {
			// set loading status
			var $this = $( e.currentTarget ).trigger( 'is-loading' );

			$.post( ajaxurl, { 'action': 'clear_search_cache' }, function ( response ) {
				alert( response.data );
			}, 'json' ).always( function ( $button ) {
				return function () {
					// clear loading status
					$button.trigger( 'loading-done' );
				};
			}( $this ) );
		} );

		// buttons loading status
		$( '#wpbody-content' ).on( 'is-loading', '.rnse-button', function ( e ) {
			$( e.currentTarget ).prop( 'disabled', true ).append( '<span class="spinner" style="visibility: visible;"></span>' );
		} ).on( 'loading-done', '.button', function ( e ) {
			$( e.currentTarget ).prop( 'disabled', false ).find( '.spinner' ).remove();
		} );

	} );
})( window, jQuery );