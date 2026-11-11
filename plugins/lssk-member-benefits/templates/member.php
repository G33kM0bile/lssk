<?php
namespace LSSK\Benefits;

defined( 'ABSPATH' ) || exit;

$assets = plugin_dir_url( LSSK_BENEFITS_FILE ) . 'assets/';
?>
<!doctype html>
<html lang="nb">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Rabattkoder | Lofoten Sportsskytterklubb</title>
	<link rel="stylesheet" href="<?php echo esc_url( $assets . 'member.css?ver=' . LSSK_BENEFITS_VERSION ); ?>">
	<script defer src="<?php echo esc_url( $assets . 'member.js?ver=' . LSSK_BENEFITS_VERSION ); ?>"></script>
</head>
<body class="lssk-standalone">
	<header class="site-header">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Lofoten Sportsskytterklubb</a>
		<?php if ( $unlocked ) : ?>
			<form method="post" action="<?php echo esc_url( member_url() ); ?>">
				<button type="submit" name="lssk_logout" value="<?php echo esc_attr( access_signature( 'logout', $settings['generation'], $_COOKIE[ ACCESS_COOKIE ] ) ); ?>">Logg ut</button>
			</form>
		<?php endif; ?>
	</header>
	<main class="lssk-benefits">
		<h1>Rabattkoder</h1>
		<?php require __DIR__ . '/offers-panel.php'; ?>
	</main>
</body>
</html>
