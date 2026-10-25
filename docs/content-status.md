# Website content and layout status

Updated: 2026-10-25. Changes were made through the signed-in WordPress UI on lssk.no. The active theme and database are not stored in this repository.

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

## Homepage shot estimate (2026-10-21)

Published a separate Elementor HTML widget on homepage 193, after the safety-course section and before the footer. Widget ID e118d3c, container 6503da1. Its CSS, HTML and JavaScript maintenance copy is in widgets/homepage-shot-estimate.html in the repository; the live source of truth is still this WordPress widget. A local copy is in outputs/lssk-shot-estimate.html.

The card uses the club's brown/gold palette. The user requested removing both the bottom explanation and the words Estimert aktivitet. The current compact display uses an approximation sign (≈) before the number and only the year beneath it; its accessible section label still identifies the value as an estimate. It is based on 1,000–2,500 shots per week, not recorded shot statistics. The calculation and update interval are unchanged. It is independent of the repository's separate shot-counter measurement application and makes no network requests, stores no visitor information and requires no cron task or new plugin.

Each Monday is assigned an inclusive 1,000–2,500 target using a stable pseudo-random hash of its calendar date and seed lssk-shot-estimate-v1. Every visitor gets the same target; page refreshes do not change the total. The annual total sums completed weeks and a linear fraction of the current week. Weeks begin Monday at midnight in Europe/Oslo and use actual elapsed time, including 167/169-hour daylight-saving transition weeks. The partial week at New Year contributes only the time inside that calendar year; the year total resets at Oslo midnight on 1 January. Preserve the seed and algorithm to avoid rewriting the displayed history.

The display refreshes every 15 seconds; the integer naturally increases about once every 4–10 minutes. There is no rapid count-up animation or audible effect. If JavaScript is disabled, it shows a dash and a short explanation rather than a fabricated static total. The timer is disposed when its widget is removed from the DOM.

Validation: the standalone Node checks in outputs/test-shot-estimate.cjs passed weekly range/stability, full-week totals, DST continuity, year boundaries for 2024–2030 and consistency across visitor time zones. Elementor mobile preview at 360px showed a single column with the number contained inside the card (card width about 293px). Verified one functioning widget on the published home page. Desktop and mobile screenshots are saved locally as outputs/lssk-skuddteller-publisert-2026-10-21.png and outputs/lssk-skuddteller-mobil-2026-10-21.png.

## Mobile banner and course-text correction (2026-10-21)

At the user's request, fixed two issues shown in phone screenshots: the Styremøtereferater banner heading extended outside the viewport, and the home page safety-course text touched the screen edges.

Appended a scoped mobile-only block to Appearance > Customize > Additional CSS (breakpoint 767px). Existing CSS was preserved; the pre-change backup is outputs/additional-css-before-mobile-text-fix.css. The new block is backed up in widgets/mobile-text-fixes.css in the repository and locally in outputs/lssk-mobile-text-fixes.css.

All breadcrumb-style1 page banners now use 52px vertical padding on mobile, 20px horizontal container padding, a fluid 24–30px heading with safe wrapping, and 14px breadcrumbs that can wrap. This covers the long board-minutes title and makes the empty Kalender/Styremøtereferater shells consistent with the completed pages.

Only the homepage #kurs widget gets 20px horizontal padding, a 26–32px heading, and 17px paragraph text with 1.7 line height. Long contact links can wrap. Mobile heading character animations are neutralized inside this widget to prevent transient clipping; desktop animations are retained. Text, links, photographs and desktop styles were not changed.

Published successfully. Customizer mobile preview at 320px confirmed the full Styremøtereferater heading at 24px, heading scroll width equal to its 263px available width, and no page overflow (303px document width). The course widget showed 20px padding, a 26px heading and 17px paragraph text. Screenshot capture timed out; validation used the rendered mobile preview and computed layout.

## Beginner guide and confirmed costs (2026-10-21)

After reviewing SVPK and Hokksund Pistolklubb, the user authorized adding the content judged useful. Confirmed facts: the ordinary safety course costs NOK 1,000; beginner club-pistol loans have no rental charge; ammunition is charged at cost. No membership fee, DSSN-course price, fixed ammunition price, equipment package or new course date was supplied.

