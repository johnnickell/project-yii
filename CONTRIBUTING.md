# Contributing

Read `AGENTS.md`, `ARCHITECTURE.md`, `planning/CONVENTIONS.md`, and the focused local planning rules before
proposing a change. EPICs own destinations, TICKETs own requirements, and TASKs own bounded implementation.
Create or update an accepted local TASK, choose the checkout with the maintainer, and branch from `develop` as
`feature/task-NNNNN-<slug>`. Keep work to a vertical slice and run `./bin/build` before requesting review.
After planning changes, run `./bin/planning-check --write` followed by `./bin/planning-check`.
Use the [shared workflow binding](planning/agents/shared-workflows.md) for installed skills, Yii resource substitutions,
canonical evidence and independent Review/QA/Land handoffs. A next recommendation does not authorize execution;
explicit Build delegates its bounded delivery stages, while work alone stops at a committed Review handoff.
No harness/skill installation, Jev enrollment, model change or OS isolation is implied.

For a fresh checkout, use `./bin/up` followed by `./bin/composer install`; all development commands run in the
repository Docker services, so host PHP and Composer versions are not prerequisites. Use `./bin/down` to stop the
complete Compose runtime. Build/start requires registry access and fails closed if required image preparation fails;
see [runtime refresh and checkout isolation](README.md#container-runtime-refresh). After runtime updates, use
`./bin/up` to recreate the services and FPM workers; `./bin/build` does not update running containers.
Do not publish tags, packages, templates, or distributions as part of a code change.
