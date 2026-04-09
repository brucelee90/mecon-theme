# Codebase Concerns

**Analysis Date:** 2026-03-12

## Tech Debt

**Generated assets and source drift risk:**
- Issue: Source files and generated artifacts are both committed, and one generated artifact is clearly a development bundle (`eval`-based output) instead of production output.
- Files: `wp-content/plugins/mecon-blocks/blocks/carousel/index.min.js`, `wp-content/plugins/mecon-blocks/blocks/carousel/view.min.js`, `wp-content/plugins/mecon-blocks/webpack.config.js`, `wp-content/plugins/mecon-blocks/.gitignore`
- Impact: Frontend payload size increases, production debugging noise leaks into shipped JS, and local rebuilds can silently diverge from committed assets.
- Fix approach: Build with production-safe output for all blocks, enforce one canonical build path, and add CI validation that compiled artifacts match source before merge.

**Global block allowlist is hard-coded:**
- Issue: Editor block availability is replaced globally with a static allowlist + `mecon-blocks/*`, without using context-specific rules.
- Files: `wp-content/plugins/mecon-blocks/includes/01-blocks.php`
- Impact: New core blocks and third-party blocks are blocked by default, causing editor regressions after WordPress/plugin updates.
- Fix approach: Gate allowlist by `$editor_context`, keep default behavior where not explicitly restricted, and move allowed block config to one maintainable map.

**Pattern content is environment-coupled:**
- Issue: Many registered patterns contain hard-coded local host media URLs and placeholder domains.
- Files: `wp-content/plugins/mecon-blocks/patterns/cta-cards.php`, `wp-content/plugins/mecon-blocks/patterns/logos.php`, `wp-content/plugins/mecon-blocks/patterns/featured-video.php`, `wp-content/plugins/mecon-blocks/patterns/content-panels.php`, `wp-content/themes/mecon-theme/patterns/header.php`
- Impact: Pattern previews and inserted content break after migration across environments, requiring manual URL cleanup.
- Fix approach: Replace hard-coded URLs with attachment IDs/dynamic media helpers, and keep pattern content environment-agnostic.

## Known Bugs

**Carousel editor contains debug and placeholder text artifacts:**
- Symptoms: Editor labels include typo/placeholder text (`Image Sliderrr`, `Image Sliderrrrrrrrrr`) and debug logging runs in editor interactions.
- Files: `wp-content/plugins/mecon-blocks/blocks/carousel/block.json`, `wp-content/plugins/mecon-blocks/blocks/carousel/src/index.tsx`
- Trigger: Add/edit the slider block in Gutenberg.
- Workaround: None in code; requires source cleanup and rebuild.

**Frontend scripts log to browser console in production path:**
- Symptoms: User-visible console noise for collapsible interactions.
- Files: `wp-content/plugins/mecon-blocks/blocks/collapsible/src/view.ts`, `wp-content/plugins/mecon-blocks/blocks/collapsible/view.min.js`
- Trigger: Load a page with `mecon-blocks/collapsible` and click toggles.
- Workaround: None in code; remove logs and rebuild assets.

## Security Considerations

**Inline SVG output bypasses escaping pipeline:**
- Risk: SVG markup loaded with `file_get_contents()` is echoed directly; if the SVG asset is modified unexpectedly, unsafe markup can be served.
- Files: `wp-content/plugins/mecon-blocks/blocks/buttons/render.php`, `wp-content/plugins/mecon-blocks/blocks/link-card/render.php`, `wp-content/plugins/mecon-blocks/blocks/quote-card/render.php`, `wp-content/plugins/mecon-blocks/blocks/collapsible/render.php`
- Current mitigation: Assets are loaded from plugin-local files under version control.
- Recommendations: Validate file existence, sanitize allowed SVG tags/attributes before output, and centralize SVG rendering in one hardened helper.

**Pattern registration executes PHP from all pattern files:**
- Risk: Every `.php` file under `patterns/` is `include`d during registration.
- Files: `wp-content/plugins/mecon-blocks/includes/02-patterns.php`
- Current mitigation: Directory is plugin-local and not user-editable in normal editor flows.
- Recommendations: Restrict includes to a controlled list or enforce strict file header validation plus non-executable pattern content generation.

## Performance Bottlenecks

**Pattern stylesheet discovery runs file-system scans per request:**
- Problem: Styles are discovered with `glob()` and versioned via `filemtime()` on every frontend/editor enqueue.
- Files: `wp-content/plugins/mecon-blocks/includes/04-scripts.php`
- Cause: Runtime filesystem traversal instead of manifest-driven enqueue.
- Improvement path: Generate a static asset manifest at build time and enqueue from cached metadata.

