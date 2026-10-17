# Production

## Identity

- Proxmox CT: `124`
- Hostname: `wordpress`
- URL: https://lssk.no
- WordPress path: `/var/www/html/wordpress`

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
