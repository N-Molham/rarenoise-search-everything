<?php
/**
 * Created by PhpStorm.
 * User: Nabeel
 * Date: 24-Nov-17
 * Time: 5:16 PM
 */
?>

<div id="search-everything-overlay" class="search-everything-overlay">

	<div class="search-everything-field uk-container">
		<input type="text" class="search-everything-input" />

		<?php if ( function_exists( 'woofc_spinner_html' ) ) : ?>
			<div class="loading-indicator uk-hidden"><?php woofc_spinner_html( false, false ); ?></div>
		<?php else: ?>
			<span class="loading-indicator uk-hidden"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i></span>
		<?php endif; ?>

		<a href="#" class="search-everything-trigger" data-search-action="close" data-uk-toggle="{target:'.tm-search-bar'}"><i class="uk-icon-close uk-icon-small"></i></a>
	</div>

	<div class="search-everything-results uk-container uk-hidden">

		<section class="search-everything-result posts-result">
			<h4 class="results-section-title"><?php _e( 'Posts', RNSE_DOMAIN ); ?></h4>

			<ul class="results-section-list"
			    data-no-results="<?php echo esc_attr( '<li class="no-results">' . __( 'No matches found.', RNSE_DOMAIN ) . '</li>' ); ?>"
			    data-template="<?php echo esc_attr( '<li><a href="{link}">{title}</a></li>' ); ?>"></ul>
		</section>

		<section class="search-everything-result artists-result">
			<h4 class="results-section-title"><?php _e( 'Artists', RNSE_DOMAIN ); ?></h4>

			<ul class="results-section-list"
			    data-no-results="<?php echo esc_attr( '<li class="no-results">' . __( 'No matches found.', RNSE_DOMAIN ) . '</li>' ); ?>"
			    data-template="<?php echo esc_attr( '<li><a href="{link}">{title}</span></a></li>' ); ?>"></ul>
		</section>

		<section class="search-everything-result releases-result">
			<h4 class="results-section-title"><?php _e( 'Releases', RNSE_DOMAIN ); ?></h4>

			<ul class="results-section-list"
			    data-no-results="<?php echo esc_attr( '<li class="no-results">' . __( 'No matches found.', RNSE_DOMAIN ) . '</li>' ); ?>"
			    data-template="<?php echo esc_attr( '<li><a href="{link}">{title}</a></li>' ); ?>"></ul>
		</section>

	</div>

</div>