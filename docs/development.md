# Development

## WordPress

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

## Shot Counter

The Shot Counter development instance is running on Proxmox host **`lab3`** in container **CT107** (CT ID `107`). Its URL is [https://skudd.lssk.no/](https://skudd.lssk.no/).

See the [Shot Counter documentation](../shot-counter/README.md#development-environment) and [installation guide](../shot-counter/docs/INSTALL.md) for application setup and migration instructions.