Published page 5573, **Ny på pistolskyting?**, at https://lssk.no/ny-pa-pistolskyting/. Source of truth: one Elementor HTML widget `fd1113f` in container `4d00f5b`. An empty imported container was removed. Maintain it in Elementor, not separately in the WordPress block editor. HTML/CSS maintenance copy: outputs/lssk-ny-pa-pistolskyting.html. Header Style 4, Footer Style 4, one-column theme layout, banner on and title Ny på pistolskyting?. The page-scoped banner CSS uses IMG_20260508_184728-scaled.jpg; the content photograph is 20260820_194724-scaled.jpg. Both are existing club photos.

Content covers first contact/visit, safe introduction, course interest by email to Mads even before a date is announced, confirmed costs, practical preparation, existing training times/map, prior-course enquiries, NSF/DSSN links and Min idrett membership. The ordinary course and DSSN's separate course/approval are explicitly distinguished; Halvor handles DSSN requirements, dates and prices. No new form, payment flow, mailing list, course booking or policy was introduced.

Homepage 193: beginner card now links to the guide; course card links to its #sikkerhetskurs section. Both texts and button labels were updated. The existing #kurs description now states NOK 1,000, free beginner pistol loans and ammunition at cost, and links to the guide and Mads. Existing images, card styling, slider, shot counter and layout are retained.

Training service 3320: added first-visit preparation and confirmed course/loan/ammunition costs, with guide/DSSN links. Existing schedule, photo, map and service template preserved. NSF 1109: added one short paragraph with costs and the guide link under En trygg vei inn i sporten. DSSN 1118: added the guide link and a direct route to Halvor under Slik kommer du i gang. Full pre-change and updated HTML copies are in outputs/ with names containing beginner-guide. Calendar work and empty board-minutes page were untouched.

Validation: signed-out browser confirmed the new page, header/footer, banner/photo and all three cost facts; homepage card/course links and published #kurs text; guide links on Training, NSF and DSSN. Mobile at 360px: document width 345px, single-column grid 295px, 28px content headings, club photo loaded, no horizontal overflow. Elementor mobile preview independently showed 293px content within a 360px viewport. Screenshots: outputs/lssk-nybegynner-publisert-2026-10-21.png, outputs/lssk-nybegynner-innhold-2026-10-21.png and outputs/lssk-nybegynner-mobil-2026-10-21.png.

Editing note: WordPress code-editor textarea overlapped the Firerangers metabox and prevented mouse selection. Switching to the visual editor restored working theme controls. Elementor initially imported the WordPress content as Text Editor; that widget was replaced with the single HTML widget above to match the discipline-page maintenance approach.

## Beginner guide menu entry (2026-10-21)

At the user's request, added page 5573 to Main Menu 71 as menu item 5594, labelled Ny på pistolskyting?, under TRENING (menu item 3330). TRENING retains its existing training-page URL. The new entry points to /ny-pa-pistolskyting/. Saved through Appearance > Menus. Verified the published desktop menu's parent/child markup and opened the mobile menu at 390px, expanded TRENING and followed the new link to the correct page. Screenshot: outputs/lssk-nybegynner-meny-mobil-2026-10-21.png. Temporary viewport override reset.

Originality clarification: the beginner guide was written for LSSK using the club's supplied facts and contacts. The other clubs informed topic selection and information structure; their paragraphs, photographs, local prices and policies were not copied.

## General contact telephone changed to Mads (2026-10-21)

User requested replacing Kjetil's telephone everywhere except his entry on Kontakt. General public telephone is now Mads, 970 63 150. Appearance > FIRERANGERS Options: Header Style Four phone field `phone_num_v4` and Mobile Sidebar `mobile_phone_no` updated and saved. The homepage's separate contact widget is also verified as «Ta kontakt med Mads» with tel:+4797063150. Header and mobile phone links render Mads's number. No footer telephone was present. Existing email addresses were outside the request and retained.

The public audit found one additional content occurrence on Personvernerklæring, page 5461. Changed its telephone anchor from tel:+4791109990 / 91 10 99 90 to tel:+4797063150 / 970 63 150 in the WordPress code editor, preserved all other policy text except its last-updated date, and saved. Backup: outputs/lssk-personvern-before-phone-update.html.

Validated signed out: home, Training, NSF, DSSN, Om oss, beginner guide, Kalender, Styremøtereferater and Personvernerklæring have no Kjetil telephone links after the update. Kontakt retains 91 10 99 90 only in Kjetil Jørgensen's STYRETS LEDER card; its global header/mobile contact is Mads. Checked the mobile menu at 390px and reset the temporary viewport. Refreshed the public home cache through the existing Delete Cache toolbar action; no cache configuration was changed. Screenshots: outputs/lssk-mads-telefon-mobil-2026-10-21.png and outputs/lssk-mads-telefon-publisert-2026-10-21.png.

