# External Integrations

**Analysis Date:** 2026-03-12

## APIs & External Services

**WordPress Platform APIs:**
- WordPress Hook + Block APIs - primary integration surface for both focused components
  - SDK/Client: WordPress core functions (`add_action`, `add_filter`, `register_block_type`, `register_post_meta`) used in `wp-content/themes/mecon-theme/functions.php` and `wp-content/plugins/mecon-blocks/includes/01-blocks.php`
  - Auth: WordPress capability checks (`current_user_can`) in `wp-content/plugins/mecon-blocks/includes/03-meta.php`

**Frontend Library:**
- Swiper.js - powers custom slider block behavior
  - SDK/Client: npm package `swiper` in `wp-content/plugins/mecon-blocks/package.json`
  - Auth: Not applicable (client-side library)

**WordPress Plugin Integrations (active coupling):**
- Contact Form 7 - form rendering and anti-spam validation hooks
  - SDK/Client: shortcode usage in `wp-content/plugins/mecon-blocks/patterns/contact-form.php` and validation filters `wpcf7_validate_text*` in `wp-content/plugins/mecon-blocks/includes/05-cf7.php`
  - Auth: WordPress plugin capability model (no extra token/env var in focused code)

**WordPress Theme Integration:**
- Parent theme `twentytwentyfive` - child theme stylesheet dependency and template inheritance
  - SDK/Client: `Template: twentytwentyfive` in `wp-content/themes/mecon-theme/style.css`, parent style enqueue in `wp-content/themes/mecon-theme/functions.php`
  - Auth: Not applicable

**Third-party plugins present (high-level only):**
- Carousel Slider Block (`carousel-block`) - separate Gutenberg carousel provider in `wp-content/plugins/carousel-block/plugin.php`
  - SDK/Client: plugin bootstrap only inspected
  - Auth: Not applicable
- WP Swiper (`wp-swiper`) - separate Gutenberg Swiper provider in `wp-content/plugins/wp-swiper/wp-swiper.php`
  - SDK/Client: plugin bootstrap only inspected
  - Auth: Not applicable

## Data Storage

**Databases:**
- WordPress MySQL database (via WordPress core abstraction)
  - Connection: managed by global WordPress config (not defined in `wp-content/themes/mecon-theme` or `wp-content/plugins/mecon-blocks`)
  - Client: WordPress metadata APIs; custom page meta fields registered in `wp-content/plugins/mecon-blocks/includes/03-meta.php`

**File Storage:**
- Local filesystem only (theme/plugin assets and pattern PHP files), e.g. `wp-content/themes/mecon-theme/assets/` and `wp-content/plugins/mecon-blocks/assets/`

**Caching:**
- No dedicated cache service integration detected in focused code

## Authentication & Identity

**Auth Provider:**
- WordPress native authentication/authorization
  - Implementation: permission gate for meta registration via `auth_callback` and `current_user_can( 'edit_posts' )` in `wp-content/plugins/mecon-blocks/includes/03-meta.php`

## Monitoring & Observability

**Error Tracking:**
- None detected in focused components (`wp-content/themes/mecon-theme`, `wp-content/plugins/mecon-blocks`)

**Logs:**
- No explicit application logging integration detected in focused components

## CI/CD & Deployment

**Hosting:**
- WordPress/PHP hosting target (inferred from plugin/theme architecture in `wp-content/themes/mecon-theme/style.css` and `wp-content/plugins/mecon-blocks/plugin.php`)

**CI Pipeline:**
- None detected in focused components (no CI config found under `wp-content/themes/mecon-theme` or `wp-content/plugins/mecon-blocks`)

## Environment Configuration

**Required env vars:**
- Not detected in focused components (`wp-content/themes/mecon-theme`, `wp-content/plugins/mecon-blocks`)

**Secrets location:**
- Standard WordPress secrets/config location is expected outside focused components (typically site-level config); no secrets file usage detected in `wp-content/themes/mecon-theme` or `wp-content/plugins/mecon-blocks`

## Webhooks & Callbacks

**Incoming:**
- Contact Form 7 validation callback hooks via WordPress filter system in `wp-content/plugins/mecon-blocks/includes/05-cf7.php`

**Outgoing:**
- None detected (no external HTTP client calls found in focused components)

---

*Integration audit: 2026-03-12*
