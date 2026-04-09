# Technology Stack

**Analysis Date:** 2026-03-12

## Languages

**Primary:**
- PHP (version not pinned in repo) - WordPress theme/plugin runtime in `wp-content/themes/mecon-theme/functions.php` and `wp-content/plugins/mecon-blocks/plugin.php`

**Secondary:**
- TypeScript 5.9.x (tooling dependency) - Gutenberg block editor/frontend code in `wp-content/plugins/mecon-blocks/blocks/**/src/*.ts` and `wp-content/plugins/mecon-blocks/blocks/**/src/*.tsx`
- JavaScript (ES modules, transpiled/minified) - built block and editor scripts in `wp-content/plugins/mecon-blocks/blocks/**/index.min.js` and `wp-content/plugins/mecon-blocks/src/hero-sidebar.min.js`
- SCSS/CSS - block, pattern, and global styles in `wp-content/plugins/mecon-blocks/blocks/**/src/*.scss`, `wp-content/plugins/mecon-blocks/patterns/scss/*.scss`, `wp-content/plugins/mecon-blocks/assets/css/global.css`, and `wp-content/themes/mecon-theme/style.css`
- JSON - block/theme metadata and config in `wp-content/plugins/mecon-blocks/blocks/**/block.json`, `wp-content/themes/mecon-theme/theme.json`, and `wp-content/plugins/mecon-blocks/tsconfig.json`

## Runtime

**Environment:**
- WordPress 6.9.4 core in `wp-includes/version.php`
- PHP runtime required by WordPress and plugins; WordPress minimum required PHP is 7.2.24 in `wp-includes/version.php`
- Browser runtime for frontend block scripts declared via `viewScript` in `wp-content/plugins/mecon-blocks/blocks/carousel/block.json`

**Package Manager:**
- npm (project uses npm scripts in `wp-content/plugins/mecon-blocks/package.json`)
- Lockfile: present (`wp-content/plugins/mecon-blocks/package-lock.json`, lockfileVersion 3)

## Frameworks

**Core:**
- WordPress Core 6.9.4 - CMS and hook/block runtime used by theme/plugin in `wp-includes/version.php`
- Gutenberg Block API (via `register_block_type`) - block registration pattern in `wp-content/plugins/mecon-blocks/includes/01-blocks.php`
- Block Theme system (`theme.json`) - global styles/settings in `wp-content/themes/mecon-theme/theme.json`

**Testing:**
- Not detected for `wp-content/themes/mecon-theme`
- Not detected for `wp-content/plugins/mecon-blocks`

**Build/Dev:**
- Webpack 5 - custom multi-config build pipeline in `wp-content/plugins/mecon-blocks/webpack.config.js`
- TypeScript + `ts-loader` - TS/TSX transpilation in `wp-content/plugins/mecon-blocks/webpack.config.js` and `wp-content/plugins/mecon-blocks/tsconfig.json`
- `@wordpress/scripts` 26.x (dev dependency) - WordPress build preset/tooling in `wp-content/plugins/mecon-blocks/package.json`
- Mini CSS Extract + Sass loader - SCSS to CSS output in `wp-content/plugins/mecon-blocks/webpack.config.js`

## Key Dependencies

**Critical:**
- `swiper` ^12.1.2 - carousel runtime for custom slider block in `wp-content/plugins/mecon-blocks/package.json` and `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts`
- `@wordpress/blocks` / `@wordpress/block-editor` - Gutenberg editor APIs used during block build and runtime mapping in `wp-content/plugins/mecon-blocks/package.json` and `wp-content/plugins/mecon-blocks/webpack.config.js`

**Infrastructure:**
- `webpack`, `webpack-cli`, `webpack-glob-entries`, `webpack-remove-empty-scripts` - dynamic entry discovery and asset output in `wp-content/plugins/mecon-blocks/webpack.config.js`
- `typescript` and WordPress type packages - typed editor code in `wp-content/plugins/mecon-blocks/package.json` and `wp-content/plugins/mecon-blocks/tsconfig.json`

## Configuration

**Environment:**
- No application env-file contract detected in the focused components (`wp-content/themes/mecon-theme` and `wp-content/plugins/mecon-blocks`)
- `.env*` files were not detected in these two component roots
- Runtime configuration is WordPress-driven through PHP hooks and metadata files in `wp-content/themes/mecon-theme/functions.php`, `wp-content/themes/mecon-theme/theme.json`, and `wp-content/plugins/mecon-blocks/blocks/**/block.json`

**Build:**
- Build commands are `npm run build` and watch mode `npm start` in `wp-content/plugins/mecon-blocks/package.json`
- Build orchestration and externals mapping are in `wp-content/plugins/mecon-blocks/webpack.config.js`
- TypeScript compiler options are in `wp-content/plugins/mecon-blocks/tsconfig.json`

## Platform Requirements

**Development:**
- WordPress installation with this child theme (`Template: twentytwentyfive`) in `wp-content/themes/mecon-theme/style.css`
- Node.js + npm available to build `wp-content/plugins/mecon-blocks` assets (`wp-content/plugins/mecon-blocks/package.json`)
- Contact Form 7 plugin expected where contact pattern/honeypot is used in `wp-content/plugins/mecon-blocks/patterns/contact-form.php` and `wp-content/plugins/mecon-blocks/includes/05-cf7.php`

**Production:**
- PHP + WordPress hosting serving block theme + custom block plugin (`wp-content/themes/mecon-theme` + `wp-content/plugins/mecon-blocks`)
- Built assets should exist in committed/output locations used by block metadata (`wp-content/plugins/mecon-blocks/blocks/**/block.json`, `wp-content/plugins/mecon-blocks/assets/css/global.css`)

---

*Stack analysis: 2026-03-12*
