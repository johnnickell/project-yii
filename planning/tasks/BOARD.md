# TASK Board

## Routing index

Read only current human decisions and active work below; otherwise the first ready TASK.
Use [lightweight routing](../CONVENTIONS.md#lightweight-work-routing) for validation and evidence limits.
[Wayfinder status and frontiers](../wayfinder/README.md) answer the decision-routing question.
Use the [Roadmap](../ROADMAP.md) and current handoffs for planning phases; do not infer full decomposition from closed maps.
Read the referenced current section, not the full historical record. Missing handoff/phase needs owner input.

TASK files own status, blockers, and `order`; generated sections below project execution priority.

## "What's Next?" Contract

Return the human decision under **Now** and the active TASK. If none is active, return the first executable TASK
under **Ready Frontier**. Do not start a second TASK just because its ID is lower. State when no work is executable.

## Now

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
administrative closeout B changed delivery metadata only. TASK-00008 now has a local stable-package implementation
and bounded installed-contract evidence with the complete gate passed, pending independent acceptance. No additional general adoption decision or decomposition is pending
for [EPIC-00003](../epics/00003-EPIC.md#progress).
Concrete conflicts or releases outside the approved envelope need scoped owner decisions; Common 2.0 remains
`needs-info` until its contract, deprecation-removal inventory, and migration guide exist.

## Wayfinder Review

[Yii AccessControl Starter Application](../wayfinder/yii-access-control-application-map.md) remains active, with
no currently executable Wayfinder review candidate. The maintainer confirmed and closed
[WF-002 — Local Development Runtime Contract](../wayfinder/tickets/WF-002-local-development-runtime-contract.md).
[WF-001 — Released Package Contract Audit](../wayfinder/tickets/WF-001-released-package-contract-audit.md)
is next but remains gated on verified installed stable Common `^1.4` / AccessControl `^0.8` contracts;
[TASK-00008](00008-TASK.md) owns bounded adoption and now records local installed-artifact evidence pending
independent acceptance. [Baseline findings](../../docs/stable-package-baseline.md) do not close the full inventory.
Shared-convention adoption alone does not satisfy that gate.
WF-003 still waits for WF-001 and Symfony's canonical wire contract. The bounded adoption TASKs do not authorize
the broader application map.

## Active Work

<!-- planning:active -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 3 | [TASK-00008](00008-TASK.md) | Install and Verify the Stable Fight Package Baseline | [TICKET-00003 — Adopt Stable Fight Common and AccessControl Contracts](../tickets/00003-TICKET.md) | in-progress | [TASK-00007](00007-TASK.md), [TASK-00011](00011-TASK.md) | — |
<!-- /planning:active -->

## Ready Frontier

<!-- planning:ready -->
None.
<!-- /planning:ready -->

## Waiting

<!-- planning:waiting -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 4 | [TASK-00009](00009-TASK.md) | Replace the Disposable Build with a Running-Container PHP Gate | [TICKET-00004 — Verify Yii Through a Running-Container PHP Gate](../tickets/00004-TICKET.md) | ready-for-agent | [TASK-00008](00008-TASK.md) | — |
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
| 2 | [TASK-00012](00012-TASK.md) | Bind Shared Engineering and Agent Workflows to Yii | [TICKET-00005 — Adopt Shared Engineering and Agent Workflow Conventions](../tickets/00005-TICKET.md) | done | [TASK-00011](00011-TASK.md) | https://github.com/johnnickell/project-yii/pull/14 |
| — | [TASK-00001](00001-TASK.md) | Establish the Governed Yii Starter Foundation | [TICKET-00001 — Yii Starter Product and Walking-Slice Acceptance](../tickets/00001-TICKET.md) | done | — | — |
| — | [TASK-00002](00002-TASK.md) | Adopt Fight Common 1.2 | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | [TASK-00004](00004-TASK.md) | — |
| — | [TASK-00004](00004-TASK.md) | Establish the Yii Complete Platform Profile | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00005](00005-TASK.md) | Establish the Lean Yii Quality Gate and Harden FPM | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00006](00006-TASK.md) | Re-certify Rewritten Fight Common Candidate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00010](00010-TASK.md) | Establish the Lean Yii Pre-Submit Quality Gate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | wontfix | — | — |
<!-- /planning:closed -->
