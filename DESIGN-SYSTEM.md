# Superb Painting Design System

Source of truth: `:root` tokens in `style.css`. Never hardcode hex, radius, or spacing when a token exists.

## Tokens

| Token | Value | Use |
|-------|-------|-----|
| `--color-primary` | `#1A1A1A` | Headings, dark sections, charcoal UI |
| `--color-accent` | `#C9A84C` | Gold CTAs, icons, accents, stat labels |
| `--color-accent-dark` | `#A8873A` | Hover states |
| `--color-bg` | `#F8F8F6` | Page background |
| `--color-bg-light` | `#EFEFEC` | Muted sections (`.superb-section--muted`) |
| `--color-bg-dark` | `#2D2D2D` | Dark panels |
| `--font-heading` | Montserrat | h1–h4, buttons, stats numbers |
| `--font-body` | Open Sans | Body copy (15px standard) |
| `--radius` / `--radius-lg` / `--radius-pill` | 8px / 12px / 50px | Inputs / cards / buttons |
| `--shadow-sm` / `--shadow-md` | defined | Card elevation |
| `--transition` | `.25s ease` | All hovers |
| `--container` | `1200px` | Max content width |
| `--section-padding-y` | `80px` | `.superb-section` vertical rhythm |

## Heroes (2 variants)

### Marketing hero
- **Classes:** `hero-split` (home) or `about-hero about-hero--cover` (about)
- Full-bleed image + gradient overlay + trust badges
- Centered breadcrumb, eyebrow (`.superb-eyebrow`), h1, lead

### Inner page hero
- **Cover (no custom image):** `page-hero page-hero--cover` + `page-hero__overlay`
- **Service image:** `page-hero page-hero--image page-hero--img-{key}` (CSS background, no inline styles)
- Used on Contact, Services, FAQ, Suburbs, Thank You, Gallery, suburb singles
- Same h1 scale as About: `clamp(2.25rem, 5vw, 3.5rem)`

Pattern reference: `patterns/page-hero.php`

## Cards

Base primitive: `.superb-card` (extended by `service-card`, `service-page-card`, `about-values__card`, `about-team__card`, `testimonial-card`, `suburb-card`).

- Radius: `--radius-lg`
- Shadow: `--shadow-sm`, hover `--shadow-md` + `translateY(-6px)`
- List cards: `border-top: 4px solid var(--color-accent)`
- **Exception:** `.feature-block` — no box/shadow (icon row)

## Stats

Unified: `.stats-row`

| Modifier | Use |
|----------|-----|
| (default) | Home animated counters — white numbers, gold labels |
| `.stats-row--static` | Milestones, suburb strips — gold numbers, white labels |
| `.stats-row__grid--3` | 3-column layout |

## CTAs

`.cta-banner.cta-banner--gold` (default) — gold background, **charcoal** `.superb-btn-orange` button.

`.cta-banner--dark` — charcoal background, gold button.

Never gold button on gold background.

## Testimonials

- **Card:** `.testimonial-card` + `.testimonial-card--narrow` for in-content quotes
- **Featured/editorial:** `.about-quote` (full-width dark block on About)
- Stars: `[superb_stars]` / `superb_icon_stars()` only — no emoji

## Buttons

| Class | Use |
|-------|-----|
| `.superb-btn-orange` | Primary CTA |
| `.superb-btn-outline-navy` / `.superb-btn-outline-primary` | Secondary on light bg |
| `.superb-btn-outline-white` | On dark/charcoal backgrounds only |

## Utilities

`.superb-eyebrow`, `.superb-section-title--left`, `.superb-section-title--compact`, `.superb-section-title--spaced`, `.superb-container--narrow` (760px), `.superb-container--prose` (800px), `.superb-container--compact` (640px), `.superb-section--tight-top`, `.superb-mt-lg`, `.superb-btn-row`

## Icons

Lucide SVGs via `[superb_icon name="…"]` / `superb_icon()` from `inc/icons.php`.

## Content sync

**Source of truth:** `patterns/*.php` — composed into pages by `superb_refresh_marketing_pages()` in `inc/setup.php`.

**Generated snapshots:** `content/*.html` — auto-written when pages sync. Do not edit directly; changes will be overwritten on the next sync or polish run.

| Page | Edit these patterns |
|------|---------------------|
| Home | hero-split, stats-row, services-grid, … (see `superb_get_marketing_page_patterns()`) |
| About | `patterns/about-*.php` |
| Services | `patterns/services-page-*.php` |
| Suburbs | `patterns/suburbs-page-*.php` |
| Contact | `patterns/contact-page-*.php` |
| FAQ | `patterns/faq-page-*.php` |
| Thank You | `patterns/thank-you-page-*.php` |

**Re-sync after pattern edits (local):**
```bash
wp eval 'superb_refresh_marketing_pages();' --path=app/public
```

Do not bump `SUPERB_THEME_VERSION` just to refresh pages — that re-runs the full polish pass (CPTs, header, menus). Use the command above instead.

**Local sync URL:** `http://superb-painting.local/?superb_sync_pages=1`

### PHP templates (not synced from patterns)

These render directly from theme files — edits apply immediately, but polish/version bumps do not overwrite them:

| Template | Rules |
|----------|-------|
| `single-suburb.php` | No “Clients Say” testimonial block. Sidebar quote form + opening hours required. |
| `single-service.php` | Pricing sidebar + quote form. |
| `page-gallery.php` | Filter tabs + masonry grid. |

After editing a PHP template, run the site audit (below).

### CF7 forms

**Source of truth:** `inc/cf7.php` — three forms (hero, quick/sidebar, full/contact).

Forms auto-sync when `SUPERB_CF7_FORMS_VERSION` changes. To force re-sync, delete the `superb_cf7_forms_version` option or bump the constant.

Contact page must include: map via `[superb_contact_map]` shortcode (WordPress strips raw iframes from saved page content), property-type field, full submit label.

### Site audit (local)

`http://superb-painting.local/?superb_audit=1` — JSON report of page HTML checks, template guardrails, and sync state. Run after any content or template change.

## Section rhythm (long pages)

white → muted → white → dark strip → white → gold CTA
