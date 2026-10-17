# Lofoten Sportsskytterklubb – website

Technical documentation for the WordPress installation serving [lssk.no](https://lssk.no).

This repository contains documentation and non-sensitive configuration notes only. Do not commit WordPress database exports, wp-config.php, credentials, API keys, salts, certificates, private keys, or backups.

## Environments

| Environment | Proxmox CT | Hostname | URL | WordPress environment |
|---|---:|---|---|---|
| Production | 124 | `wordpress` | https://lssk.no | `production` |
| Development | 105 | `wordpress-dev` | https://dev.lssk.no | `development` |

The development environment was created by cloning production, then changing the WordPress URLs and environment-specific settings.

## Documentation

- [Architecture](docs/architecture.md)
- [Production](docs/production.md)
- [Development](docs/development.md)
- [Clone production to development](docs/cloning-prod-to-dev.md)
- [WordPress and theme notes](docs/wordpress.md)
- [Plugins](docs/plugins.md)
- [Operations](docs/operations.md)
- [TODO](docs/todo.md)

## Public links used by the site

- [Min idrett – membership](https://www.minidrett.no/medlemskap/27827)
- [Norsk Tipping – Grasrotandelen](https://www.norsk-tipping.no/grasrotandelen/mottaker/994938924)
- [Brønnøysundregistrene](https://virksomhet.brreg.no/nb/oppslag/enheter/994938924)
- [Development work notes](https://etherpad.minsin.wtf/p/lssk)
