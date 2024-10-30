<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://profiles.wordpress.org/metagauss
 * @since      1.0.0
 *
 * @package    Blog_Posts_Organizer
 * @subpackage Blog_Posts_Organizer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Blog_Posts_Organizer
 * @subpackage Blog_Posts_Organizer/includes
 * @author     Metagauss <support@metagauss.com>
 */
class Blog_Posts_Organizer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
