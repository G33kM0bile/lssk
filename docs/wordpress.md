# WordPress and theme notes

## Theme stack

- Parent theme: Fire Rangers
- Child theme: Fire Rangers Child
- Elementor
- The Events Calendar

Development reports:

```text
stylesheet: firerangers-child
template:   firerangers
```

## WooCommerce

WooCommerce is present because the theme expects/supports it, but LSSK is not operating an online shop.

It can remain installed and disabled when not needed. Do not manually delete WooCommerce database tables merely to clean up the backend.

WooCommerce Coming Soon mode is not used as the normal public development notice because it obscures too much of the site.

## Fire Rangers options

The theme stores settings in serialized WordPress options. While investigating the theme marquee, these keys were found:

```text
theme_mods_firerangers-child
└── firerangers_options-mods
    ├── marquee_list_v1
    └── show_topbar_v1
```

The active site uses Header Style Four. The built-in marquee belongs to Header Style One, so a separate notification-bar plugin is used for the production work-in-progress notice.

## Favicons

Production uses the LSSK target/reticle mark on a transparent background.

Development uses the same target mark with an orange palette to make the environment visually distinct.
