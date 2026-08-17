---
name: cthulhu-style
description: The Cthulhu app's house style — the dark-green-and-parchment visual language, the component classes to reach for before writing utilities, the phone-first layout rules, and the plain-spoken voice of its UI copy and comments. Use when writing or reviewing any Vue page or component, Blade view, CSS, Tailwind config, or user-facing text in this repo.
---

# The Cthulhu house style

## The idea in one line

**A dark green frame holding sheets of aged paper, with brass for what matters and blood for what
hurts.** Everything below follows from that. A screen that reads as a dossier on a table in a dim
room is right; a screen that reads as a SaaS dashboard is wrong, however tidy.

There is **one theme**. No light mode, no `dark:` variants, no OS preference. `darkMode: 'class'` in
`tailwind.config.js` exists solely to stop `dark:` firing by accident — nothing ships a matching
palette.

## Reach in this order

1. **An `@layer components` class** from `resources/css/app.css` — `.panel`, `.card`, `.btn-primary`,
   `.field`, `.chip`, `.eyebrow`, `.page`, `.display`, `.tabular`.
2. **An existing component** — `Modal.vue`, `Tabs.vue`, `CollapsibleSection.vue`, `EraChips.vue`,
   `EraPicker.vue`, `SkillMultiSelect.vue`, `Pagination.vue`, `PartyTable.vue`.
3. **Utilities in the project palette** — only for the bit that is genuinely one-off.
4. **A new `@layer components` class** — only once the same utility string has appeared three times.

If you find yourself writing `rounded-xl bg-parchment-50 p-4 ring-1 ring-parchment-300`, you wanted
`.card`.

## The palette

Four families, and nothing else. See `references/palette.md` for every shade and what it is for.

| Family | Role |
| --- | --- |
| `cthulhu-green` 50–950 | The frame, the ink, the canvas. 950 is the page behind everything; 900 the nav; 800–900 body text on parchment; 500 muted text; 200 text on dark. |
| `parchment` 50–500 | Card surfaces and hairlines. 100 is a panel, 50 a card inside it, 300 a divider, 400 a field ring. |
| `cthulhu-yellow` 200–700 | Brass. Focus rings, the active nav item, "look at this", success. Never a whole surface. |
| `cthulhu-blood` 200–600 | Danger, damage, madness. Destructive buttons, failed rolls, a meter running out. Never decoration. |

**Never** reach for Tailwind's defaults — no `gray-*`, `slate-*`, `indigo-*`, `bg-white`,
`text-black`. Nothing in the app uses them, and one of them shows instantly.

## Page anatomy

Every authenticated screen is the same three nested things:

```vue
<template>
    <Head title="Skills" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Keeper</h1>
            <span v-if="game" class="chip-brass">{{ game.name }} · {{ game.era }}</span>
        </template>

        <div class="page">
            <section class="panel p-4 sm:p-5">…</section>
            <section class="panel p-4 sm:p-5">…</section>
        </div>
    </AuthenticatedLayout>
</template>
```

- `<Head title>` on every page. The title is the plain noun — `Skills`, `Keeper`, not `Admin — Skills`.
- The `#header` slot sits in a dark band and is a **flex row**: title on the left, a chip or an
  action on the right. Never put a `.panel` in it.
- `.page` owns the width, the gutters and the `space-y-5` between sections. The layout's `<main>` is
  deliberately neutral, so a page that does not use `.page` is a page with no margins.
- Admin pages use `<AdminLayout title="Skills">` instead, which supplies the eyebrow, the `<h1>` and
  the section nav; the page body starts straight at `<section class="panel">`.
- **Never add `<FlashMessages />` to a page.** Both layouts render it. A nested one doubles up.

## Sections and headings

A `<section class="panel p-4 sm:p-5">` per topic. `p-4 sm:p-6` for text-heavy pages, `p-5 sm:p-6`
for admin tables — pick one and be consistent within a page.

Two heading registers, and they mean different things:

- `class="display text-lg text-cthulhu-green-900"` — **Limelight**, for names and for a section that
  is a *thing*: an empty state, a modal title, an investigator.
- `class="text-base font-semibold text-cthulhu-green-900"` — plain, for a section that is a *place
  to work*: "Secret roll", "The party", "Your cast".

Never use `.display` for a paragraph, a label, or anything below `text-lg`. Limelight has no
lowercase weight to spare.

A section heading commonly carries its explanation on the same line, right-aligned and muted:

```vue
<div class="flex flex-wrap items-baseline justify-between gap-2">
    <h2 class="text-base font-semibold text-cthulhu-green-900">Your cast</h2>
    <p class="field-hint">Conjured up whole, in this game, and visible to nobody but you.</p>
</div>
```

`.eyebrow` (small caps, muted green) labels a *group* of fields or a table column. `.eyebrow-on-dark`
is the same thing over green.

