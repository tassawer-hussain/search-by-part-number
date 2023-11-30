<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Search_By_Part_Number
 * @subpackage Search_By_Part_Number/admin/partials
 */

    global $wpdb; ?>
    <!-- Top-level menu -->
    <div id="pt-general" class="wrap">
    
    <?php if( isset($_GET['status']) ): ?>
        <div class="notice notice-success is-dismissible">
            
            <?php if( $_GET['status'] == 1 ): ?>
                <p><?php _e( 'Record is Added.', 'sample-text-domain' ); ?></p>
            <?php endif; ?>
            
            <?php if( $_GET['status'] == 2 ): ?>
                <p><?php _e( 'Record is updated.', 'sample-text-domain' ); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

        <!-- Display bug list if no parameter sent in URL -->
        <?php 
        if ( ! isset($_GET['id']) ) {
            $record_id = 'new';
        } else if ( isset($_GET['id']) && is_numeric($_GET['id']) ) {
            $record_id = $_GET['id'];
        }
        $record_data = array();
        $mode = 'new';

        // Query database if numeric id is present
        if ( is_numeric($record_id) ) {
            $pt_query = 'select * from ' . $wpdb->get_blog_prefix();
            $pt_query .= 'search_by_part_number where ID = ' . $record_id;
            $record_data = $wpdb->get_row( $wpdb->prepare( $pt_query, '' ), ARRAY_A );
            // Set variable to indicate page mode
            if ( $record_data ) 
                $mode = 'edit';
        } else {
            $record_data['search_by'] = '';
            $record_data['std_prod_equiv'] = '';
            $record_data['max_prod_equiv'] = '';
        }

        // Display title based on current mode
        if ( $mode == 'new' ) {
            echo '<h3>Add New Search By Record</h3>';
        } elseif ( $mode == 'edit' ) {
            echo '<h3>Edit Search By ' . $record_data['search_by'] . ' </h3> ';
        } ?>
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
        <input type="hidden" name="action" value="save_edit_knife" />
        <input type="hidden" name="record_id" value="<?php echo esc_attr( $record_id ); ?>" />

        <!-- Adding security through hidden referrer field -->
        <?php wp_nonce_field( 'knife_add_edit' ); ?>

        <!-- Display knife editing form -->
        <table>
            <tr>
                <td style="width: 200px">Search By</td>
                <td><input type="text" name="search_by" size="60" 
                            value="<?php echo esc_attr($record_data['search_by']); ?>"/>
                </td>
            </tr>
            <tr>
                <td style="width: 200px">Std Prod Equiv</td>
                <td><input type="text" name="std_prod_equiv" size="60" 
                            value="<?php echo esc_attr($record_data['std_prod_equiv']); ?>"/>
                </td>
            </tr>
            <tr>
                <td style="width: 200px">Max Prod Equiv</td>
                <td><input type="text" name="max_prod_equiv" size="60" 
                            value="<?php echo esc_attr($record_data['max_prod_equiv']); ?>"/>
                </td>
            </tr>
        </table>
        <input type="submit" value="Submit" class="button-primary"/>
        </form>
    </div>
<?php