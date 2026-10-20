<?php
namespace LSSK\SSI;

defined( 'ABSPATH' ) || exit;

final class Plugin {
    const SETTINGS = 'lssk_ssi_settings';
    const SECRETS = 'lssk_ssi_credentials';
    const REPORT = 'lssk_ssi_report';
    const PREVIEW = 'lssk_ssi_preview';
    const LOCK = 'lssk_ssi_lock';
    const HOOK = 'lssk_ssi_hourly';
    const SOURCE = '_lssk_ssi_source';

    public static function boot() : void {
        add_action( 'admin_menu', array( self::class, 'menu' ) );
        add_action( 'admin_post_lssk_ssi', array( self::class, 'handle' ) );
        add_action( self::HOOK, array( self::class, 'cron' ) );
        add_action( 'init', array( self::class, 'schedule' ) );
    }

    public static function settings() : array {
        return array_merge( array(
            'organization' => 60, 'timezone' => 'Europe/Oslo', 'duration' => 120,
            'enabled' => false, 'publish' => false, 'accepted' => false,
        ), (array) get_option( self::SETTINGS, array() ) );
    }

    public static function activate() : void {
        add_option( self::SETTINGS, self::settings(), '', false );
    }

    public static function deactivate() : void {
        wp_clear_scheduled_hook( self::HOOK );
    }

    public static function schedule() : void {
        $settings = self::settings();
        if ( $settings['enabled'] && $settings['accepted'] ) {
            if ( ! wp_next_scheduled( self::HOOK ) ) {
                wp_schedule_event( time() + 60, 'hourly', self::HOOK );
            }
        } else {
            wp_clear_scheduled_hook( self::HOOK );
        }
    }

    public static function menu() : void {
        add_options_page( 'LSSK SSI Sync', 'LSSK SSI Sync', 'manage_options', 'lssk-ssi-sync', array( self::class, 'page' ) );
    }

    private static function credentials() : array {
        $secrets = array_merge( array( 'key' => '', 'access' => '', 'refresh' => '' ), (array) get_option( self::SECRETS, array() ) );
        if ( defined( 'LSSK_SSI_API_KEY' ) ) {
            $secrets['key'] = (string) LSSK_SSI_API_KEY;
        }
        return $secrets;
    }

    public static function api() : SSI_API {
        $credentials = self::credentials();
        if ( '' === $credentials['key'] ) {
            throw new \RuntimeException( 'Configure an SSI API key first.' );
        }
        return new SSI_API( $credentials['key'], $credentials, static function ( array $tokens ) : void {
            $stored = (array) get_option( self::SECRETS, array() );
            $stored['access'] = $tokens['access'];
            $stored['refresh'] = $tokens['refresh'];
            update_option( self::SECRETS, $stored, false );
            $saved = (array) get_option( self::SECRETS, array() );
            if ( ( $saved['access'] ?? '' ) !== $tokens['access'] || ( $saved['refresh'] ?? '' ) !== $tokens['refresh'] ) {
                throw new \RuntimeException( 'Could not save refreshed SSI credentials. Check database access.' );
            }
        } );
    }

    private static function lock() : string {
        $value = (string) get_option( self::LOCK, '' );
        if ( $value && (int) explode( ':', $value )[0] < time() - 900 ) {
            self::unlock( $value );
        }
        $value = time() . ':' . wp_generate_uuid4();
        if ( ! add_option( self::LOCK, $value, '', false ) ) {
            throw new \RuntimeException( 'A sync or preview is already running. Try again later.' );
        }
        return $value;
    }

    private static function unlock( string $value ) : void {
        global $wpdb;
        // Compare-and-delete prevents an expired worker from deleting a new lock.
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name = %s AND option_value = %s", self::LOCK, $value ) );
        wp_cache_delete( self::LOCK, 'options' );
    }

    public static function cron() : void {
        if ( ! self::settings()['enabled'] ) {
            return;
        }
        try {
            self::run( false );
        } catch ( \Throwable $error ) {
            // run() records a safe, credential-free report. No public output.
        }
    }