## Buttons

`.btn-primary` (green, filled) · `.btn-secondary` (parchment, ringed) · `.btn-ghost` (bare) ·
`.btn-danger` (blood). Add `.btn-sm` for anything in a table row or a filter bar.

- **One primary per section.** If two things are equally the point, both are secondary.
- A filter that is a toggle swaps class rather than adding a tick:
  `:class="filters.trashed ? 'btn-primary' : 'btn-secondary'"`.
- Cancel is always `.btn-ghost`, always on the left of the submit.
- `.btn` already carries the focus ring, the disabled state and `gap-2` for an icon. Don't re-add them.

## Forms

```vue
<div>
    <label for="skill-name" class="field-label">Name</label>
    <input id="skill-name" v-model="form.display_name" type="text" class="field mt-1" required />
    <p class="field-hint">What the sheets refer to. Leave it empty and it is derived from the name.</p>
    <p v-if="form.errors.display_name" class="field-error">{{ form.errors.display_name }}</p>
</div>
```

- Every input has an `id` and a real `<label>`. When the label would be visual clutter — a search
  box, a filter select — keep the label and add `class="sr-only"`. Do not fall back to `placeholder`
  as the only label.
- `.field` covers text inputs, selects and textareas alike. Add `.tabular` to numeric ones.
- `.field-hint` is where the *reason* goes. This app explains itself constantly; a hint that repeats
  the label is worse than none.
- `.field-inline` is the edit-in-place control on the sheet: invisible until enabled, brass ring on
  focus.
- Inertia `useForm` for anything that navigates; plain `axios` for the sheet's constant small saves
  (a characteristic, a round fired) — see CLAUDE.md on 419s before changing which.

## Tables

```vue
<div class="mt-4 overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-parchment-300">
                <th scope="col" class="py-2 pr-3 eyebrow">Skill</th>
                <th scope="col" class="py-2 pr-3 eyebrow text-right">Base</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-parchment-300">
            <tr v-for="skill in skills.data" :key="skill.id">
                <td class="py-2.5 pr-3 font-semibold text-cthulhu-green-900">{{ skill.display_name }}</td>
                <td class="py-2.5 pr-3 text-right tabular text-cthulhu-green-900">{{ skill.starting_value }}%</td>
            </tr>
            <tr v-if="skills.data.length === 0">
                <td colspan="2" class="py-6 text-center text-sm text-cthulhu-green-500">…</td>
            </tr>
        </tbody>
    </table>
</div>
```

- Always wrapped in `overflow-x-auto`. A phone will meet this table.
- Figures are `text-right tabular`. Always. A column of numbers that does not line up is a bug.
- The first cell is `font-semibold text-cthulhu-green-900`; supporting cells drop to `-700`;
  slugs and ids to `text-xs text-cthulhu-green-500`.
- The empty row is **not** one message. It says which of the filters emptied the table:

```vue
<template v-if="filters.trashed">No skill has been retired.</template>
<template v-else-if="filters.search">No skill matches “{{ filters.search }}”.</template>
<template v-else>There are no skills yet.</template>
```

## Empty states

An empty *screen* is a panel with a display heading and a hint saying who can fix it:

```vue
<section class="panel p-5 sm:p-6">
    <h2 class="display text-lg text-cthulhu-green-900">No game is being played</h2>
    <p class="field-hint mt-1">
        The Keeper's screen shows the party of the campaign your group is playing. An admin can
        start one on the Group page.
    </p>
</section>
```

Never "Nothing here." An empty state names the thing that is missing and the next move.

## Chips, meters and figures

- `.chip` for a neutral fact, `.chip-brass` for a good outcome or the current campaign, `.chip-blood`
  for a bad one. Chips are read-only — a chip that can be clicked should be a `.btn-sm`.
- `.tabular` on every number a player compares: skill values, ammunition, dice, counts in a chip.
- A meter turns to blood below a third remaining
  (`meterTone = (v) => v <= 1/3 ? 'bg-cthulhu-blood-400' : 'bg-cthulhu-green-600'`). Keep that
  threshold; players read it at a glance.
- Compound labels join with a middle dot and spaces: `{{ [archetype, occupation].filter(Boolean).join(' · ') }}`.

## Icons

Heroicons, and by default `@heroicons/vue/20/solid`. `24/outline` is for the nav's hamburger only;
`24/solid` for the big vitals glyphs.

```vue
<PlusIcon class="size-4" aria-hidden="true" />
```

`size-4` beside text, `size-5` when the icon is the control. Always `size-*`, never `h-4 w-4`.
Always `aria-hidden="true"` — the button's own text is the label. An icon-only button needs
`aria-label` or an `.sr-only` span.

## Phone first

The sheet lives on a phone at a table for a whole evening. That is the design constraint, not a
consideration.

