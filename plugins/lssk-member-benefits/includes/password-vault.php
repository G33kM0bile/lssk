<?php
namespace LSSK\Benefits;

defined( 'ABSPATH' ) || exit;

function password_encryption_key( string $constant ): ?string {
	$secret = defined( $constant ) ? constant( $constant ) : null;
	if ( ! is_string( $secret ) || strlen( $secret ) < 32 ) {
		return null;
	}
	return hash_hmac( 'sha256', 'lssk-benefits-password-v1|' . member_url(), $secret, true );
}

function password_encryption_error(): string {
	if ( ! function_exists( 'openssl_get_cipher_methods' ) || ! in_array( 'aes-256-gcm', openssl_get_cipher_methods(), true ) ) {
		return 'PHP har ikke OpenSSL-støtte for AES-256-GCM.';
	}
	if ( password_encryption_key( 'LSSK_BENEFITS_ENCRYPTION_KEY' ) === null && password_encryption_key( 'SECURE_AUTH_KEY' ) === null ) {
		return 'Visning av passord krever en krypteringsnøkkel i WordPress-konfigurasjonen.';
	}
	return '';
}

function encrypt_current_password( string $password, string $generation ): string {
	if ( password_encryption_error() !== '' ) {
		return '';
	}

	$key = password_encryption_key( 'LSSK_BENEFITS_ENCRYPTION_KEY' );
	$prefix = $key === null ? '' : 'v2:';
	$key = $key ?? password_encryption_key( 'SECURE_AUTH_KEY' );
	$iv = random_bytes( 12 );
	$ciphertext = openssl_encrypt( $password, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, $generation, 16 );
	return $ciphertext === false ? '' : $prefix . base64_encode( $iv . $tag . $ciphertext );
}

function decrypt_current_password( string $encrypted, string $generation ): ?string {
	if ( ! function_exists( 'openssl_decrypt' ) ) {
		return null;
	}
	$dedicated = str_starts_with( $encrypted, 'v2:' );
	$key = password_encryption_key( $dedicated ? 'LSSK_BENEFITS_ENCRYPTION_KEY' : 'SECURE_AUTH_KEY' );
	$encrypted = $dedicated ? substr( $encrypted, 3 ) : $encrypted;
	$raw = base64_decode( $encrypted, true );
	if ( $key === null || $raw === false || strlen( $raw ) <= 28 ) {
		return null;
	}

	$password = openssl_decrypt( substr( $raw, 28 ), 'aes-256-gcm', $key, OPENSSL_RAW_DATA, substr( $raw, 0, 12 ), substr( $raw, 12, 16 ), $generation );
	return $password === false ? null : $password;
}
