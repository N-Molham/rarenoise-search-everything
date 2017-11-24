<?php
/**
 * Created by PhpStorm.
 * User: Nabeel
 * Date: 24-Nov-17
 * Time: 5:16 PM
 */
?>

<?php echo $args['before_widget']; ?>

	<input type="text" id="search-everything-input" class="search-everything-input" />

	<div id="search-everything-results" class="search-everything-results">

		<section class="search-everything-result posts-result">
			<h4 class="results-section-title"><?php _e( 'Posts', RNSE_DOMAIN ); ?></h4>

			<ul class="results-section-list" 
			    data-no-results="<?php echo esc_attr( '<li class="no-results">' . __( 'No matches found.', RNSE_DOMAIN ) . '</li>' ); ?>"
			    data-template="<?php echo esc_attr( '<li id="search-post-{id}"><a href="{link}">{title}</a></li>' ); ?>"></ul>
		</section>

		<section class="search-everything-result artists-result">
			<h4 class="results-section-title"><?php _e( 'Artists', RNSE_DOMAIN ); ?></h4>

			<ul class="results-section-list" 
			    data-no-results="<?php echo esc_attr( '<li class="no-results">' . __( 'No matches found.', RNSE_DOMAIN ) . '</li>' ); ?>"
			    data-template="<?php echo esc_attr( '<li id="search-artist-{id}"><a href="{link}">{title}</span></a></li>' ); ?>"></ul>
		</section>

		<section class="search-everything-result releases-result">
			<h4 class="results-section-title"><?php _e( 'Releases', RNSE_DOMAIN ); ?></h4>

			<ul class="results-section-list" 
			    data-no-results="<?php echo esc_attr( '<li class="no-results">' . __( 'No matches found.', RNSE_DOMAIN ) . '</li>' ); ?>"
			    data-template="<?php echo esc_attr( '<li id="{search-release-{id}"><a href="{link}">{title}</a></li>' ); ?>"></ul>
		</section>

	</div>

<?php echo $args['after_widget']; ?>