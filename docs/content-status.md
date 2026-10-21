# Website content and layout status

Updated: 2026-10-21. Changes were made through the signed-in WordPress UI on lssk.no. The active theme and database are not stored in this repository.

## Editing locations

| Area | Source of truth |
| --- | --- |
| Homepage | Elementor page 193 |
| Training | Service post 3320, classic editor and Firerangers settings |
| Disciplines | Pages 1109 (NSF, /nsf/) and 1118 (DSSN, /dssn/), both maintained in one Elementor HTML widget per page; Firerangers banner settings |
| Footer | Appearance > Widgets > Footer Widget Four; Footer Four Menu |
| Responsive adjustments | Appearance > Customize > Additional CSS |

## Homepage and footer

Homepage copy covers fixed training times, beginner visits, safety courses and club firearms for beginners. Own club photos replace the main background and four information-card images. The portrait beside the introduction is 20260702_222513; the decorative target is retained.

Footer Four Menu links to the map, training/course anchors, calendar, club information, email and membership. NSF, Min idrett, NIF and DSSN logos are linked, with four columns on desktop and two on mobile. Mobile CSS addresses heading overflow, footer width, logo contrast and spacing.

## Training page

URL: https://lssk.no/service/trening/

The service post type and its template are retained. The slug was changed from range-services to trening, with a 301 redirect from the old address. Post title: Trening. Banner: Trening i Slydalen. Uses Header Style 4, Footer Style 4 and no sidebar; the original banner metadata is overridden by the Additional CSS described below.

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

## Photo banners for NSF, Training and DSSN

On 2026-10-21 the user approved using the two new club photos in the title banners. Additional CSS now overrides the banner background for NSF page 1109 with 20260820_194724-scaled.jpg (center 50%) and Training service 3320 with 20260820_194745-scaled.jpg (center 45%). Both use cover sizing and a dark rgba(25,22,16,.58) overlay with white title text. This overrides the previous NSF background-image:none rule. The WordPress banner metadata is unchanged; maintain these two banner images and their crop positions in Appearance > Customize > Additional CSS. DSSN page 1118 was subsequently updated at the user’s request with IMG_20250830_123737-scaled.jpg, centered at 55%, using the same dark overlay and white title styling. The previous DSSN background-image:none rule is overridden. Training is unchanged.

## Club navigation correction (2026-10-21)

Main Menu now uses a custom parent item OM KLUBBEN (5515) with an empty URL. It does not navigate to a page. Its first child Om oss uses page 1197 (/om-oss/), retained as menu item 1220 with a corrected label and parent. Removed old menu item 1221, which pointed to the obsolete /team-members/ demo page. Existing Blog child 1222 is retained. Verified public header markup has no href on OM KLUBBEN and the correct /om-oss/ destination on its child; clicking the parent keeps the current page.

## Mobile banner and course-text correction (2026-10-21)

At the user's request, fixed two issues shown in phone screenshots: the Styremøtereferater banner heading extended outside the viewport, and the home page safety-course text touched the screen edges.

Appended a scoped mobile-only block to Appearance > Customize > Additional CSS (breakpoint 767px). Existing CSS was preserved; the pre-change backup is outputs/additional-css-before-mobile-text-fix.css. The new block is backed up in widgets/mobile-text-fixes.css in the repository and locally in outputs/lssk-mobile-text-fixes.css.

All breadcrumb-style1 page banners now use 52px vertical padding on mobile, 20px horizontal container padding, a fluid 24–30px heading with safe wrapping, and 14px breadcrumbs that can wrap. This covers the long board-minutes title and makes the empty Kalender/Styremøtereferater shells consistent with the completed pages.

Only the homepage #kurs widget gets 20px horizontal padding, a 26–32px heading, and 17px paragraph text with 1.7 line height. Long contact links can wrap. Mobile heading character animations are neutralized inside this widget to prevent transient clipping; desktop animations are retained. Text, links, photographs and desktop styles were not changed.

Published successfully. Customizer mobile preview at 320px confirmed the full Styremøtereferater heading at 24px, heading scroll width equal to its 263px available width, and no page overflow (303px document width). The course widget showed 20px padding, a 26px heading and 17px paragraph text. Screenshot capture timed out; validation used the rendered mobile preview and computed layout.
