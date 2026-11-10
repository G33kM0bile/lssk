# Validation

## 0.2.0 — 10 November 2026

- All **54 standalone behavioral checks passed** on official PHP 8.5.12 for Windows.
- All plugin and test PHP files passed syntax checks.
- Added fixtures verify exact password whitespace/punctuation handling, GraphQL variables, omission of old JWT during sign-in, token persistence with API-key retention, no stored email/password, unchanged events/scheduling/publication, failed/incomplete login responses, lock cleanup, HTTPS/admin requirements, per-admin throttling and input validation.
- Confirmed the exact live SSI response `User must be authenticated` is now classified as missing authentication, rather than a query/schema rejection. HTTP 403 guidance never includes upstream response text.
- Rechecked `organization(id: String!)`, `events(year: Int)` and every requested event field in SSI's authenticated schema. Schema compatibility does not prove API-key approval or account authorization.
- No successful live token exchange or event import was performed for this release during these local fixtures. The account holder must connect and run Preview on the target site before enabling imports or scheduling.

## 0.1.0 — 21 October 2026

- PHP 8.3.35: all plugin and test PHP files pass syntax checks.
- Standalone behavior suite: **37 checks passed**.
- Disposable real WordPress integration: **12 checks passed** on **WordPress 7.1.3**, **The Events Calendar 6.18.2**, and PHP 8.3.35, using the WordPress Performance Team's SQLite Database Integration 3.1.0 (drop-in 1.8.0).
- Real integration exercised: preview, draft creation, ownership metadata, Oslo time conversion, duplicate prevention, ORM updates with title punctuation, rescheduling to a winter date, publishing an existing draft, unpublishing a private source event, admin page rendering without a credential leak, cron scheduling, and deactivation cleanup.
- The real integration check caught and resolved TEC event-query filtering during source lookup, the need to include drafts in scoped ORM updates, and WordPress's slashed-input requirement.
- SSI's event/organization fields and refresh-token mutation argument types were inspected in its authenticated GraphQL schema.

The real WordPress test used **intercepted fixture SSI responses**, not a live SSI API call. No real SSI credentials were used. Neither dev.lssk.no nor lssk.no was modified. The database compatibility check used SQLite, not the production database engine.

Still required on development: configure credentials, run a real API preview, confirm public visibility labels and expected event counts, verify draft entries in the site's actual theme/calendar, and then enable publishing/scheduling if desired.

Reproduce the standalone tests with `php tests/run.php`. `tests/wordpress-smoke.php` is a development-only script that requires a disposable-installation marker and a preconfigured WordPress/TEC installation. For its initial install run, add `--install`; run again without that flag to test normal plugin loading. It resets only its tagged fixture event in that disposable database.
