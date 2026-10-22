# Social sharing previews

Updated 2026-10-22. The user reported a Firerangers preview when sharing lssk.no. The public page title was correct but there were no Open Graph/Twitter tags. After explicit user approval, installed and activated **Open Graph and Twitter Card Tags 3.5.2**, official-directory slug `wonderm00ns-simple-facebook-open-graph-tags`.

Settings: WordPress **Settings > Open Graph and Twitter Card Tags** (`options-general.php?page=ogatc-settings`).

## Published configuration

- Site name/homepage sharing title: **Lofoten Sportsskytterklubb**.
- Homepage description: Pistolskyting i Slydalen i Lofoten. Trening innen NSF og DSSN, sikkerhetskurs og et inkluderende klubbmiljø for nye og erfarne skyttere.
- Default/homepage image: the club's standplass 7 photograph, explicitly chosen by the user: https://lssk.no/wp-content/uploads/2026/10/20260820_194724-scaled.jpg (attachment5488).
- Locale `nb_NO`; Twitter card `summary_large_image`.
- Automatic featured/content/media image selection disabled so legacy demo images cannot override the club default; explicit per-page custom image overrides remain enabled.
- Custom sharing descriptions saved on Kontakt1398, NSF1109, DSSN1118, beginner guide5573 and Om oss1197. Om oss sharing title: **Om oss – Lofoten Sportsskytterklubb**. Other pages retain automatic page descriptions.

Homepage metadata is also explicitly set in the plugin metabox on page193. On Elementor pages, change only these metadata fields in the standard WordPress editor; keep visible page content maintained in Elementor. No theme-content change is needed for these tags.

Cleared the existing WP Super Cache using its Delete Cache button without changing cache configuration. Signed-out public DOM checks on the homepage and all five edited subpages confirmed one set of Open Graph/Twitter tags with the intended title, description, locale and photograph. The image loads at1196 x2560. Sharing services control the final crop and can retain older previews in their own caches; actual Messenger rendering/cache refresh was not tested.

Do not add another overlapping social/SEO plugin without reviewing existing tags. The WordPress settings/database remain outside this repository.
