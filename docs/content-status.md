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

## Calendar shell and board minutes (2026-10-21)

At the user's request, cleared only the visible calendar page shell while another person works on calendar functionality. Page 5197 remains at /our-events/ (the existing Main Menu destination), renamed Kalender. Removed its entire Events demo container in Elementor and published the empty layout. This removes the English demo event cards and prices. Set Header Style 4, Footer Style 4, banner title Kalender, and original club photo IMG_20260508_184728-scaled.jpg through the page's Firerangers settings. No event records, calendar plugin settings, or /events/ functionality were changed. Main Menu still points to /our-events/; footer still points to /events/. The calendar implementer should reconcile these destinations when ready.

The Main Menu Blog child 1222 had only a # placeholder and no page destination. Created a clean, empty standard WordPress page 5517, Styremøtereferater, at https://lssk.no/styremotereferater/. It uses Header Style 4, Footer Style 4, one-column layout, and the same club banner photograph. No minutes or placeholder articles were added. Menu item 1222 is now labelled Styremøtereferater and links to the new page under OM KLUBBEN. Existing unrelated demo blog/archive pages and posts were not modified.

Verified public page titles, club header/footer, banner image URL, absence of demo event content, and the new submenu destination. Browser screenshots could not be saved because the screenshot command timed out; verification used rendered page DOM. Maintain page 5197's content in Elementor and page 5517's empty content in the standard WordPress editor until the future content workflow is chosen.

## Contact page

Contact page 1398 (/contact/) was rebuilt and published on 2026-10-21 as one Elementor HTML widget. The old Contact, Form and Map demo containers, Berlin address/map and demo forms were removed. Title/banner: Kontakt. Uses Header Style 4, Footer Style 4 and the club range photograph IMG_20260508_184728-scaled.jpg, explicitly approved by the user.

The board list was supplied by the user: Kjetil Jørgensen (chair), Jan Rune Hauge (deputy), Ørjan Andre Løvdal (treasurer), Morten Alvestad Pilkvam (range manager), and Petter Hjalmar Langstrand (board member). Kjetil uses the previously confirmed club telephone/email. Other board members show name and office only; no contact details were invented. Course cards identify Mads for general safety courses and trying pistol shooting, and Halvor Odden for DSSN courses.

## About page

Page 1197 (/om-oss/) uses one Elementor HTML widget with Norwegian club copy, values, NSF/DSSN training information, membership/contact/map links and original club photographs. Old Welcome, gallery, Why Choose Us, Team Grid and Achivements demo containers were removed. Header Style 4 and Footer Style 4 are retained. The banner uses IMG_20260508_184728-scaled.jpg through page-scoped CSS in the HTML widget. Historical research is saved locally in docs/history-research.md; no history was added to the public page.

## Completed navigation and URL cleanup (2026-10-21)

This section supersedes older URL and contact descriptions above.

| Page | WordPress ID | Current URL | Editor |
| --- | --- | --- | --- |
| NSF | 1109 | /nsf/ | One Elementor HTML widget |
| DSSN | 1118 | /dssn/ | One Elementor HTML widget |
| Trening | 3320 | /service/trening/ | Classic service editor |
| Kontakt | 1398 | /kontakt/ | One Elementor HTML widget |
| Om oss | 1197 | /om-oss/ | One Elementor HTML widget |
| Kalender shell | 5197 | /kalender/ | Empty Elementor layout |
| Styremøtereferater | 5517 | /styremotereferater/ | Empty standard page |
| Personvern | 5461 | /personvernerklaering/ | Existing page |

Header Menu 70: Medlemskap now links to https://www.minidrett.no/medlemskap/27827. Removed empty Nyheter, Galleri and Spørsmål og svar menu items. Main Menu 71 and Footer Four Menu 66 now use /kalender/. Elementor's Replace URL tool updated exact full-address references to /contact/, /training/, /service/range-services/, /our-events/ and /events/ in Elementor data. Training classic content was updated separately. No domain or site-address settings were changed.

The /service/ prefix is retained to preserve the Training post type and design. The Events Calendar archive at /events/ remains available with its original functions and settings. No event data, API keys or calendar plugin settings were changed. The other calendar implementer should use /kalender/ as the public landing page. No redirect was added for /events/ because that would interfere with the calendar application.

Installed and activated Redirection 5.10.1 by John Godley from the official WordPress plugin directory, because the old page URLs returned 404 after slug changes. Four enabled 301 redirects map /contact/ to /kontakt/, /training/ to /nsf/, /our-events/ to /kalender/, and /service/range-services/ to /service/trening/. Verified all four by navigating to the old addresses and checking the resulting URL. Permalink-change monitoring is enabled; optional redirect/404 logging and IP storage were not enabled.

DSSN page 1118 now names Halvor Odden as DSSN-course instructor, phone +47 466 69 065, email halvoro10@gmail.com. Mads remains responsible for general safety-course enquiries and trying pistol shooting. Kjetil remains the general club contact in header/footer and board section.

Confirmed demo content was set to Draft and never permanently deleted:

- 18 Pages: 1392, 1217, 1201, 1203, 1205, 2066, 2068, 2070, 1207, 1208, 1209, 974, 976, 1199, 5363, 1214, 5274, 1187.
- 19 demo Posts: 5108, 5107, 5105, 3357, 3356, 3355, 3354, 3353, 3352, 2981, 2980, 2979, 2978, 2977, 2976, 2975, 2974, 2973, 2972.
- Five demo Services: 3324, 3323, 3322, 3321, 5219. Trening 3320 remains published.
- Six legacy Team templates, including the old Mads template with an English demo layout and demo contact details. His real contact card remains on Kontakt.
- 37 imported Project/gallery entries. Their media attachments and original club photos were not removed or changed.

The obsolete List view 01 was unassigned as the WordPress Posts page before drafting it. Homepage 193 remains assigned. WooCommerce and ticket/calendar system pages are retained.

Validation: all seven principal content pages opened with expected titles, no broken visible images and no old-address links outside the admin toolbar. The home page also has no such old links. Customizer mobile preview confirmed Om klubben opens Om oss and Styremøtereferater, and showed no horizontal overflow on the home page (320px viewport, 303px document width). Desktop screenshot saved locally as outputs/lssk-opprydding-2026-10-21.png. A separate mobile screenshot timed out; mobile behaviour was checked in the rendered preview.

New beginner guidance, membership prices, range rules and public history remain deferred at the user's request.
