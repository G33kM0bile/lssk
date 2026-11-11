<?php
namespace LSSK\Benefits;

defined( 'ABSPATH' ) || exit;

const OFFER = 'lssk_member_offer';
const OFFER_DETAILS = '_lssk_offer_details';

function register_offers(): void {
	register_post_type(
		OFFER,
		array(
			'labels' => array(
				'name'          => 'Rabattkoder',
				'menu_name'     => 'Rabattkoder',
				'singular_name' => 'Rabattkode',
				'add_new'       => 'Legg til tilbud',
				'add_new_item'  => 'Legg til tilbud',
				'edit_item'     => 'Rediger tilbud',
				'all_items'     => 'Tilbud',
				'not_found'     => 'Ingen tilbud funnet.',
			),
			'public'      => false,
			'show_ui'     => true,
			'menu_icon'   => 'dashicons-tickets-alt',
			'rewrite'     => false,
			'query_var'   => false,
			'supports'    => array( 'title' ),
			'capabilities' => array_fill_keys(
				array(
					'edit_post', 'read_post', 'delete_post', 'edit_posts', 'edit_others_posts',
					'publish_posts', 'read_private_posts', 'delete_posts', 'delete_private_posts',
					'delete_published_posts', 'delete_others_posts', 'edit_private_posts',
					'edit_published_posts', 'create_posts',
				),
				'manage_options'
			),
			'map_meta_cap' => false,
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_offers' );

function offer_details( int $id ): array {
	return (array) get_post_meta( $id, OFFER_DETAILS, true ) + array(
		'code' => '', 'url' => '', 'expires' => '', 'terms' => '', 'logo' => 0,
	);
}

function active_offers(): array {
	$offers = get_posts(
		array(
			'post_type'   => OFFER,
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => 'title',
			'order'       => 'ASC',
		)
	);

	$today = wp_date( 'Y-m-d' );
	$active = array();
	foreach ( $offers as $offer ) {
		$details = offer_details( $offer->ID );
		if ( $offer->post_title === '' || $details['code'] === '' || ( $details['expires'] !== '' && $details['expires'] < $today ) ) {
			continue;
		}
		$active[] = array( 'id' => $offer->ID, 'company' => $offer->post_title ) + $details;
	}
	return $active;
}
