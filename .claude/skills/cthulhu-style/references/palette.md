# The palette, shade by shade

Defined in `tailwind.config.js`. Four families and nothing else — no `gray-*`, `slate-*`,
`indigo-*`, `bg-white`, `text-black`.

## cthulhu-green — ink and canvas

The deep green the whole app is framed in.

| Shade | Hex | Where it is used |
| --- | --- | --- |
| 50 | `#F4F7F0` | Rare. Almost-white green; parchment is usually the better choice. |
| 100 | `#DDEBDA` | Rare. |
| 200 | `#ADBC9F` | **Body text on dark.** Nav links at rest, `.eyebrow-on-dark`, hints in the header band. |
| 300 | `#80AF81` | Muted text on dark — a secondary line under a nav item, an inactive tab's icon. |
| 350 | `#508D4E` | Rare. The one off-scale shade. |
| 400 | `#436850` | Rare. |
| 500 | `#2F5540` | **Muted text on parchment.** `.eyebrow`, `.field-hint`, slugs, "—" placeholders, empty-state prose. |
| 600 | `#1A5319` | Focus rings on fields, a healthy meter, an active tab's icon. |
| 700 | `#1A4A38` | **Secondary body text on parchment.** Table cells that support the first column, paragraph text in a panel. Also `.btn-primary` hover. |
| 800 | `#12372A` | **Primary buttons, `.card-dark`, the active admin nav pill.** The workhorse green. |
| 900 | `#0C251C` | **The nav bar, the header band, and primary text on parchment.** |
| 950 | `#071711` | **The page behind everything.** `body`, the modal backdrop at 70%. |

Rules of thumb: text on parchment runs 900 → 700 → 500 as it gets less important. Text on green
runs parchment-100 → green-200 → green-300 the same way.

## parchment — card surfaces

Aged paper: the investigator's dossier.

| Shade | Hex | Where it is used |
| --- | --- | --- |
| 50 | `#FDFBF3` | `.card`, `.field` background. The lightest paper, used *inside* a panel. |
| 100 | `#F7F2E1` | **`.panel`** — the main sheet — and text on dark green. |
| 200 | `#EDE4CB` | `.btn-secondary`, a collapsible's heading strip. |
| 300 | `#DCD1AF` | **Hairlines.** `.divider`, `border-b` on a table head, `divide-y` in a table body, `.card`'s ring. |
| 400 | `#C3B58B` | `.field` ring, `.btn-secondary` ring and active state, checkbox borders. |
| 500 | `#A2946B` | Rare. The darkest paper. |

## cthulhu-yellow — brass

Accents and attention. **Never a whole surface.**

| Shade | Hex | Where it is used |
| --- | --- | --- |
| 200 | `#F5E7B2` | `.chip-brass` background, `.card-marked` at 50% — the "read this" note. |
| 300 | `#EFD48A` | Rare. |
| 400 | `#F9D689` | **The brand mark** and the active admin nav's text on green. |
| 500 | `#D9A93F` | **Focus rings on dark surfaces**, `.card-marked`'s ring, an inline field's focus ring. |
| 600 | `#E0A75E` | Rare. |
| 700 | `#A87A33` | `.chip-brass` text — the only brass dark enough to read on `yellow-200`. |

Brass means *notice this*: a successful roll, the campaign being played, the field you are editing,
the thing the Keeper should not miss.

## cthulhu-blood — danger and damage

| Shade | Hex | Where it is used |
| --- | --- | --- |
| 200 | `#FF204E` | Rare. Brightest; reserve for something that must be alarming. |
| 300 | `#D81E48` | `.btn-danger` hover. |
| 400 | `#A0153E` | **`.btn-danger`, `.field-error`, `.chip-blood`, a meter below a third, a skill ready to improve.** |
| 500 | `#7E1140` | `.btn-danger` active, a warning line under a control. |
| 600 | `#5D0E41` | Rare. |

Blood is never decoration. If nothing is being lost, damaged, deleted or failed, it is the wrong
colour.

## Type

```js
sans:    ['Figtree', ...defaultTheme.fontFamily.sans]
display: ['Limelight', ...defaultTheme.fontFamily.serif]
```

Limelight is reached only through `.display`, and only at `text-lg` and above — names, page titles,
modal titles, empty-state headings. Everything else is Figtree.

`.tabular` (`font-variant-numeric: tabular-nums`) on any figure a player compares.

## Shadows

| Token | Where |
| --- | --- |
| `shadow-card` | `.panel`. A sheet of paper lying on the table. |
| `shadow-raised` | The modal only. Paper picked up off the table. |
| `shadow-sm` | Filled buttons and `.field`. |

Both custom shadows are tinted with `rgb(7 23 17)` — green-950 — rather than black, which is why
they sit on the dark canvas without looking like a hole.

## Rings, not borders

The app uses `ring-1 ring-inset` far more than `border`. Rings do not affect layout, so a focus
state can thicken to `ring-2` without anything moving. Use `border` only for true rules: a table
head's underline, a divider, the nav's bottom edge.
