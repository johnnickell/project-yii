# Planning

Project Yii's committed planning uses **EPIC → TICKET → TASK**:

- `epics/`: destinations and boundaries.
- `tickets/`: related use cases and requirements, each owned by an EPIC.
- `tasks/`: bounded executable work and its acceptance/verification evidence.
- [TASK Board](tasks/BOARD.md): active work, execution priority, and human decisions.
- [Roadmap](ROADMAP.md): strategy, EPIC progress, and decomposition/closeout frontier.
- `adr/`: accepted architecture decisions; `agents/`: focused working instructions.
- `wayfinder/`: uncertain planning questions, not executable implementation work.

Read [CONVENTIONS.md](CONVENTIONS.md) for identities, lifecycle, templates, generated views, historical ID mapping,
and explicit-only archive operations. Record files own status and dependencies; generated views never override them.

After editing records run `./bin/planning-check --write`, then `./bin/planning-check`. The canonical build checks
planning read-only. Archive only on explicit request using `./bin/archive-planning`, dry run before `--apply`.
Ignored coordination material belongs under `.runs/`, never in this directory.
