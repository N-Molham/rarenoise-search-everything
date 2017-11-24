<?php namespace RareNoise_Search_Everything;

/**
 * Frontend logic
 *
 * @package RareNoise_Search_Everything
 */
class Frontend extends Component {

	/**
	 * @var string
	 */
	protected $_cache_key = 'rnse_search_everything';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {
		parent::init();

		add_action( 'widgets_init', [ &$this, 'register_widget' ], 20 );
	}

	/**
	 * @return void
	 */
	public function register_widget() {

		register_widget( '\RareNoise_Search_Everything\Widgets\Search_Widget' );

	}

	/**
	 * @return void
	 */
	public function clear_cache() {
		/** @var $wpdb \wpdb */
		global $wpdb;

		Helpers::set_time_limit();

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_{$this->_cache_key}_%' OR option_name LIKE '_transient_timeout_{$this->_cache_key}_%'" );
	}

	/**
	 * @param string $query
	 * @param array  $where
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function search_everything( $query, $where, $limit = 6 ) {

		// look into caching first
		$cache_key   = $this->_cache_key . '_' . md5( $query . $where );
		$cache_hours = (int) get_option( 'rnse_cache_hours', 6 );

		if ( $cache_hours <= 0 || ! defined( 'RNSE_DISABLE_CACHE' ) || false === RNSE_DISABLE_CACHE ) {

			$results = get_transient( $cache_key );
			if ( false !== $results ) {
				return json_decode( $results );
			}

		}

		/** @var array $results */
		$results = [];

		foreach ( $where as $section ) {

			$section_callback = [ &$this, 'search_section_' . $section ];

			if ( is_callable( $section_callback ) ) {
				$results[ $section ] = $section_callback( $query, $limit );
			}

		}

		set_transient( $cache_key, json_encode( $results ), $cache_hours * HOUR_IN_SECONDS );

		/**
		 * Filter search everywhere results
		 *
		 * @param array  $results
		 * @param string $query
		 * @param array  $where
		 * @param int    $limit
		 *
		 * @return array
		 */
		return (array) apply_filters( 'rnse_search_everything_results', $results, $query, $where, $limit );
	}

	/**
	 * Search releases/products
	 *
	 * @param string $query
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function search_section_releases( $query, $limit ) {
		/** @var $wpdb \wpdb */
		global $wpdb;

		$query_any  = '+' . preg_replace( '/\s/', ' +', $query );
		$query_like = '%' . $wpdb->esc_like( $query ) . '%';

		// search blog posts
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as title, MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) as relevance 
FROM {$wpdb->posts} 
WHERE post_type = %s AND 
( MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) OR MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) OR post_title LIKE %s OR post_content LIKE %s ) 
AND post_status = 'publish'
ORDER BY relevance DESC, post_date DESC LIMIT %d",
			$query_any,
			'product',
			'"' . $query . '"',
			$query_any,
			$query_like,
			$query_like,
			$limit
		) );

		foreach ( $results as &$product ) {
			// listing post object
			$article_post = get_post( $product->id );

			$product->link = apply_filters( 'the_permalink', get_permalink( $article_post ), $article_post );
		}

		return $results;
	}

	/**
	 * Search artists
	 *
	 * @param string $query
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function search_section_artists( $query, $limit ) {
		/** @var $wpdb \wpdb */
		global $wpdb;

		$query_any  = '+' . preg_replace( '/\s/', ' +', $query );
		$query_like = '%' . $wpdb->esc_like( $query ) . '%';

		// search blog posts
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as title, MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) as relevance 
FROM {$wpdb->posts} 
WHERE post_type = %s AND 
( MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) OR MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) OR post_title LIKE %s OR post_content LIKE %s ) 
AND post_status = 'publish'
ORDER BY relevance DESC, post_date DESC LIMIT %d",
			$query_any,
			'artists',
			'"' . $query . '"',
			$query_any,
			$query_like,
			$query_like,
			$limit
		) );

		foreach ( $results as &$artist ) {
			// listing post object
			$article_post = get_post( $artist->id );

			$artist->link = apply_filters( 'the_permalink', get_permalink( $article_post ), $article_post );
		}

		return $results;
	}

	/**
	 * Search posts
	 *
	 * @param string $query
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function search_section_posts( $query, $limit ) {
		/** @var $wpdb \wpdb */
		global $wpdb;

		$query_any  = '+' . preg_replace( '/\s/', ' +', $query );
		$query_like = '%' . $wpdb->esc_like( $query ) . '%';

		// search blog posts
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as title, MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) as relevance 
FROM {$wpdb->posts} 
WHERE post_type = %s AND 
( MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) OR MATCH (post_title,post_content) AGAINST (%s IN BOOLEAN MODE) OR post_title LIKE %s OR post_content LIKE %s ) 
AND post_status = 'publish'
ORDER BY relevance DESC, post_date DESC LIMIT %d",
			$query_any,
			'post',
			'"' . $query . '"',
			$query_any,
			$query_like,
			$query_like,
			$limit
		) );

		foreach ( $results as &$article ) {
			// listing post object
			$article_post = get_post( $article->id );

			$article->link = apply_filters( 'the_permalink', get_permalink( $article_post ), $article_post );
		}

		return $results;
	}
}
