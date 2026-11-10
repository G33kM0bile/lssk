# LSSK SSI Sync 0.2.0

An installable WordPress plugin that imports **public events from Shoot'n Score It organization 60** into the existing **The Events Calendar** plugin. SSI remains the source for registration. No paid calendar import add-on is needed.

Requirements: WordPress 6.4+, PHP 8.1+, The Events Calendar, and SSI API access. API use is subject to SSI's developer/API agreement and the account's permissions/subscription. Start on **dev.lssk.no**.

## Installation and first import

1. Go to **Plugins → Add New Plugin → Upload Plugin**. Upload `lssk-ssi-sync-0.2.0.zip`, install, and activate it. Ensure The Events Calendar is active. Back up an existing installation before replacing it. Existing settings and events are retained on upgrade.
2. Open **Settings → LSSK SSI Sync**. Organization ID defaults to **60**, time zone to **Europe/Oslo**. Automatic syncing and publishing are initially off.
3. Open [SSI API documentation](https://shootnscoreit.com/about-the-api/) while logged in. Review its linked developer/API agreement and obtain an API key through its **GraphQL API Keys** link. The account needs permission to read the club and events.
4. Configure the API key. Prefer this in `wp-config.php`, before the “stop editing” line:

   ```php
   define( 'LSSK_SSI_API_KEY', 'YOUR_API_KEY' );
   ```

   Alternatively, enter the key on the plugin settings page. Do not commit real keys, tokens, passwords, or `wp-config.php` to GitHub.
5. Save the API key and SSI terms acknowledgement, then use **Connect to SSI** on this same settings page over HTTPS. The SSI account holder enters their email address and password directly and presses **Connect to SSI**. There is no need to share the password with another administrator or manually copy JWT tokens. The plugin exchanges it with SSI using `token_auth`, saves only the access and refresh tokens, and does not store or repopulate the email/password. A successful connection does not import events or enable scheduling/publication. The account must have permission to read the club's events; an API key alone does not authenticate a user. Retry attempts are limited to one per administrator per minute. Existing tokens are retained if SSI rejects the sign-in or returns incomplete tokens.

   Manual token entry remains available for integrations that already have tokens. SSI's own GraphQL interface also supports this mutation:

   ```graphql
   mutation {
     token_auth(email: "YOUR_EMAIL", password: "YOUR_PASSWORD") {
       success
       token { token }
       refresh_token { token expires_at }
     }
   }
   ```

   For manual entry, paste `token.token` into **JWT access token**, and `refresh_token.token` into **Refresh token**. Clear password-bearing queries from the explorer/editor/history afterwards. Never paste real credentials into documentation, issue reports or chat. SSI returned `User must be authenticated` for a key-only organization read during testing on 10 November 2026; do not assume the API key replaces user authentication.
6. Acknowledge SSI's developer/API terms in the plugin settings once you have reviewed and accepted them. **Save settings**. Saving or activating never fetches SSI events by itself unless you enable the schedule.
7. Click **Preview SSI events**. Confirm the names, local start/end times, and expected public event count. Preview makes no calendar entries, though token refresh can update stored credentials.
8. Click **Sync now**, leaving automatic publishing unchecked. Inspect the resulting drafts under **Events**. Preview them in your theme and calendar.
9. Once verified, select **Publish public SSI events automatically**, save, and **Sync now**. Select **Enable hourly synchronization** when ready. Use the same process on production after testing development.

SSI keys and refresh tokens are sensitive. Database-stored tokens and keys are not encrypted; they use non-autoloaded options and are never populated back into HTML. Restrict database, backup, and admin access. The login password is sent to WordPress over HTTPS and then directly to SSI over verified HTTPS; the plugin never logs or saves it. Server request logging, security plugins and browser password managers must be configured appropriately, since they operate outside the plugin. Blank credential fields retain their current values. **Clear stored credentials** removes all stored values before optionally saving replacements; it does not remove an API key defined in `wp-config.php`. Deactivation stops scheduling and retains configuration. Deleting the plugin removes its options/credentials; remove the configuration constant separately if used.

## Synchronization behavior

- Queries the organization directly for the current and next calendar year in the configured time zone. It imports past and future events within those years; no full historical backfill is provided. At New Year the oldest year is no longer refreshed.
- Uses `(organization ID, SSI content type, SSI event ID)` as a stable source key. Renaming or rescheduling an event updates the same WordPress entry. Changing the organization does not remove imports from the previous organization.
- New events are drafts by default. Draft mode also moves changed or published imported entries back to draft on the next sync. Publication is a deliberate setting. Manually created WordPress events are never matched by title or altered.
- Imports title, start/end, SSI link, venue **as text in the event description**, and SSI status. It does not create separate TEC Venue records or import free-form SSI descriptions, images, competitors, member lists, or results.
- Converts ISO-8601 timestamps with explicit offsets to the configured IANA time zone. Missing end times use a configurable duration (default 120 minutes), explicitly labelled as an estimate. Invalid or ambiguous timestamps stop the import for review.
- Public visibility is checked using SSI's English `get_visibility_display` label beginning with `Public`. Other/missing labels fail closed. This must be verified with a real API preview, particularly if the SSI account uses another language. An integrator can implement the `lssk_ssi_is_public_event` filter using verified SSI visibility codes; never broadly allow unknown values.
- Existing owned events **explicitly returned as non-public** are moved to draft. Events with a status label containing `cancelled`, `canceled`, or `withdrawn` are also moved to draft; new such events are skipped. Other status labels are displayed as supplied. Localized cancellation labels require adapting the integration.
- Events **absent from a response** are retained: SSI might omit private/deleted events, so absence is not reliable proof of deletion. Review these manually. This means an event that becomes private and is then omitted by SSI will not be automatically unpublished.
- Imports in WordPress Trash stay trashed. Restore them manually if you want them synced again. Uninstall retains event posts and their source identifiers, allowing reinstall to reconnect to them.
- SSI-managed title, description, dates, website URL, and status can be overwritten when source data/settings change. Manual edits to these fields are not merged. Other fields are left alone.
- Both API responses and all public event fields are checked before writing calendar entries. HTTP/GraphQL failures retain existing entries. A database failure or the 120-second run budget can leave a partial import; successful entries remain and rerunning resumes safely using stable IDs. Reports record the counts completed before an error.
- Overlapping runs are blocked by an atomic option lock; an expired lock can be recovered after 15 minutes. At most 1,000 source events per run are accepted; preview shows the first 100 public events. This is designed for a small club.
- JWT refresh is attempted once on recognized authentication failures if a refresh token is available. Replacement tokens are persisted immediately. Expired/revoked refresh tokens require new tokens from SSI.

## Scheduling

The schedule uses WordPress's hourly WP-Cron interval and runs only when enabled. WP-Cron depends on site traffic unless a server scheduler triggers it. On a low-traffic site use your existing cron setup or, for example, schedule this as the WordPress service user every five minutes (adjust WP-CLI and the installation path):

```sh
wp --path=/path/to/wordpress cron event run --due-now --quiet
```

If `DISABLE_WP_CRON` is already enabled, an external scheduler is required. Use **Settings → LSSK SSI Sync** for last attempt, last successful sync (UTC), imported counts, and errors. No tokens, passwords, response bodies, or upstream GraphQL error messages are logged by this plugin. WordPress's surrounding server/proxy/debug configuration is outside its control.

## Troubleshooting

- **User must be authenticated / Connect to SSI:** the API key identifies the integration, but SSI still requires an authenticated user. Save the key and terms acknowledgement, then use **Connect to SSI** with the account holder. Do not share their password. A connected account is not proof that organization access is allowed: run Preview next.
- **Authentication failed:** verify the API key and JWT/refresh tokens and that the account has API/event access. The endpoint is `https://shootnscoreit.com/graphql/`; no arbitrary endpoint is configurable and redirects are not followed with credentials. The key owner's API-key page must show an enabled key; status on another user's profile is not evidence about the configured key. Weekday-only keys still need SSI approval for seven-day production operation.
- **GraphQL query rejected:** compare the query and refresh mutation in `includes/class-ssi-api.php` with SSI's live schema. All requested organization/event fields and event-query argument types were rechecked in SSI's authenticated explorer on 10 November 2026. Actual remote authentication and organization permissions must still be verified during setup. The 0.2.0 release recognizes SSI's `User must be authenticated` error as authentication failure rather than a schema rejection.
- **Preview contains no public events:** inspect the account's language, actual visibility labels, organization permissions, and current/next-year source data. Do not disable the public-only check to troubleshoot.
- **Date error:** confirm SSI supplies complete dates and explicit UTC offsets. Invalid data is rejected, rather than silently placing an event on the wrong date.
- **TEC did not save expected data:** check the installed The Events Calendar version and WordPress errors. The plugin verifies persisted fields because TEC documents a possible null ORM `save()` result.
- **Private/cancelled entry remains visible:** review cached pages and whether SSI returned that entry at all. Missing events require manual review, as explained above.

## Validation and maintenance

Run the standalone fixture suite with PHP:

```sh
php tests/run.php
```

It checks mapping, daylight saving offsets, visibility, validation, duplicate prevention, renamed/rescheduled events, draft publication, private/cancelled entries, Trash, incomplete responses, locks, token refresh, and failure recovery. Its WordPress/TEC doubles do not replace a real installation test.

Before production, complete a live preview and draft import with real SSI credentials, verify calendar appearance and times, rerun to check duplicate prevention, and verify the hourly job. Credentials are deliberately not included in this package.

References:

- [SSI API documentation and developer agreement](https://shootnscoreit.com/about-the-api/)
- [TEC event creation API](https://docs.theeventscalendar.com/apis/orm/create/events/)
- [TEC update API](https://docs.theeventscalendar.com/apis/orm/update/)
- [WordPress WP-Cron](https://developer.wordpress.org/plugins/cron/)

License: GPL-2.0-or-later. This is a club integration, not an official SSI or TEC product.

## Changelog

### 0.2.0 — 10 November 2026

- Added administrator-only HTTPS Connect to SSI form; exchange the SSI email/password for tokens without retaining the password or email.
- Kept manual token entry, API-key constants and automatic token refresh compatible with existing installations.
- Recognized SSI's actual missing-user authentication error and added safe guidance for HTTP 403 responses.
- Added login-failure, credential-retention, authorization, HTTPS and throttling fixtures. Default sync/publication settings remain off.
