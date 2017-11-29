<?php namespace RareNoise_Search_Everything\Widgets;

use RareNoise_Search_Everything\Helpers;
use WP_Widget;

/**
 * Search Widget
 *
 * @package RareNoise_Search_Everything
 */
class Search_Widget extends WP_Widget {

	/**
	 * Search_Widget constructor
	 */
	public function __construct() {

		parent::__construct(
			'rarenoise_search_widget',
			__( 'RareNoise Search Everything', RNSE_DOMAIN ), [
				'classname'   => RNSE_DOMAIN,
				'description' => __( 'Component search text field and results', RNSE_DOMAIN ),
			]
		);

	}

	public function widget( $args, $instance ) {

		wp_enqueue_script( 'rnse-search', Helpers::enqueue_path() . 'js/search.js', [ 'jquery' ], Helpers::assets_version(), true );

		wp_localize_script( 'rnse-search', 'rnse_search', [
			'is_mobile' => wp_is_mobile(),
		] );

		rnse_view( 'search_widget_content', compact( 'args', 'instance' ) );

	}

}