# LSSK email — current setup and expansion plan

**Status:** Purelymail and TiboMail Free running in production; board access not yet issued. Shared multi-user workflow still needs validation.  
**Updated:** 2026-11-11  
**Website:** https://lssk.no  
**Production location:** Lab4 / OSL3 (reported current location; older repository environment docs may still reference Lab2 and should be reconciled separately).

## Goal

Offer LSSK's board a transparent, durable shared club email address without requiring board members to manage multiple systems. Start small and add personal club addresses only when needed. Keep recurring costs and operational maintenance low.

## Phase 1: shared club mailbox (in production; board onboarding pending)

- **Selected:** `post@lssk.no` on [Purelymail](https://purelymail.com/) as the club's real shared mailbox, with [TiboMail Free](https://wordpress.org/plugins/tibomail/) installed in the Lab4 production WordPress site. Current annual pricing and quotas should still be verified in the billing portal.
- **Every authorized board member** should be able to read both Inbox and the **same server-side Sent folder**, and send/reply **as `post@lssk.no`**.
- Keep correspondence in the organizational mailbox, not forwarded exclusively into individual private inboxes. Use a common IMAP Sent folder and test that every reply is actually stored there.
- Prefer personal signatures identifying the responding board member (e.g., name and role), while the From address remains `post@lssk.no`.
- Archive instead of routinely deleting correspondence. Establish sensible retention, privacy and removal-of-access policies.
- A shared Inbox/Sent history provides visibility but is **not** a true assignment/status workflow; in the future consider labels, ownership, answered/open indicators and collision prevention.

## Current user interface: WordPress + TiboMail Free

- **Deployed:** TiboMail Free is already running on production WordPress at Lab4; the administration team **does not yet have login access**. This is not evidence that multi-user sharing and permission boundaries have been tested.
- Desired outcome: board members log into **their own** WordPress accounts and find the club mailbox in the existing administration UI.
- **Current access preference:** The club values transparency; trusted board administrators may be granted full WordPress Administrator access if appropriate for their wider duties. No need to create a custom mail-only role just for this requirement. However, full Administrator permission includes plugin/theme/settings/user management and carries substantial site-wide risk; use individual accounts, MFA and backups. If a future less-privileged role is desired, evaluate TiboMail's `tibomail_capability` filter.
- **Before onboarding the board:** verify two distinct users can read, reply, compose, see shared Sent and attachments, and that unintended users cannot access the mailbox (including via REST/AJAX routes). Menu visibility alone does not enforce authorization.
- Determine whether configured IMAP/SMTP credentials are shared safely server-side, whether sessions are isolated, whether each mailbox's permissions can be restricted, and whether sending is correctly recorded to the shared IMAP Sent folder.
- TiboMail is a relatively new plugin. Continue monitoring maintenance, security and compatibility and retain provider webmail as a fallback. Do not claim per-user mailbox isolation or team workflow functionality without a successful test.
- If TiboMail cannot enforce secure board-only access for the intended user group, reassess permissions or consider a dedicated team inbox; never share a WordPress login.

## Phase 2: optional personal addresses

Offer `firstname@lssk.no` on request, initially choosing **per person** between:

| Option | Incoming email | Reply identity | Interface |
| --- | --- | --- | --- |
| Forwarding alias | Forwards to the person's existing private address | Requires separately configured authenticated `@lssk.no` sending; otherwise replies expose private address | Existing personal mailbox |
| Real mailbox | Stored on the mail provider | Native `firstname@lssk.no` via SMTP | Provider webmail/IMAP client or future separate webmail |

For regular club correspondence, favor real mailboxes; for receive-only/occasional usage, aliases can suffice. Confirm forwarding behavior and anti-spam handling (e.g., SPF/DMARC and forwarding) before adopting it.

Do **not** put personal mailboxes in the shared WordPress interface unless the chosen plugin proves per-user mailbox authorization. Personal correspondence should remain inaccessible to other board members by default.

## Optional future separate webmail

Start with the email provider's hosted webmail for personal accounts. Only if useful, consider [Roundcube](https://roundcube.net/) at `mail.lssk.no` hosted in Lab4; that would be a mail **client**, not the SMTP/IMAP server. Avoid SquirrelMail for a new deployment unless its maintenance/security posture is independently reassessed. A Cloudflare Tunnel may provide web access, but IMAP/SMTP client connectivity and login/SSO still need separate planning.

## Mail routing and DNS

1. Verify `lssk.no` domain/DNS management and any **existing** mail service before changing records. Preserve existing addresses and flows.
2. Configure the selected provider's **MX**, **SPF**, **DKIM** and **DMARC** records as instructed; avoid duplicate conflicting SPF TXT records. Start DMARC cautiously and monitor.
3. Ensure WordPress contact forms and transactional messages use an authenticated SMTP sender with suitable From/Reply-To handling; avoid confusing website-originated automated mail with human replies.
4. Mail hosting remains external to the Lab4 WordPress LXC. Back up WordPress config separately from mailbox data. Arrange an export/backup/retention strategy with the email provider; IMAP is not a backup.
5. Never commit credentials, mailbox content, SMTP passwords, personal data or private configuration into this public repository.

## Remaining verification and onboarding checklist (production is already in use)

- [x] Selected Purelymail and TiboMail Free as the phase-one stack; deployed in production (reported by site maintainer).
- [ ] Confirm current subscription pricing, limits and billing ownership.
- [ ] Inventory current DNS, mailboxes and website-form senders before MX changes.
- [ ] Confirm receiving and sending external messages, including replies and attachments.
- [ ] Confirm both Inbox and Sent are **shared and synchronized** across two distinct board WordPress accounts and provider webmail **before granting board access**.
- [ ] Confirm non-board WordPress users cannot access mailbox UI **or APIs**; if choosing full Administrator access, acknowledge broad permissions and verify only trusted board members receive them.
- [ ] Confirm board users cannot access other people's private mailboxes if any are added.
- [ ] Confirm two users replying simultaneously do not cause silent loss/duplicate confusion; decide whether a manual workflow is initially enough.
- [ ] Enforce individual accounts, strong passwords/MFA where possible, TLS connections and revocation when a board member leaves.
- [ ] Verify spam handling, outgoing domain alignment (SPF/DKIM/DMARC), and delivery to major recipients.
- [ ] Test recovery/export, account handover and correspondence retention under appropriate GDPR/privacy policies.
- [ ] Document operational owner, vendor access, billing renewal and recovery credentials **outside Git**.
- [ ] After validation, issue **individual WordPress accounts** and onboard the administration team; no shared credentials.

## Open decisions

- Which people/roles receive access to the shared mailbox? All sitting board members is the proposed default; onboarding has not started.
- Does the board need a formal ticket-like workflow (assigned/open/answered), or is a shared Sent folder enough initially?
- Does TiboMail Free securely support the specific multi-user workflow? **Unverified pending two-user production-safe test.**
- Which personal addresses should be aliases versus independent accounts?
- Is `mail.lssk.no` needed, or is provider webmail sufficient?
- Confirm current Lab4 production details and update historical repository documentation in a separate infrastructure change.

## WordPress administration menu

The site already uses **Admin Menu Editor** to simplify WordPress navigation; no new menu-management plugin is needed. WooCommerce is installed as a theme dependency, not as an active store: hide the unused WooCommerce/Products/Payments/Marketing menu entries without uninstalling the dependency. Keep TiboMail easy to find for the board; retain access to technical settings for trusted administrators.

**Notater** is a custom WordPress admin-menu hyperlink to the independent historical project Etherpad at https://etherpad.minsin.wtf/p/lssk — **not** a WordPress notes plugin or an LSSK-hosted service. The Etherpad was established before the domain was purchased to collect ideas, plans and informal progress; it may contain unfinished tasks and outdated infrastructure notes. Preserve it as historical material and verify any outstanding items before treating them as active. Rename or reposition its menu link if desired; do not remove data.
