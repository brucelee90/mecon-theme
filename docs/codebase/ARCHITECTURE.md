# Architecture

**Analysis Date:** 2026-03-12

## Pattern Overview

**Overall:** WordPress block-theme plus block-plugin composition

**Key Characteristics:**
- Theme-level layout and global design tokens are defined in `wp-content/themes/mecon-theme/theme.json` and applied through block templates in `wp-content/themes/mecon-theme/templates/*.html`.
- Plugin-level capabilities (custom blocks, patterns, post meta, editor sidebar, global/pattern CSS) are bootstrapped from `wp-content/plugins/mecon-blocks/plugin.php` through modular include files in `wp-content/plugins/mecon-blocks/includes/*.php`.
- Runtime output is predominantly server-rendered dynamic blocks (`"save": null` + `render.php`) such as `wp-content/plugins/mecon-blocks/blocks/hero/render.php` and `wp-content/plugins/mecon-blocks/blocks/buttons/render.php`.

## Layers

**Theme Layout Layer:**
- Purpose: Compose page skeletons and shared template parts.
- Location: `wp-content/themes/mecon-theme/templates/`, `wp-content/themes/mecon-theme/parts/`, `wp-content/themes/mecon-theme/patterns/`.
- Contains: Block HTML templates (`page-hero.html`, `page-featured-image.html`), header pattern (`patterns/header.php`), template part placeholders.
- Depends on: WordPress block template engine and parent theme template parts referenced from `wp-content/themes/mecon-theme/theme.json`.
- Used by: Frontend requests for pages using custom templates declared in `wp-content/themes/mecon-theme/theme.json`.

**Theme Runtime Setup Layer:**
- Purpose: Hook parent/child styles and establish child-theme runtime wiring.
- Location: `wp-content/themes/mecon-theme/functions.php`.
- Contains: `wp_enqueue_scripts` hook for parent stylesheet and child stylesheet.
- Depends on: Parent theme `twentytwentyfive` declared in `wp-content/themes/mecon-theme/style.css` (`Template: twentytwentyfive`).
- Used by: All frontend page loads using the child theme.

**Plugin Bootstrap Layer:**
- Purpose: Load block, pattern, metadata, and asset modules.
- Location: `wp-content/plugins/mecon-blocks/plugin.php`, `wp-content/plugins/mecon-blocks/includes/*.php`.
- Contains: Include orchestration in `plugin.php`; module hooks in `01-blocks.php`, `02-patterns.php`, `03-meta.php`, `04-scripts.php`, `05-cf7.php`.
- Depends on: WordPress action/filter system (`init`, `wp_enqueue_scripts`, `enqueue_block_editor_assets`).
- Used by: Block editor and frontend rendering pipeline whenever plugin is active.

**Block Module Layer:**
- Purpose: Define editor behavior and server output per custom block.
- Location: `wp-content/plugins/mecon-blocks/blocks/<block>/`.
- Contains: `block.json`, editor code in `src/index.tsx`, optional `src/view.ts`, server renderer `render.php`, and built assets (`index.min.js`, `view.min.js`, CSS).
- Depends on: Registration loop in `wp-content/plugins/mecon-blocks/includes/01-blocks.php` (`register_block_type` per subdirectory).
- Used by: Gutenberg editor during authoring and PHP renderer during frontend output.

**Pattern Registry Layer:**
- Purpose: Discover and register local block patterns recursively.
- Location: `wp-content/plugins/mecon-blocks/includes/02-patterns.php`, `wp-content/plugins/mecon-blocks/patterns/**/*.php`.
- Contains: Header metadata parsing (`Title`, `Slug`, `Categories`, `Keywords`), folder-driven category registration, `register_block_pattern` calls.
- Depends on: Pattern header comments in files like `wp-content/plugins/mecon-blocks/patterns/contact-form.php`.
- Used by: Inserter pattern UI in editor and rendered content once inserted.

## Data Flow

**Editor Authoring Flow:**

1. WordPress loads `wp-content/plugins/mecon-blocks/plugin.php`, which requires include modules.
2. `wp-content/plugins/mecon-blocks/includes/01-blocks.php` scans `wp-content/plugins/mecon-blocks/blocks/` and registers each block directory.
3. Editor assets are enqueued by `wp-content/plugins/mecon-blocks/includes/04-scripts.php`, including `wp-content/plugins/mecon-blocks/src/hero-sidebar.min.js`.
4. React/TS editor implementations (for example `wp-content/plugins/mecon-blocks/blocks/hero/src/index.tsx`) read/update attributes or post meta and persist to post content/meta via Gutenberg stores.

**Frontend Render Flow:**

1. Theme templates such as `wp-content/themes/mecon-theme/templates/page-hero.html` reference blocks (for example `wp:mecon-blocks/hero`) and template parts (`header`, `footer`).
2. Dynamic block declarations in block metadata (for example `wp-content/plugins/mecon-blocks/blocks/hero/block.json` with `"render": "file:./render.php"`) trigger PHP rendering.
3. Renderer files (for example `wp-content/plugins/mecon-blocks/blocks/hero/render.php`) fetch attributes/meta and produce final HTML with `get_block_wrapper_attributes`.
4. Frontend CSS/JS is attached via `wp-content/plugins/mecon-blocks/includes/04-scripts.php` plus block-level `style.css` and optional `view.min.js` declared in each `block.json`.

