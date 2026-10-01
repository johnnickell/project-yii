# Planning Conventions

Project Yii owns its planning. Individual records own scope, status, dependencies, and priority; generated
indexes, child tables, the TASK Board, and Roadmap tables are projections, not additional status authorities.

## Hierarchy and identities

| Level | Responsibility | Path and displayed ID |
| --- | --- | --- |
| EPIC | Destination, outcomes, and boundaries | `epics/00001-EPIC.md` · `EPIC-00001` |
| TICKET | Related use cases and requirements | `tickets/00001-TICKET.md` · `TICKET-00001` |
| TASK | Bounded implementation, normally one PR | `tasks/00001-TASK.md` · `TASK-00001` |
| SUBTASK | Temporary implementation coordination | Ignored `.runs/` material owned by a TASK |

Each level has an independent five-digit sequence. Inspect live and archived records before allocating IDs;
never reuse identities or close gaps by renumbering. Copy the owning `_…_TEMPLATE.md`; templates are not records.
ADRs remain `adr/NNNN-description.md`; focused instructions remain in `agents/`.

EPICs are approved before requirement decomposition; accepted TICKETs are decomposed into TASKs before execution.
Prefer vertical use cases, not layer-only assignments. State input, result/failure, side effects, validation,
permissions, and observable evidence; explicitly explain non-applicable concerns. Standalone bugs and chores may
have an empty `ticket` field and `kind: bug` or `kind: chore`; they do not need an invented product EPIC.

### Existing-record migration

The 2026-09-27 migration maps local `PRD-00001`/`PRD-00002` to `TICKET-00001`/`TICKET-00002` and local
`T-00001` through `T-00006` to `TASK-00001` through `TASK-00006`, preserving numbers, history, status, and blocker
edges. `legacy_id` records provenance. New EPIC parents summarize existing local outcomes, not imported planning.
Upstream Fight Common ticket/PRD identifiers and historical branch names remain unchanged. Wayfinder `WF-NNN`
decisions are not implementation TASKs. This is a rename, not an archive operation or re-verification of old work.

The 2026-09-28 reconciliation includes the later legacy `T-00007` merged in PR #8. It maps to
[TASK-00010](tasks/00010-TASK.md), with `legacy_id: T-00007`, because TASK-00007 already identifies this migration
and TASK-00008/00009 were allocated. No existing TASK is renumbered. The imported record retains its complete
requirement text and maps every obligation to TASK-00008/00009. Under the maintainer's consolidation direction,
TASK-00010 is `wontfix` only as a duplicate delivery assignment, not as a rejection or completion of those obligations.
This documented collision mapping is an exception to numeric preservation, not permission to reuse an ID.

## Automatic parent completion

When a TASK becomes `done` or `wontfix`, complete eligible parent TICKETs and then EPICs in the same operation.
Count live and archived children. A live, non-terminal parent with at least one child closes when every child
is terminal: use `wontfix` if every child is `wontfix`, otherwise `done`. Parents without children or with an
unfinished child remain open. Preserve already-terminal and archived parents.

Child acceptance and intentional `wontfix` decisions remain with the child records. Parent completion requires
no separate assessment, independent review, QA, confirmation, or skill invocation. Record any remaining work as
an unfinished child rather than a separate parent-closeout gate. Parent status does not assert review, merge,
release, deployment or publication, and completion never archives records automatically.

Run `./bin/planning-check --write` during completion; it closes eligible parents and refreshes views.
Then run the read-only `./bin/planning-check`. Read-only validation never writes completion metadata.


## Metadata and lifecycle

```yaml
---
id: TASK-00007
ticket:
kind: chore
title: Migrate local planning to EPIC, TICKET, and TASK
status: in-progress
order: 1
blocked_by:
pr:
---
```

TICKETs require `epic: EPIC-NNNNN`. TASKs require `ticket: TICKET-NNNNN`, except standalone bugs/chores.
`kind` is `feature`, `bug`, or `chore`. `blocked_by` is a comma-separated list of TASK IDs. Preserve completed
edges; only unfinished blockers prevent execution. `order` is an optional positive priority number, lower first.
Unranked work sorts last; IDs break ties for deterministic display, not implicit priority. `pr` is an optional
full pull-request URL, not an assertion of merge state. Frontmatter uses flat, single-line values.

| Status | Meaning |
| --- | --- |
| `needs-triage` | Scope or ownership is not classified |
| `needs-info` | A decision or required evidence is missing |
| `ready-for-agent` | Decision-complete; executable when dependencies permit |
| `ready-for-human` | Human judgment or an external action is next |
| `in-progress` | Implementation or revision is underway |
| `done` | Acceptance and required verification are complete |
| `wontfix` | Intentionally closed without implementation |

