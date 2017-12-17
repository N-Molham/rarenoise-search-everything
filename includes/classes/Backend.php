<?php namespace RareNoise_Search_Everything;

use WC_Admin_Settings;

/**
 * Backend logic
 *
 * @package RareNoise_Search_Everything
 */
class Backend extends Component {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {
		parent::init();

		// WC Settings: API page
		add_filter( 'woocommerce_settings_rest_api', [ &$this, 'plugin_settings' ], 20 );

		// WC settings field: Setup button
		add_action( 'woocommerce_admin_field_rnse_button', [ &$this, 'settings_trigger_button' ] );
	}

	/**
	 * @param array $value
	 *
	 * @return void
	 */
	public function settings_trigger_button( $value ) {

		// load JS file
		wp_enqueue_script( 'rnse-admin', Helpers::enqueue_path() . 'js/admin.js', [ 'jquery' ], Helpers::assets_version(), true );

		$field_description = WC_Admin_Settings::get_field_description( $value );
		?>
		<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
			<?php echo $field_description['tooltip_html']; ?>
		</th>
		<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
			<button type="button" id="<?php echo esc_attr( $value['id'] ); ?>" class="rnse-button button"><?php echo $value['button_label']; ?></button>
			<p><?php echo $field_description['description']; ?></p>
		</td>
		</tr><?php
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	public function plugin_settings( $settings ) {

		return array_merge( $settings, [
			[
				'title' => __( 'Search Everything API', RNSE_DOMAIN ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'rnse_options',
			],
			[
				'title'        => __( 'FULLTEXT Setup', RNSE_DOMAIN ),
				'desc'         => __( 'Setup MySQL FUllTEXT indexes for quick search results', RNSE_DOMAIN ),
				'id'           => 'rnse_indexes_setup',
				'type'         => 'rnse_button',
				'button_label' => __( 'Setup', RNSE_DOMAIN ),
			],
			[
				'title'             => __( 'Cache hours', RNSE_DOMAIN ),
				'desc'              => __( 'Number of hours to cache search results. <code>0</code> will disable the cache.', RNSE_DOMAIN ),
				'id'                => 'rnse_cache_hours',
				'css'               => 'width:50px;',
				'default'           => 6,
				'desc_tip'          => false,
				'type'              => 'number',
				'custom_attributes' => [
					'min'  => 0,
					'step' => 1,
				],
			],
			[
				'title'        => __( 'Clear Cache', RNSE_DOMAIN ),
				'desc'         => __( 'Clear current search results cache. Do it if you chanced the cache hours and/or want to force clear now without waiting for it to expire.', RNSE_DOMAIN ),
				'id'           => 'rnse_clear_cache',
				'type'         => 'rnse_button',
				'button_label' => __( 'Clear Cache', RNSE_DOMAIN ),
			],
			[
				'type' => 'sectionend',
				'id'   => 'rnse_options',
			],
		] );

	}

	/**
	 * @return boolean|string
	 */
	public function setup_full_text_search_indexes() {
		/** @var $wpdb \wpdb */
		global $wpdb;

		/**
		 * Filter Database FULLTEXT indexes for search
		 *
		 * @param array $indexes
		 *
		 * @return array
		 */
		$target_indexes = (array) apply_filters( 'rnse_full_text_indexes', [
			'posts' => [ 'post_title, post_content', 'post_title' ],
		] );

		foreach ( $target_indexes as $table => $indexes ) {

			// table isn't defined in the DB object
			if ( ! isset( $wpdb->$table ) ) {
				continue;
			}

			$table_name = $wpdb->$table;

			$registered_indexes = array_map( function ( $index ) {
				return $index->Key_name;
			}, $wpdb->get_results( "SHOW INDEX from {$table_name} WHERE Index_type = 'FULLTEXT'" ) );

			/** @var string $columns */
			/** @var array $indexes */
			foreach ( $indexes as $columns ) {
				$index_key = 'ft_' . sanitize_key( str_replace( ',', '_', $columns ) );

				// skip already existing index
				if ( in_array( $index_key, $registered_indexes, true ) ) {
					continue;
				}

				$wpdb->query( "ALTER TABLE {$table_name} ADD FULLTEXT {$index_key}({$columns})" );

				if ( $wpdb->last_error ) {
					return $wpdb->last_error;
				}
			}
		}

		return true;
	}

	/**
	 * @return void
	 */
	public function clear_cache() {
		/** @var $wpdb \wpdb */
		global $wpdb;

		Helpers::set_time_limit();

		$cache_key = rarenoise_search_everything()->frontend->get_cache_key();

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_{$cache_key}_%' OR option_name LIKE '_transient_timeout_{$cache_key}_%'" );
	}
}