**State Management:**
- Page-level content state lives in Gutenberg post content and post meta (`register_post_meta` in `wp-content/plugins/mecon-blocks/includes/03-meta.php`).
- Block editing state is handled client-side by Gutenberg (`useSelect`, `setAttributes`) in files like `wp-content/plugins/mecon-blocks/blocks/hero/src/index.tsx`.
- Frontend interactive state is DOM-local in view scripts like `wp-content/plugins/mecon-blocks/blocks/collapsible/src/view.ts`.

## Key Abstractions

**Directory-Driven Block Registration:**
- Purpose: Avoid manual block registration per block.
- Examples: `wp-content/plugins/mecon-blocks/includes/01-blocks.php`, `wp-content/plugins/mecon-blocks/blocks/hero/block.json`, `wp-content/plugins/mecon-blocks/blocks/buttons/block.json`.
- Pattern: Scan each folder under `blocks/` and call `register_block_type(<folder>)`.

**Dynamic Server-Rendered Blocks:**
- Purpose: Keep frontend markup controlled by PHP with runtime meta/attribute access.
- Examples: `wp-content/plugins/mecon-blocks/blocks/hero/render.php`, `wp-content/plugins/mecon-blocks/blocks/checkmark-list/render.php`, `wp-content/plugins/mecon-blocks/blocks/collapsible/render.php`.
- Pattern: Editor implementation returns `save: () => null`; `block.json` points to `render.php`.

**Meta-Driven Hero Composition:**
- Purpose: Use page meta instead of block attributes for hero content shared with document sidebar.
- Examples: `wp-content/plugins/mecon-blocks/includes/03-meta.php`, `wp-content/plugins/mecon-blocks/src/hero-sidebar.js`, `wp-content/plugins/mecon-blocks/blocks/hero/render.php`.
- Pattern: Sidebar panel writes `_hero_*` meta; hero block reads the same meta in editor and render stages.

**External Dependency Boundary:**
- Purpose: Keep carousel and swiper internals out of core theme/plugin architectural decisions.
- Examples: `wp-content/plugins/mecon-blocks/blocks/carousel/*`, `wp-content/plugins/mecon-blocks/blocks/slider/`, `wp-content/themes/mecon-theme/style.css` (`Template: twentytwentyfive`).
- Pattern: Treat carousel block implementation, wp-swiper behavior, and parent theme implementation as external dependencies; integrate only through stable block/template contracts.

## Entry Points

**Child Theme Bootstrap:**
- Location: `wp-content/themes/mecon-theme/functions.php`
- Triggers: Theme load lifecycle via `wp_enqueue_scripts`.
- Responsibilities: Enqueue parent stylesheet and child stylesheet.

**Theme Template Routing:**
- Location: `wp-content/themes/mecon-theme/theme.json`
- Triggers: Page template selection (`page-hero`, `page-no-title`, `page-featured-image`).
- Responsibilities: Declare custom templates, template parts, global style tokens, and block defaults.

**Plugin Bootstrap:**
- Location: `wp-content/plugins/mecon-blocks/plugin.php`
- Triggers: Plugin activation/runtime include loading.
- Responsibilities: Load modular include files that register blocks, patterns, meta, scripts, and CF7 hooks.

**Block Registration Module:**
- Location: `wp-content/plugins/mecon-blocks/includes/01-blocks.php`
- Triggers: `init` action.
- Responsibilities: Register every block directory and enforce allowed block list for the editor.

## Error Handling

**Strategy:** Guard-and-fallback defensive checks

**Patterns:**
- Use ABSPATH guards (`if ( ! defined( 'ABSPATH' ) ) { exit; }`) in plugin entry and include files such as `wp-content/plugins/mecon-blocks/plugin.php` and `wp-content/plugins/mecon-blocks/includes/04-scripts.php`.
- Use existence checks and fallback defaults for filesystem/resources, for example `is_dir(...)` in `wp-content/plugins/mecon-blocks/includes/02-patterns.php` and fallback asset arrays in `wp-content/plugins/mecon-blocks/includes/04-scripts.php`.

## Cross-Cutting Concerns

**Logging:** Minimal ad-hoc console logging appears in editor/view scripts (for example `console.log` in `wp-content/plugins/mecon-blocks/blocks/collapsible/src/view.ts` and `wp-content/plugins/mecon-blocks/blocks/buttons/src/index.tsx`).
**Validation:** Input sanitization is applied at render time via `esc_url`, `esc_html`, `esc_attr`, and `wp_kses_post` in files like `wp-content/plugins/mecon-blocks/blocks/hero/render.php` and `wp-content/plugins/mecon-blocks/blocks/buttons/render.php`.
**Authentication:** Meta editing is gated by `auth_callback` with `current_user_can( 'edit_posts' )` in `wp-content/plugins/mecon-blocks/includes/03-meta.php`.

---

*Architecture analysis: 2026-03-12*
