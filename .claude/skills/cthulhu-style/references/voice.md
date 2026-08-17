# The voice

The prose is part of the design. The app is a dossier kept by someone careful, and everything it
says should sound like that: plain, complete, and explaining itself. This applies to UI copy,
validation messages, `confirm()` text, code comments, PHPDoc, commit messages and CLAUDE.md alike.

## The five rules

### 1. Say the reason, not just the fact

The app explains itself constantly. A label that only names a control is a wasted line.

> Every group on this server draws from this one list, so a change here reaches all of them.
> Retiring a skill takes it off the sheets that carry it without losing their values — restore it
> and they come back.

> Leave it empty when creating and it is derived from the name; changing it later will orphan
> anything already pointing at the old one.

Not: *Skill list.* *Slug (optional).*

### 2. Use the game's nouns

| Say | Not |
| --- | --- |
| investigator | character record, PC, entity |
| Keeper | GM, admin, moderator |
| sheet | profile, record, form |
| campaign / game | session, instance |
| the party, the table | the group of users |
| era | period, timeframe |
| retire | soft-delete, archive, disable |
| conjure up | generate, spawn |

`character` is fine in code — it is the model name. In copy shown to a player it is *investigator*.

### 3. Complete sentences, no exclamation marks

No `Oops!`, no `Something went wrong.`, no `Success!`, no emoji, no jokes. The tone is a careful
narrator. Wry is allowed where it is honest; jolly is not.

> Nobody has joined Ages of Madness yet.
> Investigators appear here once their players put them in this game, from the Manage sheet panel on
> their own sheet.

> Conjured up whole, in this game, and visible to nobody but you.

Buttons and toggles may be shorter than a sentence, and may be idiomatic:
*Everyone's here* · *Whatever suits them* · *For good?* · *Done with* · *Here tonight*

### 4. Real typography

The codebase is consistent about this. Match it.

| Character | Use |
| --- | --- |
| `—` em dash | The app's favourite punctuation. An aside, a consequence, a "which is". Spaced. |
| `“ ”` | Quoting something the user typed or named: `No skill matches “{{ filters.search }}”.` |
| `’` | Apostrophes in prose. |
| `·` middle dot | Joining facts on one line: `{{ game.name }} · {{ game.era }}`. Spaced. |
| `…` | An ellipsis, one character. |
| `×` | Multiplication in a formula (`EDU × 2`), never the letter x. |

Straight quotes and `--` are fine inside code, slugs and shell commands.

### 5. Address the reader as the person doing the thing

Second person for what they can do, third person for what the system does.

> An admin can start one on the Group page.
> Only the latest roll is kept.
> They will be signed out and unable to log back in.

Avoid "we" — the app has no committee behind it.

## Copy by situation

### Empty states

Name what is missing, then the next move and who can make it.

> **No game is being played** — The Keeper's screen shows the party of the campaign your group is
> playing. An admin can start one on the Group page.

> Nobody yet. Whoever you make lands in Ages of Madness with their skills rolled, something to fight
> with and the essentials in their pockets — and can be deleted the moment the scene is over.

An empty table says *which filter* emptied it:

> No skill has been retired. / No skill matches “driving”. / No skill belongs to that era alone. /
> There are no skills yet.

### Destructive confirmations

Say what happens **and what survives**. Never `Are you sure?`.

> Retire Kotoko's revolver? It leaves every sheet carrying it, ammunition and all, and comes back if
> you restore it.

> Delete “Ages of Madness”? Its investigators keep their sheets — they simply leave the game.

> Block Sebastian? They will be signed out and unable to log back in.

> Permanently delete Ernest Vane? This cannot be undone.

Note the calibration: a reversible retirement reads calmly; only real deletion says *permanently*
and *cannot be undone*.

### Hints under fields

The hint carries the consequence, and often an example from the book.

> Which eras have any use for the skill. Nearly all of them have both — a modern table has no more
> use for Fighting (Chainsaw) in 1925 than a Twenties one does.

### When a feature is switched off

Explain why, and where the change goes instead.

> Editing is switched off on this server, because more than one group plays here and this list is
> shared by all of them. Changes go through `SkillSeeder` or a migration.

## Comments and documentation

Same voice, and the same rule: the *why*, not the *what*.

```js
/*
 * Who is at the table tonight. Seven players are on the books and it is rare
 * for all seven to turn up, so absentees are dimmed and left out of the rolls.
 * It is the Keeper's own view of his own evening, so it lives in his browser —
 * nothing to save, nothing anyone else sees.
 */
```

```js
/* Deleting is for good, so it asks once — inline, without a dialog in the way. */
```

```js
// A corrupted entry is not worth a broken screen: start with everyone here.
```

```css
/* Installed as an app, the page runs under the home indicator. The
   inset is 0 in a browser, so this only shows on the home screen. */
```

Conventions that hold across the repo:

- **Banner comments** mark the halves of a long `<script setup>`:
  `// ---- Create / edit ------------------------------------------------------`
- **PHPDoc over inline comments** in PHP; array shape types on anything complex.
- A comment that restates the line below it should be deleted. A comment recording a decision, a
  constraint, a date something broke, or a rule that lives in two places should never be.
- Dates are absolute: *what 14 players hit in August 2026*, not *recently*.
- When a rule is mirrored in two files, say so in both: *`wizardData.js`'s `ageModifiers()` mirrors
  the deduction on the client — change both together.*
