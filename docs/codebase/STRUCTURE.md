# Codebase Structure

**Analysis Date:** 2026-03-12

## Directory Layout

```text
wp-content/
├── themes/
│   └── mecon-theme/                    # Child block theme (layout/templates/global styles)
│       ├── functions.php               # Theme bootstrap hooks
│       ├── theme.json                  # Global design tokens, template definitions
│       ├── templates/                  # Page template files used by block theme
│       ├── parts/                      # Template parts (header variants)
│       ├── patterns/                   # Theme block patterns
│       ├── assets/                     # Theme fonts/images
│       └── docs/codebase/              # Architecture/structure reference docs
└── plugins/
    └── mecon-blocks/                   # Custom Gutenberg block/plugin package
        ├── plugin.php                  # Plugin entry point
        ├── includes/                   # Runtime module registration files
        ├── blocks/                     # Per-block modules (source + build output)
        ├── patterns/                   # Plugin block patterns + pattern styles
        ├── src/                        # Shared editor scripts (hero sidebar) + global SCSS
        ├── assets/                     # Shared plugin assets and generated global CSS
        ├── webpack.config.js           # Build orchestration for blocks/patterns/styles
        └── package.json                # JS toolchain dependencies/scripts
```

## Directory Purposes

**`wp-content/themes/mecon-theme/templates/`:**
- Purpose: Define page-level block markup for specific page template variants.
- Contains: `page-hero.html`, `page-featured-image.html`, `page-no-title.html`.
- Key files: `wp-content/themes/mecon-theme/templates/page-hero.html`.

**`wp-content/themes/mecon-theme/parts/`:**
- Purpose: Store template-part markup consumed by template-part references.
- Contains: `header.html`, `mecon-header.html`.
- Key files: `wp-content/themes/mecon-theme/parts/mecon-header.html`.

**`wp-content/themes/mecon-theme/patterns/`:**
- Purpose: Theme-scoped block patterns.
- Contains: PHP pattern files with standard block pattern header comments.
- Key files: `wp-content/themes/mecon-theme/patterns/header.php`.

**`wp-content/plugins/mecon-blocks/includes/`:**
- Purpose: Runtime module boundaries for plugin behavior.
- Contains: block registration (`01-blocks.php`), pattern registration (`02-patterns.php`), meta setup (`03-meta.php`), script/style enqueue (`04-scripts.php`), CF7 hook (`05-cf7.php`).
- Key files: `wp-content/plugins/mecon-blocks/includes/01-blocks.php`, `wp-content/plugins/mecon-blocks/includes/02-patterns.php`.

**`wp-content/plugins/mecon-blocks/blocks/`:**
- Purpose: One folder per custom block, each with block metadata, editor source, frontend render, and built artifacts.
- Contains: active block modules (`hero`, `buttons`, `collapsible`, `checkmark-list`, `feature-item`, `jumbotron`, `key-fact`, `link-card`, `quote-card`) plus external/dependency modules (`carousel`, `slider`) and placeholder folder (`image-grid`).
- Key files: `wp-content/plugins/mecon-blocks/blocks/hero/block.json`, `wp-content/plugins/mecon-blocks/blocks/hero/src/index.tsx`, `wp-content/plugins/mecon-blocks/blocks/hero/render.php`.

**`wp-content/plugins/mecon-blocks/patterns/`:**
- Purpose: Plugin-scoped reusable layout patterns and their style sources.
- Contains: root pattern PHP files, nested pattern group folders like `mecon-text-and-image/`, and style sources in `scss/` with compiled output in `css/`.
- Key files: `wp-content/plugins/mecon-blocks/patterns/contact-form.php`, `wp-content/plugins/mecon-blocks/patterns/scss/contact-form.scss`, `wp-content/plugins/mecon-blocks/patterns/css/contact-form.css`.

## Key File Locations

**Entry Points:**
- `wp-content/themes/mecon-theme/functions.php`: Child theme runtime bootstrap.
- `wp-content/themes/mecon-theme/theme.json`: Block theme template + style configuration entry.
- `wp-content/plugins/mecon-blocks/plugin.php`: Plugin bootstrap entry.
- `wp-content/plugins/mecon-blocks/includes/01-blocks.php`: Block registration entry on `init`.

