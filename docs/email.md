# LSSK email — proposed architecture (planning)

**Status:** Proposal only; not deployed.  
**Date:** 2026-11-10  
**Website:** https://lssk.no  
**Production location:** Lab4 / OSL3 (reported current location; older repository environment docs may still reference Lab2 and should be reconciled separately).

## Goal

Offer LSSK's board a transparent, durable shared club email address without requiring board members to manage multiple systems. Start small and add personal club addresses only when needed. Keep recurring costs and operational maintenance low.

## Phase 1: shared club mailbox

- Create **one real mailbox**, `post@lssk.no`, with an external IMAP/SMTP email host (initial candidate: [Purelymail](https://purelymail.com/), historically advertised from about USD 10/year; **verify current prices, quotas, terms and deliverability before purchase**).
- **Every authorized board member** should be able to read both Inbox and the **same server-side Sent folder**, and send/reply **as `post@lssk.no`**.
- Keep correspondence in the organizational mailbox, not forwarded exclusively into individual private inboxes. Use a common IMAP Sent folder and test that every reply is actually stored there.
- Prefer personal signatures identifying the responding board member (e.g., name and role), while the From address remains `post@lssk.no`.
- Archive instead of routinely deleting correspondence. Establish sensible retention, privacy and removal-of-access policies.
- A shared Inbox/Sent history provides visibility but is **not** a true assignment/status workflow; in the future consider labels, ownership, answered/open indicators and collision prevention.

## Proposed user interface: WordPress + TiboMail Free

- **First candidate to evaluate in staging:** [TiboMail](https://wordpress.org/plugins/tibomail/) connecting to the provider via IMAP/SMTP.
- Desired outcome: board members log into **their own** WordPress accounts and find the club mailbox in the existing administration UI.
- WordPress **Administrator** access must not be granted solely to use email. Evaluate a custom `lssk_board_mail` capability/role and TiboMail's documented `tibomail_capability` filter.
- **Do not assume this is sufficient until verified:** test that non-admin board users can read, reply, compose, see Sent and attachments, while normal editors/other users cannot; audit backend REST/AJAX requests, not only menu visibility.
- Determine whether configured IMAP/SMTP credentials are shared safely server-side, whether sessions are isolated, whether each mailbox's permissions can be restricted, and whether sending is correctly recorded to the shared IMAP Sent folder.
- TiboMail is a relatively new plugin. Review maintenance, update history, security, WordPress compatibility and exit/migration options before production. Do not claim per-user mailbox isolation or team workflow functionality without a successful test.
- If TiboMail cannot enforce secure board-only access, consider another suitable team inbox/plugin or a separate webmail interface rather than making everybody an Administrator or sharing a WordPress login.

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

## Minimum acceptance checklist (staging first)

- [ ] Verify pricing, mailbox provisioning, and availability of required IMAP/SMTP functions.
- [ ] Inventory current DNS, mailboxes and website-form senders before MX changes.
- [ ] Confirm receiving and sending external messages, including replies and attachments.
- [ ] Confirm both Inbox and Sent are **shared and synchronized** across two distinct board WordPress accounts and provider webmail.
- [ ] Confirm non-board WordPress users cannot access mailbox UI **or APIs**; verify least-privilege capability filter and no need for Administrator role.
- [ ] Confirm board users cannot access other people's private mailboxes if any are added.
- [ ] Confirm two users replying simultaneously do not cause silent loss/duplicate confusion; decide whether a manual workflow is initially enough.
- [ ] Enforce individual accounts, strong passwords/MFA where possible, TLS connections and revocation when a board member leaves.
- [ ] Verify spam handling, outgoing domain alignment (SPF/DKIM/DMARC), and delivery to major recipients.
- [ ] Test recovery/export, account handover and correspondence retention under appropriate GDPR/privacy policies.
- [ ] Document operational owner, vendor access, billing renewal and recovery credentials **outside Git**.
- [ ] Only then roll out to the production WordPress installation.

## Open decisions

- Which people/roles receive access to the shared mailbox? All sitting board members is the proposed default.
- Does the board need a formal ticket-like workflow (assigned/open/answered), or is a shared Sent folder enough initially?
- Does TiboMail Free securely support the specific multi-user workflow? **Unverified pending staging test.**
- Which personal addresses should be aliases versus independent accounts?
- Is `mail.lssk.no` needed, or is provider webmail sufficient?
- Confirm current Lab4 production details and update historical repository documentation in a separate infrastructure change.
