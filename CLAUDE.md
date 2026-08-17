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

# Static analysis
composer phpstan          # Larastan at level 5 over app/, config/, database/, routes/

# Database
php artisan migrate
php artisan db:seed --class=SkillSeeder            # seeds canonical skill list
php artisan db:seed --class=RolesAndPermissionsSeeder

# Deploying (on the server: ssh cthulhu)
cd /var/www/cthulhu && ./deploy.sh                  # pull, install, build, migrate, optimize
```

## Architecture: how the pieces connect

### Inertia data flow
`HandleInertiaRequests` (`app/Http/Middleware/HandleInertiaRequests.php`) shares global data on every request: `auth.user`, `auth.characters` (all / others / own), `auth.equipment`, `auth.users`. Every Vue page can read these from `usePage().props`.

### Character model & pivot
`Character` ↔ `Skill` is a many-to-many with a pivot (`character_skill`) carrying `value`, `experience`, `order`, and `show`. The `show` column controls per-character skill visibility. Characters use `slug` as the route key.

`app/Misc/CharacterCreation.php` contains pure static helpers for derived stats (dodge, sanity, hit points, move rate, damage bonus, build) — these are not stored but computed from the core attributes.

`moveRate()` takes the **age deduction** off (a point per decade from the forties, capped at the eighties' five); `baseMoveRate()` is the 7/8/9 rule on its own. The rule lives there and nowhere else: `Character::move_rate` is an accessor over it, so the sheet follows an edit to STR, DEX, SIZ or age, and the wizard writes the same figure it showed. `wizardData.js`'s `ageModifiers()` mirrors the deduction on the client — change both together.

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
Users, Skills, Occupations, Weapons, Equipment.

**Admin authority is per-group.** An admin manages the group they belong to and nothing else:
`AdminController::requireGroup()` supplies the group, and `memberOfCurrentGroup()` 404s on anyone
outside it.

Creating groups and moving a player between them reach across groups, so they stay with the artisan
commands in `app/Console/Commands/` (`php artisan cthulhu:manage` for the interactive menu), which
run with the server operator's authority rather than any one group's.

### The Keeper's screen
`/keeper` (`KeeperController`, `resources/js/Pages/Keeper.vue`) is the Keeper's view of the party in
the group's **active game**: current hit points, sanity, magic points and luck, whatever conditions
are set, the passive skill values, and any weapon that takes ammunition with its rounds. **Current
figures only** — maxima belong on the player's own sheet.

Behind the `keeper` middleware alias (`EnsureUserIsKeeper`). Roles are cumulative, so it asks for the
Keeper's hat specifically: an admin who does not run the game is refused.

`cthulhu.keeper.passive_skills` (`config/cthulhu.php`) drives both the table's skill columns and the
secret-roll buttons — noticing is passive, so the Keeper rolls it rather than the player asking. An
unknown slug is ignored. `POST /keeper/roll` rolls **per character** (a player may have several) and
silently drops anyone outside the active game, the same silence the roll has always kept. Only the
latest roll is shown; nothing is stored.

Attendance ("here tonight") is per-Keeper, per-game, in `localStorage` — no schema, no effect on
anyone else's screen. Absentees dim and are left out of the rolls.

`App\Misc\SkillCheck::against()` is the one place the success ladder lives. It is a **house
simplification** of the Keeper Rulebook fumble rule (1 criticals, 99–100 fumble), kept verbatim from
the original secret roll — changing it changes the game.

### The Keeper's cast
Below the party on the same screen sits the Keeper's own cast: characters **nobody plays**, conjured up
whole in one press so a cultist can be produced mid-scene. `Keeper\NpcController` (`POST /keeper/npcs`,
`DELETE /keeper/npcs/{character}`) is the whole of it — there is no page and no wizard.

They are rows in `characters`, because a cultist has the same shape as an investigator. Three columns
tell them apart:

- `kind` (`CharacterKind`: `investigator`, `npc`) — what the row is;
- `keeper_id` — whose it is. **`user_id` stays null**, which alone keeps them out of every list that
  asks for a player's own sheets (`playersOwn`, `User::characters()`, the landing route);
- `archetype` (`Archetype`) — what it was conjured up as, for the chip on the screen.

**Only the Keeper who made one may see it.** `CharacterPolicy` short-circuits on `isNpc()` for view,
update, patch and delete: not the players, not another Keeper of the same group, not an admin. This is
deliberately narrower than every other rule in that policy. The lists a player is handed
(`HandleInertiaRequests`, the admin counts) go through the `investigators()` scope, and
`keeper.npcs.destroy` answers **404** rather than 403 on somebody else's, so a refusal cannot confirm
that a cultist exists.

Generation is `App\Misc\NpcGenerator::conjure()`, and it is **not** the book's investigator creation:
points are spent at random in lumps so two cultists differ, the archetype's `combat_floor` overrides
whatever the points said, and the ageing rules are skipped. Everything era-dependent — weapons, gear,
names, which occupations exist — comes from the game being played, so one archetype arms a 1920s cult
with a revolver and a modern one with a Glock. The numbers live in **`App\Misc\ArchetypeTable`** (a
house table, meant to be tuned) and the names in `App\Misc\NpcNames`; the occupation is the Keeper's to
pick from a dropdown, or the archetype picks something typical of itself.

Deleting is **complete**: `Character::purge()` detaches skills, weapons, equipment and games and then
`forceDelete()`s — `equipables` has no cascade to do it. Deleting a game purges the cast in it too,
since a cultist is listed nowhere else and would otherwise sit in the table unreachable; the
investigators keep their sheets, as they always have.

**Monsters** are meant to slot in here: a third `CharacterKind`, a `MonsterTable` of stat blocks where
`ArchetypeTable` holds archetypes, and a generator reading it instead of occupations. The screen, the
policy, the purge and the cast query need nothing new — they ask `kind`, not what the thing is. Nothing
has been written for them because there is no monster manual to transcribe yet.

### Reference data: skills, weapons, equipment and occupations
These tables are shared by every group on the server, so editing them is not group-scoped and sits
behind `cthulhu.admin.edit_reference_data` (`config/cthulhu.php`, env
`CTHULHU_ADMIN_EDIT_REFERENCE_DATA`, default on). It is on while one group plays here; **turn it off
once a second group exists** and the lists go back to being console-only (`SkillSeeder`,
`OccupationSeeder`, `App\Misc\WeaponTable`, `App\Misc\EquipmentTable` and the migrations reading from
them).

The toggle is enforced by the `reference-data` middleware alias on the write routes, not just hidden
in the UI. Reading and searching are never gated. Pages get an `editable` prop to decide what to
offer. It does **not** gate players adding equipment to their own sheets — that goes through
`EquipmentController`, authorized by `CharacterPolicy` — nor a player writing an occupation in the
wizard, which goes through `CharacterWizardController` on the same terms (see **Occupations**).

`skills.description` is what a player reads in the box that opens on a skill, and it is the
handbook's own words: `App\Misc\SkillDescriptions` is chapter 5 (pp. 95–121) transcribed and keyed by
slug, minus the pushing material and the era sidebars, which are the Keeper's. `SkillSeeder` and the
backfill migration both read from it, so **edit the description there, not in the seeder**. Skills the
players wrote themselves are deliberately absent — the book has nothing to say about them and their
descriptions are somebody's own words.

`skills.starting_value` is the book's printed base chance, corrected on 2026-08-17 for the fourteen
that were wrong. Seven of those were combat skills, where `App\Misc\WeaponTable::skills()` had the
book's figure all along and `SkillSeeder` disagreed — whichever ran last won. `SkillBaseChanceTest`
now asserts the two lists match, so keep them together when adding a weapon skill.

Three values are **deliberately not** the book's, because the book prints no number for them: `dodge`
and `language_own` are seeded at 0 and derived per investigator (half DEX, and EDU), and the generic
`fighting` cannot be purchased at all.

Raising a base lifts the sheets sitting under it — the book's base is what every investigator gets for
free, so nobody should be below it — while anything a player spent points to reach is left alone.
Lowering one takes nothing off anybody. The 2026-08-17 migration does exactly that, and is the shape
to copy if a base ever changes again.

### Occupations
`occupations` is the list the wizard's third step picks from — 28 from the Investigator Handbook
(`OccupationSeeder`) plus whatever the players have written. An occupation is a name, a description,
the eras it belongs to, the formula for its skill point pool, a Credit Rating range and the skills it
trains.

`skill_points_formula` is a list of components summed together, each `{multiplier, options}` where
`options` is one or more characteristics and the **highest** of them counts — which is how the book
writes "EDU × 2 + STR or DEX × 2". `Occupation::CHARACTERISTICS` is the set a component may draw on;
`skillPointsFor()` does the sum and `formulaLabel()` spells it out.

`skills` is **one column holding three kinds of entry**: a plain slug, a `choice`
(`{type, count, options, label}` — "one interpersonal skill"), and an `any`
(`{type, count, label}` — free slots outside the list). `WizardSkillsRequest` enforces all three when
the points are spent. The forms send the three apart (`skills`, `choices`, `any_count`/`any_label`)
and `App\Http\Requests\OccupationRequest::occupationAttributes()` folds them back into the column —
so validation stays flat, and nothing seeded is lost when an admin edits a row.

**Players contribute to the list.** *Custom occupation* on the wizard's Occupation step opens the
same form the admin page uses (`resources/js/Components/OccupationFields.vue`) and posts to
`character.wizard.occupation.store`. What is written joins the shared list marked `is_custom` with
`created_by`, and is chosen for the draft straight away — the step is **not** advanced, so the player
still presses *Save occupation* and the flow reads the same either way. This reaches every group on
the server, which is the point: the lists grow from play, as with player-created skills.

Managed at `/admin/occupations` (`Admin\OccupationController`), which filters by era, by retired, and
by **player-written** — that last is how an admin finds contributions to tidy or prune. Editing never
clears `is_custom` or `created_by`: where a row came from is a fact, not something an edit changes.

`Occupation` uses `SoftDeletes` and `HasEras` like the rest of the reference data. A retired
occupation keeps its id, so the investigators who trained as it still read as what they are; it
simply stops being offered. Names are unique **including retired ones**, so validation tells the
admin to restore rather than dying on the constraint.

### Games
A **game** is a campaign — the thing a group actually plays, and what the era belongs to. Characters
join games **many-to-many** (`character_game`): a game holds a party, and once in a while one
investigator turns up in two campaigns.

A group plays **one game at a time**, and that is structural rather than a convention: the pointer is
`groups.active_game_id`, so two games physically cannot both be active. `Group::startGame()` makes
one (the first becomes active automatically); `Game::activate()` switches which is played.

- `Character::era()` reads the current game's era, falling back to `groups.era` while the character
  is in no game and to the Twenties while it has no group either.
- `Character::currentGame()` is the group's active game when the character is in it, otherwise the
  most recent game they belong to — so a sheet left behind in a finished campaign still reads as
  that campaign, not as today's.
- `in_active_game` is **appended** to every serialised character. It is what the nav sorts on:
  `Characters` holds the active game, `Previous games` holds the rest plus anything in no game.
  It is also what the landing route filters on — see **Where a signed-in user lands** below.
- New characters (wizard and `CharacterController::store`) join the group's active game.
- Games are managed in the admin section under Group — `Admin\GameController`, scoped by
  `gameOfCurrentGroup()`. They are group data, so **not** behind the reference-data toggle.
- Deleting a game only takes investigators out of it; their sheets are untouched. The active game
  cannot be deleted — activate another first, or rename it.
- Players choose which games their own character is in from the sheet's *Manage sheet* panel
  (`character.games.update`, authorized by `CharacterPolicy@update`, so a Keeper may move any sheet
  in their group).

`php artisan group:create` starts a group off with a campaign, so a new group is playable at once.
`player:assign` moves characters' game membership along with the group, since games are group-scoped.

### Where a signed-in user lands
`/home` (`PageController::home`) is a redirect, not a page, and every authenticated entry point
falls back to it: login, email verification, password confirmation, invitation acceptance, the
`redirectUsersTo` guest middleware and the welcome page's *Enter*. `redirect()->intended()` still
wins, so a deep link chased before logging in is unaffected.

It hands back the character the user edited most recently **that is in the group's active game** —
`in_active_game`, so a sheet left in a finished campaign is not somewhere to land. A draft goes to
the wizard instead of a sheet, since it has none yet.

The wizard resumes a draft rather than starting over, but only one that is **in the active game**
(`CharacterWizardController::resumableDraft`) — a draft left half-built in a finished campaign is
not picked up again, so whoever arrives starts from the profile step. While a group plays no game at
all there is no campaign to be outside of, and the latest draft is resumed as before; without that
fallback a fresh draft would be stranded the moment the page reloaded.

With nothing to land on, the split is by role, and roles are cumulative so it asks for the player's
hat specifically (`User::isPlayer()`, **not** `! isKeeper()`): someone who plays goes to the wizard
to make an investigator for the game that is on, and a Keeper or admin who only runs the game gets
the dashboard. This gates the redirect only — `CharacterPolicy::create` still lets anyone create.

The dashboard keeps its own route and its place in the nav.

### Installing to a phone
The sheet is meant to be added to a player's home screen and run without browser chrome — on
**iPhone and Android alike** — so `public/manifest.json` (name, icons, `display: standalone`, the dark
green `theme_color`) plus the `apple-mobile-web-app-*` tags in `resources/views/app.blade.php` are
part of the app, not decoration. `start_url` is `/home`, so launching the icon lands on the
investigator being played (see **Where a signed-in user lands**); a player who is signed out gets the
login screen, since `/home` is behind `auth`.

The manifest carries the whole Android side: Chrome mints a real app from it (own launcher entry, own
task, splash screen from `background_color` and the 512 icon). iOS needs the Apple tags on top of it.

The home-screen icons (`public/images/icon-{180,192,512}.png`, plus
`icon-maskable-512.png`) are the nav's brand mark in brass on dark green, **not** the favicons. They
must stay full-bleed and opaque: iOS composites a transparent icon onto black, and the mark is dark,
so a transparent PNG disappears on the home screen — which is exactly what the old
`android-chrome-*.png` and `apple-touch-icon.png` did before they were replaced. The maskable one
keeps the mark inside the middle ~56% so Android's circle crop cannot clip a tentacle; the `any` ones
fill the square. `favicon-{16,32}.png` and `favicon.ico` are untouched — they are the browser tab,
where transparency is right.

The status bar is `black` rather than translucent, which keeps the top inset at 0 and leaves the
layouts alone. `viewport-fit=cover` plus `env(safe-area-inset-bottom)` on `.page` is what keeps the
last row of a sheet clear of the home indicator — the inset is 0 in a browser, so it only shows once
installed.

There is no service worker: nothing is cached and nothing works offline. Chrome has not needed one to
install from its menu since v108, iOS never did, and the app is useless without the server anyway. It
does mean Chrome will not raise the automatic install banner — players install from the browser menu.

### Eras
`groups.era` is the era a **new game** is born with — the default, not the thing anything queries;
what a sheet actually plays in comes from its game (see **Games** above). `Skill`, `Weapon`,
`EquipmentItem` and `Occupation` each carry an **`eras` JSON list** saying which eras they belong to
— a list, because most things belong to both. It is **never empty**: something available throughout carries every era,
and the `HasEras` trait (`app/Models/Concerns/HasEras.php`) normalises an empty list back to all of
them on save. Use its `inEra(?Era)` scope and `availableIn(?Era)` helper; passing `null` means "every
era", so callers without one need not branch.

`app/Misc/EraTable.php` is where the values come from. Only the weapons' are from the book —
`forWeapon()` parses the handbook's printed availability cell ("1920s, Modern", "WWII, Later",
"Rare"). The skills and equipment are **guesses**, deliberately kept in that one file rather than
spread through the seeders. `weapons.era` stays as the book prints it and is a note, not a filter;
`weapons.eras` is what anything queries.

On the character sheet (`$character->era()` — the game's, then the group's, then the Twenties) the era
**narrows what is offered, never what is owned**: the weapon and equipment pickers open on this era
with a "show every era" tick, out-of-era skills stay off the sheet until they have a value above
their starting one, and anything already owned is shown with an era chip rather than hidden. A
Keeper handing a 1920s table a Garand is a legitimate move.

`Skill`, `Weapon`, `EquipmentItem`, `Occupation` and `StorageLocation` all use `SoftDeletes`
("retire" in the UI).
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
- `resources/js/Pages/Components/Wizard/` — the create-investigator wizard, one component per step
- `resources/js/Pages/Composables/` — shared Vue composables
- `resources/js/Components/` — generic UI primitives (buttons, inputs, modal, tabs)

### The characteristics step
`StepCharacteristics.vue` is a chooser between three interchangeable ways to reach the same eight
numbers — `CharacteristicsBasic` (type the finished values), `CharacteristicsRoll` (dice totals,
multiplied by five) and `CharacteristicsPointBuy` (share `POINT_BUY.pool` points, 15–90 each). Each
hands the finished nine values back through `v-model:finals` / `v-model:ready`; the derived table and
the save live in the step itself, so the server contract is the same whichever is chosen. Switching
method remounts, and so clears what was typed.

**Only the dice method applies the age modifiers.** Under the other two the player is doing their own
arithmetic, so the age panel is a reminder and nothing is adjusted for them. Point Buy's recommended
floor of 40 for INT and SIZ warns rather than blocks (the Keeper may waive it), and only once every
characteristic has been filled in — half-typed numbers are not a choice worth warning about.

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

The full house style — page anatomy, the heading registers, tables, empty states, phone-first
rules, and the voice the copy and comments are written in — is the **`cthulhu-style` skill**
(`.claude/skills/cthulhu-style/`). Load it before writing or reviewing any Vue page, component,
Blade view, CSS, or user-facing text.

### No broadcasting
There is none. Player-to-player messaging was a proof of concept that never took off and was
removed along with the whole Reverb/Echo/Pusher stack; the `messages` table is left in place but
nothing reads or writes it. The Keeper's secret roll is a plain `axios.post` to `keeper.roll` that
answers with the outcomes — it never needed a socket.

### Sessions, and pages left open
A sheet lives on a phone for a whole game evening, so `session.lifetime` is **two weeks** and
`expire_on_close` is **false** — Laravel's two hours ran out mid-play and every save from the
still-open page came back 419, which is what 14 players hit in August 2026. *Remember me* is ticked
by default on the login form for the same reason. `TokenMismatchException` extends `HttpException`,
so **none of this ever reaches `storage/logs`** — that is Laravel's behaviour, not a hole to plug.

A 419 is still possible, and `withExceptions` in `bootstrap/app.php` answers it **two different ways,
because the sheet saves two different ways**:

- An **Inertia** visit (`X-Inertia`) is sent `back(303)` with the message flashed. It must be 303 and
  not 302: a 302 is re-issued with the original method, straight back into the same 419, until the
  browser dies with `ERR_TOO_MANY_REDIRECTS`.
- **Everything else XHR** gets a real `419` JSON. Most of the sheet does *not* save through Inertia —
  a characteristic, a skill value, a round fired are plain `axios` calls (`useAdjustAttribute`,
  `Vitals`, `Weapons`, `Skills`). Redirecting those is worse than useless: the browser follows the
  303 itself, the page that comes back is dropped by the `.then()`, and **the flash is spent on a
  render nobody sees**, so the next real visit has nothing left to show either.

The message is flashed in both cases, and the axios interceptor in `resources/js/bootstrap.js` turns
a 419 into `router.reload()` — which fetches the page carrying the flash, so `FlashMessages.vue`
shows the same banner whichever way the save went, and, being a GET that passes the CSRF check,
leaves a **fresh token** in the tab so trying again actually works.

`FlashMessages.vue` is rendered once by `AuthenticatedLayout` and once by `GuestLayout`. Do not add a
banner to a page nested inside them — it would double up, which is why `AdminLayout` and `Keeper.vue`
no longer have their own.

### Deploying
Production is a plain git checkout at `/var/www/cthulhu` on the host reachable as `ssh cthulhu`
(Ubuntu, **UTC**, so its timestamps read two hours behind Danish local time). Deploying is
`./deploy.sh` in that directory, which is the old `git pull` plus everything that has to follow it.

It is safe to run twice, and safe to run after pulling by hand — the pull becomes a no-op and each
step decides for itself whether it has work. Dependencies are installed only when `composer.lock`
or `package-lock.json` differs from what was installed last time, which is remembered by hash in
`storage/app/.deploy-state` (gitignored, and specific to that box). The site is put in maintenance
mode for the migration alone, with a trap that lifts it however the script exits.

**`php artisan optimize` is the step that must never be skipped**, and the reason the script
exists. Routes are cached in production, so a route added in a release does not exist until the
cache is rebuilt — and because `app.blade.php` uses `@routes`, Ziggy hands the browser that stale
list and `route('...')` throws client-side. The button does nothing, no request reaches the server,
and **nothing is logged anywhere**. That is what happened to the Keeper's cast on 2026-08-11. The
script ends by refusing to report success while `bootstrap/cache/routes-v7.php` is older than
anything in `routes/`.

A full page reload is needed after deploying: Inertia navigation keeps the Ziggy list that was
baked into the HTML the browser already has.

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
- `composer phpstan` must stay green too. `phpstan-baseline.neon` freezes what was already there when
  Larastan was first wired up (73 errors then, 56 now) — nearly all of them Larastan mis-typing
  Eloquent pivots, scopes and enum casts. Fix errors rather than adding to the baseline; when you do fix one,
  regenerate with `vendor/bin/phpstan analyse --generate-baseline=phpstan-baseline.neon` so the
  count only ever falls. Model scopes marked `#[Scope]` must be `protected` — Larastan only reads
  the attribute on non-public methods, and Laravel's own convention agrees.
- All tests are Pest; create with `php artisan make:test --pest {Name}`.
- Feature tests use `RefreshDatabase` (configured in `tests/Pest.php`).
