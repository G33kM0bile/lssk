<?php
namespace LSSK\Benefits;

defined( 'ABSPATH' ) || exit;

function register_offer_box(): void {
	add_meta_box( 'lssk-offer', 'Tilbudsdetaljer', __NAMESPACE__ . '\render_offer_box', OFFER, 'normal', 'high' );
}
add_action( 'add_meta_boxes_' . OFFER, __NAMESPACE__ . '\register_offer_box' );

function render_offer_box( \WP_Post $post ): void {
	$offer = offer_details( $post->ID );
	wp_nonce_field( 'lssk_save_offer', 'lssk_offer_nonce' );
	?>
	<p>Bruk firmanavnet som tittel. Publiser tilbudet for å vise det til medlemmer. Sett det tilbake til kladd for å skjule det.</p>
	<p><label for="lssk-code">Rabattkode</label><br>
		<input class="regular-text" id="lssk-code" name="lssk_code" type="text" value="<?php echo esc_attr( $offer['code'] ); ?>" maxlength="128" autocomplete="off"></p>
	<p><label for="lssk-url">Lenke til nettbutikk eller tilbud (valgfritt)</label><br>
		<input class="regular-text" id="lssk-url" name="lssk_url" type="url" value="<?php echo esc_attr( $offer['url'] ); ?>"></p>
	<p><label for="lssk-expires">Gyldig til og med (valgfritt)</label><br>
		<input id="lssk-expires" name="lssk_expires" type="date" value="<?php echo esc_attr( $offer['expires'] ); ?>"></p>
	<p><label for="lssk-terms">Vilkår eller instruksjoner (valgfritt)</label><br>
		<textarea class="large-text" id="lssk-terms" name="lssk_terms" rows="3"><?php echo esc_textarea( $offer['terms'] ); ?></textarea></p>
	<div id="lssk-logo-controls">
		<p>Firmalogo (valgfritt)</p>
		<p id="lssk-logo-preview"><?php echo wp_get_attachment_image( (int) $offer['logo'], array( 120, 60 ) ); ?></p>
		<input type="hidden" id="lssk-logo" name="lssk_logo" value="<?php echo esc_attr( (string) $offer['logo'] ); ?>">
		<button class="button" type="button" id="lssk-choose-logo">Velg logo</button>
		<button class="button" type="button" id="lssk-remove-logo">Fjern logo</button>
	</div>
	<?php
}

function posted_text( string $name ): string {
	return isset( $_POST[ $name ] ) && is_string( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
}

function save_offer( int $id ): void {
	if ( ! current_user_can( 'manage_options' ) || wp_is_post_autosave( $id ) || wp_is_post_revision( $id ) ||
		! wp_verify_nonce( posted_text( 'lssk_offer_nonce' ), 'lssk_save_offer' ) ) {
		return;
	}

	$expiry = posted_text( 'lssk_expires' );
	if ( $expiry !== '' && ( ! preg_match( '/\A([0-9]{4})-([0-9]{2})-([0-9]{2})\z/', $expiry, $parts ) ||
		! checkdate( (int) $parts[2], (int) $parts[3], (int) $parts[1] ) ) ) {
		$expiry = offer_details( $id )['expires'];
	}
	$url = esc_url_raw( posted_text( 'lssk_url' ), array( 'https', 'http' ) );
	if ( ! wp_parse_url( $url, PHP_URL_HOST ) || ! in_array( wp_parse_url( $url, PHP_URL_SCHEME ), array( 'https', 'http' ), true ) ) {
		$url = '';
	}
	$code = trim( wp_check_invalid_utf8( posted_text( 'lssk_code' ) ) );
	$code = preg_replace( '/[\x00-\x1F\x7F]/', '', $code );
	$logo = absint( posted_text( 'lssk_logo' ) );
	update_post_meta(
		$id,
		OFFER_DETAILS,
		wp_slash(
			array(
				'code'    => $code,
				'url'     => $url,
				'expires' => $expiry,
				'terms'   => sanitize_textarea_field( posted_text( 'lssk_terms' ) ),
				'logo'    => wp_attachment_is_image( $logo ) ? $logo : 0,
			)
		)
	);
}
add_action( 'save_post_' . OFFER, __NAMESPACE__ . '\save_offer' );

function register_access_menu(): void {
	$hook = add_submenu_page(
		'edit.php?post_type=' . OFFER,
		'Tilgang til rabattkoder',
		'Tilgang og QR-kode',
		'manage_options',
		'lssk-benefits-access',
		__NAMESPACE__ . '\render_access_page'
	);
	if ( $hook ) {
		add_action( 'load-' . $hook, __NAMESPACE__ . '\verify_password_reveal' );
		add_action(
			'admin_enqueue_scripts',
			static function ( string $current_hook ) use ( $hook ): void {
				if ( $current_hook === $hook ) {
					$base = plugin_dir_url( LSSK_BENEFITS_FILE ) . 'assets/';
					wp_enqueue_media();
					wp_add_inline_style( 'common', '#lssk-qr-download[hidden], #lssk-qr-branded-download[hidden] { display: none; }' );
					wp_enqueue_script( 'lssk-benefits-qr', $base . 'qrcodegen.js', array(), LSSK_BENEFITS_VERSION, true );
					wp_enqueue_script( 'lssk-benefits-admin', $base . 'admin.js', array( 'lssk-benefits-qr', 'media-editor' ), LSSK_BENEFITS_VERSION, true );
				}
			}
		);
	}
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_access_menu' );

function verify_password_reveal(): void {
	if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) === 'POST' && isset( $_POST['lssk_reveal_password'] ) ) {
		check_admin_referer( 'lssk_reveal_password' );
	}
}

