<?php
namespace LSSK\SSI;

defined( 'ABSPATH' ) || exit;

final class Event_Mapper {
    public static function identity( array $event, int $organization ) : string {
        $id = (string) ( $event['id'] ?? '' );
        $ct = (string) ( $event['get_content_type_key'] ?? '' );
        if ( ! ctype_digit( $id ) || ! ctype_digit( $ct ) || (int) $ct < 1 || (int) $id < 1 ) {
            throw new \RuntimeException( 'An SSI event has an invalid identifier.' );
        }
        return $organization . ':' . $ct . ':' . $id;
    }

    public static function is_public( array $event ) : bool {
        // SSI event 22/30342 verified on 2026-11-10: res means visible/searchable,
        // with details and participant names restricted. LSSK approved importing
        // only title, dates, venue and source link. Unknown/private codes stay excluded.
        $label = trim( (string) ( $event['get_visibility_display'] ?? '' ) );
        $public = ( $event['visibility'] ?? null ) === 'res'
            || (bool) preg_match( '/^public(?:$|[\s,:(])/i', $label );
        return (bool) apply_filters( 'lssk_ssi_is_public_event', $public, $event );
    }

    public static function date( $value, \DateTimeZone $zone ) : \DateTimeImmutable {
        if ( ! is_string( $value ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}:\d{2})$/D', $value ) ) {
            throw new \RuntimeException( 'SSI date is missing an explicit UTC offset or is not ISO-8601.' );
        }
        try {
            $date = new \DateTimeImmutable( $value );
            $errors = \DateTimeImmutable::getLastErrors();
            if ( is_array( $errors ) && ( $errors['warning_count'] || $errors['error_count'] ) ) {
                throw new \RuntimeException( 'Invalid calendar date.' );
            }
            return $date->setTimezone( $zone );
        } catch ( \Exception $error ) {
            throw new \RuntimeException( 'SSI supplied an invalid event date.' );
        }
    }

    public static function map( array $event, array $settings ) : array {
        $title = sanitize_text_field( (string) ( $event['name'] ?? '' ) );
        if ( '' === $title ) {
            throw new \RuntimeException( 'An SSI event has no title.' );
        }
        $zone = new \DateTimeZone( $settings['timezone'] );
        $start = self::date( $event['starts'] ?? null, $zone );
        $assumed = empty( $event['ends'] );
        $end = $assumed ? $start->modify( '+' . (int) $settings['duration'] . ' minutes' ) : self::date( $event['ends'], $zone );
        if ( $end <= $start ) {
            throw new \RuntimeException( 'An SSI event ends before or at its start.' );
        }
        // Use SSI's canonical event route from validated identifiers. This avoids
        // legacy/signed API URL variants and never publishes their query or token.
        $parts = wp_parse_url( (string) ( $event['get_full_absolute_url'] ?? '' ) );
        $relative = is_array( $parts )
            && ! isset( $parts['scheme'] ) && ! isset( $parts['host'] )
            && str_starts_with( $parts['path'] ?? '', '/' ) && ! str_starts_with( $parts['path'] ?? '', '//' );
        $trusted = $relative || ( is_array( $parts ) && in_array( $parts['scheme'] ?? '', array( 'http', 'https' ), true )
            && strtolower( $parts['host'] ?? '' ) === 'shootnscoreit.com' );
        if ( ! $trusted || isset( $parts['user'] ) || isset( $parts['pass'] )
            || isset( $parts['port'] ) || isset( $parts['query'] ) || isset( $parts['fragment'] ) ) {
            throw new \RuntimeException( 'An SSI event has an unexpected source URL.' );
        }
        self::identity( $event, (int) ( $settings['organization'] ?? 0 ) );
        $url = 'https://shootnscoreit.com/event/' . (int) $event['get_content_type_key'] . '/' . (int) $event['id'] . '/';
        $venue = sanitize_text_field( (string) ( $event['venue'] ?? '' ) );
        $content = '<p><a href="' . esc_url( $url ) . '">Se arrangement og påmelding på Shoot\'n Score It</a></p>';
        if ( $venue ) {
            $content .= '<p>Sted: ' . esc_html( $venue ) . '</p>';
        }
        if ( $assumed ) {
            $content .= '<p>Sluttidspunktet er estimert. Se SSI for gjeldende tidsplan.</p>';
        }
        // Deliberately omit SSI free-form descriptions and all participant data.
        return array(
            'title' => $title, 'content' => $content,
            'start_date' => $start->format( 'Y-m-d H:i:s' ),
            'end_date' => $end->format( 'Y-m-d H:i:s' ),
            'timezone' => $settings['timezone'], 'all_day' => false,
            'url' => $url, 'show_map' => false, 'show_map_link' => false,
        );
    }
}
