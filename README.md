# superb-painting

Kadence child theme for **Superb Painting**, a Melbourne interior and exterior painter. Live at `superbpainting.com`.

Marketing pages are assembled from block patterns under `patterns/` (home, about, services, suburbs, contact, FAQ, thank-you) rather than one giant `front-page.php`. Suburb and Service are custom post types with their own singles; quote capture goes through Contact Form 7. ACF field groups are registered in PHP and written to work with free ACF — numbered image and process-step fields instead of repeaters.

- Design tokens live at the top of `style.css` (charcoal + gold, Montserrat / Open Sans). See `DESIGN-SYSTEM.md`.
- Sample photography and logos sit in `assets/images/` and are referenced by theme-relative URL, not by Media Library ID.

## Requirements

- WordPress 6.4 or newer, PHP 8.0 or newer
- The **Kadence** parent theme, Contact Form 7 for the quote forms, and ACF (free is enough).

## Installing

Kadence has to be present first. Copy or symlink this folder into `wp-content/themes/` and activate it. After activation, `inc/setup.php` registers the Suburb and Service post types and seeds the marketing pages. The local install currently lives as `kadence-child-superb`; a symlink from that name into this repo is the usual way to keep editing here.

## Deploys

There is no automated deploy. Code and content go live manually — a change that works locally is not live until it is copied up, and the database side (ACF values, Media Library items) has to be reproduced on the target install by hand.
