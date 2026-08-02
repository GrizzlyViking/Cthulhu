# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this app is

A Call of Cthulhu tabletop RPG character sheet web app. Players manage their own characters (stats, skills, weapons, sanity, hit points, etc.) on a smartphone-friendly interface. The "Keeper" (game master) has a dashboard to send messages and roll skills against selected players without them knowing.

## Tech stack

- **PHP 8.4 / Laravel 12** — Inertia.js v1 server-side rendering, no API
- **Vue 3** — frontend via Inertia pages; no separate SPA routing
- **Filament v3** — admin panel at `/admin`; resources auto-discovered from `app/Filament/Resources/`
- **Livewire v3** — available but minimal current usage
- **Spatie Laravel Permission** — role/permission system (`RoleEnum`: `player`, `keeper`, `admin`)
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

### Roles
Three roles (see `RoleEnum`): `player`, `keeper` (= GM), `admin`. Authorization is enforced via `CharacterPolicy` and Spatie permissions. Players may only view/edit their own characters.

### Filament admin
Resources for `Character`, `Skill`, `Weapon`, `User`, `Group` are under `app/Filament/Resources/`. Admin panel is separate from the player-facing Inertia app.

### Vue page structure
- `resources/js/Pages/` — Inertia page components (one per route)
- `resources/js/Pages/Components/Character/` — the five character sheet tab components (Characteristics, Skills, Vitals, Weapons, Backstory)
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