**Carousel bundle ships large dependency surface:**
- Problem: Carousel assets are disproportionately large for one block, including full Swiper runtime internals.
- Files: `wp-content/plugins/mecon-blocks/blocks/carousel/view.min.js`, `wp-content/plugins/mecon-blocks/blocks/carousel/style.css`, `wp-content/plugins/mecon-blocks/package.json`
- Cause: Dependency-heavy client bundle plus broad module footprint.
- Improvement path: Limit imported Swiper modules, tree-shake aggressively, and split editor/view assets with strict size budgets.

## Fragile Areas

**Hero feature relies on tightly coupled meta keys across PHP and JS:**
- Files: `wp-content/plugins/mecon-blocks/includes/03-meta.php`, `wp-content/plugins/mecon-blocks/src/hero-sidebar.js`, `wp-content/plugins/mecon-blocks/blocks/hero/render.php`, `wp-content/plugins/mecon-blocks/blocks/hero/src/index.tsx`
- Why fragile: Meta field names are duplicated in multiple files without shared constants or schema generation.
- Safe modification: Add or rename hero fields only through a single source-of-truth map, then update all consumers in one change.
- Test coverage: No automated tests detected for hero meta registration, editor panel behavior, or render output.

**Template part references depend on parent theme behavior:**
- Files: `wp-content/themes/mecon-theme/style.css`, `wp-content/themes/mecon-theme/functions.php`, `wp-content/themes/mecon-theme/templates/page-hero.html`, `wp-content/themes/mecon-theme/parts/header.html`
- Why fragile: Child theme depends on `twentytwentyfive` template parts while local `parts/header.html` is empty, increasing fallback ambiguity on parent changes.
- Safe modification: Keep child template part overrides explicit and non-empty for any slug used by child templates.
- Test coverage: No snapshot/regression tests detected for template rendering across WordPress updates.

## Scaling Limits

**Pattern maintenance scales poorly with manual static markup:**
- Current capacity: Pattern library is maintained as many static PHP files with repeated HTML/image references.
- Limit: Changes to shared structure, URLs, or visual tokens require many manual edits and increase drift risk.
- Scaling path: Introduce shared helpers/partials for repeated pattern fragments and move media references to dynamic attachment resolution.

## Dependencies at Risk

**`twentytwentyfive` (parent theme dependency):**
- Risk: Child theme behavior and styling can shift when parent theme templates/styles change.
- Impact: Layout or style regressions in child templates and global style inheritance.
- Migration plan: Override critical template parts and styles in child theme to reduce reliance on parent internals.

**`swiper` (carousel runtime):**
- Risk: Major/minor API and bundle-size changes can affect frontend slider behavior and performance.
- Impact: Carousel rendering, navigation/autoplay behavior, and page performance.
- Migration plan: Pin compatible versions, document supported API usage, and add smoke tests for slider initialization.

**`carousel-block` / `wp-swiper` (external plugin overlap):**
- Risk: Not detected in this codebase, but naming/feature overlap with `mecon-blocks/slider` can create duplicate UI patterns or script/style conflicts when installed.
- Impact: Editor confusion, duplicated block options, and possible frontend class/script collisions.
- Migration plan: Keep interoperability checks in staging and namespace block classes/scripts defensively.

## Missing Critical Features

**No automated quality gates for plugin/theme changes:**
- Problem: No test runner config or test files are detected, and plugin scripts only provide `build`/`start`.
- Blocks: Safe refactors of render callbacks, block registration, pattern registration, and enqueue logic.

## Test Coverage Gaps

**Server-rendered blocks and registration hooks are untested:**
- What's not tested: Render callbacks, block registration scan, pattern registration, and script/style enqueue behavior.
- Files: `wp-content/plugins/mecon-blocks/includes/01-blocks.php`, `wp-content/plugins/mecon-blocks/includes/02-patterns.php`, `wp-content/plugins/mecon-blocks/includes/04-scripts.php`, `wp-content/plugins/mecon-blocks/blocks/*/render.php`
- Risk: Regressions ship undetected during WordPress/core dependency updates.
- Priority: High

**Theme template and style integration is untested:**
- What's not tested: Child theme compatibility with parent template/style changes and page template rendering.
- Files: `wp-content/themes/mecon-theme/functions.php`, `wp-content/themes/mecon-theme/theme.json`, `wp-content/themes/mecon-theme/templates/page-hero.html`, `wp-content/themes/mecon-theme/templates/page-featured-image.html`, `wp-content/themes/mecon-theme/templates/page-no-title.html`
- Risk: Rendering breakage appears only at runtime after updates.
- Priority: Medium

---

*Concerns audit: 2026-03-12*
