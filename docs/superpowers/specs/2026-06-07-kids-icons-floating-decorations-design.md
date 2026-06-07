# Kids Icons — Floating Decorations Design

**Date:** 2026-06-07
**Status:** Approved

## Goal

Make the Ameer homepage (`index.html`, `theme-sunny theme-v2`) feel more playful and
"alive" for a kids-nutrition audience by adding the sticker-style PNG icons in
`assets/icon-kids/` as a layer of gently animated floating decorations distributed
across all sections.

Interactivity scope: **ambient motion only** (drift / sway / bob / parallax). No
click or hover reactions.

## Assets

`assets/icon-kids/`: `forest.png` (two trees + sun), `bird.png` (dove), `ladybug.png`,
`balloon.png`, `tree.png`. Bold black-outlined cartoon stickers in the site palette
(green / red / white).

## Approach

Augment, don't replace. Add a reusable `.kid-icon` decoration system on top of the
existing per-section `.X-deco` SVG decorations. No existing art is removed.

### Markup pattern

Each decoration is a parallax wrapper around an `<img>`:

```html
<div class="kid-icon kid-icon-<name>" aria-hidden="true" data-parallax="0.2">
  <img src="assets/icon-kids/<file>.png" alt="" />
</div>
```

Parallax sets an inline `transform` on the **wrapper**; the ambient keyframe animation
runs on the inner `<img>`, so depth and float compose without conflict. (Putting both
on one element would let the inline parallax transform override the CSS animation.)
`data-parallax` is optional per icon and the existing JS already targets `[data-parallax]`
page-wide — no JS changes needed.

### Base CSS (`css/sunny-v2.css`)

`.theme-sunny .kid-icon`: `position:absolute`, `pointer-events:none`, `z-index` below
content, drop-shadow, responsive width via `clamp()`. Inner `img`: `width:100%; height:auto; display:block`.

### Placement & motion

| Section  | Icon(s)                  | Motion                              |
|----------|--------------------------|-------------------------------------|
| Hero     | bird (across sky), balloon (side) | bird: `fly-across`; balloon: `bob` + tilt |
| About    | ladybug (corner), tree (edge)     | ladybug: `wiggle`; tree: `sway`     |
| Products | forest (lower edge)               | `sway` (slow)                       |
| Tips     | ladybug, balloon                  | `wiggle` / `bob`                    |
| Feedback | bird, balloon                     | `fly-across` / `bob`                |
| Social   | tree                              | `sway`                              |
| Events   | forest (base, near yellow hill)   | `sway`                              |

1–2 icons per section, kept to section edges so they never crowd content.

### Keyframes

Reuse existing `bob` and `sway`. Add two small ones:
- `fly-across` — long-loop `translateX` with a subtle vertical bob (bird flight).
- `wiggle` — a few degrees of rotate on a short loop (ladybug).

## Non-goals / Safety

- No click/hover interactivity.
- All icons `aria-hidden="true"` + `pointer-events:none` — decorative, never block clicks.
- Motion inherits the existing global `prefers-reduced-motion` rule (`css/base.css`),
  which kills all animations — no extra handling required.
- Sections must not overflow horizontally from off-edge icons (use `overflow` already
  present on sections / keep icons within bounds).

## Files touched

- `index.html` — add `.kid-icon` markup per section.
- `css/sunny-v2.css` — add `.kid-icon` base block + `fly-across` and `wiggle` keyframes.
