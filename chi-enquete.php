<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Chi_Enquete
 *
 * @wordpress-plugin
 * Plugin Name:       Chi's Vitality Survey Chart
 * Plugin URI:        http://example.com/chi-enquete-uri/
 * Description:       Offers a survey and generates a chart
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       chi-enquete
 * Domain Path:       /languages
 * Requires at least: 4.6
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-chi-enquete-activator.php
 */
function activate_chi_enquete() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chi-enquete-activator.php';
	Chi_Enquete_Activator::activate();
}

function chi_enquete_update_db_check() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-chi-enquete-activator.php';
    $version = get_site_option( '_chi_enquete_db_version' );
    if ( $version != Chi_Enquete_Activator::DB_VERSION ) {
        //do nothing right now, keep db unchanged unless manual deletion first
    }
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-chi-enquete-deactivator.php
 */
function deactivate_chi_enquete() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chi-enquete-deactivator.php';
	Chi_Enquete_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_chi_enquete' );
register_deactivation_hook( __FILE__, 'deactivate_chi_enquete' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-chi-enquete.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_chi_enquete() {

	$plugin = new Chi_Enquete();
	$plugin->run();

}
run_chi_enquete();
