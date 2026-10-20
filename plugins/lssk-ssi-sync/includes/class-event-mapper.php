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
        // Fail closed. Raw visibility is not an enum in SSI's schema. Use the
        // English display label actually supplied by SSI, or an explicit filter.
        $label = trim( (string) ( $event['get_visibility_display'] ?? '' ) );
        $public = (bool) preg_match( '/^public(?:$|[\s,:(])/i', $label );
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
        $url = (string) ( $event['get_full_absolute_url'] ?? '' );
        $parts = wp_parse_url( $url );
        if ( ! is_array( $parts ) || ( $parts['scheme'] ?? '' ) !== 'https' || strtolower( $parts['host'] ?? '' ) !== 'shootnscoreit.com'
            || isset( $parts['user'] ) || isset( $parts['pass'] ) || isset( $parts['port'] )
            || ! preg_match( '#^/event/[0-9]+/[0-9]+/?$#D', $parts['path'] ?? '' ) || isset( $parts['query'] ) || isset( $parts['fragment'] ) ) {
            throw new \RuntimeException( 'An SSI event has an unexpected source URL.' );
        }
        $venue = sanitize_text_field( (string) ( $event['venue'] ?? '' ) );
        $status = sanitize_text_field( (string) ( $event['get_status_display'] ?? '' ) );
        $content = '<p><a href="' . esc_url( $url ) . '">Event details and registration on Shoot\'n Score It</a></p>';
        if ( $venue ) {
            $content .= '<p>Location: ' . esc_html( $venue ) . '</p>';
        }
        if ( $status ) {
            $content .= '<p>SSI status: ' . esc_html( $status ) . '</p>';
        }
        if ( $assumed ) {
            $content .= '<p>End time is estimated; check SSI for the schedule.</p>';
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