    public static function run( bool $preview ) : array {
        $settings = self::settings();
        if ( ! $settings['accepted'] ) {
            throw new \RuntimeException( 'Review SSI developer/API terms and acknowledge them in settings before connecting.' );
        }
        if ( ! $preview && ! function_exists( 'tribe_events' ) ) {
            throw new \RuntimeException( 'Activate The Events Calendar before importing.' );
        }
        $lock = self::lock();
        $started = microtime( true );
        $report = array( 'at' => gmdate( 'c' ), 'preview' => $preview, 'created' => 0, 'updated' => 0, 'unchanged' => 0, 'hidden' => 0, 'skipped' => 0, 'items' => array(), 'error' => '' );
        try {
            $api = self::api();
            $year = (int) ( new \DateTimeImmutable( 'now', new \DateTimeZone( $settings['timezone'] ) ) )->format( 'Y' );
            // Fetch both complete responses before touching WordPress events.
            $events = array_merge( $api->events( $settings['organization'], $year ), $api->events( $settings['organization'], $year + 1 ) );
            if ( count( $events ) > 1000 ) {
                throw new \RuntimeException( 'SSI returned more than 1,000 events. Import stopped for review.' );
            }
            $normalized = array();
            foreach ( $events as $event ) {
                if ( ! is_array( $event ) ) {
                    throw new \RuntimeException( 'SSI returned a malformed event list; no events were changed.' );
                }
                $identity = Event_Mapper::identity( $event, $settings['organization'] );
                if ( isset( $normalized[ $identity ] ) && $normalized[ $identity ] !== $event ) {
                    throw new \RuntimeException( 'SSI returned conflicting duplicate identifiers; no events were changed.' );
                }
                $normalized[ $identity ] = $event;
            }
            $plan = array();
            foreach ( $normalized as $identity => $event ) {
                if ( ! Event_Mapper::is_public( $event ) ) {
                    // Do not store or preview private event titles or other details.
                    $plan[] = array( 'identity' => $identity, 'hide' => true );
                    ++$report['skipped'];
                    continue;
                }
                $mapped = Event_Mapper::map( $event, $settings );
                $cancelled = (bool) preg_match( '/cancelled|canceled|withdrawn/i', (string) $event['get_status_display'] );
                $plan[] = array( 'identity' => $identity, 'hide' => $cancelled, 'mapped' => $mapped );
                if ( count( $report['items'] ) < 100 ) {
                    $report['items'][] = array( 'title' => $mapped['title'], 'starts' => $mapped['start_date'], 'ends' => $mapped['end_date'], 'action' => $cancelled ? 'Hide / skip cancelled event' : ( $settings['publish'] ? 'Publish / update' : 'Draft / update' ) );
                }
            }
            // All public event data is validated before the first write.
            if ( ! $preview ) {
                foreach ( $plan as $item ) {
                    if ( microtime( true ) - $started > 120 ) {
                        throw new \RuntimeException( 'Sync paused after its time limit. Some entries may have been saved; rerun safely to finish.' );
                    }
                    $result = self::upsert( $item, $settings );
                    ++$report[ $result ];
                }
            }
            $report['total'] = count( $plan );
            if ( $preview ) {
                update_option( self::PREVIEW, $report, false );
            } else {
                $report['last_success'] = gmdate( 'c' );
                unset( $report['items'] );
                update_option( self::REPORT, $report, false );
            }
            return $report;
        } catch ( \Throwable $error ) {
            $previous = (array) get_option( self::REPORT, array() );
            // Only our controlled RuntimeException messages are safe to display.
            $report['error'] = $error instanceof \RuntimeException ? $error->getMessage() : 'Unexpected sync failure. Check WordPress/PHP compatibility; existing entries were retained where possible.';
            $report['last_success'] = $previous['last_success'] ?? '';
            unset( $report['items'] );
            update_option( $preview ? self::PREVIEW : self::REPORT, $report, false );
            throw new \RuntimeException( $report['error'] );
        } finally {
            self::unlock( $lock );
        }
    }

