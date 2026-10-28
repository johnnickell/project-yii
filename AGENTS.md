# AGENTS.md

## Work routing

For “What's next?”, `next`, or an unqualified `/ask-matt`, go straight to the [Board](planning/tasks/BOARD.md)
before general project orientation. Read its current human decision and active work; otherwise select the first
executable ready TASK in Board order. Use the [Wayfinder index](planning/wayfinder/README.md) for map status and current frontier pointers.
Run offline read-only `./bin/planning-check` before routing. Historical publication checkpoints do not create
current merge actions or recurring post-merge planning work; uncertainty needs its exact owner/input.
Follow [lightweight work routing](planning/CONVENTIONS.md#lightweight-work-routing) for validation,
bounded evidence reads and planning handoffs. Recommend and stop; load execution guidance when work is invoked.
This routing path does not require reading README, architecture, full project context, task history or receiving skills.

## Project guidance

For execution or substantive planning changes, load the applicable guidance below. The read-only routing
path above takes precedence over broad orientation reads; it does not waive engineering or delivery rules.

Read `ARCHITECTURE.md`, `planning/README.md`, `planning/CONVENTIONS.md`, and `planning/agents/` before changing behavior.
Use the [shared workflow binding](planning/agents/shared-workflows.md) for execution and substantive planning;
installed shared engineering/delivery guidance is the default with its explicit Yii substitutions and stronger local guarantees.
Land owns [publication closeout](planning/CONVENTIONS.md#publication-closeout): accepted TASK/current-doc closure,
exact live own-PR/default/head/remote validation and eligible owned local Git/Docker/storage retirement. Human
merge and open-PR remote heads remain separate; no tracked queue or recurring post-merge bookkeeping. Planning
checks stay offline; preserve historical acceptance/publication and independent reports.
Work in independently verifiable vertical slices. Use the repository-owned `./bin/build`, `./bin/phpunit`, `./bin/up`, `./bin/down`, `./bin/composer`, and `./bin/exec` commands; `./bin/build` is the single noninteractive local and hosted gate.

Yii owns its configuration-provider composition, DI bindings, HTTP and console entry points, Twig presentation, and future adapters. Fight Common and Fight AccessControl are public Composer dependencies only. Do not implement login, persistence, browser journeys, releases, tags, Packagist publication, template enablement, or create-project distribution without an accepted local TASK.

## Run and Worktree Isolation

For new runs, use the [canonical base-owned evidence layout](planning/CONVENTIONS.md#execution-evidence):
`.runs/build/<TASK-ID>/`, `.runs/reviews/<TASK-ID>/`, `.runs/qa/<TASK-ID>/` and `.runs/tasks/<TASK-ID>/runs/`.
This supersedes the dated coordinate-build rule for new runs only; preserve historical and already-started evidence
in place. `.runs/` is gitignored and must never be staged. Runtime/tool caches remain Yii-owned `var/`.

## Branch Conventions

Create TASK branches from `develop` as `feature/task-NNNNN-<slug>`. Preserve existing authorized branches. Never commit directly to `develop` or `main`; choose this checkout or an isolated worktree explicitly with the user.

## Pre-Submit Gate

For a long non-interactive build, run `screen -dmS <task>-build /bin/zsh -lc './bin/build > /private/tmp/<task>-build.log 2>&1; print -r -- $? > /private/tmp/<task>-build.exit'`, then inspect the log and require an exit file containing `0`; never treat foreground timeout output as a build result.

Always run before committing or creating a PR:

```bash
./bin/build
```

## Planning

See `planning/CONVENTIONS.md` for EPIC → TICKET → TASK ownership, lifecycle, generated views, Wayfinder maps,
file naming, templates, and explicit-only archive operations. Refresh views with `./bin/planning-check --write`;
`./bin/planning-check` and the canonical build remain read-only. Never archive as a completion side effect;
run `./bin/archive-planning` only on an explicit request, review its dry run, and then apply it.

### Pre-PR Sync Checklist

Before final commit and PR for any feature or bug fix:

1. Record TASK verification honestly; mark `done` only when acceptance and required checks are complete
2. Refresh generated views with `./bin/planning-check --write`, including `planning/tasks/BOARD.md`
3. Verify the active/ready frontier and authored human decisions remain correct
4. Update parent TICKET and EPIC progress; apply automatic parent completion
5. Update `planning/ROADMAP.md` if strategic progress changed
6. Preserve `blocked_by` history; completed blockers must no longer prevent execution
7. Run `./bin/planning-check` and the complete `./bin/build`; review and publication remain separate

When completing a TASK, apply [Automatic parent completion](planning/CONVENTIONS.md#automatic-parent-completion)
in the same operation; do not leave a separate parent assessment or closeout action for the user.

## Certification retirement

Test owned application behavior and meaningful package integrations. Do not create or restore framework-support
certification files, receipt readers/generators, dependency certification matrices, or tests of those mechanisms.
Validate build, configuration and planning tools directly with their owning commands, outside product suites.
Historical certification notes remain history, not current gates.
