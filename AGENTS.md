# AGENTS.md

## Project Context

- This is a Call of Cthulhu tabletop RPG character sheet app.
- Read `CLAUDE.md` before making non-trivial changes. It is the canonical project guide for architecture, domain rules, conventions, testing, and deployment.
- Before changing Vue pages/components, Blade views, CSS, Tailwind config, or app-facing copy, read `.claude/skills/cthulhu-style/SKILL.md`.

## Environment

- Local site: `https://cthulhu.test`
- Production site: `https://cthulhu.inibriati.com`
- Production SSH alias: `ssh cthulhu`
- Production checkout: `/var/www/cthulhu`
- Production deploy command: `cd /var/www/cthulhu && ./deploy.sh`

## Working Expectations

- The local development site is served by Laravel Valet; Vite is configured for `cthulhu.test`.
- Keep changes scoped to the requested behavior and the existing Laravel/Inertia/Vue patterns.
- Do not revert existing worktree changes unless the user explicitly asks.
- Run focused tests for the area changed. For PHP changes, prefer `php artisan test <file-or-filter>`; for JavaScript component changes, use `npx vitest`.
- Run `vendor/bin/pint --dirty` before committing PHP changes.

## Deployment Notes

- Production deploys from the server checkout, not directly from the local machine.
- `deploy.sh` is intentionally idempotent and handles pulling, dependency installation, asset building, migrations, cache refresh, and route verification.
- A full browser reload may be needed after deployment because Ziggy routes are baked into the HTML already loaded by Inertia.
