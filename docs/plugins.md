# Plugins

This file documents the purpose of notable plugins. Update it when the stack changes.

## Site functionality

- **Elementor** — page builder.
- **The Events Calendar** — club events/calendar functionality.
- **WooCommerce** — installed as a theme dependency/supporting plugin; not used as an online shop.

- **Open Graph and Twitter Card Tags 3.5.2** — sharing titles, descriptions and club photograph; [configuration](social-sharing.md).

## Custom integrations

- **LSSK SSI Sync** — imports public Shoot'n Score It organization 60 events into The Events Calendar. Version 0.1.0 is available in this repository; deployment and live SSI authentication are still pending. Defaults to preview/manual operation and draft imports, with optional hourly sync and publishing.
- [Setup and deployment notes](ssi-sync.md), [source and full guide](../plugins/lssk-ssi-sync/README.md), [installable ZIP](../dist/lssk-ssi-sync-0.1.0.zip).

## Administration and environment

- **Environment Indicator** — visually identifies production vs development using `WP_ENVIRONMENT_TYPE`.
- **Top Bar Links** — admin-bar shortcuts to project resources and environments.
- **WPFront Notification Bar** — slim public work-in-progress notice on production.

## Maintenance

A maintenance-mode plugin may be used during maintenance windows. This is separate from the normal public “site under development” notification bar.

## Potential additions

Plugins discussed as potentially useful:

- Two Factor
- Simple History
- Safe SVG
- Query Monitor on development only
- WP Crontrol for troubleshooting
- an SMTP plugin when outbound form mail is enabled
- Redis Object Cache only after Redis is intentionally configured server-side

Prefer a small, maintainable plugin stack and avoid overlapping cache, security, or SEO plugins.
