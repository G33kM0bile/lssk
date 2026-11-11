<?php
namespace LSSK\Benefits;

defined( 'ABSPATH' ) || exit;

?>
<div data-lssk-panel>
<?php if ( $embedded && $unlocked ) : ?>
	<form class="lssk-embedded-logout" method="post" action="<?php echo esc_url( member_url() ); ?>">
		<button type="submit" name="lssk_logout" value="<?php echo esc_attr( access_signature( 'logout', $settings['generation'], $_COOKIE[ ACCESS_COOKIE ] ) ); ?>">Logg ut</button>
	</form>
<?php endif; ?>
<?php if ( ! $unlocked ) : ?>
	<section class="panel access-panel">
		<p>Bruk klubbens fellespassord eller skann QR-koden på banen for å se tilbudene.</p>
		<?php if ( $error !== '' ) : ?>
			<p class="error" role="alert"><?php echo esc_html( $error ); ?></p>
		<?php endif; ?>
		<form id="lssk-access-form" method="post" action="<?php echo esc_url( member_url() ); ?>">
			<label for="lssk-member-password">Fellespassord</label>
			<input id="lssk-member-password" name="lssk_password" type="password" autocomplete="current-password" maxlength="128" required>
			<button type="submit">Vis rabattkoder</button>
		</form>
	</section>
<?php elseif ( ! $offers ) : ?>
	<p class="panel">Ingen aktive tilbud akkurat nå.</p>
<?php else : ?>
	<div class="offers">
		<?php foreach ( $offers as $offer ) : ?>
			<article class="panel offer">
				<?php echo wp_get_attachment_image( (int) $offer['logo'], 'medium', false, array( 'class' => 'offer-logo', 'alt' => '' ) ); ?>
				<h2><?php echo esc_html( $offer['company'] ); ?></h2>
				<?php if ( $offer['terms'] !== '' ) : ?>
					<p class="terms"><?php echo esc_html( $offer['terms'] ); ?></p>
				<?php endif; ?>
				<div class="code-row">
					<code><?php echo esc_html( $offer['code'] ); ?></code>
					<button type="button" class="copy-code" hidden>Kopier kode</button>
					<span class="copy-status" aria-live="polite"></span>
				</div>
				<?php if ( $offer['expires'] !== '' ) : ?>
					<p class="expires">Gyldig til og med <time datetime="<?php echo esc_attr( $offer['expires'] ); ?>"><?php echo esc_html( implode( '.', array_reverse( explode( '-', $offer['expires'] ) ) ) ); ?></time></p>
				<?php endif; ?>
				<?php if ( $offer['url'] !== '' ) : ?>
					<a href="<?php echo esc_url( $offer['url'] ); ?>" target="_blank" rel="noopener noreferrer">Gå til tilbudet</a>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
</div>
