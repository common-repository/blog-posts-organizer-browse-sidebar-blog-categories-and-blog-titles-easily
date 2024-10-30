<?php

/**
 *
 * @link              https://profiles.wordpress.org/metagauss
 * @since             1.0.0
 * @package           Blog_Posts_Organizer
 *
 * @wordpress-plugin
 * Plugin Name:       Blog Posts Organizer - Browse Sidebar, Blog Categories and Blog Titles Easily.
 * Plugin URI:        https://metagauss.com
 * Description:       This plugin is designed to enhance user experience by providing a dynamic sidebar that neatly categorizes blog posts, making it effortless to explore your content.
 * Version:           1.0.0
 * Author:            Blog Post Sidebar
 * Author URI:        https://profiles.wordpress.org/bpsidebar/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BLOG_POSTS_ORGANIZER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blog-posts-organizer-activator.php
 */
function bpo_org_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blog-posts-organizer-activator.php';
	Blog_Posts_Organizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blog-posts-organizer-deactivator.php
 */
function bpo_org_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blog-posts-organizer-deactivator.php';
	Blog_Posts_Organizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'bpo_org_activate' );
register_deactivation_hook( __FILE__, 'bpo_org_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blog-posts-organizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function bpo_org_run() {

	$plugin = new Blog_Posts_Organizer();
	$plugin->run();

}
bpo_org_run();
