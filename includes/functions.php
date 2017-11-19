<?php
/**
 * Created by Nabeel
 * Date: 2016-01-22
 * Time: 2:38 AM
 *
 * @package RareNoise_Search_Everything
 */

use RareNoise_Search_Everything\Component;
use RareNoise_Search_Everything\Plugin;

if ( ! function_exists( 'rarenoise_search_everything' ) ):
	/**
	 * Get plugin instance
	 *
	 * @return Plugin
	 */
	function rarenoise_search_everything() {
		return Plugin::get_instance();
	}
endif;

if ( ! function_exists( 'rnse_component' ) ):
	/**
	 * Get plugin component instance
	 *
	 * @param string $component_name
	 *
	 * @return Component|null
	 */
	function rnse_component( $component_name ) {
		if ( isset( rarenoise_search_everything()->$component_name ) ) {
			return rarenoise_search_everything()->$component_name;
		}

		return null;
	}
endif;

if ( ! function_exists( 'rnse_view' ) ):
	/**
	 * Load view
	 *
	 * @param string  $view_name
	 * @param array   $args
	 * @param boolean $return
	 *
	 * @return void
	 */
	function rnse_view( $view_name, $args = null, $return = false ) {
		if ( $return ) {
			// start buffer
			ob_start();
		}

		rarenoise_search_everything()->load_view( $view_name, $args );

		if ( $return ) {
			// get buffer flush
			return ob_get_clean();
		}
	}
endif;

if ( ! function_exists( 'rnse_version' ) ):
	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	function rnse_version() {
		return rarenoise_search_everything()->version;
	}
endif;