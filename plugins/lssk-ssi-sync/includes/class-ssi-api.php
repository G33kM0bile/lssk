<?php
namespace LSSK\SSI;

defined( 'ABSPATH' ) || exit;

final class SSI_API {
    const ENDPOINT = 'https://shootnscoreit.com/graphql/';
    private $key;
    private $tokens;
    private $persist;

    public function __construct( string $key, array $tokens, callable $persist ) {
        $this->key = $key;
        $this->tokens = $tokens;
        $this->persist = $persist;
    }

    public function events( int $organization, int $year ) : array {
        $query = <<<'GRAPHQL'
query LsskEvents($org: String!, $year: Int!) {
  organization(id: $org) {
    id name
    events(year: $year) {
      id get_content_type_key name starts ends venue status
      get_status_display visibility get_visibility_display get_full_absolute_url
    }
  }
}
GRAPHQL;
        $variables = array( 'org' => (string) $organization, 'year' => $year );
        try {
            $data = $this->request( $query, $variables );
        } catch ( \RuntimeException $error ) {
            if ( 401 !== $error->getCode() || empty( $this->tokens['refresh'] ) ) {
                throw $error;
            }
            $this->refresh();
            $data = $this->request( $query, $variables );
        }
        $org = $data['organization'] ?? null;
        if ( ! is_array( $org ) || (string) ( $org['id'] ?? '' ) !== (string) $organization || ! isset( $org['events'] ) || ! is_array( $org['events'] ) ) {
            throw new \RuntimeException( 'SSI returned an incomplete or unexpected organization response; no events were changed.' );
        }
        return $org['events'];
    }

    private function refresh() : void {
        $query = <<<'GRAPHQL'
mutation LsskRefresh($refresh: String!) {
  refresh_token(refresh_token: $refresh, revoke_refresh_token: false) {
    success token { token } refresh_token { token expires_at }
  }
}
GRAPHQL;
        $data = $this->request( $query, array( 'refresh' => $this->tokens['refresh'] ), false );
        $result = $data['refresh_token'] ?? array();
        if ( empty( $result['success'] ) || empty( $result['token']['token'] ) ) {
            throw new \RuntimeException( 'SSI token refresh failed. Supply a new access token and refresh token.' );
        }
        $this->tokens['access'] = $result['token']['token'];
        if ( ! empty( $result['refresh_token']['token'] ) ) {
            $this->tokens['refresh'] = $result['refresh_token']['token'];
        }
        // Persist immediately, before any event writes. Never print or log credentials.
        call_user_func( $this->persist, $this->tokens );
    }

    private function request( string $query, array $variables, bool $authorize = true ) : array {
        $headers = array( 'Content-Type' => 'application/json', 'Accept' => 'application/json', 'x-api-key' => $this->key );
        if ( $authorize && ! empty( $this->tokens['access'] ) ) {
            $headers['Authorization'] = 'JWT ' . $this->tokens['access'];
        }
        $response = wp_remote_post( self::ENDPOINT, array(
            'timeout' => 25, 'redirection' => 0, 'sslverify' => true,
            'limit_response_size' => 2 * 1024 * 1024,
            'headers' => $headers,
            'body' => wp_json_encode( array( 'query' => $query, 'variables' => $variables ) ),
        ) );
        if ( is_wp_error( $response ) ) {
            throw new \RuntimeException( 'Could not reach SSI. Check connectivity and TLS certificates, then retry.' );
        }
        $status = wp_remote_retrieve_response_code( $response );
        if ( 401 === $status ) {
            throw new \RuntimeException( 'SSI authentication failed. Check the API key and access/refresh tokens.', 401 );
        }
        if ( 200 !== $status ) {
            throw new \RuntimeException( 'SSI returned HTTP ' . (int) $status . '. Existing events were retained.' );
        }
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( ! is_array( $body ) ) {
            throw new \RuntimeException( 'SSI returned invalid JSON. Existing events were retained.' );
        }
        if ( ! empty( $body['errors'] ) ) {
            $auth = false;
            foreach ( $body['errors'] as $error ) {
                $message = (string) ( $error['message'] ?? '' );
                $code = strtoupper( (string) ( $error['extensions']['code'] ?? '' ) );
                $auth = $auth || in_array( $code, array( 'UNAUTHENTICATED', 'TOKEN_EXPIRED', 'INVALID_TOKEN' ), true )
                    || (bool) preg_match( '/not authenticated|authentication required|signature has expired|token (?:has )?expired|invalid token/i', $message );
            }
            // Do not retain upstream error text: responses can contain private data.
            throw new \RuntimeException( $auth ? 'SSI authentication expired or was rejected.' : 'SSI rejected the GraphQL query. Check API permissions and schema compatibility; no response data was imported.', $auth ? 401 : 0 );
        }
        if ( ! isset( $body['data'] ) || ! is_array( $body['data'] ) ) {
            throw new \RuntimeException( 'SSI returned no data. Existing events were retained.' );
        }
        return $body['data'];
    }
}
