# Boxerklub

WordPress site for the Boxerklub, built around the [Elementor](https://elementor.com/) page builder (v4).

## Stack

- WordPress core + plugins/themes managed via **Composer** ([wpackagist](https://wpackagist.org/))
- **Elementor v4** plugin + **Hello Elementor** base theme
- Custom `boxerklub` child theme in `wp-content/themes/boxerklub`
- Local dev via **Docker Compose** (WordPress + MySQL + WP-CLI)

## Design system (Elementor Variables)

The theme ships brand colors and typography as design tokens instead of
hard-coded values:

| Token                | Value              |
| -------------------- | ------------------ |
| `boxerklub-primary`  | `#D91E2B` (red)    |
| `boxerklub-dark`     | `#111111`          |
| `boxerklub-light`    | `#F5F5F5`          |
| `boxerklub-accent`   | `#C9A227` (gold)   |
| `boxerklub-heading`  | Oswald, 700        |
| `boxerklub-body`     | Roboto, 400        |

`boxerklub_seed_elementor_kit_variables()` in `functions.php` writes these
into the active Elementor Kit the first time the theme is activated (and
only if no custom colors/typography exist yet, so it never overwrites
changes made in the editor). Elementor v4 automatically surfaces Kit
colors and typography as **Variables** under *Site Settings → Variables*
in the editor — edit them there afterwards, no code changes needed.

`style.css` consumes the same tokens as CSS custom properties
(`var(--e-global-color-boxerklub-primary, #D91E2B)`, etc.), each with a
static fallback so the front end renders correctly even before Elementor
has compiled the Kit's CSS.

### Spacing, radius & size tokens

`style.css` also defines a fluid spacing/radius/size scale as plain CSS
custom properties. These are **not** seeded into the Elementor Kit (unlike
colors/typography above) since Elementor core has no built-in Kit setting
for a spacing/size scale — they only exist as theme-owned tokens. All
`clamp()` values scale linearly between a 375px and a 1440px viewport.

| Token                        | Value (375px → 1440px)         |
| ----------------------------- | ------------------------------- |
| `boxerklub-space-3xs`         | 4px → 6px                       |
| `boxerklub-space-2xs`         | 8px → 10px                      |
| `boxerklub-space-xs`          | 12px → 16px                     |
| `boxerklub-space-s`           | 16px → 20px                     |
| `boxerklub-space-m`           | 24px → 32px                     |
| `boxerklub-space-l`           | 32px → 48px                     |
| `boxerklub-space-xl`          | 48px → 64px                     |
| `boxerklub-space-2xl`         | 64px → 96px                     |
| `boxerklub-space-3xl`         | 96px → 128px                    |
| `boxerklub-radius-xs`         | 4px (static)                    |
| `boxerklub-radius-s`          | 6px → 8px                       |
| `boxerklub-radius-m`          | 8px → 12px                      |
| `boxerklub-radius-l`          | 12px → 16px                     |
| `boxerklub-radius-xl`         | 16px → 24px                     |
| `boxerklub-radius-full`       | 999px (static)                  |
| `boxerklub-size-icon-s`       | 16px → 20px                     |
| `boxerklub-size-icon-m`       | 24px → 28px                     |
| `boxerklub-size-icon-l`       | 32px → 40px                     |
| `boxerklub-size-avatar-s`     | 40px → 48px                     |
| `boxerklub-size-avatar-m`     | 64px → 80px                     |
| `boxerklub-size-avatar-l`     | 96px → 128px                    |
| `boxerklub-size-container`    | fluid, capped at 1200px         |

### Global classes

Reusable component classes built on the tokens above, defined in
`style.css`. Add them as **Additional CSS Classes** to any Elementor
widget/container to apply them:

| Class               | Used on                                     | Notes                                    |
| -------------------- | -------------------------------------------- | ----------------------------------------- |
| `card`               | Card container                               | Base card: padding, radius, shadow       |
| `card-media`         | Image/video inside a `card`                  | Bleeds to the card's edges               |
| `card-compact`       | Card container, combined with `card`         | Tighter padding/gap variant              |
| `card-person`        | Portrait image inside a `card`               | Circular avatar (trainer/boxer profiles) |
| `button-secondary`   | Button widget                                | Outline button, fills on hover           |
| `form-field`         | Input / textarea / select                    | Focus ring in the primary color          |
| `nav-link`           | Primary menu item                            | Hover state in the primary color         |
| `nav-link-active`    | Primary menu item, combined with `nav-link`  | Marks the current page                   |

## Local setup

1. Copy the environment file and adjust if needed:
   ```bash
   cp .env.example .env
   ```
2. Install WordPress core, Elementor and the Hello Elementor theme:
   ```bash
   composer install
   ```
3. Start the containers:
   ```bash
   docker compose up -d
   ```
4. Visit `http://localhost:8080` (or your configured `WP_PORT`) and complete the WordPress install wizard, **or** run it via WP-CLI:
   ```bash
   docker compose exec wpcli wp core install \
     --url="http://localhost:8080" \
     --title="Boxerklub" \
     --admin_user=admin \
     --admin_password=admin \
     --admin_email=admin@example.com \
     --path=/var/www/html
   ```
5. Activate Elementor and the theme:
   ```bash
   docker compose exec wpcli wp plugin activate elementor --path=/var/www/html
   docker compose exec wpcli wp theme activate boxerklub --path=/var/www/html
   ```

WordPress core is installed into `/wp` (composer, git-ignored); only `wp-content` is tracked in this repository, and within it only the custom `boxerklub` theme — plugins and the parent theme come from Composer, not git.

## Directory structure

```
composer.json              # WordPress core + Elementor + Hello Elementor via wpackagist
docker-compose.yml         # WordPress, MySQL, WP-CLI containers for local dev
wp-content/
  themes/boxerklub/        # Custom child theme of Hello Elementor, wired for Elementor
```
