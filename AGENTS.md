# AGENTS.md

Read `ARCHITECTURE.md`, `planning/README.md`, `planning/CONVENTIONS.md`, and `planning/agents/` before changing behavior. Work in independently verifiable vertical slices. Use the repository-owned `./bin/build`, `./bin/phpunit`, `./bin/up`, `./bin/down`, `./bin/composer`, and `./bin/exec` commands; `./bin/build` is the single noninteractive local and hosted gate.

Yii owns its configuration-provider composition, DI bindings, HTTP and console entry points, Twig presentation, and future adapters. Fight Common and Fight AccessControl are public Composer dependencies only. Do not implement login, persistence, browser journeys, releases, tags, Packagist publication, template enablement, or create-project distribution without a local ticket.

## Work Routing

When asked "What's next?" or invoked without a task, read `planning/tickets/BOARD.md` and return the current human decision under **Now** and the first ticket under **Ready Frontier**. Use `planning/CONVENTIONS.md` to interpret ticket status and ordering.

## Run and Worktree Isolation

Coordinate-build scratch belongs in `.runs/<YYYY-MM-DD>-<slug>/`. It is gitignored and must never be staged.

## Branch Conventions

Create feature branches from `develop` as `feature/<description>`. Never commit directly to `develop` or `main`.

## Pre-Submit Gate

Always run before committing or creating a PR:

```bash
./bin/build
```

## Planning

See `planning/CONVENTIONS.md` for the canonical planning structure: ticket lifecycle, BOARD.md execution frontier,
Wayfinder maps, PRD and epic conventions, file naming, templates, and explicit-only archive operations. Never
archive planning records as a completion side effect; run `./bin/archive-planning` only on an explicit request,
review its dry run, and then apply it.

### Pre-PR Sync Checklist

Before final commit and PR for any feature or bug fix:

1. Mark the ticket `done` with verified acceptance criteria
2. Move the ticket to **Recently Done** in `planning/tickets/BOARD.md`
3. Recalculate the "What's Next?" contract if dependencies shifted
4. Update parent PRD and epic progress sections
5. Update `ROADMAP.md` if strategic progress changed
6. Verify no downstream ticket still lists the completed ticket as `blocked_by`
7. Run `./bin/planning-check`