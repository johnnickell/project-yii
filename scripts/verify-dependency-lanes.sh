#!/usr/bin/env sh
set -eu

project_root=$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)
mode=${1:-verify}

copy_lane() {
    target=$1
    for path in composer.json config public resources scripts src tests phpunit.xml; do
        cp -R "$project_root/$path" "$target/"
    done
}

run_composer() {
    lane_root=$1
    shift
    output_file="$lane_root/composer-command.log"
    if ! composer --working-dir="$lane_root" "$@" >"$output_file" 2>&1; then
        cat "$output_file"
        return 1
    fi
    cat "$output_file"
    if grep -E 'Ambiguous class resolution|^Warning:' "$output_file" >/dev/null; then
        echo "Composer emitted an unapproved warning." >&2
        return 1
    fi
}

if [ "$mode" = "refresh-lowest" ]; then
    lane_root=$(mktemp -d)
    trap 'rm -rf "$lane_root"' EXIT HUP INT TERM
    copy_lane "$lane_root"
    run_composer "$lane_root" update --with-all-dependencies --prefer-lowest --prefer-stable --no-interaction --prefer-dist --no-progress
    cp "$lane_root/composer.lock" "$project_root/composer-lowest.lock"
    php -r 'echo hash_file("sha256", $argv[1]), "  composer-lowest.lock\n";' "$project_root/composer-lowest.lock" > "$project_root/composer-lowest.lock.sha256"
    exit 0
fi

if [ "$mode" != "verify" ]; then
    echo "Usage: scripts/verify-dependency-lanes.sh [verify|refresh-lowest]" >&2
    exit 2
fi

expected=$(php -r 'echo hash_file("sha256", $argv[1]), "  composer-lowest.lock";' "$project_root/composer-lowest.lock")
actual=$(tr -d '\r\n' < "$project_root/composer-lowest.lock.sha256")
if [ "$expected" != "$actual" ]; then
    echo "composer-lowest.lock digest has drifted." >&2
    exit 1
fi

for lane in latest lowest; do
    lane_root=$(mktemp -d)
    trap 'rm -rf "$lane_root"' EXIT HUP INT TERM
    copy_lane "$lane_root"
    if [ "$lane" = latest ]; then
        cp "$project_root/composer.lock" "$lane_root/composer.lock"
    else
        cp "$project_root/composer-lowest.lock" "$lane_root/composer.lock"
    fi
    before=$(php -r 'echo hash_file("sha256", $argv[1]);' "$lane_root/composer.lock")
    run_composer "$lane_root" install --no-interaction --prefer-dist --no-progress
    after=$(php -r 'echo hash_file("sha256", $argv[1]);' "$lane_root/composer.lock")
    if [ "$before" != "$after" ]; then
        echo "$lane install changed its committed lock." >&2
        exit 1
    fi
    php "$lane_root/scripts/framework-support-profile.php" --assert-lane "$lane_root"
    (
        cd "$lane_root"
        php vendor/bin/phpunit tests/Integration/HttpApplicationJourneyTest.php tests/Integration/MessagingJourneyTest.php tests/Integration/StatefulCapabilityJourneyTest.php tests/Integration/IntegrationFallbackJourneyTest.php tests/Integration/SourceBoundaryTest.php
    )
    rm -rf "$lane_root"
    trap - EXIT HUP INT TERM
done

echo "Yii latest and lowest dependency lanes passed."
