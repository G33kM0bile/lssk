# Development

## WordPress

- Proxmox: Lab2, node `delta`, CT `105`
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

### Refresh on 2026-11-11

CT 105 was refreshed from the verified 2026-11-10 production backup. It kept its existing network identity and Cloudflare route. The previous dev contents and local snapshots were replaced.

WordPress and Elementor URLs were rewritten for `dev.lssk.no`, the Elementor CSS cache was cleared, and the Apache vhost uses the dev hostname. The development environment constant is set for the admin indicator. The old dev favicon was not reapplied.

The `lssk-dev-safety.php` must-use plugin blocks WordPress mail, direct WordPress cron execution, and the Action Scheduler async runner. The inherited WordPress system cron entry was disabled, and Mailchimp for WordPress was deactivated. Page caching and the Redis object cache were disabled during the refresh.

The public homepage, login page, training redirect destination, and Elementor CSS returned HTTP 200. The homepage advertised `noindex, nofollow`, and `wp-cron.php` returned HTTP 403. A public probe appeared in CT 105’s Apache log.

## Shot Counter

The Shot Counter development instance is running on Proxmox host **`lab3`** in container **CT107** (CT ID `107`). Its URL is [https://skudd.lssk.no/](https://skudd.lssk.no/).

See the [Shot Counter documentation](../shot-counter/README.md#development-environment) and [installation guide](../shot-counter/docs/INSTALL.md) for application setup and migration instructions.
