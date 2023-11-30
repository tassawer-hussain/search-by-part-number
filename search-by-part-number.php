<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://2bytecode.com/
 * @since             1.0.0
 * @package           Search_By_Part_Number
 *
 * @wordpress-plugin
 * Plugin Name:       Search By Part Number
 * Plugin URI:        https://2bytecode.com/
 * Description:       Custom solution to lookup product using part number.
 * Version:           1.0.0
 * Author:            2ByteCode
 * Author URI:        https://2bytecode.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       search-by-part-number
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
define( 'SEARCH_BY_PART_NUMBER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-search-by-part-number-activator.php
 */
function activate_search_by_part_number() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-search-by-part-number-activator.php';
	Search_By_Part_Number_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-search-by-part-number-deactivator.php
 */
function deactivate_search_by_part_number() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-search-by-part-number-deactivator.php';
	Search_By_Part_Number_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_search_by_part_number' );
register_deactivation_hook( __FILE__, 'deactivate_search_by_part_number' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-search-by-part-number.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_search_by_part_number() {

	$plugin = new Search_By_Part_Number();
	$plugin->run();

}
run_search_by_part_number();
