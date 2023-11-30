<?php

/**
 * Fired during plugin activation
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/includes
 * @author     2ByteCode <support@2bytecode.com>
 */
class Search_By_Part_Number_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        /**
        * GDPR Request Leader Table
         */
        $search_by_part_number = $wpdb->prefix.'search_by_part_number';
        
        $sql = "CREATE TABLE IF NOT EXISTS $search_by_part_number (
            ID int(11) NOT NULL AUTO_INCREMENT,
            `search_by` varchar(200),
            `std_prod_equiv` varchar(200),
            `max_prod_equiv` varchar(200),
            PRIMARY KEY (ID)
        ) $charset_collate;";
        dbDelta( $sql );

	}

}
