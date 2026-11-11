# Rabattkoder

A WordPress plugin for offers available to club members. A board administrator maintains the offers in WordPress, and members can open `/rabattkoder/` with the range poster's QR code or a shared password.

## First setup

1. Upload and activate the plugin on the development site. It serves `/rabattkoder/` directly, so reserve that URL and confirm the web server routes it to WordPress. Use HTTPS.
2. Exclude `/rabattkoder/` from any WordPress, reverse proxy, or CDN page cache. Purge any cached copies of that URL. The plugin also sends `Cache-Control: private, no-store`.
3. Open **Rabattkoder → Tilgang og QR-kode**. Set a randomly generated shared password of at least eight characters and distribute it through the club's members' channel. Download the PNG QR code for the poster. The QR is generated in the admin's browser with the bundled MIT-licensed Project Nayuki library. Its token grants access to whoever has the link.
4. Under **Rabattkoder → Legg til tilbud**, enter the company name as the title and add the code. Choose a logo from the Media Library and optionally add a shop link, expiry date, or instructions. Publish the offer to display it to members. Return it to draft to hide it. An offer with an expiry date is displayed through that date in WordPress's configured time zone.
5. Start with a dummy code. Check the QR scan and shared password in signed-out browsers, confirm a fresh private window never shows the dummy code without access, and repeat the checks after warming the page through the actual proxy and cache. Check that an edit to the dummy offer is visible through the same poster link.

## Rotation and operations

The WordPress dashboard widget **Rabattkoder som snart utløper** shows administrators active codes expiring within 30 days, with the earliest expiry first and a link to edit each offer.

After updating from version 0.1.2 or older, download a new QR image for /rabattkoder/. The previous URL, /medlemsfordeler/, is available for a public WordPress page.

For a branded poster, download **Last ned QR-kode med logo (PNG)**. The preview starts with the WordPress site icon or theme logo. **Velg QR-logo** saves an image from the Media Library for future QR codes and posters. **Fjern QR-logo** saves the plain QR preference. Both PNGs encode the same access link. Scan a printed copy before posting it.

**Last ned plakat (PNG)** combines the selected QR image, page address and shared password in one portrait image. Reveal the current password to fill the poster field automatically, or type it into the poster field. The typed value is used by the browser to draw the poster.

At membership renewal, use **Bytt passord og QR-kode**, then download the new QR and replace the poster. Saving a different password invalidates the previous password, QR link, and existing browser sessions. Sessions last up to 30 days, and members can end their browser session with **Logg ut**. Updating an individual discount code leaves access in place.

Administrators can reveal the current shared password. For passwords saved before encryption was enabled, enter the existing password under **Gjeldende fellespassord** and choose **Lagre gjeldende passord** to enable reveal while retaining the QR link. This feature requires OpenSSL with AES-256-GCM and a dedicated `LSSK_BENEFITS_ENCRYPTION_KEY` of at least 32 characters in `wp-config.php`. Keep the key in the server configuration and its protected backups. The plugin also supports the WordPress `SECURE_AUTH_KEY` for existing installations. The rotation history records dates and administrators. When rotating, the previous encrypted copy is replaced.

The public URL serves a standalone page with local assets. Offer records have an admin UI and are excluded from public queries and the WordPress REST API. Use only company artwork in the Media Library and enter discount codes in the offer editor. When cloning production to dev, remove live offers or restrict access before bringing dev online, and configure separate development credentials.

The plugin follows the repository's MIT license. The QR encoder retains its upstream MIT notice in `assets/qrcodegen.js`.