- Base styles are the phone. `sm:` adds the desk. Almost every breakpoint in the app is `sm:`; `lg:`
  is for widening a grid, `md:`/`xl:` are rare and need a reason.
- Grids start at `grid-cols-2` and grow: `grid grid-cols-2 gap-3 lg:grid-cols-4`.
- A row of tabs becomes a `<select>` under `sm` — that is what `Tabs.vue` does, don't reinvent it.
- A long catalogue arrives folded (`CollapsibleSection.vue`), not scrolled.
- Tap targets are at least the `.btn` height. `.btn-sm` is the floor, and only in a dense row.
- Spacing between list items is `gap-*` on the container, never margins on the children.
- `.page` handles `env(safe-area-inset-bottom)` for the home indicator. Don't add bottom padding on
  top of it.

## Modals

`Modal.vue` supplies the backdrop, the escape key, the scroll lock and the transitions. Its slot
gets a `.panel`:

```vue
<Modal :show="showForm" max-width="xl" @close="showForm = false">
    <form class="panel flex flex-col gap-4 p-6" @submit.prevent="submit">
        <h2 class="display text-lg text-cthulhu-green-900">{{ editing ? `Edit ${editing.name}` : 'New skill' }}</h2>
        …
        <div class="flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" @click="showForm = false">Cancel</button>
            <button type="submit" class="btn-primary" :disabled="form.processing">
                {{ editing ? 'Save' : 'Add skill' }}
            </button>
        </div>
    </form>
</Modal>
```

A tall form gets `flex max-h-[85vh] flex-col overflow-y-auto`. One `useForm` serves both create and
edit — `form.defaults()`, then `form.reset()` and `form.clearErrors()` on open.

## Destructive actions

Two shapes, and which one you use depends on where you are:

- **Admin, and anything with consequences worth spelling out:** `confirm()` with a sentence that says
  what survives. `Retire ${weapon.name}? It leaves every sheet carrying it, ammunition and all, and
  comes back if you restore it.` Never `Are you sure?`.
- **At the table, mid-scene:** a two-press inline confirm — the button turns `.btn-danger` and its
  label becomes the question (`Delete` → `For good?`). No dialog in the way. See `Keeper.vue`.

Retiring is soft-deletion and is reversible, so it is `.btn-danger` but the copy is calm. Actual
deletion says so: `Permanently delete ${name}? This cannot be undone.`

## Motion and focus

- `transition` with Tailwind's default duration. Anything longer than 300ms is too slow for a phone.
- Fold animations use `grid-rows-[0fr]` → `grid-rows-[1fr]` so nothing has to be measured, and carry
  `motion-reduce:transition-none`.
- Focus is **brass on dark, green on parchment**: `focus-visible:outline-cthulhu-yellow-500` over
  green surfaces, `focus-visible:outline-cthulhu-green-800` over parchment. The `.btn-*` classes
  already do this. Never `focus:outline-none` without a visible replacement.

## The voice

The copy is as much of the style as the colour. Full guidance and examples in
`references/voice.md`; the short version:

- **Plain English, complete sentences, and the reason included.** "Retiring a skill takes it off the
  sheets that carry it without losing their values — restore it and they come back."
- **The game's own nouns**: investigator, Keeper, sheet, campaign, era, the party, the table. Not
  user, GM, record, session.
- **No exclamation marks, no jokes, no "Oops".** The tone is a careful narrator, not a chirpy app.
- **Typography is real**: em dashes `—`, curly quotes `“ ”`, ellipsis `…`, middle dot `·`. The
  codebase is consistent about this; match it.
- Comments explain *why*, in the same voice — `/* Deleting is for good, so it asks once — inline,
  without a dialog in the way. */`

## Never

- `gray-*`, `slate-*`, `indigo-*`, `bg-white`, `text-black`
- `dark:` anything
- A raw `<a>` for internal navigation — `<Link>` or `router.visit()`
- An HTML `<form action>` full-page POST — `router.post` / `useForm`
- `h-4 w-4` on an icon — `size-4`
- Margins between list items — `gap-*`
- A second `<FlashMessages />`
- Re-typing a utility string that a `@layer components` class already names
- `Are you sure?`, `Oops!`, `Something went wrong.`

## Before you call it done

- [ ] Every colour is from the four families.
- [ ] Every repeated utility string is a component class instead.
- [ ] It works at 375px: no horizontal scroll, no tap target under `.btn-sm`.
- [ ] Numbers are `.tabular` and right-aligned.
- [ ] Every input has a label; every icon-only control has an accessible name; every icon is
      `aria-hidden`.
- [ ] The empty state names what is missing and the next move.
- [ ] The destructive copy says what survives.
- [ ] `npm run build` is clean and the copy reads aloud without wincing.
