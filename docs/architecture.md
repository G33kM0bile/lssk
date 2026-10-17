# Architecture

## Platform

The site runs in an unprivileged Debian 13 LXC on Proxmox VE. The original WordPress container was created with the Community Scripts WordPress installer:

https://community-scripts.org/scripts/wordpress

Production baseline:

- Proxmox CT: `124`
- Debian 13
- Unprivileged LXC
- 2 vCPU
- 2048 MiB RAM
- 5 GB disk
- Bridge: `vmbr0`
- IPv4: DHCP
- IPv6: auto
- Timezone: `Europe/Oslo`
- Nesting: enabled
- Keyctl: enabled
- FUSE: disabled

The development clone is CT `105`.

## Request flow

Production:

```text
Browser
  ↓ HTTPS
DNS / reverse proxy
  ↓ HTTP
CT 124
  ↓
Apache
  ↓
/var/www/html/wordpress
  ↓
WordPress
```

Development follows the same pattern, but routes to CT 105.

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
