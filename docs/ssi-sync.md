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

Version 0.1.0 was created on 21 October 2026 and installed on development by the user. Version **0.2.0 was installed and activated on production lssk.no on 10 November 2026**, independently of the old development environment. Production had no SSI plugin before this fresh installation. The supplied API key was saved without exposing it in HTML, screenshots or the repository. Organization defaults to 60, Europe/Oslo, 120-minute fallback duration; automatic sync/publication are off. The terms acknowledgement and stored API/access/refresh credentials are now configured. Live Preview at 2026-11-10 22:23:11 UTC received 37 events and excluded all 37 through the public-visibility check; no entries changed. The previous sync report at 22:00:12 UTC reported created 0, updated 0, unchanged 37 and non-public skipped 37; the Events list was empty during review. Do not treat the unchanged counter as proof of an import. Automatic publication was found enabled during the calendar review and explicitly disabled; hourly synchronization remains disabled. The public homepage and the HTTPS administration form loaded correctly after activation.

The new form exchanges an SSI email/password for access and refresh tokens. Neither the email nor the password is saved by the plugin. The account holder enters their password directly on production; there is no need to share it with another administrator. Manual token entry remains supported. A key-only organization read in SSI's interface returned `User must be authenticated`; all queried organization/event fields and argument types match the current schema. The configured key belongs to the club's responsible person; a closed key on a different user's profile does not establish that this configured key is closed. The owner's supplied screenshot showed a weekday-enabled key; arrange seven-day approval with SSI before relying on weekend syncing.

There is no calendar widget prerequisite for syncing. Entries can be inspected directly in The Events Calendar. Successful sign-in/preview is still required before enabling unattended publication.

## Published calendar and current blocker (10 November 2026)

- Public landing page: https://lssk.no/kalender/, WordPress page **5197**. Inspected as an empty Elementor layout before editing; it now contains one Elementor HTML widget with The Events Calendar native embed **5646**, URL https://lssk.no/calendar-embed/Yv2UoLHPAb3/embed/. Maintain page content in Elementor and the calendar under Events > Calendar Embeds.
- Retained the club banner photograph, Header Style 4 and Footer Style 4. The theme's Event List widget is a manually entered card with demo defaults, not the live calendar, and was not published. The parent theme has a legacy single-event override; real event-detail rendering is still untested.
- Main and footer calendar links already point to /kalender/ and were verified. Keep native /events/ routes available for embedded navigation; do not redirect them wholesale. The standalone archive uses different theme defaults and is not the public landing page.
- Site language changed from English to Norsk bokmål (nb_NO). Site and SSI time zones are Europe/Oslo; existing 24-hour time, numeric dates and Monday week start retained. Small gettext/gettext_with_context filters in firerangers-child/functions.php complete the empty-day message and Home breadcrumb. The active theme is outside this repository.
- WPFront Notification Bar > Custom CSS hides the notice only on body.single-tec_calendar_embed: #wpfront-notification-bar-spacer, #wpfront-notification-bar and #wpfront-notification-bar-open-button. The outer site's notice remains. TEC Month View Cache was disabled during localization verification. No permanent child-theme stylesheet change remains.
- Verified one published calendar iframe, Norwegian empty-state text, public next-month navigation, retained header/footer, and Elementor mobile preview at 343px document/scroll width. The browser viewport override did not reliably resize the public tab, so mobile checking used Elementor. No signed-out or real-event detail test was completed. No demo/fabricated events were created.

### Why there are no events yet

The importer currently accepts only an English visibility display label beginning with Public. SSI's API documentation says display labels can be localized; raw values and choices are available separately. The 37 skipped records are not proof that all source events are private.

The signed-in SSI organization page for organization 60 shows **Lofoten Cold Trigger**, event **22/30342**, on **28 November 2026 at 10:00 Europe/Oslo**, at Slydalen Skytbane. Its event page explicitly says: “Synliggjøring av informasjon er begrenset tilgang; synlig og søkbar, men kun detaljer og navn på deltakerne.” This differs from the importer's strict Public-only rule. The other 36 events were not individually checked.

The browser's SSI session does not by itself authenticate the GraphQL interface: a read-only event query returned User must be authenticated. Do not copy stored WordPress credentials or ask for passwords in chat. The configured WordPress integration did receive events successfully, but its exact raw visibility-code/display-label mapping remains to be verified through an authenticated API preview.

