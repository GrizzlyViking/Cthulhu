#!/usr/bin/env bash
#
# Deploy Cthulhu, on the box it runs on:
#
#     ssh cthulhu
#     cd /var/www/cthulhu && ./deploy.sh
#
# It replaces the `git pull` that used to be the whole deploy, and it is happy
# either way round: run it instead of pulling, or run it after pulling — the
# pull below is a no-op then, and every other step decides for itself whether it
# has work to do.
#
# What used to go wrong is the cache refresh, and it fails invisibly: a route
# added in a release is not in `bootstrap/cache/routes-v7.php` until `optimize`
# rebuilds it, `@routes` hands the browser that stale list, and `route('...')`
# throws in the browser rather than anywhere a log would see it. The button
# simply does nothing. Hence `verify_caches_are_current` at the end, which
# refuses to call a deploy finished while the route cache is older than the
# routes it is meant to hold.
#
# Every step is idempotent, so running this twice is safe, and running it again
# after a failure halfway through is how to finish the job.
#
# The whole script lives inside functions and is called on the last line: bash
# reads a script as it runs it, and this one replaces itself with `git pull`
# partway through.

set -euo pipefail

readonly BRANCH=main

# What was installed last time, so a deploy that changes no dependencies does
# not spend three minutes reinstalling them. Not in git: it describes this box.
readonly STATE_FILE=storage/app/.deploy-state

log() {
    printf '\n\033[1;32m==>\033[0m %s\n' "$1"
}

fail() {
    printf '\n\033[1;31m!!\033[0m %s\n' "$1" >&2
    exit 1
}

# Refuse to deploy over uncommitted work, or from the wrong branch: this is a
# working checkout, and a stray edit here may be the only copy of it.
require_clean_checkout() {
    local current
    current=$(git rev-parse --abbrev-ref HEAD)

    [[ "$current" == "$BRANCH" ]] || fail "On branch ${current}, not ${BRANCH}. Nothing was changed."

    if [[ -n "$(git status --porcelain)" ]]; then
        git status --short
        fail 'There are uncommitted changes here. Deal with them first; nothing was changed.'
    fi
}

# Fast-forward only: a merge commit made on the server is a mess nobody wants to
# untangle over SSH.
pull() {
    log 'Fetching'
    git pull --ff-only origin "$BRANCH"
    git --no-pager log -1 --format='    %h %s (%cr)'
}

recorded() {
    [[ -f "$STATE_FILE" ]] || return 0
    grep -E "^$1 " "$STATE_FILE" 2>/dev/null | cut -d' ' -f2- || true
}

record() {
    mkdir -p "$(dirname "$STATE_FILE")"
    {
        [[ -f "$STATE_FILE" ]] && grep -vE "^$1 " "$STATE_FILE" || true
        printf '%s %s\n' "$1" "$2"
    } >"${STATE_FILE}.tmp"
    mv "${STATE_FILE}.tmp" "$STATE_FILE"
}

# Install only when the lock file is not the one we installed from. Reading the
# file rather than the git range is what makes this work when the pull already
# happened by hand.
install_if_changed() {
    local lock=$1
    shift

    local hash
    hash=$(sha256sum "$lock" | cut -d' ' -f1)

    if [[ "$hash" == "$(recorded "$lock")" ]]; then
        printf '    %s unchanged\n' "$lock"
        return 0
    fi

    log "Installing from ${lock}"
    "$@"
    record "$lock" "$hash"
}

# `--no-dev` because Pint, PHPStan and Pest have no business in production. The
# JS devDependencies stay, because Vite is one of them and it does the build.
install_dependencies() {
    log 'Dependencies'

    # A missing node_modules is a reinstall whatever the lock file says.
    [[ -d node_modules ]] || record package-lock.json 'absent'

    install_if_changed composer.lock \
        composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
    install_if_changed package-lock.json npm ci
}

build_assets() {
    log 'Building assets'
    npm run build
}

# The site goes down for the migration itself and no longer, and the trap brings
# it back up however this exits — a failed migration must not leave the door
# shut. With nothing pending, `migrate` is a no-op and this costs a second.
migrate() {
    log 'Migrating'

    trap 'php artisan up >/dev/null 2>&1 || true' EXIT
    php artisan down --retry=15
    php artisan migrate --force
    php artisan up
    trap - EXIT
}

# `optimize` is config:cache, event:cache, route:cache and view:cache in one.
# This is the step that was being forgotten, and the reason this file exists.
refresh_caches() {
    log 'Refreshing caches'
    php artisan optimize
    php artisan queue:restart
}

# A cached route table older than the routes it was built from is the failure
# this script exists to prevent, so it is checked rather than assumed.
verify_caches_are_current() {
    log 'Verifying'

    local cache=bootstrap/cache/routes-v7.php
    [[ -f "$cache" ]] || fail "No ${cache} — routes are not cached."

    local newest
    newest=$(find routes -type f -name '*.php' -printf '%T@ %p\n' | sort -rn | head -1 | cut -d' ' -f2-)

    if [[ -n "$newest" && "$newest" -nt "$cache" ]]; then
        fail "${newest} is newer than ${cache}: the route cache is stale. Run: php artisan optimize"
    fi

    printf '    %s routes cached, none of them newer than the cache\n' \
        "$(php artisan route:list --json | grep -o '"uri"' | wc -l)"
    printf '    %s\n' "$(php artisan --version)"
}

main() {
    cd "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

    require_clean_checkout
    pull
    install_dependencies
    build_assets
    migrate
    refresh_caches
    verify_caches_are_current

    log 'Deployed.'
}

main "$@"
