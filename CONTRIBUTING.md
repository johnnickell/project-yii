# Contributing

Read `AGENTS.md`, `ARCHITECTURE.md`, `planning/CONVENTIONS.md`, and the focused local planning rules before
proposing a change. EPICs own destinations, TICKETs own requirements, and TASKs own bounded implementation.
Create or update an accepted local TASK, choose the checkout with the maintainer, and branch from `develop` as
`feature/task-NNNNN-<slug>`. Keep work to a vertical slice and run `./bin/build` before requesting review.
At authorized Work, Land or substantive planning intake, run `./bin/planning-check --reconcile-delivery` to
retire only verified ended publication markers and preserve merge/integration evidence; uncertainty prevents all
planned writes. After planning changes, run `./bin/planning-check --write` followed by `./bin/planning-check`.
Read-only routing uses `./bin/planning-check --check-delivery`; Land repeats it at intake, pre-publication and
final handoff. Live modes query only explicit `delivery: awaiting-merge` TASKs, not historical PRs. Ordinary
checks/writer/build stay offline; see [delivery conventions](planning/CONVENTIONS.md#live-handoff-verification).
Use the [shared workflow binding](planning/agents/shared-workflows.md) for installed skills, Yii resource substitutions,
canonical evidence and independent Review/QA/Land handoffs. A next recommendation does not authorize execution;
explicit Build delegates its bounded delivery stages, while work alone stops at a committed Review handoff.
No harness/skill installation, Jev enrollment, model change or OS isolation is implied.

For a fresh checkout, use `./bin/up`, then
`./bin/composer install --no-interaction --prefer-dist --no-progress`, then `./bin/build`. Repeat installation after
lock changes. All development commands run in the repository Docker services, so host PHP, Composer and Python
versions are not prerequisites for the gate. Direct host `./bin/planning-check` still needs Python 3. Use
`./bin/down` to stop the complete Compose runtime. Image preparation requires registry access and fails closed
if required image or writable-directory preparation fails. `./bin/up` uses a one-shot root container only to
create/chown generated `vendor/` and `var/` for the image's non-root user; source ownership is preserved even
when the Linux checkout owner has a different UID. Composer/install, the gate and FPM remain non-root;
see [runtime refresh and checkout isolation](README.md#container-runtime-refresh). After runtime updates, use
`./bin/up` to recreate the services and FPM workers; `./bin/build` does not update running containers.

## Verification contract

`./bin/build` is a thin entrypoint into `scripts/build.php` in the already-running `app` service. Missing service,
locked development tools, enabled PCOV or runtime tools fail with setup guidance; the gate does not repair them.
It checks offline planning freshness before expensive phases, then validates FPM, Composer metadata/lock and
installed platform requirements, PHP syntax,
Common PHPCS formatting, PHPStan, inward Deptrac boundaries and Rector dry-run before one PHPUnit invocation runs
every configured Unit, Integration and Functional suite exactly once, followed by final offline planning
validation. No frontend/browser suites are configured.
`./bin/phpunit`, `./bin/exec`, `./bin/composer` and `./bin/console` use the same running service. Runtime/tool caches
belong in `var/`; the Composer cache is `var/cache/composer`.

Coverage remains exact 100% of every owned-production `src/` statement, including PHP providers and composition
contracts. Direct Unit tests declare `#[CoversClass]`; Integration/Functional journeys declare `#[CoversNothing]`
and cannot inflate the report. PHPUnit includes uncovered source files and disables coverage-ignore metadata;
a direct grep phase rejects ignore directives. The installed `rregeer/phpunit-coverage-check` CLI enforces threshold
100 without rounded-percentage comparison. It also requires the reported method/element metrics to be complete.
There are no source exclusions, baselines, suppressions or custom coverage-report verifier. Validate tools with
their owning commands, not product tests or deliberately invalid fixtures.

TASK-00009 supersedes TASK-00005's disposable clean-clone one-command setup and separate `--no-dev` installation/
boot probe. The probe and bespoke verifier are removed. The retained HTTP/console, deployment-credential rejection
and adapter journeys exercise the development graph, **not** a production-only installation. No production-install
claim replaces that retired evidence. CI explicitly prepares the environment and lock, invokes the same gate, and
stops the environment even after failure. Local equivalence is not proof of actual hosted execution.

Do not publish tags, packages, templates, or distributions as part of a code change.
