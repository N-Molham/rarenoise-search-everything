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
		add_action( 'woocommerce_admin_field_rnse_button', [ &$this, 'fulltext_setup_button' ] );
	}

	/**
	 * @param array $value
	 *
	 * @return void
	 */
	public function fulltext_setup_button( $value ) {

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
			<button type="button" id="<?php echo esc_attr( $value['id'] ); ?>" class="button"><?php _e( 'Setup', RNSE_DOMAIN ); ?></button>
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
				'title' => __( 'Search Everything', RNSE_DOMAIN ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'rnse_options',
			],
			[
				'title' => __( 'FULLTEXT Setup', RNSE_DOMAIN ),
				'desc'  => __( 'Setup MySQL FUllTEXT indexes for quick search results', 'woocommerce' ),
				'id'    => 'rnse_indexes_setup',
				'type'  => 'rnse_button',
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
}
