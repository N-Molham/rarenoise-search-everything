(function ( $ ) {
	'use strict';

	$( function () {

		// vars
		var $body      = $( 'body' ),
		    $body_html = $( 'html,body' ),
		    search_request;

		$body.on( 'click', '.uk-navbar-flip a', function ( e ) {
			setTimeout( function ( link ) {

				var $input = rnse_search.is_mobile ?
					$( link ).closest( '.uk-navbar-flip' ).siblings( '.tm-search-bar' ).find( 'input.search-everything-input' ) :
					$( link ).closest( '.tm-navbar' ).find( 'input.search-everything-input:visible' );

				if ( $input.length ) {
					$input.focus();
					$body.addClass( 'search-everything-overlay-open' );
					$body_html.css( { 'overflow': 'hidden' } );
				}

			}( e.currentTarget ), 50 );
		} );

		$body.on( 'click', '.tm-search-bar .uk-float-right a[data-uk-toggle]', function () {

			if ( $body.hasClass( 'search-everything-overlay-open' ) ) {
				$body.removeClass( 'search-everything-overlay-open' );
				$body_html.css( { 'overflow': '' } );
			}

		} );

		$( '.search-everything-input' ).each( function ( index, search_input ) {
			// vars
			var $search_input     = $( search_input ),
			    $search_container = $search_input.siblings( '.search-everything-results' ),
			    $search_results   = $search_container.find( 'section.search-everything-result' );

			// Search input typing handler
			$search_input.typeWatch( {
				captureLength: 2,
				wait         : 500,
				callback     : function ( $input, $container, $results ) {
					return function ( value ) {

						if ( search_request ) {
							// clear previous request
							search_request.abort();
						}

						$container.addClass( 'is-loading' );
						$input.addClass( 'is-loading' ).siblings( 'div.loading-indicator' ).removeClass( 'uk-hidden' );

						// fetch the form
						search_request = $.post( wc_cart_fragments_params.ajax_url, {
							action: 'search_everything',
							query : value,
							where : 'posts,artists,releases'
						}, function ( response ) {
							// walk through results parts
							for ( var part in response.data ) {
								// skip non-property 
								if ( !response.data.hasOwnProperty( part ) ) {
									continue;
								}

								var results       = response.data[ part ],
								    $results_part = $results.filter( '.' + part + '-result' );

								if ( 0 === $results_part.length ) {
									continue;
								}

								var $results_list = $results_part.find( 'ul.results-section-list' ).empty();

								if ( results.length ) {
									// results found
									$results_part.addClass( 'has-results' );

									var results_items = [],
									    item_template = $results_list.data( 'template' );

									for ( var i = 0; i < results.length; i++ ) {
										results_items.push( parse_template( results[ i ], item_template ) );
									}

									$results_list.html( results_items.join( '' ) );
								} else {
									// found nothing
									$results_list.html( $results_list.data( 'no-results' ) );
									$results_part.removeClass( 'has-results' );
								}
							}
						} ).always( function () {
							$container.removeClass( 'is-loading uk-hidden' );
							$input.removeClass( 'is-loading' ).siblings( 'div.loading-indicator' ).addClass( 'uk-hidden' );
						} );
					};
				}( $search_input, $search_container, $search_results )
			} );
		} );

	} );

	/**
	 * @param {Object} item
	 * @param {String} template
	 *
	 * @return {String}
	 */
	function parse_template( item, template ) {

		for ( var key in item ) {
			if ( item.hasOwnProperty( key ) ) {
				template = template.replace( new RegExp( '{' + key + '}', 'g' ), $.isArray( item[ key ] ) ? item[ key ].join( ', ' ) : item[ key ] );
			}
		}

		return template;
	}

	/*
	* TypeWatch 3
	* 
	* Dual licensed under the MIT and GPL licenses:
	* http://www.opensource.org/licenses/mit-license.php
	* http://www.gnu.org/licenses/gpl.html
	*/
	$.fn.typeWatch = function ( user_options ) {
		// The default input types that are supported
		var _supportedInputTypes = [ 'TEXT', 'TEXTAREA', 'TEL', 'SEARCH', 'URL', 'EMAIL',
			'DATETIME', 'DATE', 'MONTH', 'WEEK', 'TIME', 'DATETIME-LOCAL' ];

		// Options
		var options = $.extend( {
			wait         : 750,
			callback     : function () {
			},
			highlight    : true,
			captureLength: 2,
			allowSubmit  : false,
			inputTypes   : _supportedInputTypes
		}, user_options );

		function checkElement( timer, override ) {
			var value = timer.$el.val();

			// If has capture length and has changed value
			// Or override and has capture length or allowSubmit option is true
			// Or capture length is zero and changed value
			if ( (value.length >= options.captureLength && value !== timer.text)
				|| (override && (value.length >= options.captureLength || options.allowSubmit))
				|| (0 === value.length && timer.text) ) {
				timer.text = value;
				timer.cb.call( timer.el, value );
			}
		}

		function watchElement( element ) {
			var elementType = (element.type || element.nodeName).toUpperCase();
			if ( $.inArray( elementType, options.inputTypes ) >= 0 ) {

				// Allocate timer element
				var timer = {
					timer: null,
					text : $( element ).val(),
					cb   : options.callback,
					el   : element,
					$el  : $( element ),
					type : elementType,
					wait : options.wait
				};

				// Set focus action (highlight)
				if ( options.highlight ) {
					timer.$el.on( 'focus', function ( e ) {
						e.currentTarget.select();
					} );
				}

				// Key watcher / clear and reset the timer
				timer.$el.on( 'keydown paste cut input', function ( e ) {
					var timerWait    = timer.wait,
					    overrideBool = false;

					// If enter key is pressed and not a TEXTAREA
					if ( typeof e.keyCode !== 'undefined' && e.keyCode === 13
						&& elementType !== 'TEXTAREA' ) {
						timerWait    = 1;
						overrideBool = true;
					}

					var timerCallbackFx = function () {
						checkElement( timer, overrideBool )
					};

					// Clear timer
					clearTimeout( timer.timer );
					timer.timer = setTimeout( timerCallbackFx, timerWait );
				} );
			}
		}

		// Watch each element
		return this.each( function () {
			watchElement( this );
		} );
	};
})( jQuery );