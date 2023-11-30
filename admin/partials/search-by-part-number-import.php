<div class="wrap">
    <h1 class="wp-heading-inline">Search By</h1>

    <hr class="wp-header-end">

    <?php if( isset($_GET['status']) && $_GET['status'] == 1 ): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Import is completed.', 'sample-text-domain' ); ?></p>
        </div>
    <?php endif; ?>

    <!-- Form to upload new record in csv format -->
    <form method="post"
        action="<?php echo admin_url( 'admin-post.php' ); ?>" 
        enctype="multipart/form-data">
        
        <input type="hidden" name="action" value="import_search_by_records" />

        <!-- Adding security through hidden referrer field -->
        <?php wp_nonce_field( 'search_by_records_import' ); ?>

        <h3>Import Records</h3>
        <p>Import <strong>Search By</strong> records from CSV File<p>
        <p>(For reference see this <a href="<?php echo plugins_url( 'importtemplate.csv', dirname(__FILE__)); ?>">Template</a> file)</p>
        <input name="search_by" type="file" /> <br /><br />
        <input type="submit" value="Import" class="button-primary" id="import-csv-file"/>

    </form>
</div>