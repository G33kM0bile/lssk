<?php
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Event posts and their source identifiers are intentionally retained, so a
// reinstall can reconnect to existing entries without creating duplicates.
foreach ( array( 'lssk_ssi_settings', 'lssk_ssi_credentials', 'lssk_ssi_report', 'lssk_ssi_preview', 'lssk_ssi_lock' ) as $option ) {
    delete_option( $option );
}
wp_clear_scheduled_hook( 'lssk_ssi_hourly' );
