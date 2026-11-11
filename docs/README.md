# LSSK documentation

Documentation for the Lofoten Sportsskytterklubb website and club applications.

[Repository home](../README.md) · [Public website](https://lssk.no) · [WordPress development](https://dev.lssk.no) · [Shot Counter development](https://skudd.lssk.no/)

## Start here

| Guide | What it covers |
| --- | --- |
| [Architecture](architecture.md) | Proxmox containers, WordPress hosting, Apache, reverse proxy, and HTTPS handling. |
| [Website content and layout status](content-status.md) | Current page URLs, editing locations, Elementor layouts, theme/CSS changes, and content decisions. Read this before editing existing pages. |
| [Development environments](development.md) | WordPress development settings and the Shot Counter instance on lab3, CT107. |
| [TODO](todo.md) | Outstanding website and infrastructure documentation tasks. |

## Email

- [Club email setup and rollout](email.md) — **Rollout in progress.** The shared `post@lssk.no` mailbox is live on Purelymail with TiboMail Free in production. Board onboarding, shared Inbox and Sent access, and contact-form delivery checks are pending.

## WordPress administration

| Guide | What it covers |
| --- | --- |
| [Production](production.md) | Production identity, environment indicator, public development notice, and indexing. |
| [Lab4 migration](migration-lab4-20261110.md) | Production cutover, verified backup, rollback, and the dev refresh. |
| [Clone production to development](cloning-prod-to-dev.md) | Container cloning, URL replacement, environment settings, caches, and Apache configuration. |
| [WordPress and theme notes](wordpress.md) | Fire Rangers parent/child themes, Elementor, theme options, WooCommerce, and favicons. |
| [Plugins](plugins.md) | Plugin roles, custom integrations, administration tools, and potential additions. |
| [Operations](operations.md) | Apache checks, WP-CLI commands, reverse-proxy diagnostics, backups, and crawler settings. |

## Website features

- [Rabattkoder](../plugins/lssk-member-benefits/README.md) — protected discount offers, QR poster downloads, and expiry reminders.

- [Homepage sponsor presentation](homepage-sponsors.md) — sponsor invitations, responsive spotlight, moving logos, drag/touch behavior and maintenance source.
- [Social sharing previews](social-sharing.md) — Open Graph/Twitter configuration and the standplass 7 sharing photograph.

## Calendar integration

- [Shoot'n Score It synchronization](ssi-sync.md) — **Live in production.** The verified import published 35 eligible events to the [club calendar](https://lssk.no/kalender/). Sync runs manually, with hourly scheduling disabled.
- [LSSK SSI Sync setup guide and source](../plugins/lssk-ssi-sync/README.md) — credentials, preview, draft imports, scheduling, and troubleshooting.
- [Live import verification](ssi-sync.md#verified-live-import-after-approved-visibility-change) and [local validation notes](../plugins/lssk-ssi-sync/VALIDATION.md).
- Build a fresh ZIP from the current source before reinstalling. The existing ZIPs predate the production fixes.

## Shot Counter

- [Application overview](../shot-counter/README.md) — features, privacy controls, development environment, limitations, and roadmap.
- [Installation and migration](../shot-counter/docs/INSTALL.md) — Debian deployment, service configuration, updates, and migration from the original repository.
- [Connectivity architecture](../shot-counter/docs/CONNECTIVITY_ARCHITECTURE.md) — offline-first design and planned outbound synchronization.

## Maintaining these docs

Update the relevant guide when configuration or operating procedures change, and add new documents to this index. Keep current website editing decisions in [content-status.md](content-status.md); the active WordPress theme and database remain outside this repository.

Do not commit live configuration, credentials, API keys, database exports, audio recordings, or backup archives.