function register_expiry_widget(): void {
	if ( current_user_can( 'manage_options' ) ) {
		wp_add_dashboard_widget( 'lssk_expiring_codes', 'Rabattkoder som snart utløper', __NAMESPACE__ . '\render_expiry_widget' );
	}
}
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\register_expiry_widget' );

function render_expiry_widget(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$deadline = current_datetime()->modify( '+30 days' )->format( 'Y-m-d' );
	$expiring = array_filter( active_offers(), static fn( array $offer ): bool => $offer['expires'] !== '' && $offer['expires'] <= $deadline );
	usort( $expiring, static fn( array $first, array $second ): int => $first['expires'] <=> $second['expires'] );
	if ( ! $expiring ) {
		echo '<p>Ingen aktive rabattkoder utløper de neste 30 dagene.</p>';
		return;
	}
	?>
	<p>Aktive rabattkoder som utløper innen 30 dager.</p>
	<ul>
		<?php foreach ( $expiring as $offer ) : ?>
			<li>
				<a href="<?php echo esc_url( get_edit_post_link( $offer['id'], 'raw' ) ); ?>"><?php echo esc_html( $offer['company'] ); ?></a>
				<code><?php echo esc_html( $offer['code'] ); ?></code>
				&middot; <time datetime="<?php echo esc_attr( $offer['expires'] ); ?>"><?php echo esc_html( mysql2date( 'j. F Y', $offer['expires'] ) ); ?></time>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

function access_admin_url(): string {
	return admin_url( 'edit.php?post_type=' . OFFER . '&page=lssk-benefits-access' );
}

function rotate_access(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Du har ikke tilgang til denne handlingen.', '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'lssk_rotate_access' );

	$password = trim( posted_text( 'lssk_password' ) );
	$old = access_settings();
	$status = 'invalid';
	if ( preg_match( '/\A.{8,128}\z/u', $password ) &&
		( $old['password'] === '' || ! wp_check_password( $password, $old['password'] ) ) ) {
		$generation = bin2hex( random_bytes( 16 ) );
		$history = $old['password_history'] ?? array();
		array_unshift( $history, array( 'time' => time(), 'user_id' => get_current_user_id() ) );
		$saved = update_option(
			ACCESS_OPTION,
			array(
				'password'           => wp_hash_password( $password ),
				'generation'         => $generation,
				'password_encrypted' => encrypt_current_password( $password, $generation ),
				'password_history'   => $history,
			),
			false
		);
		$status = $saved ? 'saved' : 'failed';
	}
	wp_safe_redirect( add_query_arg( 'lssk_result', $status, access_admin_url() ), 303 );
	exit;
}
add_action( 'admin_post_lssk_rotate_access', __NAMESPACE__ . '\rotate_access' );

