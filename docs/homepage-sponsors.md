# Homepage sponsor presentation

Updated 2026-10-22. Public section: https://lssk.no/#lssk-sponsors.

## Source of truth

Edit homepage **193** in Elementor, HTML widget **8902097**, container **ada784f**. The maintenance copy is [widgets/homepage-sponsors.html](../widgets/homepage-sponsors.html). GitHub changes do not deploy to WordPress automatically: replace the complete widget code in Elementor, publish, verify signed out, then update this copy.

## Current public content

The club has no confirmed sponsors yet. The section invites businesses to support the club; it does not claim existing sponsorship agreements. All three panels and small logo choices currently use the club's existing `LSK-Logo.svg`. Test notices and demonstration labels were removed at the user's request. Replace these entries with actual sponsor names, logos, descriptions and website links when agreed. Preserve original logo colours. Do not invent prices, tiers or contributions.

## Layout and interaction

- Cream, brown/green and gold palette, target rings, crosshair, soft red dot and a short hit-ring/logo animation when the main panel changes. No sound or device-motion access.
- Two-column presentation on desktop, stacked visual/copy on mobile. Compact logo row uses 84px choices and 8px gaps, including desktop.
- Clicking a small logo changes the main panel. Keyboard arrows, Home and End work on logo buttons. The main panel also has a horizontal pointer-swipe handler.
- The small logo row uses real horizontal scrolling: native touch panning/momentum on phones and pointer-captured mouse dragging after a horizontal threshold on desktop. Vertical touch page scrolling remains allowed. Dragging suppresses its release click; a subsequent intentional press still works.
- No play/pause button. Automatic movement pauses during interaction, mouse hover, keyboard focus, hidden-page or offscreen conditions. Main panels normally rotate every seven seconds; the row advances at one repeated-group width per 36 seconds.
- Three equivalent groups allow seamless scroll wrapping. Extra filler/duplicate buttons are outside the tab order and hidden from assistive technology but remain pointer-selectable. ResizeObserver fills wide layouts without large gaps.
- Reduced-motion preference removes animations and automatic movement and leaves the three original choices available for native scrolling. Without JavaScript, the first panel/contact link remain readable.
- Event listeners, animation frames, timers and observers are cleaned up on removal or Elementor reinitialization. No external slider library or visitor tracking.

## Maintenance

Update each `.ls-panel` and its corresponding original `.ls-choice`; JavaScript generates repeated logos. Give buttons and slides meaningful accessible names. Use appropriate sponsored links for actual sponsor destinations. Update numbering/labels if the number of panels changes; simplify navigation for a single sponsor.

The outer section is currently visible. Adding `hidden` to `#lssk-sponsors` hides it publicly and hides the surrounding container to avoid a gap; Elementor still shows it for editing. Keep the container-specific CSS selector in sync if moving the widget.

## Verification and limitations

Signed-out public desktop testing verified selection of panel 2, a 300px mouse drag (scrollLeft 1104 to 1404) without changing the panel, and an immediate click selecting panel 1. A local browser test also moved 280px one way and 250px back. Pause control is absent. At a 390px mobile viewport the section client/scroll widths are both 355px, with native horizontal scrolling and `touch-action: pan-x pan-y`. The user previously approved the mobile appearance. Actual finger gestures on a physical phone remain to be confirmed by the user.

The live WordPress widget is the deployed source. No calendar, SSI Sync, Shot Counter application or infrastructure configuration was changed as part of this section.
