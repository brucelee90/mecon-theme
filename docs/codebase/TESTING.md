# Testing Patterns

**Analysis Date:** 2026-03-12

## Test Framework

**Runner:**
- Not detected
- Config: Not detected (`jest.config.*`, `vitest.config.*`, `phpunit.xml*`, `playwright.config.*`, and `cypress.config.*` are absent in `wp-content/themes/mecon-theme` and `wp-content/plugins/mecon-blocks`)

**Assertion Library:**
- Not detected

**Run Commands:**
```bash
npm run build            # Build plugin assets and surface bundling failures (`wp-content/plugins/mecon-blocks/package.json`)
npm run start            # Watch compile assets while changing blocks (`wp-content/plugins/mecon-blocks/package.json`)
npx tsc -p tsconfig.json # Type-check TypeScript source in plugin (`wp-content/plugins/mecon-blocks/tsconfig.json`)
```

## Test File Organization

**Location:**
- No automated test directories are present (`__tests__`, `tests`, `test` not found in `wp-content/themes/mecon-theme` and `wp-content/plugins/mecon-blocks`)

**Naming:**
- No `*.test.*` or `*.spec.*` files are present in `wp-content/themes/mecon-theme` and `wp-content/plugins/mecon-blocks`

**Structure:**
```
Not applicable (no committed automated test suite)
```

## Test Structure

**Suite Organization:**
```typescript
// Not detected: no describe/it/test suites in source directories.
```

**Patterns:**
- Setup pattern: manual WordPress environment setup via Local WP and plugin activation; no scripted fixtures.
- Teardown pattern: manual editor/frontend verification; no automated cleanup hooks.
- Assertion pattern: visual/behavior checks in Gutenberg editor and rendered frontend output.

## Mocking

**Framework:**
- Not detected

**Patterns:**
```typescript
// Not applicable: no test runner or mocking utilities in use.
```

**What to Mock:**
- If automated tests are added, mock WordPress editor/store selectors around `useSelect` in `wp-content/plugins/mecon-blocks/blocks/hero/src/index.tsx`.
- If automated tests are added, mock browser-only APIs used by view scripts in `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts` and `wp-content/plugins/mecon-blocks/blocks/collapsible/src/view.ts`.

**What NOT to Mock:**
- Do not mock the external Swiper integration internals in `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts`; treat `carousel-block/wp-swiper` behavior as an external integration and verify it via runtime smoke tests.

## Fixtures and Factories

**Test Data:**
```typescript
// Current pattern is inline defaults in block attributes:
// `wp-content/plugins/mecon-blocks/blocks/*/block.json`
// `wp-content/plugins/mecon-blocks/blocks/*/src/index.tsx`
```

**Location:**
- No shared fixtures/factories directory is present.
- Default values are embedded per block (`wp-content/plugins/mecon-blocks/blocks/carousel/block.json`, `wp-content/plugins/mecon-blocks/blocks/collapsible/block.json`, `wp-content/plugins/mecon-blocks/blocks/link-card/block.json`).

## Coverage

**Requirements:**
- None enforced

**View Coverage:**
```bash
Not applicable (coverage tooling not configured)
```

## Test Types

**Unit Tests:**
- Not used currently.
- Candidate low-cost unit target: utility parsing in `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts` (`parseBool`, `parseIntSafe`).

**Integration Tests:**
- Not used currently.
- Current reality is manual integration validation in WordPress editor + frontend for dynamic block render paths (`wp-content/plugins/mecon-blocks/blocks/*/render.php`).

**E2E Tests:**
- Not used

## Common Patterns

**Async Testing:**
```typescript
// Not implemented. Current async behavior depends on DOM ready hooks:
// document.addEventListener('DOMContentLoaded', initAllSliders)
// in `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts`
```

**Error Testing:**
```typescript
// Not implemented. Defensive behavior is currently in runtime guards:
// - fallback parsing in `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts`
// - ABSPATH/file/dir guards in `wp-content/plugins/mecon-blocks/includes/*.php`
```

## Pragmatic Checks (Current Baseline)

- Build + type checks on every change touching block source: run `npm run build` and `npx tsc -p tsconfig.json` in `wp-content/plugins/mecon-blocks`.
- PHP syntax check on changed server-rendered files before commit: run `php -l` against edited files in `wp-content/plugins/mecon-blocks/blocks/*/render.php`, `wp-content/plugins/mecon-blocks/includes/*.php`, and `wp-content/themes/mecon-theme/functions.php`.
- Gutenberg editor smoke pass for changed blocks: create/edit/save each touched block and confirm no editor console errors in `wp-content/plugins/mecon-blocks/blocks/*/src/index.tsx`.
- Frontend smoke pass for dynamic blocks: validate rendered markup/classes in browser for `wp-content/plugins/mecon-blocks/blocks/hero/render.php`, `wp-content/plugins/mecon-blocks/blocks/carousel/render.php`, and `wp-content/plugins/mecon-blocks/blocks/link-card/render.php`.
- External integration check for carousel: verify navigation, pagination, autoplay toggles, and breakpoints driven by `wp-content/plugins/mecon-blocks/blocks/carousel/src/view.ts` while treating Swiper internals as third-party.

---

*Testing analysis: 2026-03-12*