function render_access_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	nocache_headers();
	$settings = access_settings();
	$configured = $settings['generation'] !== '';
	$result = $_GET['lssk_result'] ?? '';
	$encrypted_password = $settings['password_encrypted'] ?? '';
	$reveal_requested = $configured && ( $_SERVER['REQUEST_METHOD'] ?? '' ) === 'POST' && isset( $_POST['lssk_reveal_password'] );
	$current_password = null;
	if ( $reveal_requested ) {
		$current_password = decrypt_current_password( $encrypted_password, $settings['generation'] );
	}
	?>
	<div class="wrap">
		<h1>Tilgang til rabattkoder</h1>
		<?php if ( $result === 'saved' ) : ?>
			<div class="notice notice-success"><p>Tilgangen er oppdatert. Last ned den nye QR-koden.</p></div>
		<?php elseif ( $result === 'invalid' ) : ?>
			<div class="notice notice-error"><p>Velg et annet passord på 8–128 tegn. Bruk gjerne en passordgenerator.</p></div>
		<?php elseif ( $result === 'failed' ) : ?>
			<div class="notice notice-error"><p>Tilgangen kunne ikke lagres. Prøv på nytt.</p></div>
		<?php endif; ?>
		<p>Medlemsside: <a href="<?php echo esc_url( member_url() ); ?>"><?php echo esc_html( member_url() ); ?></a></p>
		<?php if ( $configured ) : ?>
			<h2>Gjeldende fellespassord</h2>
			<?php if ( $current_password !== null ) : ?>
				<p><label for="lssk-current-password">Fellespassord</label><br>
					<input class="regular-text" id="lssk-current-password" type="text" readonly autocomplete="off" value="<?php echo esc_attr( $current_password ); ?>">
					<a class="button" href="<?php echo esc_url( access_admin_url() ); ?>">Skjul passord</a></p>
			<?php elseif ( $reveal_requested ) : ?>
				<p>Passordet kan ikke vises med gjeldende servernøkkel. Du kan sette et nytt passord under Forny tilgang.</p>
			<?php elseif ( $encrypted_password === '' ) : ?>
				<?php $encryption_error = password_encryption_error(); ?>
				<p><?php echo esc_html( $encryption_error ?: 'Dette passordet ble lagret uten en kryptert kopi. Visning blir tilgjengelig når et nytt passord settes.' ); ?></p>
			<?php else : ?>
				<form method="post" action="<?php echo esc_url( access_admin_url() ); ?>">
					<?php wp_nonce_field( 'lssk_reveal_password' ); ?>
					<button class="button" type="submit" name="lssk_reveal_password" value="1">Vis gjeldende passord</button>
				</form>
			<?php endif; ?>
			<h2>Gjeldende QR-kode</h2>
			<p>Medlemmer kan skanne koden for å åpne siden. Heng den opp på banen og erstatt plakaten når tilgangen fornyes.</p>
			<?php
			$scan_url = member_url() . '#tilgang=' . qr_token( $settings['generation'] );
			$logo_url = get_site_icon_url( 192 ) ?: wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ), 'medium' );
			?>
			<canvas id="lssk-qr-canvas" data-access-url="<?php echo esc_attr( $scan_url ); ?>" data-logo-url="<?php echo esc_url( $logo_url ?: '' ); ?>" role="img" aria-label="QR-kode for rabattkoder" hidden></canvas>
			<p>
				<a class="button" id="lssk-qr-download" download="lssk-rabattkoder-qr.png" hidden>Last ned QR-kode (PNG)</a>
				<a class="button" id="lssk-qr-branded-download" download="lssk-rabattkoder-qr-med-logo.png" hidden>Last ned QR-kode med logo (PNG)</a>
				<button class="button" type="button" id="lssk-qr-choose-logo">Velg QR-logo</button>
			</p>
			<p id="lssk-qr-logo-status" role="status" hidden></p>
			<p><label for="lssk-scan-url">QR-lenke</label><br><input class="large-text" id="lssk-scan-url" type="text" readonly value="<?php echo esc_attr( $scan_url ); ?>"></p>
			<h2>Utskriftsklar plakat</h2>
			<form id="lssk-poster-form" autocomplete="off">
				<p><label for="lssk-poster-password">Fellespassord på plakaten</label><br>
					<input class="regular-text" id="lssk-poster-password" type="password" maxlength="128" required value="<?php echo esc_attr( $current_password ?? '' ); ?>"></p>
				<button class="button" id="lssk-poster-download" type="submit" disabled>Last ned plakat (PNG)</button>
			</form>
		<?php endif; ?>
		<h2><?php echo $configured ? 'Forny tilgang' : 'Opprett tilgang'; ?></h2>
		<?php if ( $configured ) : ?>
			<p>Et nytt passord erstatter QR-koden og avslutter eksisterende nettlesertilgang.</p>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="lssk_rotate_access">
			<?php wp_nonce_field( 'lssk_rotate_access' ); ?>
			<p><label for="lssk-password">Nytt fellespassord (minst 8 tegn)</label><br>
				<input class="regular-text" id="lssk-password" name="lssk_password" type="password" required minlength="8" maxlength="128" autocomplete="new-password"></p>
			<?php submit_button( $configured ? 'Bytt passord og QR-kode' : 'Lagre passord og opprett QR-kode' ); ?>
		</form>
		<h2>Passordhistorikk</h2>
		<?php if ( empty( $settings['password_history'] ) ) : ?>
			<p>Ingen passordendringer er registrert ennå.</p>
		<?php else : ?>
			<ul>
				<?php foreach ( $settings['password_history'] as $change ) : ?>
					<?php $admin = get_userdata( $change['user_id'] ); ?>
					<li><?php echo esc_html( wp_date( 'j. F Y H:i', $change['time'] ) ); ?> · <?php echo esc_html( $admin ? $admin->display_name : 'Slettet bruker' ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
}

function enqueue_admin_assets(): void {
	$screen = get_current_screen();
	if ( ! $screen || $screen->post_type !== OFFER || $screen->base !== 'post' ) {
		return;
	}
	$base = plugin_dir_url( LSSK_BENEFITS_FILE ) . 'assets/';
	wp_enqueue_media();
	wp_enqueue_script( 'lssk-benefits-admin', $base . 'admin.js', array( 'media-editor' ), LSSK_BENEFITS_VERSION, true );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_assets' );
