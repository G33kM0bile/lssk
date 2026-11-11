<?php
namespace LSSK\Benefits;

defined( 'ABSPATH' ) || exit;

function password_encryption_error(): string {
	// Require a server configuration key so a database export alone cannot reveal the password.
	if ( ! defined( 'SECURE_AUTH_KEY' ) || ! is_string( SECURE_AUTH_KEY ) ||
		strlen( SECURE_AUTH_KEY ) < 32 ) {
		return 'SECURE_AUTH_KEY mangler eller har færre enn 32 tegn i WordPress-konfigurasjonen.';
	}
	if ( ! function_exists( 'openssl_get_cipher_methods' ) || ! in_array( 'aes-256-gcm', openssl_get_cipher_methods(), true ) ) {
		return 'PHP har ikke OpenSSL-støtte for AES-256-GCM.';
	}
	return '';
}

function password_encryption_key(): ?string {
	if ( password_encryption_error() !== '' ) {
		return null;
	}

	return hash_hmac( 'sha256', 'lssk-benefits-password-v1|' . member_url(), SECURE_AUTH_KEY, true );
}

function encrypt_current_password( string $password, string $generation ): string {
	$key = password_encryption_key();
	if ( $key === null ) {
		return '';
	}

	$iv = random_bytes( 12 );
	$ciphertext = openssl_encrypt( $password, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, $generation, 16 );
	return $ciphertext === false ? '' : base64_encode( $iv . $tag . $ciphertext );
}

function decrypt_current_password( string $encrypted, string $generation ): ?string {
	$key = password_encryption_key();
	$raw = base64_decode( $encrypted, true );
	if ( $key === null || $raw === false || strlen( $raw ) <= 28 ) {
		return null;
	}

	$password = openssl_decrypt( substr( $raw, 28 ), 'aes-256-gcm', $key, OPENSSL_RAW_DATA, substr( $raw, 0, 12 ), substr( $raw, 12, 16 ), $generation );
	return $password === false ? null : $password;
}
