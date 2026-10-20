# Shoot'n Score It calendar synchronization

LSSK SSI Sync imports public events from [SSI organization 60](https://shootnscoreit.com/organization/60/) into the site's existing The Events Calendar plugin. SSI remains responsible for registration; imported entries link back to SSI.

## Files

- [Plugin source and complete setup guide](../plugins/lssk-ssi-sync/README.md)
- [Installable version 0.1.0 ZIP](../dist/lssk-ssi-sync-0.1.0.zip?raw=true)
- [Validation results](../plugins/lssk-ssi-sync/VALIDATION.md)
- [Standalone behavioral tests](../plugins/lssk-ssi-sync/tests/run.php)
- [Disposable WordPress integration test](../plugins/lssk-ssi-sync/tests/wordpress-smoke.php)

ZIP SHA-256: `9da2d1213e8f25d73e6b7e9b048800cf748067207ba425f134ad762c7405b4d7`.

## Deployment status

Created and validated locally on 21 October 2026. Neither production nor development has been modified by this work. A live SSI API preview and draft import using real credentials remain to be performed.

## Development first

1. On **dev.lssk.no**, upload the ZIP under Plugins and activate it alongside The Events Calendar.
2. Open **Settings → LSSK SSI Sync**. Defaults are organization 60, Europe/Oslo, no hourly schedule, and no automatic publication.
3. Review [SSI API documentation and its developer agreement](https://shootnscoreit.com/about-the-api/). Configure an API key and the JWT/refresh tokens required by the account. The full guide explains the authentication flow. Never add real credentials or wp-config.php to this repository.
4. Save settings, run **Preview SSI events**, and confirm the expected public event count, names, and local times.
5. Run **Sync now** to create drafts. Check calendar rendering in the actual theme and repeat the sync to verify that entries are updated without duplicates.
6. Enable publication and hourly synchronization only after verification. Follow the same process on production afterwards.

## Behavior to understand before enabling

- Imports the current and next calendar year; stable SSI identifiers keep renamed/rescheduled entries linked.
- Public visibility currently relies on SSI's English display label. Preview must confirm that this matches the account's actual API output.
- Explicitly returned private/cancelled events are moved to draft. Events omitted from SSI responses are retained for manual review, including a formerly public event that SSI no longer returns.
- Venue is included as text in the imported description. Missing end times use a labelled fallback duration.
- HTTP/GraphQL failures preserve existing entries. Database failures or the run budget can leave a partial import; rerunning is safe.
- WP-Cron depends on traffic unless the existing server scheduler invokes due WordPress cron jobs.
- The plugin retains credentials on deactivation and removes stored credentials on uninstall. Imported entries and source identifiers remain.

The plugin's source is GPL-2.0-or-later as declared in its header and guide. It is a club integration rather than an official SSI or TEC product.
