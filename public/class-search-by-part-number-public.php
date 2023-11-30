<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/public
 * @author     2ByteCode <support@2bytecode.com>
 */
class Search_By_Part_Number_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode('search_by_lookup', array( $this, 'searchby_frontend_shortcode' ));

		// Render tyres results
		add_action( 'wp_ajax_nopriv_render_seach_by_results', array( $this, 'render_seach_by_results' ) );
		add_action( 'wp_ajax_render_seach_by_results', array( $this, 'render_seach_by_results' ) );

	}

	public function render_seach_by_results() {

        $part_number = '';
        $message = array();

        if ( empty( $_REQUEST['part_number'] ) ) {
            $message = array(
				'status' => 'error',
				'message' => 'Please add value for part number',
			);
			echo json_encode($message);
			wp_die();
        } else {
			global $wpdb;
            $part_number = $_REQUEST['part_number'];

			$pt_query = 'select std_prod_equiv, max_prod_equiv from ' . $wpdb->get_blog_prefix();
			$pt_query .= 'search_by_part_number where search_by = "' . $part_number.'"';
			$record_data = $wpdb->get_row( $wpdb->prepare( $pt_query, '' ), ARRAY_N );
			
			$sku = '';
			$count = count($record_data);
			for ( $i=0; $i<$count; $i++ ) {
				$sku .= "'".$record_data[$i]."'";
				if($i+1 != $count):
					$sku .= ",";
				endif;
			}

			$pt_query = "select post_id FROM " . $wpdb->get_blog_prefix();
			$pt_query .= "postmeta where meta_key = '_sku' AND meta_value IN ($sku)";
			$product_ids = $wpdb->get_results( $wpdb->prepare( $pt_query, '' ));

			
			if( !empty($product_ids) ) {
				$pid_array = '<table class="sb-product-list">';
				foreach( $product_ids as $pid):
					$product = wc_get_product( $pid->post_id );
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
					
					$pid_array .= '<tr>';

					$pid_array .= '<td class="sb-prodcut-img">';
					$pid_array .= '<a href="'. get_permalink( $product->get_id() ) .'"><img src="'. $image[0] .'" alt="'.$product->get_name().'" /></a>';
					$pid_array .= '</td>';
					
					$pid_array .= '<td class="sb-product-title">';
					$pid_array .= '<h3><a href="'. get_permalink( $product->get_id() ) .'">'.$product->get_name().'</a></h3>';
					$pid_array .= '</td>';
					
					$pid_array .= '</tr>';
					
				endforeach;
				$pid_array .= '</table>';

			} else {
				$pid_array .= '<p>No Product found.</p>';
			}

        }

		$return_arr = array(
			'status' => 'found',
			'html' => $pid_array,
		);
		
		echo json_encode($return_arr);
		wp_die();

    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/search-by-part-number-public.css', array(), $this->version, 'all' );
		wp_register_style( 'sb-bootstrap-new', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/search-by-part-number-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'frontend_ajax_object',
		array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			)
		);

	}

	/**
	 * Added a shortcode to display the form and results on entring values.
	 *
	 * @since    1.0.0
	 */
	public function searchby_frontend_shortcode() {

		wp_enqueue_style('sb-bootstrap-new');
        $output = '
        
        <div class="container">
            <div class="row form-row">
                <div class="form-group col-md-6">
					<div class="search-heading">
						<h2>Search for a part number:</h2>
					</div>

					<div class="search-notices">
						<p class="part-number-error"></p>
					</div>
					<div class="search-container">
						<input type="text" class="part-number" name="part-number" id="part-number" placeholder="part number" value="" />
						<button type="button" value="Search" id="search-button" class="search-button">Search</button>
					</div>

					<!-- <div class="search-desc">
						<p>Enter a part number and click the <strong>Search Button.</strong><br>
						Click on any results shown to see greater detail.<br>
						<strong>If no results are shown we may not offer that item at this time.</strong></p>
					</div> -->
                    
                </div>
                <div class="form-group col-md-6">
					<div class="search-heading">
						<h2>Resulting Matches:</h2>
					</div>

					<div class="search-results">
					</div>
                </div>
            </div>
        </div>
        ';

		return $output;
	}

}
