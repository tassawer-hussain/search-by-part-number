<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/admin
 * @author     2ByteCode <support@2bytecode.com>
 */
class Search_By_Part_Number_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Admin menu in admin
		add_action( 'admin_menu', array($this, 'add_menu_in_admin_2bc') );

		add_action( 'admin_init', array($this, 'search_by_part_number_admin_init') );

		add_action( 'admin_post_save_edit_knife', array($this, 'process_search_by') );

	}

	public function process_search_by() {
    
		// Check if user has proper security level
		if ( !current_user_can( 'manage_options' ) )
			wp_die( 'Not allowed' );

		// Check if nonce field is present for security
		check_admin_referer( 'knife_add_edit' );

		global $wpdb;
		// Place all user submitted values in an array (or empty
		// strings if no value was sent)
		$record_data = array();
		$record_data['search_by'] = ( isset($_POST['search_by']) ? $_POST['search_by'] : '' );
		$record_data['std_prod_equiv'] = ( isset($_POST['std_prod_equiv']) ? $_POST['std_prod_equiv'] : '' );
		$record_data['max_prod_equiv'] = ( isset($_POST['max_prod_equiv']) ? $_POST['max_prod_equiv'] : '' );

		// Call the wpdb insert or update method based on value
		// of hidden bug_id field
		if ( isset($_POST['record_id']) && $_POST['record_id']=='new') {
			$wpdb->insert( $wpdb->get_blog_prefix() . 'search_by_part_number', $record_data );
			$status = 1;
		} elseif ( isset($_POST['record_id']) && is_numeric($_POST['record_id']) ) {
			$wpdb->update( $wpdb->get_blog_prefix() . 'search_by_part_number', $record_data, array('ID' => $_POST['record_id']) );
			$status = 2;
		}

		// Redirect the page to the user submission form
		wp_redirect( 
			add_query_arg( 
				array(
					'page' => 'add-new-search-by',
					'status' => $status
				),
				admin_url( 'admin.php' ) 
			) 
		);
		exit;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Search_By_Part_Number_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Search_By_Part_Number_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/search-by-part-number-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Search_By_Part_Number_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Search_By_Part_Number_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/search-by-part-number-admin.js', array( 'jquery' ), $this->version, true );

	}

	public function search_by_part_number_admin_init() {
		add_action( 'admin_post_import_search_by_records', array($this, 'import_search_by_part_number_records') );
	}
	
	public function import_search_by_part_number_records() {
		// Check that user has proper security level
		if ( !current_user_can( 'manage_options' ) )
			wp_die( 'Not allowed' );

		// Check if nonce field is present
		check_admin_referer( 'search_by_records_import' );

		// Check if file has been uploaded
		if( array_key_exists( 'search_by', $_FILES ) ) {
			// If file exists, open it in read mode
			$handle = fopen( $_FILES['search_by']['tmp_name'], 'r' );

			// If file is successfully open, extract a row of data
			// based on comma separator, and store in $data array
			if ( $handle ) {
				$row = 0;
				while (( $data = fgetcsv($handle, 5000, ',') ) !== FALSE ) {
					$row += 1;

					// If row count is ok and row is not header row
					// Create array and insert in database
					if ( $row != 1 ) {
						$new_record = array(
							'search_by' => $data[0],
							'std_prod_equiv' => $data[1],
							'max_prod_equiv' => $data[2]
						);

						global $wpdb;
						$wpdb->insert( $wpdb->get_blog_prefix() . 'search_by_part_number', $new_record );
					}
				}
			}
		}

		// Redirect the page to the user submission form
		wp_redirect( 
			add_query_arg( 
				array(
					'page' => 'search-by-part-number-settings',
					'status' => 1
				),
				admin_url( 'admin.php' ) 
			) 
		);
		exit;
	}

	public function add_menu_in_admin_2bc() {
		$searchby_page = add_menu_page(
			'Search By Part Number',
			'Search By',
			'manage_options',
			'search-by-part-number',
			array( $this, 'list_table_all_search_by_records'),
			'dashicons-search',
			22
		);
		add_submenu_page(
			'search-by-part-number',
			'Add New Search Entry',
			'Add New',
			'manage_options',
			'add-new-search-by',
			array( $this, 'add_new_search_by_record'),
		);
		add_submenu_page(
			'search-by-part-number',
			'Settings',
			'Settings',
			'manage_options',
			'search-by-part-number-settings',
			array( $this, 'search_by_record_settings'),
		);

		// Initialize the list table instance when the page is loaded.
        add_action( "load-$searchby_page", [ $this, 'init_list_table' ] );
	}

	public function init_list_table() {
		require_once 'class/class-search-by-list-table.php';
		$this->$myListTable = new Search_By_List_Table();
	}

	public function list_table_all_search_by_records() {
		$this->$myListTable->display_asset_list(); 
	}

	public function add_new_search_by_record() {
		require_once 'partials/search-by-part-number-add-edit.php';
	}

	public function search_by_record_settings() {
		require_once 'partials/search-by-part-number-import.php';
	}

}
