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
