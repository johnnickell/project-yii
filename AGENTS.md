# AGENTS.md

Repository-local instructions are canonical for implementation, planning, triage, and completion. Read
`ARCHITECTURE.md`, `planning/README.md`, and the focused instructions in `planning/agents/` before changing
behavior. Work in independently verifiable vertical slices. A slice is complete only when its local ticket,
documentation, architecture boundaries, and `./bin/build` are green.

Use repository-owned commands: `./bin/composer`, `./bin/phpunit`, `./bin/console`, `./bin/up`, `./bin/down`,
`./bin/exec`, and `./bin/build`. `./bin/build` is the single noninteractive local and hosted gate.

Do not copy Fight Common or Fight AccessControl source. They are public Composer dependencies only. Yii owns its
configuration-provider integration, DI, HTTP, console, presentation, and future adapters. Do not implement login,
persistence, browser journeys, release distribution, or publication transitions without a local ticket.
