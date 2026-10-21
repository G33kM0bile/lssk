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

## Homepage shot estimate (2026-10-21)

Published a separate Elementor HTML widget on homepage 193, after the safety-course section and before the footer. Widget ID e118d3c, container 6503da1. Its CSS, HTML and JavaScript maintenance copy is in widgets/homepage-shot-estimate.html in the repository; the live source of truth is still this WordPress widget. A local copy is in outputs/lssk-shot-estimate.html.

The card uses the club's brown/gold palette and explicitly labels the number as estimated activity, based on 1,000–2,500 shots per week, not recorded shot statistics. It is independent of the repository's separate shot-counter measurement application and makes no network requests, stores no visitor information and requires no cron task or new plugin.

Each Monday is assigned an inclusive 1,000–2,500 target using a stable pseudo-random hash of its calendar date and seed lssk-shot-estimate-v1. Every visitor gets the same target; page refreshes do not change the total. The annual total sums completed weeks and a linear fraction of the current week. Weeks begin Monday at midnight in Europe/Oslo and use actual elapsed time, including 167/169-hour daylight-saving transition weeks. The partial week at New Year contributes only the time inside that calendar year; the year total resets at Oslo midnight on 1 January. Preserve the seed and algorithm to avoid rewriting the displayed history.

The display refreshes every 15 seconds; the integer naturally increases about once every 4–10 minutes. There is no rapid count-up animation or audible effect. If JavaScript is disabled, it shows a dash and a short explanation rather than a fabricated static total. The timer is disposed when its widget is removed from the DOM.

Validation: the standalone Node checks in outputs/test-shot-estimate.cjs passed weekly range/stability, full-week totals, DST continuity, year boundaries for 2024–2030 and consistency across visitor time zones. Elementor mobile preview at 360px showed a single column with the number contained inside the card (card width about 293px). Verified one functioning widget on the published home page. Desktop and mobile screenshots are saved locally as outputs/lssk-skuddteller-publisert-2026-10-21.png and outputs/lssk-skuddteller-mobil-2026-10-21.png.
