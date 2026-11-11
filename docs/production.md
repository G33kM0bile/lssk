# Production

## Identity

- Proxmox: Lab4 / OSL3, node `pve`, CT `804`
- Hostname: `wordpress.lssk.no`
- URL: https://lssk.no
- https://www.lssk.no redirects to the canonical HTTPS URL, preserving paths and query strings.
- WordPress path: `/var/www/html/wordpress`
- Database: MariaDB inside the container

Production moved on 2026-11-10. The old Lab2 Delta CT `124` is stopped and retained for rollback. Lab4 CT `124` is Etherpad. See the [migration record](migration-lab4-20261110.md) for backup and cutover details.

Production explicitly defines:

```php
define( 'WP_ENVIRONMENT_TYPE', 'production' );
```

## Environment indicator

The Environment Indicator plugin is installed and configured to show production as green in the WordPress admin UI.

## Public development notice

While the site is still being completed, production displays a slim public notice rather than a full maintenance/coming-soon page:

> 🚧 Dette nettstedet er under utvikling.  
> Enkelte funksjoner og sider kan være ufullstendige eller midlertidig utilgjengelige.

This keeps the site publicly usable while setting expectations.

## Search indexing

Production is intended to be indexable. Do not enable WordPress's “Discourage search engines from indexing this site” setting on production.

## WP-CLI

WP-CLI was not installed on production at the time this documentation was created. Configuration changes on production should therefore be made carefully through WordPress admin or direct file edits with a backup first.
