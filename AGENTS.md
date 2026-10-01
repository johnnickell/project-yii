# AGENTS.md

Read `ARCHITECTURE.md`, `planning/README.md`, `planning/CONVENTIONS.md`, and `planning/agents/` before changing behavior. Work in independently verifiable vertical slices. Use the repository-owned `./bin/build`, `./bin/phpunit`, `./bin/up`, `./bin/down`, `./bin/composer`, and `./bin/exec` commands; `./bin/build` is the single noninteractive local and hosted gate.

Yii owns its configuration-provider composition, DI bindings, HTTP and console entry points, Twig presentation, and future adapters. Fight Common and Fight AccessControl are public Composer dependencies only. Do not implement login, persistence, browser journeys, releases, tags, Packagist publication, template enablement, or create-project distribution without an accepted local TASK.

## Work Routing

When asked "What's next?" or invoked without a task, read `planning/tasks/BOARD.md`. Return the current human decision under **Now** and the active TASK; if none is active, return the first executable TASK under **Ready Frontier**. Use `planning/CONVENTIONS.md` to interpret status, blockers, and ordering. EPICs own destinations, TICKETs own requirements, and TASKs own implementation.

## Run and Worktree Isolation

Coordinate-build scratch belongs in `.runs/<YYYY-MM-DD>-<slug>/`. It is gitignored and must never be staged.

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