Before importing, agree whether the website should include basic event information from SSI's limited-visible/searchable category. If so, verify its exact raw code and meaning, implement an explicit allowlist limited to the approved fields, and keep private/unknown visibility excluded. Then preview real names/times, import as drafts, test event details and duplicate handling, and publish only reviewed entries. Automatic publication and hourly synchronization remain **off** until verified.

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


## Verified live import after approved visibility change

This section supersedes the earlier import blocker above. The user explicitly approved publishing only basic information (name, date/time, venue and SSI link) for SSI's visible/searchable restricted category.

- The authenticated WordPress integration verified event **22/30342** has raw visibility **res**, display label **Restricted; visible, searchable but details/names only participants**. Event_Mapper::is_public now explicitly accepts this verified raw code in addition to the existing Public-label rule. Unknown/private entries remain excluded.
- Source URLs returned by SSI can omit the scheme or use legacy paths. The mapper now validates an exact SSI host or a relative SSI path, rejects foreign hosts, user/password, ports, query strings and fragments, then constructs a canonical HTTPS event URL from validated numeric content type and event ID. It does not publish signed/opaque source paths.
- Imported content is limited to the approved fields and a Norwegian end-time estimate notice when needed. SSI free-form descriptions, participant names/results and status paragraphs are not imported into public content. Cancellation checking remains in the synchronization plan.
- Preview: **37 received, 15 eligible, 22 excluded**. First sync created **15 drafts**. A second draft sync created/updated **0** and left **37 unchanged** (including the 22 exclusions); no duplicate posts appeared.
- After checking the real Cold Trigger title, venue and **28 November 2026 10:00 Europe/Oslo** against SSI, publication was enabled and a manual sync at **2026-11-10 23:02:53 UTC** updated the **15** eligible entries to published. WordPress IDs **5658–5672**. Cold Trigger is **5672**, https://lssk.no/event/lofoten-cold-trigger/. Fourteen eligible entries are earlier in 2026; Cold Trigger is the one upcoming event in this response.
- SSI provides no Cold Trigger end time. **12:00 is the configured 120-minute fallback**, not a verified finish time; the event description discloses the estimate.
- **Publication on manual sync is enabled; hourly synchronization remains disabled.** This does not run unattended imports. Keep the weekend/API-key approval issue from the earlier deployment notes in mind before scheduling. Never run Sync now with publication disabled unless intentionally returning owned entries to draft.
- A live template fault was fixed in the parent theme's **tribe-events/single-event.php**: guard the optional Tribe__Tickets__Tickets class with class_exists and use an empty ticket array when unavailable. Event Tickets was not installed. This avoids a critical error when following an event.
- The same parent template now shows Norwegian headings and full date format j. F Y, removes the template's inferred Available/Unavailable badge, and omits the empty organizer contact box. These are direct parent-theme changes: preserve/reapply them after a theme update or migrate the override to the child theme before updating.
- Firerangers Options > Footer Setting now defaults to **Footer Style 4**, so single events and the native archive use the existing club footer rather than theme demo widgets. Existing page overrides and the calendar's club photo/header/footer remain.
- TEC Date & Time display formats: **j. F Y**, **j. F**, **F Y**, compact **15.01.2026** option, date/time separator **kl.**. Site and imported event time zones remain Europe/Oslo.
- Verified the published calendar contains Cold Trigger on 28 November, and clicking its link opens a separate working event tab. The event shows the canonical SSI link, Slydalen Skytbane, Norwegian copy and club footer. Elementor mobile preview at 343px supports selecting the marked 28th and then displays the event without horizontal overflow. The public tab was checked while signed in; a signed-out check was not completed.
- Regression cases were added to tests/run.php for res with English/localized labels, excluded private/unknown codes, relative and scheme-less SSI links, foreign-host rejection and omission of participant/free-text data. **The standalone PHP suite was not run in this workspace because PHP is unavailable**; live preview, draft round-trip, duplicate check, publication and frontend checks passed. Do not report the fixture suite as passing without running it in a PHP environment.
- The live Event Mapper changes are committed in this repository. **The old 0.2.0 ZIP has not been regenerated** and does not contain these fixes. Do not overwrite the patched mapper with that archive; build a new versioned package from current source before a reinstall or migration.
