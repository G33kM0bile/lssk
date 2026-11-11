# Architecture

## Platform

The site runs in an unprivileged Debian 13 LXC on Proxmox VE. The original WordPress container was created with the Community Scripts WordPress installer:

https://community-scripts.org/scripts/wordpress

Production baseline:

- Proxmox: Lab4 / OSL3, node `pve`, CT `804`
- Debian 13
- Unprivileged LXC
- 2 vCPU
- 8192 MiB RAM, 1024 MiB swap
- 5 GB disk
- Bridge: `vmbr2`
- IPv4: DHCP
- IPv6: auto
- Timezone: `Europe/Oslo`
- Nesting: enabled
- Keyctl: enabled
- FUSE: disabled

Development runs on Lab2 Delta CT `105`. Production and development each have their own local MariaDB database.

## Request flow

Production:

```text
Browser
  ↓ HTTPS
Cloudflare Tunnel (Lab4 connector CT 103)
  ↓ HTTP
Lab4 CT 804
  ↓
Apache
  ↓
/var/www/html/wordpress
  ↓
WordPress
```

`dev.lssk.no` follows the same pattern through the Lab2 tunnel to Delta CT `105`.

TLS is terminated by the upstream reverse proxy. WordPress correctly recognizes HTTPS when the proxy sends:

```text
X-Forwarded-Proto: https
```

## Apache

WordPress document root:

```text
/var/www/html/wordpress
```

Development Apache vhost:

```apache
<VirtualHost *:80>
    ServerName dev.lssk.no
    DocumentRoot /var/www/html/wordpress

    <Directory /var/www/html/wordpress>
        AllowOverride All
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

## Security note

This is a public repository. Do not add:

- private IP addresses unless there is a strong reason
- passwords or tokens
- SMTP credentials
- API keys
- WordPress salts
- TLS private keys
- database exports
- backup archives
