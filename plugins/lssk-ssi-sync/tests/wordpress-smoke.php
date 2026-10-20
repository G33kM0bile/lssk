<?php
/** Run only against the disposable WordPress installation prepared for this test. */
if ( PHP_SAPI !== 'cli' || empty( $argv[1] ) ) { exit( "Usage: php wordpress-smoke.php /temporary/wordpress\n" ); }
$root = rtrim( $argv[1], '/\\' );
if ( ! file_exists( $root . '/lssk-disposable-test' ) ) { exit( "Disposable test marker is required.\n" ); }
define( 'WP_INSTALLING', in_array( '--install', $argv, true ) );
define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );
register_shutdown_function( static function () { $error = error_get_last(); if ( $error && in_array( $error['type'], array( E_ERROR, E_PARSE ), true ) ) { fwrite( STDERR, print_r( $error, true ) ); } } );
$_SERVER['HTTP_HOST'] = 'lssk-smoke.invalid';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
require $root . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
add_filter( 'pre_wp_mail', '__return_true' );
if ( ! is_blog_installed() ) {
    wp_install( 'Disposable LSSK test', 'smoke-admin', 'test@example.invalid', false, '', bin2hex( random_bytes( 24 ) ) );
}
if ( ! is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) {
    $result = activate_plugin( 'the-events-calendar/the-events-calendar.php' );
    if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
    exit( "Test WordPress installed and TEC activated. Run again to test normal plugin boot.\n" );
}
require_once __DIR__ . '/../lssk-ssi-sync.php';
wp_set_current_user( 1 );
update_option( 'timezone_string', 'Europe/Oslo' );
$source = array(
    'id' => '30091', 'get_content_type_key' => 22, 'name' => 'LSSK smoke match',
    'starts' => '2026-10-16T16:00:00Z', 'ends' => '2026-10-16T18:00:00Z',
    'venue' => 'Slydalen', 'status' => 'ac', 'get_status_display' => 'Active',
    'visibility' => 'pub', 'get_visibility_display' => 'Public, visible to anyone',
    'get_full_absolute_url' => 'https://shootnscoreit.com/event/22/30091/',
);
add_filter( 'pre_http_request', static function ( $pre, $args, $url ) use ( &$source ) {
    if ( $url !== \LSSK\SSI\SSI_API::ENDPOINT ) { return new WP_Error( 'blocked', 'No external requests in smoke test' ); }
    $query = json_decode( $args['body'], true );
    $year = (int) gmdate( 'Y' );
    return array( 'headers' => array(), 'response' => array( 'code' => 200, 'message' => 'OK' ), 'body' => wp_json_encode( array( 'data' => array( 'organization' => array( 'id' => '60', 'events' => $query['variables']['year'] === $year ? array( $source ) : array() ) ) ) ) );
}, 10, 3 );
use LSSK\SSI\Plugin;
global $wpdb;
// Reset only fixture-owned posts inside this explicitly marked disposable DB.
$old_ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", Plugin::SOURCE, '60:22:30091' ) );
foreach ( $old_ids as $old_id ) { wp_delete_post( (int) $old_id, true ); }
$settings = Plugin::settings();
$settings['accepted'] = true; // No real SSI traffic is sent in this test.
update_option( Plugin::SETTINGS, $settings, false );
update_option( Plugin::SECRETS, array( 'key' => 'fixture-key' ), false );
function verify( $condition, $label ) { if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $label ); } echo "PASS: {$label}\n"; }
$r = Plugin::run( true );
verify( 1 === $r['total'], 'Actual WordPress: preview' );
$r = Plugin::run( false );
verify( 1 === $r['created'], 'Actual TEC: create imported draft' );
$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", Plugin::SOURCE, '60:22:30091' ) );
verify( count( $ids ) === 1, 'Actual database: source ownership metadata persisted on creation' );
$id = $ids[0];
verify( '2026-10-16 18:00:00' === get_post_meta( $id, '_EventStartDate', true ), 'Actual TEC: localized start date' );
$r = Plugin::run( false );
verify( 1 === $r['unchanged'], 'Actual TEC: second sync creates no duplicate' );
$source['name'] = 'Renamed LSSK — O\'Brien \\ test';
$source['starts'] = '2026-12-16T16:00:00Z';
$source['ends'] = '2026-12-16T18:00:00Z';
$r = Plugin::run( false );
verify( 1 === $r['updated'] && $source['name'] === get_post( $id )->post_title, 'Actual TEC: scoped ORM update preserves punctuation' );
verify( '2026-12-16 17:00:00' === get_post_meta( $id, '_EventStartDate', true ), 'Actual TEC: rescheduled date uses Oslo winter offset' );
$settings['publish'] = true;
update_option( Plugin::SETTINGS, $settings, false );
Plugin::run( false );
verify( 'publish' === get_post_status( $id ), 'Actual TEC: publish existing imported draft' );
$source['get_visibility_display'] = 'Private';
Plugin::run( false );
verify( 'draft' === get_post_status( $id ), 'Actual WordPress: private source unpublishes original entry' );
ob_start();
Plugin::page();
$html = ob_get_clean();
verify( str_contains( $html, 'Preview SSI events' ) && ! str_contains( $html, 'fixture-key' ), 'Actual WordPress: admin page renders without exposing stored API key' );
$settings['enabled'] = true;
update_option( Plugin::SETTINGS, $settings, false );
Plugin::schedule();
verify( false !== wp_next_scheduled( Plugin::HOOK ), 'Actual WordPress: hourly job scheduled' );
Plugin::deactivate();
verify( false === wp_next_scheduled( Plugin::HOOK ), 'Actual WordPress: deactivation unschedules job' );
global $wp_version;
echo 'Smoke test passed on WordPress ' . $wp_version . ', TEC ' . Tribe__Events__Main::VERSION . ', PHP ' . PHP_VERSION . ".\n";
