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

## Elementor MCP server

This repo declares an MCP server in [`.mcp.json`](.mcp.json) that lets Claude Code talk to the live site's Elementor endpoint (`https://boxerklub-nordholz-v1.de/wp-json/elementor/mcp/`) via HTTP Basic auth. The server definition is checked into git, but the credential is not — it's read from the `ELEMENTOR_MCP_AUTH_TOKEN` environment variable at session start.

To use it locally:

1. Get the Basic-auth token: base64-encode `username:application_password` (strip the spaces WordPress shows in the application password), e.g.
   ```bash
   echo -n 'your-wp-username:yourapplicationpassword' | base64
   ```
2. Export it in your shell before launching `claude` (Claude Code reads process environment variables, not `.env` files, when expanding `${...}` in `.mcp.json`):
   ```bash
   export ELEMENTOR_MCP_AUTH_TOKEN='<base64 value from step 1>'
   ```
   For persistence, add the `export` line to your shell profile (`~/.zshrc`, `~/.bashrc`, …) or load it via [direnv](https://direnv.net/) — either way, keep the actual token out of any file committed to this repo. `.env.example` documents the variable name (`ELEMENTOR_MCP_AUTH_TOKEN=`) as a placeholder only.
3. Start `claude` from the project root; on first use it will ask you to approve the project-scoped `elementor-boxerklub-nordholz-v1-de` server.

## Directory structure

```
composer.json              # WordPress core + Elementor + Hello Elementor via wpackagist
docker-compose.yml         # WordPress, MySQL, WP-CLI containers for local dev
wp-content/
  themes/boxerklub/        # Custom child theme of Hello Elementor, wired for Elementor
```
