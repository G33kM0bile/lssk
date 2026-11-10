# Shoot'n Score It calendar synchronization

LSSK SSI Sync imports public events from [SSI organization 60](https://shootnscoreit.com/organization/60/) into the site's existing The Events Calendar plugin. SSI remains responsible for registration; imported entries link back to SSI.

## Files

- [Plugin source and complete setup guide](../plugins/lssk-ssi-sync/README.md)
- [Installable version 0.2.0 ZIP](../dist/lssk-ssi-sync-0.2.0.zip?raw=true)
- [Validation results](../plugins/lssk-ssi-sync/VALIDATION.md)
- [Standalone behavioral tests](../plugins/lssk-ssi-sync/tests/run.php)
- [Disposable WordPress integration test](../plugins/lssk-ssi-sync/tests/wordpress-smoke.php)

0.2.0 ZIP SHA-256: `0267fd49ecda1c57b5b9fb2ed3d57aec08f076c06f37b5854dbc586146446fd0`.

## Deployment status

Version 0.1.0 was created on 21 October 2026 and installed on development by the user. Version **0.2.0 was installed and activated on production lssk.no on 10 November 2026**, independently of the old development environment. Production had no SSI plugin before this fresh installation. The supplied API key was saved without exposing it in HTML, screenshots or the repository. Organization defaults to 60, Europe/Oslo, 120-minute fallback duration; automatic sync/publication are off. The SSI account holder still needs to acknowledge the API terms and use **Connect to SSI**, then run Preview. No real account token exchange or event import has been confirmed. The public homepage and the HTTPS administration form loaded correctly after activation.

The new form exchanges an SSI email/password for access and refresh tokens. Neither the email nor the password is saved by the plugin. The account holder enters their password directly on production; there is no need to share it with another administrator. Manual token entry remains supported. A key-only organization read in SSI's interface returned `User must be authenticated`; all queried organization/event fields and argument types match the current schema. The configured key belongs to the club's responsible person; a closed key on a different user's profile does not establish that this configured key is closed. The owner's supplied screenshot showed a weekday-enabled key; arrange seven-day approval with SSI before relying on weekend syncing.

There is no calendar widget prerequisite for syncing. Entries can be inspected directly in The Events Calendar. Successful sign-in/preview is still required before enabling unattended publication.

## Development first

1. On **dev.lssk.no**, upload the ZIP under Plugins and activate it alongside The Events Calendar.
2. Open **Settings → LSSK SSI Sync**. Defaults are organization 60, Europe/Oslo, no hourly schedule, and no automatic publication.
3. Review [SSI API documentation and its developer agreement](https://shootnscoreit.com/about-the-api/). Save an API key and the terms acknowledgement, then let the account holder use **Connect to SSI** to generate tokens directly. The full guide explains the authentication flow and manual-token alternative. Never add real credentials or wp-config.php to this repository.
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