**Configuration:**
- `wp-content/themes/mecon-theme/style.css`: Theme metadata and parent theme linkage (`Template: twentytwentyfive`).
- `wp-content/plugins/mecon-blocks/webpack.config.js`: Build outputs and entry discovery rules.
- `wp-content/plugins/mecon-blocks/tsconfig.json`: TS compilation scope and strictness.
- `wp-content/plugins/mecon-blocks/package.json`: Build/watch commands.

**Core Logic:**
- `wp-content/plugins/mecon-blocks/includes/02-patterns.php`: Recursive pattern/category registration.
- `wp-content/plugins/mecon-blocks/includes/03-meta.php`: Hero meta schema registration.
- `wp-content/plugins/mecon-blocks/src/hero-sidebar.js`: Editor document sidebar for hero metadata.
- `wp-content/plugins/mecon-blocks/blocks/*/render.php`: Server-render logic per block.

**Testing:**
- Not applicable: no test directories or test configs are detected in `wp-content/themes/mecon-theme/` or `wp-content/plugins/mecon-blocks/`.

## Naming Conventions

**Files:**
- Plugin include files use numeric prefixes for load order: `wp-content/plugins/mecon-blocks/includes/01-blocks.php`.
- Block modules use fixed file contract names: `block.json`, `render.php`, `src/index.tsx`, optional `src/view.ts` in `wp-content/plugins/mecon-blocks/blocks/<name>/`.
- Theme templates use `page-<variant>.html` naming: `wp-content/themes/mecon-theme/templates/page-hero.html`.

**Directories:**
- Block directories are kebab-case slugs: `wp-content/plugins/mecon-blocks/blocks/checkmark-list`.
- Pattern group directories are kebab-case categories: `wp-content/plugins/mecon-blocks/patterns/mecon-text-and-image`.

## Where to Add New Code

**New Feature:**
- Primary code: add runtime hooks to a new include file under `wp-content/plugins/mecon-blocks/includes/` and require it from `wp-content/plugins/mecon-blocks/plugin.php`.
- Tests: not applicable (no existing test harness in `wp-content/themes/mecon-theme/` or `wp-content/plugins/mecon-blocks/`).

**New Component/Module:**
- Implementation: create a new block folder at `wp-content/plugins/mecon-blocks/blocks/<new-block>/` with `block.json`, `src/index.tsx`, `render.php`, and styles; registration occurs automatically via `wp-content/plugins/mecon-blocks/includes/01-blocks.php`.

**Utilities:**
- Shared helpers: place plugin-wide editor utilities in `wp-content/plugins/mecon-blocks/src/` and enqueue via `wp-content/plugins/mecon-blocks/includes/04-scripts.php`.
- Shared visual assets: place reusable images/icons in `wp-content/plugins/mecon-blocks/assets/` and load from block renderers/styles.

## Special Directories

**`wp-content/plugins/mecon-blocks/blocks/carousel/`:**
- Purpose: Carousel block integration surface; uses Swiper at runtime.
- Generated: Partially (contains source and compiled assets).
- Committed: Yes.

**`wp-content/plugins/mecon-blocks/blocks/slider/`:**
- Purpose: External/dependency placeholder directory for slider-related integration.
- Generated: No detected generated files.
- Committed: Yes.

**`wp-content/plugins/mecon-blocks/node_modules/`:**
- Purpose: Local dependency installation for plugin build tooling.
- Generated: Yes.
- Committed: Yes (currently present in repository).

**`wp-content/plugins/mecon-blocks/build/` and `wp-content/plugins/mecon-blocks/dist/`:**
- Purpose: Build artifact/output directories.
- Generated: Yes.
- Committed: Yes (currently present in repository).

**`wp-content/themes/mecon-theme/docs/codebase/`:**
- Purpose: Human-authored codebase reference docs for architecture and structure.
- Generated: No.
- Committed: Yes.

---

*Structure analysis: 2026-03-12*
