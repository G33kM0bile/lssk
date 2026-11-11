<?php
/**
 * Plugin Name: Rabattkoder
 * Description: Administrer klubbens rabattkoder og del tilgang med passord eller QR-kode.
 * Version: 0.1.9
 * Requires at least: 6.5
 * Requires PHP: 8.0
 * License: MIT
 */

defined( 'ABSPATH' ) || exit;

define( 'LSSK_BENEFITS_FILE', __FILE__ );
define( 'LSSK_BENEFITS_VERSION', '0.1.9' );

require __DIR__ . '/includes/offers.php';
require __DIR__ . '/includes/access.php';

if ( is_admin() ) {
	require __DIR__ . '/includes/password-vault.php';
	require __DIR__ . '/includes/admin.php';
}