Blocking is derived, never stored as a status. A terminal parent cannot have an unfinished child. `done` does not
assert independent review, merge, release, or deployment; record these separately. Apply [Automatic parent completion](#automatic-parent-completion)
in the operation that completes the final child.

## Board, indexes, and Roadmap

`tasks/BOARD.md` presents authored **Now** and **Wayfinder Review** decisions, then generated **Active Work**,
**Ready Frontier**, **Waiting**, **Needs Info**, **Human Action**, **Needs Triage**, and **Recently Closed**.
Generated rows show order, ID, title, parent, status, blockers, and PR. An active TASK takes precedence over
starting another ready TASK. For "What's next?", return the current human decision, the active TASK if any,
and otherwise the first executable TASK in Ready Frontier. Say explicitly when there is no executable work.
The Wayfinder pointer remains advisory, not permission to bypass an active TASK or its blockers.

`ROADMAP.md` retains authored strategy and completion narrative. Its generated EPIC table shows current status;
Planning Frontier identifies non-terminal parents missing children for decomposition. Live EPIC/TICKET child tables include archived children; archived records retain their
historical text. Each level has a generated live index; archives get separate generated indexes when used.

Generated blocks use `<!-- planning:NAME -->` and `<!-- /planning:NAME -->`. Edit records and authored prose,
not generated rows. After changes run:

```bash
./bin/planning-check --write
./bin/planning-check
```

The writer first validates records and local Markdown links, then refreshes marked views. The read-only command
fails on stale views, invalid IDs/parents/status/priority, duplicate IDs, missing links, and dependency cycles.
`./bin/build` invokes only the read-only check. Validate planning mechanisms with their owning tools, never
product tests or deliberately invalid fixtures.

## Wayfinder

Maps investigate work too uncertain for approved implementation requirements. `wayfinder/README.md` indexes maps;
`wayfinder/tickets/WF-NNN-description.md` owns each decision and `wayfinder/research/` holds research evidence.
Use the local map and decision templates. Maps contain Active/Closed status, destination and done condition,
notes and linked decision summaries, decision table and dependencies, one Frontier, fog, and exclusions.
The map is an index, not a second store of decisions. Wayfinder tables and frontiers remain authored locally.

The Board names one unblocked Wayfinder review candidate only when an active map has a real decision frontier.
When none exists, say so. A Closed map has no frontier and links the resulting EPIC/TICKET/TASK handoff. A planning
handoff does not authorize runtime implementation; each executable TASK must have accepted scope.

## Explicit-only archive operation

Archive only on an explicit request, never as a completion side effect. Inspect the owning command's dry run
before applying it; do not hand-move records into archives.

| Request | Command | Destination |
| --- | --- | --- |
| Archive TASKs | `./bin/archive-planning tasks TASK-00001 … [--apply]` | `tasks/archive/` |
| Archive TICKETs | `./bin/archive-planning tickets TICKET-00001 … [--apply]` | `tickets/archive/` |
| Archive EPICs | `./bin/archive-planning epics EPIC-00001 … [--apply]` | `epics/archive/` |
| Archive a map | `./bin/archive-planning wayfinder map-name [--apply]` | Existing Wayfinder archive directories |

TASKs must be terminal. TICKETs additionally require all child TASKs terminal; EPICs require all child TICKETs
terminal, including archived children. Maps must be Closed, all linked decisions Closed, their Frontier must say
`None.`, and their resolution must link an implementation handoff. The command repairs owned Markdown links and
refreshes generated views; ignored scratch and dependency files are not rewritten. Inspect the resulting diff,
authored continuity notes, and `./bin/planning-check` output. Never flatten or renumber archived history.

## Branches and completion

Use this checkout or an isolated worktree as explicitly chosen with the user. New TASK branches start at `develop`
as `feature/task-NNNNN-<slug>`; never commit directly to `develop` or `main`. Preserve existing authorized branches.
TASK PR titles use `TASK-NNNNN — <title>`. Coordination scratch belongs under gitignored `.runs/`, never planning.

Before a commit or PR:

1. Record verified TASK acceptance, outstanding evidence, and review status honestly; mark done only when verified.
2. Refresh Board/index/parent/Roadmap projections with `./bin/planning-check --write` and validate read-only.
3. Update authored parent progress and strategic narrative where the outcome changed; apply automatic parent completion.
4. Preserve dependency edges, and ensure their resolved status yields the correct execution frontier.
5. Refresh Wayfinder continuity and the advisory pointer when a decision or handoff changed.
6. Run `git diff --check` and the complete canonical `./bin/build`; inspect exit evidence, not timeout output.

No planning completion authorizes commits to protected branches, publication, release, runtime enrollment,
or automatic archiving. Project-owned package contracts and architecture remain authoritative.