## General contact email changed to Mads (2026-10-21)

User requested the same replacement for email: general public address is now mads-johannessen@hotmail.com; retain kjetil.jorgensen@gmail.com in Kjetil's Kontakt board card. Updated theme Mobile Sidebar field mobile_email_v1, Footer Four Menu 66's Kontakt item 3211 (mailto URL), and the contact address/link in Personvernerklæring page 5461. Its email-service sentence now identifies Hotmail/Microsoft rather than Gmail/Google; no other policy content was changed. Backup: outputs/lssk-personvern-before-email-update.html.

Saved all changes through WordPress and refreshed the existing public cache. Signed-out audit of Home, Training, NSF, DSSN, Om oss, beginner guide, Kalender, Styremøtereferater and Personvernerklæring found no Kjetil mailto links. Kontakt contains exactly one, in his preserved STYRETS LEDER card. Mobile menu at 390px displays Mads's complete address wrapped within the panel and a matching mailto link. Screenshot: outputs/lssk-mads-epost-mobil-2026-10-21.png. Viewport override reset. No messages were sent and no mailbox/account configuration was changed.


## Social sharing and sponsor presentation (2026-10-22)

Published the club sharing metadata and a public sponsor-invitation widget on homepage 193. See [Social sharing previews](social-sharing.md) for the installed plugin/settings and [Homepage sponsor presentation](homepage-sponsors.md) for the current widget, editing instructions and verification. Sponsor widget 8902097/container ada784f uses the club logo until actual sponsors are agreed. It has compact moving logos, native touch scrolling and mouse dragging; the play/pause control is removed. Maintenance code is [widgets/homepage-sponsors.html](../widgets/homepage-sponsors.html). These notes supersede earlier deferrals and hidden-test descriptions; WordPress remains the deployed source of truth.

## Contact page: board, instructors and projects (2026-10-25)

Published the user's expanded role list in the existing Elementor HTML widget **ee642d4** on Kontakt page **1398**, https://lssk.no/kontakt/. Existing header/footer, club banner photograph, colours and responsive card style preserved. Added anchor navigation for Styret, Kurs og instruktører and Baner og prosjekter.

Current board: Kjetil Jørgensen (chair), Jan Rune Hauge (deputy), Morten Pilkvam (secretary), Ørjan Løvdal (treasurer), Petter Langstrand (board member), Halvor Odden and Mads Johannesen (deputy board members). Jan Rune's supplied telephone/email are now clickable in his board card. Existing Kjetil contact details retained only on Kontakt; global Mads contacts unchanged. Names use the user's latest shorter forms instead of earlier middle-name forms.

NSF instructors: Mads Johannessen, Petter Langstrand, Kjetil Jørgensen and Jan Rune Hauge. DSSN instructors/ROs: Kjetil Jørgensen, Halvor Odden, Petter Langstrand and Jan Rune Hauge. Additional DSSN ROs: Alfr Nordheim and Mads Johannessen. Mads remains the main safety-course/first-visit contact and Halvor the DSSN-course contact, with their existing details. No course dates, certifications beyond the supplied roles, or prices were invented.

Baneansvarlig Slydalen is now **Sindre Bertheussen**, replacing Morten's previous range-manager label. Indoor range project group: Jan Rune Hauge, Skule Karijord and Walter Hansen. Slydalen range expansion: Jan Rune Hauge. Jan Rune's project mentions link to his contact card; no contact details were invented for others.

Spelling follows the input: the board list says Mads Johannesen, while instructor/RO lists say Mads Johannessen. These forms may be unified once the user confirms the preferred spelling.

Signed-out audit of Om oss, NSF, DSSN, Trening and beginner guide found no conflicting old board/range-role references; their existing Mads/Halvor course contact routes remain valid. Signed-out Kontakt verification confirmed seven board cards, four NSF instructors, six DSSN instructor/RO entries and three range/project cards. Telephone/mail links checked. At390px viewport: document client/scroll widths both375px, section widths both355px, project grid one315px column. Temporary viewport reset.

Live source remains Elementor. Local before/after copies: outputs/lssk-kontakt-before-roles-2026-10-25.html and outputs/lssk-kontakt-roles-2026-10-25.html. Screenshots: outputs/lssk-kontakt-styret-2026-10-25.png and outputs/lssk-kontakt-prosjekter-mobil-2026-10-25.png. These local artifacts are not GitHub-deployed files.
