<?php
namespace LSSK\Benefits;

defined( 'ABSPATH' ) || exit;

const ACCESS_OPTION = 'lssk_benefits_access';
const ACCESS_COOKIE = 'lssk_benefits_session';

function member_url(): string {
	return home_url( '/rabattkoder/' );
}

function embed_url(): string {
	return add_query_arg( 'lssk_view', 'embed', member_url() );
}

function themed_shortcode(): string {
	$assets = plugin_dir_url( LSSK_BENEFITS_FILE ) . 'assets/';
	wp_enqueue_style( 'lssk-benefits-member', $assets . 'member.css', array(), LSSK_BENEFITS_VERSION );
	wp_enqueue_script( 'lssk-benefits-member', $assets . 'member.js', array(), LSSK_BENEFITS_VERSION, true );

	return '<section class="lssk-benefits" data-lssk-embed="' . esc_url( embed_url() ) . '"><p><a href="' .
		esc_url( member_url() ) . '">Åpne rabattkoder</a></p></section>';
}
add_shortcode( 'lssk_rabattkoder', __NAMESPACE__ . '\themed_shortcode' );

function is_member_route(): bool {
	$path = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
	return is_string( $path ) && untrailingslashit( $path ) === untrailingslashit( wp_parse_url( member_url(), PHP_URL_PATH ) );
}

function access_settings(): array {
	return (array) get_option( ACCESS_OPTION, array() ) + array( 'password' => '', 'generation' => '' );
}

function access_signature( string $purpose, string $generation, string $value = '' ): string {
	return hash_hmac( 'sha256', "$purpose|$generation|$value|" . member_url(), wp_salt( 'auth' ) );
}

function qr_token( string $generation ): string {
	return access_signature( 'qr', $generation );
}

function has_session( array $settings ): bool {
	$cookie = $_COOKIE[ ACCESS_COOKIE ] ?? '';
	if ( $settings['generation'] === '' || ! is_string( $cookie ) || ! preg_match( '/\A([0-9]{1,12})\.([a-f0-9]{64})\z/', $cookie, $matches ) ) {
		return false;
	}

	$expiry = $matches[1];
	return (int) $expiry > time() && hash_equals( access_signature( 'session', $settings['generation'], $expiry ), $matches[2] );
}

function grant_session( string $generation ): void {
	$expiry = time() + 30 * DAY_IN_SECONDS;
	set_session_cookie( $expiry . '.' . access_signature( 'session', $generation, (string) $expiry ), $expiry );
}

function set_session_cookie( string $value, int $expiry ): void {
	setcookie(
		ACCESS_COOKIE,
		$value,
		array(
			'expires'  => $expiry,
			'path'     => wp_parse_url( member_url(), PHP_URL_PATH ),
			'secure'   => wp_parse_url( member_url(), PHP_URL_SCHEME ) === 'https',
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);
}

function normalize_password( string $value ): string {
	return trim( wp_unslash( $value ) );
}

function attempt_access( array $settings ): string {
	if ( $settings['generation'] === '' || $settings['password'] === '' ) {
		return 'Tilgangen er ikke satt opp ennå.';
	}

	$token = $_POST['lssk_qr'] ?? null;
	if ( $token !== null ) {
		if ( is_string( $token ) && hash_equals( qr_token( $settings['generation'] ), $token ) ) {
			return '';
		}
		return 'QR-koden er ugyldig. Bruk gjeldende QR-kode eller passord.';
	}

	$password = $_POST['lssk_password'] ?? null;
	if ( ! is_string( $password ) || strlen( $password ) > 512 ) {
		return 'Skriv inn klubbens passord.';
	}

	$address = $_SERVER['REMOTE_ADDR'] ?? '';
	$limit_key = 'lssk_benefits_' . hash( 'sha256', $settings['generation'] . '|' . $address );
	$attempts = (int) get_transient( $limit_key );
	if ( $attempts >= 12 ) {
		return 'For mange forsøk. Vent noen minutter og prøv igjen.';
	}
	if ( wp_check_password( normalize_password( $password ), $settings['password'] ) ) {
		delete_transient( $limit_key );
		return '';
	}
	set_transient( $limit_key, $attempts + 1, 5 * MINUTE_IN_SECONDS );
	return 'Passordet er feil.';
}

function handle_route(): void {
	if ( ! is_member_route() ) {
		return;
	}
	$embedded = ( $_GET['lssk_view'] ?? '' ) === 'embed';
	$response_url = $embedded ? embed_url() : member_url();

	// Serve this route without theme or analytics scripts and prevent shared caches storing offers.
	nocache_headers();
	header( 'Cache-Control: private, no-store, max-age=0', true );
	header( 'X-Robots-Tag: noindex, nofollow, noarchive', true );
	header( 'Referrer-Policy: no-referrer', true );
	header( "Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; form-action 'self'; base-uri 'none'; frame-ancestors 'none'", true );

	$https = wp_parse_url( member_url(), PHP_URL_SCHEME ) === 'https';
	if ( ! $https && wp_get_environment_type() !== 'local' ) {
		wp_die( 'Rabattkoder krever HTTPS.', 'Rabattkoder', array( 'response' => 503 ) );
	}
	if ( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) !== wp_parse_url( member_url(), PHP_URL_PATH ) ) {
		wp_safe_redirect( $response_url, 307 );
		exit;
	}

	$settings = access_settings();
	$unlocked = has_session( $settings );
	$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
	$error = '';
	if ( $method === 'POST' ) {
		if ( isset( $_POST['lssk_logout'] ) ) {
			if ( $unlocked && is_string( $_POST['lssk_logout'] ) && hash_equals(
				access_signature( 'logout', $settings['generation'], $_COOKIE[ ACCESS_COOKIE ] ),
				$_POST['lssk_logout']
			) ) {
				set_session_cookie( '', time() - DAY_IN_SECONDS );
			}
			wp_safe_redirect( $response_url, 303 );
			exit;
		}
		$error = attempt_access( $settings );
		if ( $error === '' ) {
			grant_session( $settings['generation'] );
			wp_safe_redirect( $response_url, 303 );
			exit;
		}
	} elseif ( ! in_array( $method, array( 'GET', 'HEAD' ), true ) ) {
		header( 'Allow: GET, HEAD, POST' );
		status_header( 405 );
		exit;
	}

	$offers = $unlocked ? active_offers() : array();
	status_header( 200 );
	header( 'Content-Type: text/html; charset=UTF-8', true );
	if ( $method !== 'HEAD' ) {
		if ( $embedded ) {
			require dirname( __DIR__ ) . '/templates/offers-panel.php';
		} else {
			require dirname( __DIR__ ) . '/templates/member.php';
		}
	}
	exit;
}
add_action( 'template_redirect', __NAMESPACE__ . '\handle_route', 0 );
