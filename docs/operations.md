# Operations

## Apache checks

```bash
apache2ctl configtest
apache2ctl -S
```

Reload Apache:

```bash
systemctl reload apache2
```

Check listeners:

```bash
ss -ltnp | grep -E ':(80|443)\s'
```

## Development WordPress URLs

```bash
wp --path=/var/www/html/wordpress option get home --allow-root
wp --path=/var/www/html/wordpress option get siteurl --allow-root
```

Expected:

```text
https://dev.lssk.no
https://dev.lssk.no
```

## Reverse-proxy test

```bash
curl -I \
  -H 'Host: dev.lssk.no' \
  -H 'X-Forwarded-Proto: https' \
  http://127.0.0.1/
```

## Backups

Take a database export before migrations or bulk URL changes.

Do not commit database exports or backup archives to this repository.

## robots.txt

WordPress may provide a virtual `robots.txt` when no physical file exists.

Development also has `blog_public=0`, which is a crawler hint rather than an access-control boundary.

## security.txt

A public `/.well-known/security.txt` is planned. It should contain a monitored contact and no sensitive information.
