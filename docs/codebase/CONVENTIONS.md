# Coding Conventions

**Analysis Date:** 2026-03-12

## Naming Patterns

**Files:**
- Use kebab-case for block folders and assets in `wp-content/plugins/mecon-blocks/blocks/*` (for example `link-card`, `checkmark-list`, `feature-item`).
- Use fixed source entry names inside each block: `index.tsx`, `index.scss`, `style.scss`, optional `view.ts` in `wp-content/plugins/mecon-blocks/blocks/*/src/`.
- Use numeric prefixing for plugin include bootstraps in `wp-content/plugins/mecon-blocks/includes/` (`01-blocks.php`, `02-patterns.php`, `03-meta.php`, `04-scripts.php`, `05-cf7.php`).
- Use lowercase slug naming in block metadata (`"name": "mecon-blocks/..."`) in `wp-content/plugins/mecon-blocks/blocks/*/block.json`.

**Functions:**
- Use `mecon_*` snake_case for PHP functions/hooks in `wp-content/themes/mecon-theme/functions.php` and `wp-content/plugins/mecon-blocks/includes/*.php`.
- Use camelCase for TypeScript helpers and handlers in `wp-content/plugins/mecon-blocks/blocks/*/src/*.ts*` (`parseBool`, `parseIntSafe`, `onSelectImages`, `updateSetting`).
- Use `is*` prefixes for boolean state in TS/TSX (`isOpen`, `isLight`) in `wp-content/plugins/mecon-blocks/blocks/collapsible/src/index.tsx` and `wp-content/plugins/mecon-blocks/blocks/hero/src/index.tsx`.

**Variables:**
- Use snake_case for PHP locals in render/include files (`$wrapper_attributes`, `$btn_primary_text`) in `wp-content/plugins/mecon-blocks/blocks/*/render.php`.
- Use camelCase for JS/TS locals and props (`selectedImages`, `blockProps`) in `wp-content/plugins/mecon-blocks/blocks/*/src/*.ts*`.
- Use BEM-style CSS class segments with `mecon-` prefix (`mecon-slider__controls`, `mecon-hero--light`) across `wp-content/plugins/mecon-blocks/blocks/*/src/*.scss` and `wp-content/plugins/mecon-blocks/src/scss/global.scss`.

**Types:**
- Use PascalCase for TypeScript interfaces (`SliderAttributes`, `HeroMeta`, `LinkCardAttributes`) in `wp-content/plugins/mecon-blocks/blocks/*/src/index.tsx`.

## Code Style

**Formatting:**
- Formatting tool config is not detected (no `.prettierrc`, `prettier.config.*`, `.editorconfig`, or eslint config files in `wp-content/themes/mecon-theme` and `wp-content/plugins/mecon-blocks`).
- Keep existing style per file: tabs are common in PHP and some TS files (`wp-content/plugins/mecon-blocks/webpack.config.js`, `wp-content/plugins/mecon-blocks/blocks/carousel/src/index.tsx`), while 4-space indentation appears in several TSX files (`wp-content/plugins/mecon-blocks/blocks/buttons/src/index.tsx`, `wp-content/plugins/mecon-blocks/blocks/jumbotron/src/index.tsx`).
- Preserve brace/array style already used in WordPress-oriented PHP (`array(...)`) in `wp-content/plugins/mecon-blocks/includes/*.php`.

**Linting:**
- Linting is not configured via repo-level config files.
- TypeScript strictness is enabled (`"strict": true`) in `wp-content/plugins/mecon-blocks/tsconfig.json`.
- Keep compiled artifacts (`index.min.js`, `view.min.js`) generated and source-of-truth in `src` files under `wp-content/plugins/mecon-blocks/blocks/*/src/`.

## Import Organization

**Order:**
1. WordPress package imports (for example `@wordpress/blocks`, `@wordpress/block-editor`, `@wordpress/components`).
2. i18n and React/runtime imports (`@wordpress/i18n`, `react/jsx-runtime`, `@wordpress/element`).
3. Local helpers/types (when present) inside same file.

