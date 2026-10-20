<?php
/**
 * Plugin Name: LSSK SSI Sync
 * Description: Import public Shoot'n Score It organization events into The Events Calendar.
 * Version: 0.1.0
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author: LSSK
 * License: GPL-2.0-or-later
 * Text Domain: lssk-ssi-sync
 */

defined( 'ABSPATH' ) || exit;
require_once __DIR__ . '/includes/class-ssi-api.php';
require_once __DIR__ . '/includes/class-event-mapper.php';
require_once __DIR__ . '/includes/class-plugin.php';

LSSK\SSI\Plugin::boot();
register_activation_hook( __FILE__, array( LSSK\SSI\Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( LSSK\SSI\Plugin::class, 'deactivate' ) );
