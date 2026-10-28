# TASK Board

## Routing index

Validate with offline read-only `./bin/planning-check`, then read generated Current Execution and current human
decisions below; otherwise the first ready TASK. Historical publication checkpoints do not create current merge
actions or recurring post-merge planning work; uncertainty needs its exact owner/input.
Use [lightweight routing](../CONVENTIONS.md#lightweight-work-routing) for validation and evidence limits.
[Wayfinder status and frontiers](../wayfinder/README.md) answer the decision-routing question.
Use the [Roadmap](../ROADMAP.md) and current handoffs for planning phases; do not infer full decomposition from closed maps.
Read the referenced current section, not the full historical record. Missing handoff/phase needs owner input.

TASK files own status, blockers, and `order`; generated sections below project execution priority.

## "What's Next?" Contract

Return the human decision under **Now** and the active TASK. If none is active, return the first executable TASK
under **Ready Frontier**. Do not start a second TASK just because its ID is lower. State when no work is executable.

## Current Execution

<!-- planning:next -->
| Route | TASK | Title | Status | Unresolved blockers |
| --- | --- | --- | --- | --- |
| Needs Info | [TASK-00003](00003-TASK.md) | Prepare Fight Common 2.0 Migration | needs-info | — |

No TASK is currently executable; unresolved work may remain.
<!-- /planning:next -->

## Now

[Publication closeout](../CONVENTIONS.md#publication-closeout) retires the former delivery-marker/queue and
recurring reconciliation duties described in the historical checkpoint below. Land closes accepted TASK/current
docs, validates its exact live subject and retires eligible owned local resources during delivery. Human merge
stays separate; historical acceptance/publication evidence remains, without a recurring post-merge planning chore.

On 2026-10-25 the maintainer approved [TASK-00014 — Keep Yii Post-Merge Routing and Delivery Handoffs Current](00014-TASK.md)
as a standalone bug, using the main checkout. Port Fight Common's bounded live delivery checking, explicit
idempotent intake reconciliation and generated current summaries with Yii-native validation guarantees. PR #16 is
already merged and locally integrated; no human merge or routine cleanup handoff remains for TASK-00009.
The planning baseline was integrated through PR #17 at `30c02ce69625eecafe8884eda83d4e4715bfd900`.
TASK-00014 candidate A is independently accepted and QA-passed. At its publication checkpoint,
[PR #18](https://github.com/johnnickell/project-yii/pull/18) awaited human review/merge; intake reconciliation now verifies it
MERGED on 2026-10-25 at `f8d41eefa2eb3b4cb550ab7bb940248be30b2d38`, integrated in this checkout, with
[exact delivery evidence](00014-TASK.md#delivery-reconciliation--pr-18). No merge or routine cleanup handoff remains.
The earlier planning-publication instruction is historical, not an implementation delivery handoff.
Preserve its historical PR #16 reconciliation evidence, not the retired marker/manual-maintenance duties. WF-001's research closeout below is separate.

On 2026-10-24 the maintainer approved the urgent standalone runtime security repair in
[TASK-00013](00013-TASK.md), with an isolated worktree and no package-adoption prerequisite. Independent review
accepted candidate A and independent behavioral QA passed; [PR #13](https://github.com/johnnickell/project-yii/pull/13)
is now merged into `develop` at `7edee4a707e904e75789f98baba2cf067bc83f0b`. The maintainer's earlier
authorization to merge `develop` into that feature branch using the main checkout remains historical;
runtime repair does not authorize the broader gate redesign or deployment.

On 2026-10-24 the maintainer approved comprehensive shared-standard/skill/workflow adoption, retaining Yii-native
integration and stronger local guarantees, and latest stable Common 1.4.0 / AccessControl 0.8.0 (`^1.4` / `^0.8`).
Use the main checkout on separate feature branches. [TASK-00011](00011-TASK.md) records this independently
accepted and QA-passed planning amendment, now integrated into `develop` through
[PR #12](https://github.com/johnnickell/project-yii/pull/12). Preferred implementation order remains
[TASK-00012](00012-TASK.md) (shared conventions and target bindings), [TASK-00008](00008-TASK.md) (packages/PHPCS), then
[TASK-00009](00009-TASK.md) (running-container gate). Existing package/gate assignments are not duplicated.
[TASK-00010](00010-TASK.md) retains the legacy source/mapping, closed only as a duplicate assignment.
[TASK-00012](00012-TASK.md#completion-notes) independently accepted candidate A, independently QA-passed, and
is integrated through merged [PR #14](https://github.com/johnnickell/project-yii/pull/14) on the TASK-00008 base;
administrative closeout B changed delivery metadata only. TASK-00008 is independently accepted and QA-passed at
its exact candidate; [PR #15](https://github.com/johnnickell/project-yii/pull/15) is merged at
`f40543de48705007f17cd8c02ea29c1c2c015a11`. TASK-00009 independently accepted candidate A `d810303ebeeea9c1114e77ac2025da3a3138d197` after the
[owner's clarification](00009-TASK.md#current-work--owner-clarification-applied), resolved REV-01, and independent
QA passed all seven scenarios (visual N/A). Its administrative closeout B records factual completion and
[PR #16](https://github.com/johnnickell/project-yii/pull/16) only; no requirement, instruction or behavior changed.
Hosted CI is optional and unobserved. [Current integration](00009-TASK.md#current-integration--pr-16) verifies
PR #16 merged into `develop` on 2026-10-25 at `26f8ebb06fd07e9aebeb7fb51a33e32ca32509c8`, already integrated
in this checkout. No human merge handoff remains for TASK-00009; this does not authorize release or deployment.
No additional general adoption decision or decomposition is pending for [EPIC-00003](../epics/00003-EPIC.md#progress).
Concrete conflicts or releases outside the approved envelope need scoped owner decisions; Common 2.0 remains
`needs-info` until its contract, deprecation-removal inventory, and migration guide exist.

## Wayfinder Review

[Yii AccessControl Starter Application](../wayfinder/yii-access-control-application-map.md) remains active. The
maintainer confirmed and closed [WF-002 — Local Development Runtime Contract](../wayfinder/tickets/WF-002-local-development-runtime-contract.md).
[WF-001 — Released Package Contract Audit](../wayfinder/tickets/WF-001-released-package-contract-audit.md) is now
Closed with the [complete source/workflow audit](../wayfinder/research/wf-001-released-package-contract-audit.md):
1,124 unique declarations, 4,817 direct public members and 125 schema components have explicit dispositions.
This is research, not implementation or consumer qualification. No Wayfinder decision is currently evidenced as
available. [WF-003 — Yii ADR and Shared OpenAPI Contract](../wayfinder/tickets/WF-003-yii-adr-and-shared-openapi-contract.md)
is next in map order but needs the Symfony wire-contract owner's immutable accepted artifact (repository, full
revision, artifact path and acceptance reference), then Yii map-owner gate verification. Do not begin its prototype
or decompose the broader application yet; the bounded adoption TASKs do not authorize that implementation.

## Active Work

<!-- planning:active -->
None.
<!-- /planning:active -->

## Ready Frontier

<!-- planning:ready -->
None.
<!-- /planning:ready -->

## Waiting

<!-- planning:waiting -->
None.
<!-- /planning:waiting -->

## Needs Info

<!-- planning:needs-info -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| — | [TASK-00003](00003-TASK.md) | Prepare Fight Common 2.0 Migration | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | needs-info | — | — |
<!-- /planning:needs-info -->

## Human Action

<!-- planning:human -->
None.
<!-- /planning:human -->

## Needs Triage

<!-- planning:triage -->
None.
<!-- /planning:triage -->

## Recently Closed

<!-- planning:closed -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | [TASK-00007](00007-TASK.md) | Migrate Local Planning to EPIC, TICKET, and TASK | — | done | — | https://github.com/johnnickell/project-yii/pull/9 |
| 1 | [TASK-00011](00011-TASK.md) | Record the Comprehensive Yii Adoption Plan | — | done | — | https://github.com/johnnickell/project-yii/pull/12 |
| 1 | [TASK-00013](00013-TASK.md) | Refresh and Enforce Patched Yii Container Runtimes | — | done | — | https://github.com/johnnickell/project-yii/pull/13 |
| 1 | [TASK-00014](00014-TASK.md) | Keep Yii Post-Merge Routing and Delivery Handoffs Current | — | done | — | https://github.com/johnnickell/project-yii/pull/18 |
| 2 | [TASK-00012](00012-TASK.md) | Bind Shared Engineering and Agent Workflows to Yii | [TICKET-00005 — Adopt Shared Engineering and Agent Workflow Conventions](../tickets/00005-TICKET.md) | done | [TASK-00011](00011-TASK.md) | https://github.com/johnnickell/project-yii/pull/14 |
| 3 | [TASK-00008](00008-TASK.md) | Install and Verify the Stable Fight Package Baseline | [TICKET-00003 — Adopt Stable Fight Common and AccessControl Contracts](../tickets/00003-TICKET.md) | done | [TASK-00007](00007-TASK.md), [TASK-00011](00011-TASK.md) | https://github.com/johnnickell/project-yii/pull/15 |
| 4 | [TASK-00009](00009-TASK.md) | Replace the Disposable Build with a Running-Container PHP Gate | [TICKET-00004 — Verify Yii Through a Running-Container PHP Gate](../tickets/00004-TICKET.md) | done | [TASK-00008](00008-TASK.md) | https://github.com/johnnickell/project-yii/pull/16 |
| — | [TASK-00001](00001-TASK.md) | Establish the Governed Yii Starter Foundation | [TICKET-00001 — Yii Starter Product and Walking-Slice Acceptance](../tickets/00001-TICKET.md) | done | — | — |
| — | [TASK-00002](00002-TASK.md) | Adopt Fight Common 1.2 | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | [TASK-00004](00004-TASK.md) | — |
| — | [TASK-00004](00004-TASK.md) | Establish the Yii Complete Platform Profile | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00005](00005-TASK.md) | Establish the Lean Yii Quality Gate and Harden FPM | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00006](00006-TASK.md) | Re-certify Rewritten Fight Common Candidate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00010](00010-TASK.md) | Establish the Lean Yii Pre-Submit Quality Gate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | wontfix | — | — |
<!-- /planning:closed -->
