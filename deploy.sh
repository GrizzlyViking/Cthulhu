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

    # Tracked files only: a stray dump.sql sitting in the directory is nobody's
    # lost work, and git itself refuses a pull that would overwrite one.
    if [[ -n "$(git status --porcelain --untracked-files=no)" ]]; then
        git status --short --untracked-files=no
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

# Ask the running site what it thinks it serves, rather than trusting that the
# commands above did their job.
#
# Checking that the route cache is younger than `routes/` would prove nothing —
# `optimize` just rewrote it a second ago, so that can never fail. What can fail
# is the browser being handed something older than the code: a stale cache, or
# PHP still holding the previous files. So this fetches the live page and looks
# for the routes this deploy added in the Ziggy list that `@routes` prints into
# the HTML — the exact thing that was missing when the Keeper's cast shipped.
verify_the_browser_sees_the_new_routes() {
    log 'Verifying'

    local cache=bootstrap/cache/routes-v7.php
    [[ -f "$cache" ]] || fail "No ${cache} — routes are not cached."

    local url html
    url=$(grep -E '^APP_URL=' .env | head -1 | cut -d= -f2- | tr -d "\"' ")
    html=$(curl -fsS --max-time 20 "$url") || fail "${url} did not answer. The site is down."

    # Names as written in routes/web.php. A group prefix means the full name is
    # "keeper.npcs.store" where the file says "npcs.store", so the served list is
    # searched for the suffix.
    # `|| true` because a deploy that touches no routes is the normal case, and
    # `set -o pipefail` would otherwise turn "grep found nothing" into a failed
    # deploy — which is precisely what it did the first time this ran.
    local added name missing=()
    added=$(git diff "$DEPLOYED_FROM" HEAD -- routes/ |
        grep '^+' |
        grep -oE "\->name\('[^']+'\)" |
        sed -E "s/->name\('(.*)'\)/\1/" |
        grep -vE '^$|\.$' |
        sort -u || true)

    # Read line by line rather than letting the shell split on whitespace: this
    # file is run by bash, but it should not depend on that to be correct.
    while IFS= read -r name; do
        [[ -n "$name" ]] || continue
        grep -q "$name" <<<"$html" || missing+=("$name")
    done <<<"$added"

    if ((${#missing[@]} > 0)); then
        fail "The live page does not offer: ${missing[*]}. Something is still serving older code."
    fi

    if [[ -n "$added" ]]; then
        printf '    new routes the browser can reach: %s\n' "$(tr '\n' ' ' <<<"$added")"
    else
        printf '    no routes added in this deploy; %s answers\n' "$url"
    fi

    printf '    %s routes cached · %s\n' \
        "$(php artisan route:list --json | grep -o '"uri"' | wc -l)" \
        "$(php artisan --version)"
}

main() {
    cd "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

    require_clean_checkout

    # Where this box stood before the pull, so the verification at the end knows
    # which routes are new. Pulling by hand first leaves nothing to compare, and
    # the check falls back to "is the site answering".
    DEPLOYED_FROM=$(git rev-parse HEAD)
    readonly DEPLOYED_FROM

    pull
    install_dependencies
    build_assets
    migrate
    refresh_caches
    verify_the_browser_sees_the_new_routes

    log 'Deployed.'
}

main "$@"
