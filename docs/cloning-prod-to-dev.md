# Clone production to development

This documents the procedure used to create CT 105 from production CT 124.

## 1. Rename the cloned container

On the Proxmox host:

```bash
pct set 105 -hostname wordpress-dev
pct reboot 105
pct enter 105
```

## 2. Confirm the WordPress path

```bash
find /var/www -name wp-config.php -type f 2>/dev/null
```

Expected:

```text
/var/www/html/wordpress/wp-config.php
```

## 3. Install WP-CLI if needed

```bash
cd /tmp
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
php wp-cli.phar --info
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
ln -s /usr/local/bin/wp /usr/bin/wp
hash -r
```

## 4. Back up the database

```bash
wp --path=/var/www/html/wordpress \
  db export /root/wordpress-dev-before-domain-change.sql \
  --allow-root
```

Do not commit the database dump.

## 5. Change WordPress URLs

```bash
wp --path=/var/www/html/wordpress option update home 'https://dev.lssk.no' --allow-root
wp --path=/var/www/html/wordpress option update siteurl 'https://dev.lssk.no' --allow-root
```

## 6. Replace production URLs

Dry-run first:

```bash
wp --path=/var/www/html/wordpress \
  search-replace 'https://lssk.no' 'https://dev.lssk.no' \
  --all-tables-with-prefix \
  --skip-columns=guid \
  --precise \
  --dry-run \
  --allow-root
```

Then execute the same command without `--dry-run`.

Do not rewrite `wp_posts.guid`.

## 7. Set development environment

```bash
wp --path=/var/www/html/wordpress \
  config set WP_ENVIRONMENT_TYPE development \
  --type=constant \
  --allow-root
```

## 8. Disable indexing

```bash
wp --path=/var/www/html/wordpress option update blog_public 0 --allow-root
```

## 9. Flush caches and rewrites

```bash
wp --path=/var/www/html/wordpress cache flush --allow-root
wp --path=/var/www/html/wordpress rewrite flush --allow-root
```

## 10. Historical Elementor log

During the original clone, the remaining non-GUID production URL was found only in `elementor_log`. After confirming it contained historical diagnostic data, it was deleted:

```bash
wp --path=/var/www/html/wordpress option delete elementor_log --allow-root
```

Elementor generated files were then cleared/regenerated from the WordPress admin UI.

## 11. Apache development vhost

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

Validate and reload:

```bash
apache2ctl configtest
systemctl reload apache2
apache2ctl -S
```

## 12. Verify reverse-proxy HTTPS detection

```bash
curl -I \
  -H 'Host: dev.lssk.no' \
  -H 'X-Forwarded-Proto: https' \
  http://127.0.0.1/
```

A normal page response is expected. A redirect back to the same HTTPS URL can indicate incorrect proxy HTTPS detection.
