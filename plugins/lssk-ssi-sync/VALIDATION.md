# Validation — 21 October 2026

- PHP 8.3.35: all plugin and test PHP files pass syntax checks.
- Standalone behavior suite: **37 checks passed**.
- Disposable real WordPress integration: **12 checks passed** on **WordPress 7.1.3**, **The Events Calendar 6.18.2**, and PHP 8.3.35, using the WordPress Performance Team's SQLite Database Integration 3.1.0 (drop-in 1.8.0).
- Real integration exercised: preview, draft creation, ownership metadata, Oslo time conversion, duplicate prevention, ORM updates with title punctuation, rescheduling to a winter date, publishing an existing draft, unpublishing a private source event, admin page rendering without a credential leak, cron scheduling, and deactivation cleanup.
- The real integration check caught and resolved TEC event-query filtering during source lookup, the need to include drafts in scoped ORM updates, and WordPress's slashed-input requirement.
- SSI's event/organization fields and refresh-token mutation argument types were inspected in its authenticated GraphQL schema.

The real WordPress test used **intercepted fixture SSI responses**, not a live SSI API call. No real SSI credentials were used. Neither dev.lssk.no nor lssk.no was modified. The database compatibility check used SQLite, not the production database engine.

Still required on development: configure credentials, run a real API preview, confirm public visibility labels and expected event counts, verify draft entries in the site's actual theme/calendar, and then enable publishing/scheduling if desired.

Reproduce the standalone tests with `php tests/run.php`. `tests/wordpress-smoke.php` is a development-only script that requires a disposable-installation marker and a preconfigured WordPress/TEC installation. For its initial install run, add `--install`; run again without that flag to test normal plugin loading. It resets only its tagged fixture event in that disposable database.
