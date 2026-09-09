# Boxerklub

WordPress site for the Boxerklub, built around the [Elementor](https://elementor.com/) page builder.

## Stack

- WordPress core + plugins/themes managed via **Composer** ([wpackagist](https://wpackagist.org/))
- **Elementor** plugin + **Hello Elementor** base theme
- Custom `boxerklub` child theme in `wp-content/themes/boxerklub`
- Local dev via **Docker Compose** (WordPress + MySQL + WP-CLI)

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
