<?php
/** Standalone behavioral fixtures. No real SSI or WordPress credentials needed. */
define( 'ABSPATH', __DIR__ );
$options = $posts = $meta = $http = array();
$calls = array();
$next_id = 1;
$fail_save = false;
$tests = 0;
function apply_filters( $hook, $value, ...$args ) { return $value; }
function sanitize_text_field( $value ) { return trim( strip_tags( $value ) ); }
function wp_parse_url( $value ) { return parse_url( $value ); }
function esc_url( $value ) { return htmlspecialchars( $value, ENT_QUOTES ); }
function esc_html( $value ) { return htmlspecialchars( $value, ENT_QUOTES ); }
function wp_json_encode( $value ) { return json_encode( $value ); }
function wp_slash( $value ) { return is_array( $value ) ? array_map( 'wp_slash', $value ) : ( is_string( $value ) ? addslashes( $value ) : $value ); }
function wp_unslash( $value ) { return is_array( $value ) ? array_map( 'wp_unslash', $value ) : ( is_string( $value ) ? stripslashes( $value ) : $value ); }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
class WP_Error {}
function get_option( $key, $default = false ) { global $options; return $options[$key] ?? $default; }
function add_option( $key, $value, $deprecated = '', $autoload = false ) { global $options; if ( isset( $options[$key] ) ) { return false; } $options[$key] = $value; return true; }
function update_option( $key, $value, $autoload = false ) { global $options; $options[$key] = $value; return true; }
function delete_option( $key ) { global $options; unset( $options[$key] ); }
function wp_cache_delete( ...$args ) {}
function wp_generate_uuid4() { return uniqid( '', true ); }
class Fake_DB {
    public $options = 'wp_options';
    public $posts = 'wp_posts';
    public $postmeta = 'wp_postmeta';
    public $last_error = '';
    public function prepare( $sql, ...$args ) { return $args; }
    public function query( $args ) { global $options; if ( ( $options[$args[0]] ?? '' ) === $args[1] ) { unset( $options[$args[0]] ); } }
    public function get_col( $args ) { return get_posts( array( 'meta_key' => $args[1], 'meta_value' => $args[2] ) ); }
}
$wpdb = new Fake_DB();
function wp_remote_post( $url, $args ) {
    global $http, $calls;
    $calls[] = array( $url, $args );
    if ( ! $http ) { throw new Exception( 'No HTTP fixture queued' ); }
    return array_shift( $http );
}
function wp_remote_retrieve_response_code( $r ) { return $r['code']; }
function wp_remote_retrieve_body( $r ) { return $r['body']; }
function get_posts( $args ) {
    global $posts, $meta;
    return array_slice( array_values( array_filter( array_keys( $posts ), static function ( $id ) use ( $args, $meta ) {
        return ( $meta[$id][$args['meta_key']] ?? null ) === $args['meta_value'];
    } ) ), 0, 2 );
}
function get_post( $id ) { global $posts; return isset( $posts[$id] ) ? (object) $posts[$id] : null; }
function get_post_status( $id ) { return get_post( $id )->post_status; }
function get_post_meta( $id, $key, $single = true ) { global $meta; return $meta[$id][$key] ?? ''; }
function update_post_meta( $id, $key, $value ) { global $meta; $meta[$id][$key] = $value; }
function delete_post_meta( $id, $key ) { global $meta; unset( $meta[$id][$key] ); }
function wp_update_post( $args, $error = false ) { global $posts; $posts[$args['ID']]['post_status'] = $args['post_status']; return $args['ID']; }
class Fake_Repository {
    private $id = 0;
    private $args = array();
    public function where( $key, $id ) { if ( 'status' === $key && 'any' === $id ) { return $this; } if ( 'id' !== $key ) { throw new Exception( 'Unscoped update' ); } $this->id = $id; return $this; }
    public function set_args( $args ) { $this->args = $args; return $this; }
    public function create() { global $next_id; $this->id = $next_id++; $this->write(); return get_post( $this->id ); }
    public function save() { if ( ! $this->id ) { throw new Exception( 'Unscoped save' ); } $this->write(); return null; }
    private function write() {
        global $posts, $meta, $fail_save;
        if ( $fail_save ) { return; }
        $a = wp_unslash( $this->args );
        $posts[$this->id] = array( 'ID' => $this->id, 'post_title' => $a['title'], 'post_content' => $a['content'], 'post_status' => $a['status'] );
        foreach ( array( 'start_date' => '_EventStartDate', 'end_date' => '_EventEndDate', 'timezone' => '_EventTimezone', 'url' => '_EventURL' ) as $key => $field ) { $meta[$this->id][$field] = $a[$key]; }
        foreach ( $a['meta_input'] ?? array() as $key => $value ) { $meta[$this->id][$key] = $value; }
    }
}
function tribe_events() { return new Fake_Repository(); }
require __DIR__ . '/../includes/class-event-mapper.php';
require __DIR__ . '/../includes/class-ssi-api.php';
require __DIR__ . '/../includes/class-plugin.php';
use LSSK\SSI\Event_Mapper;
use LSSK\SSI\SSI_API;
use LSSK\SSI\Plugin;
function check( $condition, $label ) { global $tests; ++$tests; if ( ! $condition ) { throw new Exception( 'FAIL: ' . $label ); } echo 'PASS: ' . $label . PHP_EOL; }
function fails( callable $fn, $label ) { try { $fn(); } catch ( RuntimeException $e ) { check( true, $label ); return; } check( false, $label ); }
function response( $data, $code = 200 ) { return array( 'code' => $code, 'body' => json_encode( array( 'data' => $data ) ) ); }
function fixture( $overrides = array() ) {
    return array_merge( array( 'id' => '30091', 'get_content_type_key' => 22, 'name' => 'Lofoten test', 'starts' => '2026-10-16T16:00:00Z', 'ends' => '2026-10-16T18:00:00Z', 'venue' => 'Slydalen', 'status' => 'ac', 'get_status_display' => 'Active', 'visibility' => 'pub', 'get_visibility_display' => 'Public, visible to anyone', 'get_full_absolute_url' => 'https://shootnscoreit.com/event/22/30091/' ), $overrides );
}
function queue_events( $events, $next = array() ) { global $http; $http = array( response( array( 'organization' => array( 'id' => '60', 'events' => $events ) ) ), response( array( 'organization' => array( 'id' => '60', 'events' => $next ) ) ) ); }
$settings = Plugin::settings();
check( ! $settings['enabled'] && ! $settings['publish'] && ! $settings['accepted'], 'Safe defaults: no requests or publication on activation' );
$mapped = Event_Mapper::map( fixture(), $settings );
check( '2026-10-16 18:00:00' === $mapped['start_date'], 'UTC to Oslo summer time' );
check( '2026-12-16 17:00:00' === Event_Mapper::map( fixture( array( 'starts' => '2026-12-16T16:00:00Z', 'ends' => '2026-12-16T18:00:00Z' ) ), $settings )['start_date'], 'UTC to Oslo winter time' );
check( '2026-10-16 20:00:00' === Event_Mapper::map( fixture( array( 'ends' => null ) ), $settings )['end_date'], 'Fallback duration applied when end is absent' );
check( str_contains( Event_Mapper::map( fixture( array( 'ends' => null ) ), $settings )['content'], 'estimated' ), 'Estimated end time disclosed' );
fails( static fn() => Event_Mapper::map( fixture( array( 'starts' => '2026-10-16T16:00:00' ) ), $settings ), 'Reject ambiguous timestamps without offset' );
fails( static fn() => Event_Mapper::map( fixture( array( 'starts' => '2026-02-30T16:00:00Z' ) ), $settings ), 'Reject impossible dates' );
fails( static fn() => Event_Mapper::map( fixture( array( 'ends' => '2026-10-16T15:00:00Z' ) ), $settings ), 'Reject end before start' );
fails( static fn() => Event_Mapper::map( fixture( array( 'get_full_absolute_url' => 'https://evil.example/event/22/30091/' ) ), $settings ), 'Reject unexpected source hosts' );
fails( static fn() => Event_Mapper::map( fixture( array( 'get_full_absolute_url' => 'https://shootnscoreit.com/event/22/30091/?secret=123' ) ), $settings ), 'Reject source URLs carrying query credentials' );
check( ! Event_Mapper::is_public( fixture( array( 'get_visibility_display' => 'Private' ) ) ) && ! Event_Mapper::is_public( array() ), 'Unknown and private visibility fail closed' );
check( '60:22:30091' !== Event_Mapper::identity( fixture( array( 'get_content_type_key' => 30 ) ), 60 ), 'Content types disambiguate event IDs' );
$options[Plugin::SETTINGS] = array_merge( $settings, array( 'accepted' => true ) );
$options[Plugin::SECRETS] = array( 'key' => 'test-key', 'access' => 'old-jwt', 'refresh' => 'old-refresh' );
queue_events( array( fixture() ) );
$preview = Plugin::run( true );
check( 1 === $preview['total'] && ! $posts, 'Preview performs no calendar writes' );
check( ! isset( $options[Plugin::LOCK] ), 'Preview releases its lock' );
queue_events( array( fixture() ) );
$r = Plugin::run( false );
check( 1 === $r['created'] && 'draft' === get_post_status( 1 ), 'First import creates a draft' );
queue_events( array( fixture() ) );
$r = Plugin::run( false );
check( 1 === $r['unchanged'] && count( $posts ) === 1, 'Repeated import is idempotent' );
$changed = fixture( array( 'name' => 'Renamed match', 'starts' => '2026-10-17T16:00:00Z', 'ends' => '2026-10-17T19:00:00Z' ) );
queue_events( array( $changed ) );
$r = Plugin::run( false );
check( 1 === $r['updated'] && count( $posts ) === 1 && 'Renamed match' === get_post( 1 )->post_title, 'Renaming and rescheduling update the original post, including null ORM returns' );
$options[Plugin::SETTINGS]['publish'] = true;
queue_events( array( $changed ) );
Plugin::run( false );
check( 'publish' === get_post_status( 1 ), 'Publication requires explicit setting' );
queue_events( array( fixture( array( 'get_visibility_display' => 'Private', 'name' => 'SECRET PRIVATE TITLE' ) ) ) );
$r = Plugin::run( false );
check( 'draft' === get_post_status( 1 ) && 1 === $r['hidden'] && ! str_contains( json_encode( $r ), 'SECRET PRIVATE TITLE' ), 'Previously public event becomes draft without storing private data' );
queue_events( array( $changed ) ); Plugin::run( false );
queue_events( array( fixture( array( 'get_status_display' => 'Cancelled' ) ) ) ); Plugin::run( false );
check( 'draft' === get_post_status( 1 ), 'Explicit cancellation unpublishes imported event' );
queue_events( array() ); Plugin::run( false );
check( count( $posts ) === 1, 'Missing source events are retained' );
$posts[1]['post_status'] = 'trash';
queue_events( array( fixture() ) ); Plugin::run( false );
check( 'trash' === get_post_status( 1 ) && count( $posts ) === 1, 'Trashed imports are neither recreated nor restored' );
$posts[1]['post_status'] = 'publish';
$before = serialize( array( $posts, $meta ) );
$http = array( response( array( 'organization' => array( 'id' => '60', 'events' => array( $changed ) ) ) ), array( 'code' => 503, 'body' => 'Unavailable' ) );
fails( static fn() => Plugin::run( false ), 'Second-year API failure aborts before any event writes' );
check( $before === serialize( array( $posts, $meta ) ) && ! isset( $options[Plugin::LOCK] ), 'API failure preserves entries and releases lock' );
queue_events( array( $changed, fixture( array( 'id' => '30092', 'starts' => 'bad' ) ) ) );
fails( static fn() => Plugin::run( false ), 'Validate every event before writing' );
check( $before === serialize( array( $posts, $meta ) ), 'Malformed event prevents partial writes during planning' );
queue_events( array( fixture(), $changed ) );
fails( static fn() => Plugin::run( false ), 'Conflicting source duplicates are rejected' );
$options[Plugin::LOCK] = time() . ':other-worker';
fails( static fn() => Plugin::run( false ), 'Concurrent sync is blocked' );
unset( $options[Plugin::LOCK] );
$options[Plugin::LOCK] = ( time() - 1000 ) . ':stale-worker';
queue_events( array() ); Plugin::run( true );
check( ! isset( $options[Plugin::LOCK] ), 'Expired lock can be recovered' );
$saved_tokens = array();
$api = new SSI_API( 'test-key', array( 'access' => 'expired', 'refresh' => 'refresh-value' ), static function ( $tokens ) use ( &$saved_tokens ) { $saved_tokens = $tokens; } );
$http = array(
    array( 'code' => 401, 'body' => '' ),
    response( array( 'refresh_token' => array( 'success' => true, 'token' => array( 'token' => 'new-jwt' ), 'refresh_token' => array( 'token' => 'new-refresh' ) ) ) ),
    response( array( 'organization' => array( 'id' => '60', 'events' => array( fixture() ) ) ) ),
);
$api->events( 60, 2026 );
check( 'new-jwt' === $saved_tokens['access'] && 'new-refresh' === $saved_tokens['refresh'], 'Token refresh persists replacement credentials' );
check( 'JWT new-jwt' === end( $calls )[1]['headers']['Authorization'], 'Failed request retries with refreshed JWT' );
check( 0 === end( $calls )[1]['redirection'] && true === end( $calls )[1]['sslverify'], 'Credentials use verified TLS without following redirects' );
$http = array( array( 'code' => 200, 'body' => json_encode( array( 'errors' => array( array( 'message' => 'private secret echoed by upstream' ) ), 'data' => array( 'organization' => array( 'events' => array() ) ) ) ) ) );
try { $api->events( 60, 2026 ); check( false, 'GraphQL errors rejected' ); } catch ( RuntimeException $e ) { check( ! str_contains( $e->getMessage(), 'private secret' ), 'GraphQL partial errors rejected without exposing upstream text' ); }
$http = array( array( 'code' => 200, 'body' => '<html>Login</html>' ) );
fails( static fn() => $api->events( 60, 2026 ), 'HTML login response is rejected' );
$http = array( response( array( 'organization' => array( 'id' => '61', 'events' => array() ) ) ) );
fails( static fn() => $api->events( 60, 2026 ), 'Unexpected organization is rejected' );
// A manual WP event has no SSI source key and must never be matched.
$posts[99] = array( 'ID' => 99, 'post_title' => 'Manual event', 'post_content' => 'Keep me', 'post_status' => 'publish' );
queue_events( array( $changed ) ); Plugin::run( false );
check( 'Keep me' === $posts[99]['post_content'], 'Manually entered calendar events are untouched' );
$fail_save = true;
queue_events( array( fixture( array( 'name' => 'Failed update' ) ) ) );
fails( static fn() => Plugin::run( false ), 'Persisted event verification catches failed ORM saves' );
$fail_save = false;
echo "\nAll {$tests} checks passed. These fixtures do not replace a live WordPress/TEC smoke test.\n";
