# Development

- CT: `105`
- Hostname: `wordpress-dev`
- URL: https://dev.lssk.no
- WordPress path: `/var/www/html/wordpress`

Development defines:

```php
define( 'WP_ENVIRONMENT_TYPE', 'development' );
```

Search indexing is discouraged with:

```text
blog_public = 0
```

This is a crawler hint, not an access-control mechanism.

WP-CLI is installed at:

```text
/usr/local/bin/wp
/usr/bin/wp
```

Development is deliberately easy to distinguish from production:
- DEVELOPMENT admin indicator
- orange development favicon
- explicit WordPress development environment
