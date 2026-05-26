# Architecture

`wordpress-regulatory-disclosure-kit` is a hybrid WordPress + static-demo repo:

- a WordPress plugin publishes a machine-readable disclosure manifest and shortcode-rendered proof block
- a PHP control plane exposes synthetic disclosure lanes, evidence packs, and verification gates for local review
- a prerender step emits a static `site/` bundle so the public demo can ship through GitHub Pages under `disclosure.kineticgain.com`

## Core layers

1. `plugin/wordpress-regulatory-disclosure-kit.php`
   - exposes a sample disclosure manifest through a shortcode and REST route
2. `src/Services/RegulatoryDisclosureKitService.php`
   - loads synthetic operator data and computes the summary layer
3. `src/Views/render.php`
   - renders the locked dark-neon shell across overview, disclosure, evidence, verification, and docs routes
4. `scripts/prerender.php`
   - emits static HTML and JSON artifacts into `site/` for GitHub Pages deployment

## Why the shape matters

Regulated copy governance breaks when legal, SEO, and commercial disclaimers drift apart across homepage modules, PDFs, support macros, and schema fields. This repo makes those relationships visible in one operator surface.
