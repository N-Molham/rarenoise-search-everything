<?php namespace RareNoise_Search_Everything;

/**
 * Base Component
 *
 * @package RareNoise_Search_Everything
 */
class Component extends Singular {
	/**
	 * Plugin Main Component
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {
		// vars
		$this->plugin = Plugin::get_instance();
	}
}
