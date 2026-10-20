# Website content and layout status

Updated: 2026-10-20. Changes were made through the signed-in WordPress UI on lssk.no. The active theme and database are not stored in this repository.

## Editing locations

| Area | Source of truth |
| --- | --- |
| Homepage | Elementor page 193 |
| Training | Service post 3320, classic editor and Firerangers settings |
| Footer | Appearance > Widgets > Footer Widget Four; Footer Four Menu |
| Responsive adjustments | Appearance > Customize > Additional CSS |

## Homepage and footer

Homepage copy covers fixed training times, beginner visits, safety courses and club firearms for beginners. Own club photos replace the main background and four information-card images. The portrait beside the introduction is 20260702_222513; the decorative target is retained.

Footer Four Menu links to the map, training/course anchors, calendar, club information, email and membership. NSF, Min idrett, NIF and DSSN logos are linked, with four columns on desktop and two on mobile. Mobile CSS addresses heading overflow, footer width, logo contrast and spacing.

## Training page

URL: https://lssk.no/service/range-services/

The existing URL is retained for the current menu link. Post title: Trening. Banner: Trening i Slydalen. Uses Header Style 4, Footer Style 4, no sidebar and the club range photo IMG_20260508_184728 as banner background.

Confirmed content: NSF Thursday and DSSN Friday, both 18:00-21:00; visitors may drop in but should preferably contact the club first; beginner club-firearm loans; several courses per year; address, map, contact and membership links. No unconfirmed dates, prices or entry requirements were added.

The legacy Service template still renders demo repeaters when section headings are empty. Page-scoped Additional CSS hides .service-details-premier-services on .postid-3320. Demo repeater values remain in the admin database and should be removed during a template refactor. Visible training content is maintained in the classic editor. Keep the hiding rule until the template stops outputting those demo sections.

## Remaining work

- Complete other content pages and audit main/header menus: some still point to demo content or #.
- Main-menu Calendar still points to /our-events/ and should use /events/. Homepage card and Footer Four Menu already use /events/.
- Add an approved privacy page and replace the placeholder privacy link.
- Calendar currently has no upcoming events.
- Recheck real-device mobile appearance after future theme/header changes.
- Consider moving page-specific CSS and Service markup into the child theme during later code maintenance.