    private static function upsert( array $item, array $settings ) : string {
        global $wpdb;
        // TEC injects date/occurrence conditions into WP_Query, even when looking
        // up an identifier. Resolve owned posts directly, including past drafts
        // and Trash, then perform writes through the supported calendar API.
        $ids = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT p.ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID WHERE p.post_type = %s AND m.meta_key = %s AND m.meta_value = %s LIMIT 2",
            'tribe_events', self::SOURCE, $item['identity']
        ) );
        if ( $wpdb->last_error ) {
            throw new \RuntimeException( 'Could not look up existing SSI imports. No new event was created.' );
        }
        if ( count( $ids ) > 1 ) {
            throw new \RuntimeException( 'Duplicate imported identifiers exist in WordPress. Resolve them before syncing.' );
        }
        $id = $ids ? (int) $ids[0] : 0;
        if ( $id && 'trash' === get_post_status( $id ) ) {
            return 'unchanged'; // Respect an administrator's deliberate removal.
        }
        if ( $item['hide'] ) {
            if ( $id && 'draft' !== get_post_status( $id ) ) {
                $result = wp_update_post( array( 'ID' => $id, 'post_status' => 'draft' ), true );
                if ( is_wp_error( $result ) || ! $result ) {
                    throw new \RuntimeException( 'Could not unpublish a previously imported private/cancelled event.' );
                }
                delete_post_meta( $id, '_lssk_ssi_hash' );
                return 'hidden';
            }
            return 'unchanged';
        }
        $args = $item['mapped'];
        $args['status'] = $settings['publish'] ? 'publish' : 'draft';
        $hash = hash( 'sha256', wp_json_encode( $args ) );
        if ( $id && get_post_meta( $id, '_lssk_ssi_hash', true ) === $hash && get_post_status( $id ) === $args['status'] ) {
            return 'unchanged';
        }
        if ( $id ) {
            // Always narrow the ORM update to a single owned post.
            tribe_events()->where( 'id', $id )->where( 'status', 'any' )->set_args( wp_slash( $args ) )->save();
        } else {
            $args['meta_input'] = array( self::SOURCE => $item['identity'] );
            $created = tribe_events()->set_args( wp_slash( $args ) )->create();
            if ( is_wp_error( $created ) || ! is_object( $created ) || empty( $created->ID ) ) {
                throw new \RuntimeException( 'The Events Calendar could not create an imported event.' );
            }
            $id = (int) $created->ID;
        }
        // TEC documents a possible null save() return; verify persisted data.
        $post = get_post( $id );
        if ( ! $post || $post->post_title !== $args['title'] || $post->post_content !== $args['content'] || $post->post_status !== $args['status']
            || get_post_meta( $id, '_EventStartDate', true ) !== $args['start_date']
            || get_post_meta( $id, '_EventEndDate', true ) !== $args['end_date']
            || get_post_meta( $id, '_EventTimezone', true ) !== $args['timezone']
            || get_post_meta( $id, '_EventURL', true ) !== $args['url'] ) {
            throw new \RuntimeException( 'The Events Calendar did not save the expected event data. Retry after checking plugin compatibility.' );
        }
        update_post_meta( $id, self::SOURCE, $item['identity'] );
        update_post_meta( $id, '_lssk_ssi_hash', $hash );
        if ( get_post_meta( $id, self::SOURCE, true ) !== $item['identity'] || get_post_meta( $id, '_lssk_ssi_hash', true ) !== $hash ) {
            throw new \RuntimeException( 'Could not save SSI ownership metadata. Check database access before retrying.' );
        }
        return $ids ? 'updated' : 'created';
    }

    public static function handle() : void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Administrator access required.', '', array( 'response' => 403 ) );
        }
        check_admin_referer( 'lssk_ssi' );
        $action = sanitize_key( wp_unslash( $_POST['ssi_action'] ?? '' ) );
        try {
            if ( 'save' === $action ) {
                self::save_settings( wp_unslash( $_POST ) );
            } elseif ( in_array( $action, array( 'preview', 'sync' ), true ) ) {
                self::run( 'preview' === $action );
            } else {
                throw new \RuntimeException( 'Unknown action.' );
            }
            set_transient( 'lssk_ssi_notice_' . get_current_user_id(), array( 'ok' => true, 'text' => 'save' === $action ? 'Settings saved.' : ( 'preview' === $action ? 'Preview completed. No calendar entries changed.' : 'Sync completed.' ) ), 60 );
        } catch ( \Throwable $error ) {
            set_transient( 'lssk_ssi_notice_' . get_current_user_id(), array( 'ok' => false, 'text' => $error instanceof \RuntimeException ? $error->getMessage() : 'Operation failed. Check plugin compatibility.' ), 60 );
        }
        wp_safe_redirect( admin_url( 'options-general.php?page=lssk-ssi-sync' ) );
        exit;
    }

    private static function save_settings( array $input ) : void {
        $org = filter_var( $input['organization'] ?? '', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1, 'max_range' => 2147483647 ) ) );
        $zone = sanitize_text_field( $input['timezone'] ?? '' );
        $duration = filter_var( $input['duration'] ?? '', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1, 'max_range' => 1440 ) ) );
        if ( ! $org || ! $duration || ! in_array( $zone, \DateTimeZone::listIdentifiers(), true ) ) {
            throw new \RuntimeException( 'Enter a positive organization ID, valid IANA time zone, and duration of 1–1440 minutes.' );
        }
        $settings = array( 'organization' => $org, 'timezone' => $zone, 'duration' => $duration,
            'enabled' => ! empty( $input['enabled'] ), 'publish' => ! empty( $input['publish'] ), 'accepted' => ! empty( $input['accepted'] ) );
        if ( $settings['enabled'] && ! $settings['accepted'] ) {
            throw new \RuntimeException( 'Acknowledge SSI developer/API terms before enabling scheduled sync.' );
        }
        $secrets = (array) get_option( self::SECRETS, array() );
        if ( ! empty( $input['clear_credentials'] ) ) {
            $secrets = array();
        }
        foreach ( array( 'key', 'access', 'refresh' ) as $name ) {
            $secret = trim( (string) ( $input[ $name ] ?? '' ) );
            if ( strlen( $secret ) > 8192 || preg_match( '/[\x00-\x20\x7f]/', $secret ) ) {
                throw new \RuntimeException( 'Credentials must not contain whitespace/control characters or exceed 8192 bytes.' );
            }
            if ( '' !== $secret && ! ( 'key' === $name && defined( 'LSSK_SSI_API_KEY' ) ) ) {
                $secrets[ $name ] = $secret;
            }
        }
        update_option( self::SECRETS, $secrets, false );
        update_option( self::SETTINGS, $settings, false );
        delete_option( self::PREVIEW );
        self::schedule();
    }

    public static function page() : void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $settings = self::settings();
        $credentials = self::credentials();
        $notice_key = 'lssk_ssi_notice_' . get_current_user_id();
        $notice = get_transient( $notice_key );
        delete_transient( $notice_key );
        ?>
        <div class="wrap">
        <h1>LSSK SSI Sync</h1>
        <p>Import public club events from Shoot'n Score It into The Events Calendar. Start with Preview and draft imports.</p>
        <?php if ( $notice ) : ?>
            <div class="notice <?php echo $notice['ok'] ? 'notice-success' : 'notice-error'; ?>"><p><?php echo esc_html( $notice['text'] ); ?></p></div>
        <?php endif; ?>
        <?php if ( ! function_exists( 'tribe_events' ) ) : ?>
            <div class="notice notice-warning"><p>The Events Calendar is not active. Preview is available; importing requires it.</p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'lssk_ssi' ); ?>
        <input type="hidden" name="action" value="lssk_ssi"><input type="hidden" name="ssi_action" value="save">
        <table class="form-table" role="presentation">
            <tr><th><label for="ssi-org">Organization ID</label></th><td><input id="ssi-org" name="organization" type="number" min="1" required value="<?php echo esc_attr( $settings['organization'] ); ?>"></td></tr>
            <tr><th><label for="ssi-zone">Time zone</label></th><td><input id="ssi-zone" name="timezone" required value="<?php echo esc_attr( $settings['timezone'] ); ?>"><p class="description">SSI timestamps are converted to this zone, including daylight saving time.</p></td></tr>
            <tr><th><label for="ssi-duration">Fallback duration (minutes)</label></th><td><input id="ssi-duration" name="duration" type="number" min="1" max="1440" required value="<?php echo esc_attr( $settings['duration'] ); ?>"><p class="description">Only used when SSI has no end time; the event description labels the estimate.</p></td></tr>
            <?php foreach ( array( 'key' => 'API key', 'access' => 'JWT access token', 'refresh' => 'Refresh token' ) as $name => $label ) : ?>
                <tr><th><label for="ssi-<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th><td>
                <input id="ssi-<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" type="password" autocomplete="new-password" class="regular-text" value="" <?php disabled( 'key' === $name && defined( 'LSSK_SSI_API_KEY' ) ); ?>>
                <p class="description"><?php echo ! empty( $credentials[ $name ] ) ? 'Configured. Leave blank to keep the stored value.' : 'Not configured.'; ?><?php if ( 'key' === $name && defined( 'LSSK_SSI_API_KEY' ) ) { echo ' API key comes from wp-config.php.'; } ?></p></td></tr>
            <?php endforeach; ?>
            <tr><th>Credentials</th><td><label><input type="checkbox" name="clear_credentials" value="1"> Clear stored credentials before saving replacement values</label><p class="description">Stored in the WordPress database without autoload, not encrypted. No SSI password is stored. Prefer LSSK_SSI_API_KEY in wp-config.php for the API key.</p></td></tr>
            <tr><th>SSI terms</th><td><label><input type="checkbox" name="accepted" value="1" <?php checked( $settings['accepted'] ); ?>> I have reviewed and accepted SSI's developer/API terms for this integration.</label><p><a href="https://shootnscoreit.com/about-the-api/" target="_blank" rel="noopener noreferrer">API documentation and developer agreement</a></p></td></tr>
            <tr><th>Publishing</th><td><label><input type="checkbox" name="publish" value="1" <?php checked( $settings['publish'] ); ?>> Publish public SSI events automatically</label><p class="description">Unchecked: new and updated imported entries are drafts. Cancelled or non-public entries returned by SSI are moved to draft. Trashed entries stay trashed.</p></td></tr>
            <tr><th>Schedule</th><td><label><input type="checkbox" name="enabled" value="1" <?php checked( $settings['enabled'] ); ?>> Enable hourly synchronization</label><p class="description">Uses WP-Cron. Low-traffic sites should trigger WordPress cron from the server scheduler.</p></td></tr>
        </table>
        <?php submit_button( 'Save settings' ); ?>
        </form>
        <h2>Import controls</h2>
        <p>Reads the current and next calendar year. Save settings before running. Entries missing from SSI responses are retained for manual review.</p>
        <?php foreach ( array( 'preview' => 'Preview SSI events', 'sync' => 'Sync now' ) as $action => $label ) : ?>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px">
                <?php wp_nonce_field( 'lssk_ssi' ); ?>
                <input type="hidden" name="action" value="lssk_ssi"><input type="hidden" name="ssi_action" value="<?php echo esc_attr( $action ); ?>">
                <?php submit_button( $label, 'secondary', 'submit', false ); ?>
            </form>
        <?php endforeach; ?>
        <h2>Last synchronization</h2>
        <?php $report = (array) get_option( self::REPORT, array() ); ?>
        <p>Last successful sync: <?php echo esc_html( $report['last_success'] ?? 'Never' ); ?>. Times shown here are UTC.</p>
        <?php if ( $report ) : ?>
            <p><?php echo esc_html( sprintf( 'Attempt: %s. Created: %d; updated: %d; unchanged: %d; hidden: %d; non-public skipped: %d.', $report['at'] ?? '', $report['created'] ?? 0, $report['updated'] ?? 0, $report['unchanged'] ?? 0, $report['hidden'] ?? 0, $report['skipped'] ?? 0 ) ); ?></p>
            <?php if ( ! empty( $report['error'] ) ) : ?><p><strong><?php echo esc_html( $report['error'] ); ?></strong></p><?php endif; ?>
        <?php endif; ?>
        <?php $preview = (array) get_option( self::PREVIEW, array() ); ?>
        <?php if ( $preview ) : ?>
            <h2>Last preview</h2>
            <p><?php echo esc_html( $preview['at'] ?? '' ); ?> UTC. No calendar entries changed. First 100 public entries are shown.</p>
            <?php if ( ! empty( $preview['error'] ) ) : ?><p><?php echo esc_html( $preview['error'] ); ?></p><?php endif; ?>
            <table class="widefat striped"><thead><tr><th>Event</th><th>Start</th><th>End</th><th>Action</th></tr></thead><tbody>
            <?php foreach ( $preview['items'] ?? array() as $item ) : ?>
                <tr><?php foreach ( array( 'title', 'starts', 'ends', 'action' ) as $field ) : ?><td><?php echo esc_html( $item[ $field ] ); ?></td><?php endforeach; ?></tr>
            <?php endforeach; ?>
            </tbody></table>
            <p><?php echo esc_html( sprintf( '%d events received; %d non-public entries excluded.', $preview['total'] ?? 0, $preview['skipped'] ?? 0 ) ); ?></p>
        <?php endif; ?>
        </div>
        <?php
    }
}
