# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this app is

A Call of Cthulhu tabletop RPG character sheet web app. Players manage their own characters (stats, skills, weapons, sanity, hit points, etc.) on a smartphone-friendly interface. The "Keeper" (game master) has a dashboard to send messages and roll skills against selected players without them knowing.

## Tech stack

- **PHP 8.4 / Laravel 12** — Inertia.js v1 server-side rendering, no API
- **Vue 3** — frontend via Inertia pages; no separate SPA routing
- **Admin section** — native Inertia pages at `/admin` (see below); Filament has been removed
- **Livewire v3** — available but minimal current usage
- **Spatie Laravel Permission** — role system (`RoleEnum`: `player`, `keeper`, `admin`); roles are cumulative
- **Laravel Reverb + Echo** — real-time messaging
- **Tailwind CSS v3** — single dark-framed theme; `darkMode: 'class'` so `dark:` variants never fire off the OS preference
- **Vite** — served via Laravel Valet at `cthulhu.test` with TLS

## Commands

```bash
# Development
composer run dev          # starts all dev processes together
npm run dev               # Vite dev server (HMR)
npm run build             # production asset build

# Testing
php artisan test                                    # all tests
php artisan test tests/Feature/CharacterTest.php   # single file
php artisan test --filter=testName                  # single test by name
npx vitest                                          # JS component tests (vitest)

# Code style (run before finalising PHP changes)
vendor/bin/pint --dirty

# Database
php artisan migrate
php artisan db:seed --class=SkillSeeder            # seeds canonical skill list
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Architecture: how the pieces connect

### Inertia data flow
`HandleInertiaRequests` (`app/Http/Middleware/HandleInertiaRequests.php`) shares global data on every request: `auth.user`, `auth.characters` (all / others / own), `auth.equipment`, `auth.users`. Every Vue page can read these from `usePage().props`.

### Character model & pivot
`Character` ↔ `Skill` is a many-to-many with a pivot (`character_skill`) carrying `value`, `experience`, `order`, and `show`. The `show` column controls per-character skill visibility. Characters use `slug` as the route key.

`app/Misc/CharacterCreation.php` contains pure static helpers for derived stats (dodge, sanity, hit points, move rate, damage bonus, build) — these are not stored but computed from the core attributes.

### Weapons & ammunition
`app/Misc/WeaponTable.php` is the canonical transcription of the Investigator
Handbook weapons table (pp. 250–254) — 104 weapons with `category`, `era`,
`impale` and the verbatim "1920s/modern" `cost` cell. Both `WeaponSeeder` and the
sync migration read from it, so add weapons there rather than in the seeder.

`Character` ↔ `Weapon` is a morph pivot (`equipables`) carrying `ammo` (rounds in
the magazine) and `ammo_reserve` (rounds carried). Magazine size is derived, not
stored: `Weapon::$magazine_capacity` parses the book's free-text "Bullets in Gun
(Mag)" column and returns `null` for anything uncountable. The shoot/reload
arithmetic lives in `WeaponController` — the client only renders what it returns.

### Equipment
Which eras a weapon is offered in lives in `eras`, not in `era` — see **Eras** below.

`app/Misc/EquipmentTable.php` is the transcription of the handbook's 1920s equipment lists
(pp. 238–246) — 258 items in 13 sections, carrying the book's price cell verbatim. It holds only what
an investigator would carry; food, lodging, real estate, furniture, household goods, vehicles and
fares are deliberately absent, as are the p.246 melee weapons, which live in `WeaponTable` instead.
Add items there, not in `EquipmentSeeder`.

`equipables` is **both** kinds of possession: `Character::weapons()` and `Character::equipment()` are
two `morphedByMany` relations over the same table, which is what lets the Equipment tab show a
revolver and its spare rounds side by side. The pivot carries `storage_location_id`, `quantity` and
`notes` for both.

`StorageLocation` is a table, not an enum — players add their own from the sheet. The four starting
places come from `StorageLocation::STARTING_LOCATIONS`.

A name a player types that the catalogue lacks becomes an `EquipmentItem` with `is_custom = true`, so
the typeahead offers it next time; the admin Equipment page filters to those for pruning. Prices are
only ever shown while choosing — never against something already owned.

### Roles
Three roles (see `RoleEnum`): `player`, `keeper` (= GM), `admin`. **Roles are cumulative** — a user
may hold any combination of the three, so always ask `hasRole()` / `isAdmin()` / `isKeeper()`, never
compare against a single value. Spatie's `model_has_roles` pivot is the only source of truth; the old
`users.role` column is gone.

The frontend reads `usePage().props.auth.roles` (an array) via the `useRoles()` composable in
`resources/js/Pages/Composables/useRoles.js`. Serialised users carry `role_names`; eager-load `roles`
when listing them or it costs a query per row.

Authorization is enforced via `CharacterPolicy` and the `admin` middleware alias
(`EnsureUserIsAdmin`). Players may only view/edit their own characters.

### Admin section
Native Inertia pages under `/admin`, controllers in `app/Http/Controllers/Admin/` extending
`AdminController`, Vue pages in `resources/js/Pages/Admin/` wrapped in `AdminLayout`. Pages: Group,
Users, Skills, Weapons, Equipment.

**Admin authority is per-group.** An admin manages the group they belong to and nothing else:
`AdminController::requireGroup()` supplies the group, and `memberOfCurrentGroup()` 404s on anyone
outside it.

Creating groups and moving a player between them reach across groups, so they stay with the artisan
commands in `app/Console/Commands/` (`php artisan cthulhu:manage` for the interactive menu), which
run with the server operator's authority rather than any one group's.

### Reference data: skills, weapons and equipment
These tables are shared by every group on the server, so editing them is not group-scoped and sits
behind `cthulhu.admin.edit_reference_data` (`config/cthulhu.php`, env
`CTHULHU_ADMIN_EDIT_REFERENCE_DATA`, default on). It is on while one group plays here; **turn it off
once a second group exists** and the lists go back to being console-only (`SkillSeeder`,
`App\Misc\WeaponTable`, `App\Misc\EquipmentTable` and the migrations reading from them).

The toggle is enforced by the `reference-data` middleware alias on the write routes, not just hidden
in the UI. Reading and searching are never gated. Pages get an `editable` prop to decide what to
offer. It does **not** gate players adding equipment to their own sheets — that goes through
`EquipmentController`, authorized by `CharacterPolicy`.

### Eras
A group plays in one era (`groups.era`, the `Era` enum: `1920s`, `modern`). `Skill`, `Weapon` and
`EquipmentItem` each carry an **`eras` JSON list** saying which eras they belong to — a list, because
most things belong to both. It is **never empty**: something available throughout carries every era,
and the `HasEras` trait (`app/Models/Concerns/HasEras.php`) normalises an empty list back to all of
them on save. Use its `inEra(?Era)` scope and `availableIn(?Era)` helper; passing `null` means "every
era", so callers without one need not branch.

`app/Misc/EraTable.php` is where the values come from. Only the weapons' are from the book —
`forWeapon()` parses the handbook's printed availability cell ("1920s, Modern", "WWII, Later",
"Rare"). The skills and equipment are **guesses**, deliberately kept in that one file rather than
spread through the seeders. `weapons.era` stays as the book prints it and is a note, not a filter;
`weapons.eras` is what anything queries.

On the character sheet (`$character->era()`, the group's, or the Twenties while ungrouped) the era
**narrows what is offered, never what is owned**: the weapon and equipment pickers open on this era
with a "show every era" tick, out-of-era skills stay off the sheet until they have a value above
their starting one, and anything already owned is shown with an era chip rather than hidden. A
Keeper handing a 1920s table a Garand is a legitimate move.

`Skill`, `Weapon`, `EquipmentItem` and `StorageLocation` all use `SoftDeletes` ("retire" in the UI).
A retired row keeps its id, so:
- it drops out of `$character->skills`, `$character->weapons` and the shared armoury automatically —
  the relations apply the soft-delete scope;
- its pivot rows (`character_skill`, `equipables`) survive, so restoring puts it back on every sheet
  with the values and ammunition each character had;
- the unique indexes on `skills.slug`, `skills.display_name` and weapon names still cover retired
  rows, so validation checks uniqueness **including trashed** and tells the admin to restore instead.

### Vue page structure
- `resources/js/Pages/` — Inertia page components (one per route)
- `resources/js/Pages/Components/Character/` — the character sheet tab components (Characteristics, Skills, Vitals, Equipment, Backstory). `Equipment.vue` composes `Weapons.vue` (stats and ammunition) over `EquipmentList.vue` (everything owned, grouped by where it is kept)
- `resources/js/Pages/Composables/` — shared Vue composables
- `resources/js/Components/` — generic UI primitives (buttons, inputs, modal, tabs)

### Design system
The visual language is a dark green frame holding parchment-coloured panels, with brass
accents and blood red reserved for danger and damage. Reusable classes live in
`@layer components` in `resources/css/app.css` — **use these rather than repeating raw
utility strings**:

- Layout: `.page` (standard page container — pages own their width; the layout's `<main>` is neutral)
- Surfaces: `.panel` (parchment sheet), `.card`, `.card-marked` (brass-ringed), `.card-dark`
- Buttons: `.btn-primary`, `.btn-secondary`, `.btn-ghost`, `.btn-danger`, plus `.btn-sm`
- Forms: `.field`, `.field-label`, `.field-hint`, `.field-error`, `.field-inline` (edit-in-place)
- Text: `.display` (Limelight, for names and page titles), `.eyebrow`, `.eyebrow-on-dark`
- Chips: `.chip`, `.chip-brass`, `.chip-blood`; `.tabular` for aligned figures

Palette families are `cthulhu-green` (canvas and ink, 50–950), `parchment` (card surfaces),
`cthulhu-yellow` (brass accents) and `cthulhu-blood` (danger). Do not reach for Tailwind's
default `gray-*`, `indigo-*` or `bg-white` — nothing in the app uses them.

### Real-time messaging
`MessageSent` event broadcasts via Reverb. `resources/js/echo.js` configures Laravel Echo. `SendMessage` is a queued job.

## Key conventions

- Characters are always routed by `slug`, not `id`.
- Enums go in `app/Enums/`; enum keys are TitleCase (e.g. `RoleEnum::Keeper`).
- Always use `casts()` method on models, not the `$casts` property.
- Use PHP 8 constructor property promotion; never leave empty `__construct()`.
- Always use explicit return types on methods.
- PHPDoc over inline comments; add array shape types for complex arrays.
- Inertia navigation: use `<Link>` or `router.visit()`, never plain `<a>` tags.
- Form submissions: use `router.post` / `router.put`; never HTML `<form>` with full-page POST.
- Tailwind spacing in lists: `gap-*` utilities, not margins.
- Reach for the `@layer components` classes (`.panel`, `.btn-primary`, `.field`, …) before writing new utility strings.
- No `dark:` variants — the app ships one theme (see Design system).
- `env()` only inside `config/` files; everywhere else use `config()`.
- `vendor/bin/pint --dirty` must pass before any PHP change is considered done.
- All tests are Pest; create with `php artisan make:test --pest {Name}`.
- Feature tests use `RefreshDatabase` (configured in `tests/Pest.php`).
