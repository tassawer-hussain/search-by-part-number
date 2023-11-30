<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/includes
 * @author     2ByteCode <support@2bytecode.com>
 */
class Search_By_Part_Number_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'search-by-part-number',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