**Path Aliases:**
- Use package imports for WordPress APIs.
- `tsconfig` maps `@wordpress/*` types via `"paths"` in `wp-content/plugins/mecon-blocks/tsconfig.json`.
- No project-local alias (like `@/`) is detected.

## Error Handling

**Patterns:**
- Use ABSPATH guard early in PHP entry points and includes (`if ( ! defined( 'ABSPATH' ) ) { exit; }`) in `wp-content/themes/mecon-theme/functions.php` and `wp-content/plugins/mecon-blocks/includes/*.php`.
- Use guard clauses for optional files/directories before enqueue or registration (`is_dir`, `file_exists`) in `wp-content/plugins/mecon-blocks/includes/04-scripts.php` and `wp-content/plugins/mecon-blocks/includes/01-blocks.php`.
- Use safe defaults for attributes and meta reads (`??` and fallback strings) in `wp-content/plugins/mecon-blocks/blocks/*/render.php` and `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts`.

## Logging

**Framework:** console

**Patterns:**
- Console logging is present in source and should be treated as temporary debugging only (`wp-content/plugins/mecon-blocks/blocks/carousel/src/index.tsx`, `wp-content/plugins/mecon-blocks/blocks/collapsible/src/view.ts`, `wp-content/plugins/mecon-blocks/blocks/buttons/src/index.tsx`, `wp-content/plugins/mecon-blocks/blocks/jumbotron/src/index.tsx`).
- Keep production behavior deterministic and remove noisy editor/frontend logs before release builds.

## Comments

**When to Comment:**
- Use PHP file headers and short section comments for WordPress hooks and registration responsibilities, as seen in `wp-content/plugins/mecon-blocks/plugin.php`, `wp-content/plugins/mecon-blocks/includes/04-scripts.php`, and `wp-content/themes/mecon-theme/functions.php`.
- Use concise SCSS section labels for larger style modules in `wp-content/plugins/mecon-blocks/blocks/hero/src/style.scss`.

**JSDoc/TSDoc:**
- Full TSDoc/JSDoc blocks are not standard in TS/TSX files.
- Prefer clear interface names and typed callback signatures over verbose comments in `wp-content/plugins/mecon-blocks/blocks/*/src/index.tsx`.

## Function Design

**Size:**
- Keep block definitions self-contained in a single `registerBlockType` module per block (`wp-content/plugins/mecon-blocks/blocks/*/src/index.tsx`).
- Factor reusable, tiny helpers when repeated logic appears (for example `normalizeSelectedImages` in `wp-content/plugins/mecon-blocks/blocks/carousel/src/index.tsx`).

**Parameters:**
- Type destructured `edit` props inline for Gutenberg blocks (`{ attributes, setAttributes }`) in `wp-content/plugins/mecon-blocks/blocks/*/src/index.tsx`.
- Type DOM callbacks explicitly in frontend scripts (`event: Event`, `el: HTMLElement`) in `wp-content/plugins/mecon-blocks/blocks/collapsible/src/view.ts` and `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts`.

**Return Values:**
- Use `save: () => null` for dynamic/server-rendered blocks in `wp-content/plugins/mecon-blocks/blocks/hero/src/index.tsx` and `wp-content/plugins/mecon-blocks/blocks/carousel/src/index.tsx`.
- Use explicit JSX save output only for static blocks in `wp-content/plugins/mecon-blocks/blocks/checkmark-list/src/index.tsx` and `wp-content/plugins/mecon-blocks/blocks/jumbotron/src/index.tsx`.

## Module Design

**Exports:**
- Keep block registration files side-effect based (calling `registerBlockType` directly) in `wp-content/plugins/mecon-blocks/blocks/*/src/index.tsx`.
- Keep PHP integration split by concern and included from one plugin entry in `wp-content/plugins/mecon-blocks/plugin.php`.

**Barrel Files:**
- Barrel files are not used. Import directly from package modules and local files.

---

*Convention analysis: 2026-03-12*
