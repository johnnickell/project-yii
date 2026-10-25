# Planning Conventions

## Lightweight work routing

For “What's next?”, `next`, or an unqualified `/ask-matt`, use the routing entry in root AGENTS.md.
This read-only path precedes general orientation; full implementation, review, QA and delivery requirements
still apply when the selected workflow is invoked.

1. Run `./bin/planning-check --check-delivery` once. It validates local planning read-only, then queries only
   explicitly current delivery handoffs. Failure stops selection with the actual owner/input gap; do not refresh
   views or retire markers. Verified MERGED/integrated `done` handoffs resolve read-only, so continue selection
   without routine cleanup. Apply [live handoff verification](#live-handoff-verification) for evidence and manual
   fallback; ordinary offline validation alone does not verify current PR state.
   Without a validator, disclose that limitation and check selected record metadata against the authored Board;
   do not claim generated freshness.
2. Read only the Board's generated Current Execution/Current Delivery, current human decision, active work,
   and (if no active work) first ready entry.
   Use local section names and authoritative order. Return all active TASKs, not a new winner. Inspect selected
   TASK metadata and its current handoff, plus blocker metadata only as needed. An unresolved contradiction
   is a named input gap, not permission to audit every record or infer acceptance from `done`.
3. Read the Wayfinder index's map-status/frontier inventory. Closed or decision-complete maps need no historical
   reread. For active/open maps, read only current frontier and decision/dependency status or explicit external
   prerequisite evidence needed for availability. Unknown state or an absent frontier needs its owner's input.
   Multiple available maps remain independent choices; no global priority is implied.
4. Use the Board/Roadmap's current planning handoff for unfinished decomposition, including closed maps.
   Verify the named phase in the owning record's current progress/approval section. Map closure, parent status
   and child counts do not prove complete decomposition. If the handoff is missing or contradictory, report
   the missing phase/owner; do not reconstruct it by reading every closed map and descendant.
5. Read canonical review/QA identity and disposition only when they change the selected route. Unresolved
   identity is an evidence handoff, never acceptance. Confirm a suggested skill from supplied capability
   metadata/bindings; load its full instructions and references when invoked, not merely to recommend it.

Prefer heading searches and bounded section reads. Aim to retrieve no more than about 24,000 characters
of routing evidence; stop with a precise unresolved input before exceeding that scope unless the user requests
a deeper audit. Do not read append-only task histories, other worktrees' entire tasks, general architecture,
or all receiving skills to produce a recommendation. Return TASK, WAYFINDER, and relevant PLANNING/HUMAN INPUT;
stop without invoking a workflow or changing files. Missing indexes are reported, not fabricated.

During authorized planning updates, keep current Board/Roadmap handoffs source-linked and concise: target,
phase, pending decision/owner. Keep Wayfinder inventory/frontier pointers current. These are navigation aids,
not another backlog or proof of delivery. Preserve generated sections and all historical decisions.

Routing sources: [TASK Board](tasks/BOARD.md), [Wayfinder inventory](wayfinder/README.md), [Roadmap](ROADMAP.md).
Provider `next` and `build` are explicit-only skills (`disable-model-invocation: true`), even if absent from the
automatic skill list. Confirm them through supplied metadata or the operator-supplied provider
`.pi/skills/<name>/SKILL.md`; never guess a private installation, copy/install a skill or execute a recommendation.
For unimplemented/ongoing work or confirmed repairs recommend `/skill:build TASK-NNNNN`; an explicit
implementation-only request uses `work`. Completed implementation awaiting acceptance routes to independent
`review`; accepted work to applicable `qa`, then authorized `land`. Unknown/stale report identity needs its evidence
owner's reconciliation, not a guessed verdict. Preserve the current checkout, ledger and consumed repair allowance.

Project Yii owns its planning. Individual records own scope, status, dependencies, and priority; generated
indexes, child tables, the TASK Board, and Roadmap tables are projections, not additional status authorities.

## Live handoff verification

Use `./bin/planning-check --check-delivery` at read-only routing and Land intake, pre-publication and final
handoff. It validates records/views before live queries; no writes, fetch, merge, setup or publication occur.
An optional `delivery: awaiting-merge` on a live TASK is the sole automated current-publication marker. Land sets
it only after verifying the exact OPEN publication and its `pr` URL. Neither `done`, a PR link, nor historical
prose creates a current delivery handoff. Generated rows say “Published; verify live state”, not unconditional OPEN.
Do not add markers to old PRs to scan history. Empty/absent markers have no live query effect.

At authorized Work, Land or substantive planning intake run `./bin/planning-check --reconcile-delivery` before
edits. This explicit write mode validates records, all live evidence and planned output before writing; it retires
only verified MERGED/integrated `done` markers, appends exact observed head/merge/time/local-HEAD evidence and
refreshes views/eligible parent rollups. OPEN markers remain. Any failure prevents all planned writes. Repeating
retirement with no current markers is a query-free, byte-identical no-op when views are current. Ordinary checks,
Review, QA and routing never retire markers. A full gate stays offline and read-only.

Live verification checks at most 16 markers, one `gh pr view` per marker, with a 15-second/16384-byte response
limit each. It requires the target GitHub origin, exact repository/URL/number/TASK title, TASK feature branch,
`develop` base, 40-hex published head, valid state and exact merge timestamp/commit. Missing tools, authentication,
metadata or local objects hold routing for the named evidence/Git/planning owner, not maintainer cleanup or setup
authority. Successful MERGED resolution proves integration only, never independent acceptance or QA.

Preserve the manual procedure for existing authorized authored handoffs or unavailable automation. Before routing
from an authored current publication/merge handoff, inspect only its named PR through existing
read-only operator access, for example `gh pr view <PR-URL> --json number,url,title,state,mergedAt,baseRefName,headRefName,headRefOid,mergeCommit`.
Match the target origin, exact PR URL/number, TASK/title, feature branch and `develop` base. Do not scan historical
PRs, fetch, merge, publish or refresh views during read-only routing.

- OPEN: human review/merge remains pending. A `done` status alone is not technical acceptance or QA evidence.
- MERGED: resolve the old human merge handoff only when the TASK is `done`, exact merge time/commit are present,
  and local Git proves published head → merge commit → current HEAD ancestry with replacement objects and lazy
  fetching disabled (`git --no-replace-objects`, `GIT_NO_LAZY_FETCH=1`). Continue normal Board/Wayfinder selection;
  do not ask the maintainer for routine cleanup of a verified ended handoff. This proves integration, not new
  review, QA, release or deployment authority.
- CLOSED without merge, unavailable/mismatched/contradictory metadata, non-`done` scope or absent local ancestry:
  name the exact evidence or integration gap and its owner. Do not infer OPEN, MERGED, acceptance or completion.

At authorized substantive planning or Work/Land intake, reconcile verified ended current handoffs in the owning
TASK and affected authored Board/Roadmap/parent summaries. Preserve historical publication checkpoints and exact
observed head/merge/time/local-HEAD evidence; refresh and validate views explicitly. Repeating reconciliation of
unchanged evidence must need no further edit. Read-only routing, Review, QA, `./bin/planning-check` and `./bin/build`
never perform this retirement or network-dependent mutation.

[TASK-00014](tasks/00014-TASK.md) adapts Fight Common's
[TASK-00176](https://github.com/johnnickell/fight-common/pull/234) tooling reference with Yii's local validation and
authored Wayfinder guarantees. `./bin/planning-check` and `--write` remain offline and do not assert live freshness.
`--write`, `--check-delivery` and `--reconcile-delivery` are mutually exclusive effects; no combined implicit writes.

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
full pull-request URL, not an assertion of merge state. Optional `delivery: awaiting-merge` identifies a verified
OPEN publication snapshot on a live TASK; see [live handoff verification](#live-handoff-verification). Frontmatter uses flat, single-line values.

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
Generated rows show order, ID, title, parent, status, blockers, and PR. Generated Current Execution on the Board
and Roadmap uses the same ordering/classification: all active TASKs, otherwise the first executable ready TASK;
Human Action/Needs Info remain visible, and no-executable routes include first triage/waiting reasons. Completed
blocker edges remain in full tables while routing lists only unresolved blockers. Current Delivery projects only
explicit publication markers; authored Now decisions/history and planning decomposition remain separate. An active TASK takes precedence over
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
`./bin/build` invokes only the offline read-only check, first as a freshness preflight before expensive checks
and again as its final planning phase. Validate planning mechanisms with their owning tools, never
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

## Map-wide planning order

Within one accepted map scope, finish decisions/grilling and all approved EPIC handoffs before decomposing its
EPICs into accepted TICKETs; finish that phase across the map before decomposing TICKETs into TASKs. Respect an
explicit narrower human choice without inventing dependencies between otherwise independent records/maps.
An interview-mode Wayfinder decision stays inside its own resolution boundary; it does not invoke EPIC-producing
`grill` automatically. Each grill writes at most one approved EPIC. Reuse settled decisions and live/archived IDs.

Use current map/Board/Roadmap handoffs and owning progress sections to distinguish decisions resolved, EPICs
written/approved, TICKET splits accepted and TASK splits accepted. Nonempty child tables and terminal parent
status are not phase markers. Missing/contradictory mapping needs the planning owner, not a history-wide search.
A Closed map may still have unfinished linked decomposition; continue that handoff without reopening decisions.
When phases are complete, recommend read-only `next` for Board-based selection; never start implementation.

## Execution evidence

For new runs resolve the canonical base through Git common-directory and registered-worktree metadata:

| Base-owned ignored path | Owner / purpose |
| --- | --- |
| `.runs/build/<TASK-ID>/` | Build coordinator context, ledger, active lock and unique attempts |
| `.runs/reviews/<TASK-ID>/review.md` | Independent Reviewer only; canonical latest verdict, preserved history |
| `.runs/qa/<TASK-ID>/qa.md` | Independent QA only; canonical disposition, unique `runs/<run-id>/` evidence |
| `.runs/tasks/<TASK-ID>/runs/<run-id>/` | Unique task-owned implementation, checks and delivery evidence |
| `.runs/prototypes/<scope>/` | Disposable design/prototype artifacts, never production code |
| `.runs/worktrees/` | Explicitly selected registered isolated worktrees, managed through Git |

This supersedes the old `.runs/<YYYY-MM-DD>-<slug>/` coordinate-build convention for **new runs only**. Preserve
historical and in-flight runs in place, including failed evidence; adoption grants no relocation/deletion authority.
Never stage `.runs/`. Keep application runtime and tool caches under Yii's `var/`, not provider scratch/cache paths.
Independent owners alone replace canonical reports; implementers/coordinators may read but never fabricate or
rewrite them. Unknown report/base ownership holds that action. See the [shared binding](agents/shared-workflows.md)
for identity, continuation and publication contracts. Existing explicit archive rules remain unchanged.

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
