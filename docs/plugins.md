# Plugins

This file documents the purpose of notable plugins. Update it when the stack changes.

## Site functionality

- **Elementor** — page builder.
- **The Events Calendar** — club events/calendar functionality.
- **WooCommerce** — installed as a theme dependency/supporting plugin; not used as an online shop.

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
