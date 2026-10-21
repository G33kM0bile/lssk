# Website content and layout status

Updated: 2026-10-21. Changes were made through the signed-in WordPress UI on lssk.no. The active theme and database are not stored in this repository.

## Editing locations

| Area | Source of truth |
| --- | --- |
| Homepage | Elementor page 193 |
| Training | Service post 3320, classic editor and Firerangers settings |
| Disciplines | Pages 1109 (NSF, /training/) and 1118 (DSSN, /dssn/), both maintained in one Elementor HTML widget per page; Firerangers banner settings |
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

The bottom demo photograph (featured image) was removed from the Training service at the user’s request.

## Discipline pages

NSF and DSSN pages now use original Norwegian copy, training times, beginner information, club contacts, links to training and official federation resources, and cross-links between disciplines. NSF (page 1109) is now maintained in an Elementor HTML widget containing the complete `.lssk-discipline` markup. Its old Elementor demo events were removed and the replacement published, so opening Elementor no longer reactivates demo content. DSSN (page 1118) was migrated on 2026-10-21 using the same approach. Its old Walk in grid and Membership demo containers were removed, and the complete existing Norwegian markup and club photograph were preserved in one Elementor HTML widget. The replacement was published and verified after reopening Elementor. Maintain both discipline pages in Elementor; do not replace their content separately in the WordPress block editor.

The `.lssk-discipline` styles in Additional CSS provide a two-column desktop introduction, single-column mobile layout and training information card. The NSF banner title and the Main Menu label were corrected (NSSF -> NSF). Demo banner images were removed. NSF now uses the dedicated standplass 7 photo uploaded on 2026-10-21 (20260820_194724-scaled.jpg), replacing the temporary winter photograph. The existing DSSN activity photo is retained.

## Course and beginner contact

Mads is the contact for general safety courses and visitors who want to try pistol shooting (confirmed by the user on 2026-10-21): phone 970 63 150, email mads-johannessen@hotmail.com. Updated homepage page 193 (beginner slider, beginner card, course card, and safety-course/club-firearm section), Training service 3320, and NSF page 1109. Telephone and email links use Mads’s contact details. DSSN page 1118 also identifies Mads for general safety courses and trying pistol shooting; Kjetil remains its contact for dynamic shooting/IPSC. General club header/footer contacts remain unchanged. Preserve this distinction in future edits.

## New range photographs

On 2026-10-21, two original club photos were added: attachment 5488 / 20260820_194724-scaled.jpg shows standplass 7 and the pistol targets; attachment 5495 / 20260820_194745-scaled.jpg shows the inside of the standplass building. The first replaces the NSF introduction photo in its existing Elementor HTML widget and 4:3 layout. The second is an inline, uncropped portrait figure (maximum width 360px, shrinking on smaller screens) near the location section of Training service 3320; it links to the full-size image. Both have descriptive alt text and captions. Existing course contact details and club design are preserved.

## Remaining work

- Complete other content pages and audit main/header menus: some still point to demo content or #.
- Main-menu Calendar still points to /our-events/ and should use /events/. Homepage card and Footer Four Menu already use /events/.
- Privacy page 5461 is published at /personvernerklaering/ and linked from Footer Bottom Menu Two (item 4900). Technical log and backup retention still require review. The adopted ordinary-email retention policy needs manual monthly follow-up.
- Calendar currently has no upcoming events.
- Recheck real-device mobile appearance after future theme/header changes.
- Consider moving page-specific CSS and Service markup into the child theme during later code maintenance.







NSF menu repair: verified the desktop and mobile Grener > NSF link opens /training/ and displays the current content. Mobile menu logo/contact settings are maintained in FIRERANGERS Options > Mobile Sidebar Settings; use Save Changes there for text fields. Additional CSS handles white logo contrast and long email wrapping.



