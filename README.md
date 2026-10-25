# Project Yii

Public Yii 3 application composition for Fight libraries. The starter consumes Fight Common and Fight AccessControl
only through their public Composer packages and keeps Yii configuration, dependency injection, HTTP, console, and
presentation composition local. The [installed stable-package baseline](docs/stable-package-baseline.md) records
exact artifacts, selected-contract compatibility and the limits of that verification.

Prepare a checkout explicitly, then verify it:

```sh
./bin/up
./bin/composer install --no-interaction --prefer-dist --no-progress
./bin/build
```

Open <http://localhost:8088/> for the hello-world runtime. GitHub Actions performs the same preparation and delegates
verification to `./bin/build` for pushes and pull requests targeting `develop`, `main`, and `release/**`.

## Commands

- `./bin/console list` runs the Yii-native console.
- `./bin/phpunit` runs all configured PHPUnit suites in the running application container; pass `--testsuite Unit`
  for a focused run.
- `./bin/build` is the complete noninteractive quality gate used locally and by GitHub Actions. It requires the
  running `app` service and locked development dependencies; it never builds images or installs dependencies.
  Named PHP phases stop on the first failure. See [Contributing](CONTRIBUTING.md) for checks and coverage ownership.

This repository is MIT-licensed source, not a release or package-distribution claim. Do not add copied shared
source, credentials, production data, tags, Packagist publication, template enablement, or create-project
distribution without a separately accepted local TASK. Planning follows EPIC → TICKET → TASK; see
[the planning guide](planning/README.md) and [TASK Board](planning/tasks/BOARD.md).

## Container runtime refresh

The Dockerfiles pin PHP `8.5.12-fpm` and NGINX `1.30.6-alpine`. `./bin/up` refreshes both service builds with
`--pull`, prepares writable `vendor/` and `var/` directories, then force-recreates containers so no old FPM workers
remain. Directory preparation uses a one-shot root container to create those generated directories and assign
their directories and regular files to the image's `systemuser` (uid/gid 1000), without following symlinks or
touching sockets; checkout/source ownership is unchanged. This supports
Linux checkouts owned by a different UID, including hosted runners. Composer, verification and FPM still run
non-root. Host tools must account for runtime-owned generated files. The non-root PHP image includes PCOV and
Python 3 for the existing planning validator. `./bin/build` only verifies the prepared running service and does
not refresh images or workers. Registry/Docker/build failures stop preparation rather than recreating services
from stale images. Existing services may still be running after a failed preparation; inspect them and
retry after fixing access. A successful image build alone does not refresh running services: run `./bin/up` too.
Explicit release pins still need deliberate updates when later security releases appear.

For parallel checkouts, set a unique `COMPOSE_PROJECT_NAME` and unused `PROJECT_YII_HTTP_PORT` (default `8088`)
for every wrapper invocation, including `./bin/down`. For example:

```sh
export COMPOSE_PROJECT_NAME=project-yii-my-task PROJECT_YII_HTTP_PORT=18088
./bin/up
./bin/down
```

The startup INI disables `session.upload_progress.enabled` per PHP's
[follow-up guidance](https://www.php.net/archive/2026.php#2026-10-23-1) for CVE-2026-115560; PHP 8.5.12 fixes
the two original October advisories, not this follow-up. The application does not change the session serialize
handler at runtime, so the reported follow-up preconditions are not present; disabling the unused feature is
additional protection. Keep it disabled pending evaluation of a subsequent fix, and rebuild/recreate all workers
after INI changes. Finite PHP/request limits remain enabled: base defaults are `memory_limit=128M`,
`post_max_size=8M`, `upload_max_filesize=2M`, and NGINX `client_max_body_size=1m`; FPM terminates requests after
60 seconds. These limits are not a substitute for patched runtimes. No container memory cap is configured here.

## Deployment security

Configure these environment variables in every deployment: `FIGHT_HMAC_PUBLIC` for the nonblank HMAC identity,
`FIGHT_HMAC_PRIVATE` for the 32-byte hexadecimal HMAC secret, and `FIGHT_JWT_SECRET` for the 32-byte hexadecimal
JWT secret. JWT encoding and decoding use `HS256`.
