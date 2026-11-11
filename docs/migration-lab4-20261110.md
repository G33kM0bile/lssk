# WordPress migration to Lab4 — 2026-11-10

| Site | Current host | Change |
| --- | --- | --- |
| [lssk.no](https://lssk.no) | Lab4 / OSL3, node `pve`, CT `804` | Production cutover on 2026-11-10 |
| [dev.lssk.no](https://dev.lssk.no) | Lab2 Delta, CT `105` | Refreshed on 2026-11-11 |

The stop-mode backup `ct/124/2026-11-10T17:52:25Z` was protected and verified on Lab2 PBS with zero errors. A copy was transferred to Lab4 PBS and restored as CT `804`. WordPress and its MariaDB database were restored together. The new production guest starts automatically and is included in Lab4's recurring backup job because it is outside the exclusion list.

Cloudflare now routes the production hostname to Lab4. A public probe appeared in CT `804`'s Apache access log, and the production homepage returned HTTP 200. Both HTTP and HTTPS requests to `www.lssk.no` redirect to `https://lssk.no` with their paths and query strings preserved.

Lab2 Delta CT `124` was shut down and retained for rollback, with automatic startup disabled. Before moving traffic back, reconcile any writes made on Lab4 since cutover. Confirm the site before operating CT `124`: on Lab4 that ID belongs to Etherpad.

The verified pre-cutover backup was also used to overwrite Lab2 CT `105`. See the [development notes](development.md#refresh-on-2026-11-11) for the configuration and checks.
