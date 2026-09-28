# Contributing

Read `AGENTS.md`, `ARCHITECTURE.md`, `planning/CONVENTIONS.md`, and the focused local planning rules before
proposing a change. EPICs own destinations, TICKETs own requirements, and TASKs own bounded implementation.
Create or update an accepted local TASK, choose the checkout with the maintainer, and branch from `develop` as
`feature/task-NNNNN-<slug>`. Keep work to a vertical slice and run `./bin/build` before requesting review.
After planning changes, run `./bin/planning-check --write` followed by `./bin/planning-check`.

For a fresh checkout, use `./bin/up` followed by `./bin/composer install`; all development commands run in the
repository Docker services, so host PHP and Composer versions are not prerequisites. Use `./bin/down` to stop the
complete Compose runtime. Do not publish tags, packages, templates, or distributions as part of a code change.
