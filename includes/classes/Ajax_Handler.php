<?php namespace RareNoise_Search_Everything;

/**
 * AJAX handler
 *
 * @package RareNoise_Search_Everything
 */
class Ajax_Handler extends Component {

	/**
	 * @var array
	 */
	protected $_actions;

	/**
	 * @var array
	 */
	protected $_nopriv_actions;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {
		parent::init();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			$action = filter_var( isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '', FILTER_SANITIZE_STRING );

			$this->_nopriv_actions = [
				'search_everything',
			];

			$this->_actions = [
				'search_everything',
				'setup_search_fulltext',
				'clear_search_cache',
			];

			if ( in_array( $action, $this->_actions, true ) ) {
				// hook into action if it's method exists
				add_action( 'wp_ajax_' . $action, [ &$this, $action ] );
			}

			if ( in_array( $action, $this->_nopriv_actions, true ) ) {
				// hook into action if it's method exists
				add_action( 'wp_ajax_nopriv_' . $action, [ &$this, $action ] );
			}
		}
	}

	/**
	 * @return void
	 */
	public function search_everything() {

		$query = isset( $_REQUEST['query'] ) ? sanitize_text_field( $_REQUEST['query'] ) : null;

		if ( '' === $query || empty( $query ) || strlen( $query ) > 100 ) {
			$this->error( __( 'invalid query argument', RNSE_DOMAIN ) );
		}

		// search for place by default
		$where = isset( $_REQUEST['where'] ) ? array_map( 'sanitize_key', explode( ',', $_REQUEST['where'] ) ) : null;
		if ( null === $where || empty( $where ) ) {
			$where = [ 'releases' ];
		}

		$this->success( rarenoise_search_everything()->frontend->search_everything( $query, $where ) );
	}

	/**
	 * @return void
	 */
	public function clear_search_cache() {

		if ( false === current_user_can( 'manage_options' ) ) {
			$this->error( __( 'Insufficient Permissions', RNSE_DOMAIN ) );
		}

		rarenoise_search_everything()->frontend->clear_cache();

		$this->success( __( 'Done', RNSE_DOMAIN ) );
	}
	
	/**
	 * @return void
	 */
	public function setup_search_fulltext() {

		if ( false === current_user_can( 'manage_options' ) ) {
			$this->error( __( 'Insufficient Permissions', RNSE_DOMAIN ) );
		}

		$done = rarenoise_search_everything()->backend->setup_full_text_search_indexes();

		if ( true === $done ) {
			$this->success( __( 'Done', RNSE_DOMAIN ) );
		}

		$this->debug( $done );
	}

	/**
	 * AJAX Debug response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function debug( $data ) {
		// return dump
		$this->error( $data );
	}

	/**
	 * AJAX Debug response ( dump )
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $args
	 *
	 * @return void
	 */
	public function dump( $args ) {
		// return dump
		$this->error( print_r( func_num_args() === 1 ? $args : func_get_args(), true ) );
	}

	/**
	 * AJAX Error response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function error( $data ) {
		wp_send_json_error( $data );
	}

	/**
	 * AJAX success response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function success( $data ) {
		wp_send_json_success( $data );
	}

	/**
	 * AJAX JSON Response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $response
	 *
	 * @return void
	 */
	public function response( $response ) {
		// send response
		wp_send_json( $response );
	}
}
