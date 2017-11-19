<?php namespace RareNoise_Search_Everything;

/**
 * Frontend logic
 *
 * @package RareNoise_Search_Everything
 */
class Frontend extends Component {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {
		parent::init();
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
		$cache_key = 'rnse_search_everything_' . md5( $query . $where );

		if ( ! defined( 'RNSE_DISABLE_CACHE' ) || false === RNSE_DISABLE_CACHE ) {

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

		set_transient( $cache_key, json_encode( $results ), 6 * HOUR_IN_SECONDS );

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

		// search blog posts
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as title, MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) as relevance 
FROM {$wpdb->posts} 
WHERE post_type = %s AND 
( MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) OR MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) ) 
AND post_status = 'publish'
ORDER BY relevance DESC, post_date DESC LIMIT %d",
			'+' . $query . '*',
			'product',
			'+' . $query . '*',
			$query . '*',
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
	 * Search artistes
	 *
	 * @param string $query
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function search_section_artistes( $query, $limit ) {
		/** @var $wpdb \wpdb */
		global $wpdb;

		// search blog posts
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as title, MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) as relevance 
FROM {$wpdb->posts} 
WHERE post_type = %s AND 
( MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) OR MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) ) 
AND post_status = 'publish'
ORDER BY relevance DESC, post_date DESC LIMIT %d",
			'+' . $query . '*',
			'artists',
			'+' . $query . '*',
			$query . '*',
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

		// search blog posts
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as title, MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) as relevance 
FROM {$wpdb->posts} 
WHERE post_type = %s AND 
( MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) OR MATCH (post_title) AGAINST (%s IN BOOLEAN MODE) ) 
AND post_status = 'publish'
ORDER BY relevance DESC, post_date DESC LIMIT %d",
			'+' . $query . '*',
			'post',
			'+' . $query . '*',
			$query . '*',
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
