# Lofoten Sportsskytterklubb – website and club applications

Technical documentation, custom WordPress plugins, and club application source for [Lofoten Sportsskytterklubb](https://lssk.no).

This repository contains documentation, non-sensitive configuration notes, custom WordPress plugins, and the Shot Counter application. Do not commit WordPress database exports, wp-config.php, live application configuration, databases, audio recordings, credentials, API keys, salts, certificates, private keys, or backups.

## Applications

- [Shot Counter](shot-counter/README.md) — offline-first range audio processing and activity dashboard
- [LSSK SSI Sync](plugins/lssk-ssi-sync/README.md) — imports public Shoot'n Score It events into the WordPress calendar.

## Environments

| Environment | Proxmox CT | Hostname | URL | WordPress environment |
|---|---:|---|---|---|
| Production | 124 | `wordpress` | https://lssk.no | `production` |
| Development | 105 | `wordpress-dev` | https://dev.lssk.no | `development` |



Shot Counter development is running on Proxmox host **`lab3`**, container **CT107**, at [skudd.lssk.no](https://skudd.lssk.no/). See [Shot Counter development](shot-counter/README.md#development-environment).

## Documentation

- [Architecture](docs/architecture.md)
- [Production](docs/production.md)
- [Development](docs/development.md)
- [Clone production to development](docs/cloning-prod-to-dev.md)
- [WordPress and theme notes](docs/wordpress.md)
- [Plugins](docs/plugins.md)
- [Shoot'n Score It calendar synchronization](docs/ssi-sync.md)
- [Operations](docs/operations.md)
- [TODO](docs/todo.md)

## Public links used by the site

- [Min idrett – membership](https://www.minidrett.no/medlemskap/27827)
- [Norsk Tipping – Grasrotandelen](https://www.norsk-tipping.no/grasrotandelen/mottaker/994938924)
- [Brønnøysundregistrene](https://virksomhet.brreg.no/nb/oppslag/enheter/994938924)
- [Development work notes](https://etherpad.minsin.wtf/p/lssk)
