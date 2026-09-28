# TASK Board

TASK files own status, blockers, and `order`; generated sections below project execution priority.

## "What's Next?" Contract

Return the human decision under **Now** and the active TASK. If none is active, return the first executable TASK
under **Ready Frontier**. Do not start a second TASK just because its ID is lower. State when no work is executable.

## Now

The maintainer approved stronger alignment: stable Fight Common 1.2+ / AccessControl 0.4+, a running-container
PHP-orchestrated gate, and EPIC/TICKET/TASK planning in this checkout, including the later legacy plan merged in
PR #8. Its requirements are consolidated into stable adoption in [TASK-00008](00008-TASK.md) and the complete
standards-aligned gate in [TASK-00009](00009-TASK.md). [TASK-00010](00010-TASK.md) preserves the legacy source and
mapping, closed only as a duplicate assignment. No additional alignment decision is pending.
Fight Common 2.0 remains `needs-info` until its contract, deprecation-removal inventory, and migration guide exist.

## Wayfinder Review

[Yii AccessControl Starter Application](../wayfinder/yii-access-control-application-map.md) remains active.
[WF-002 — Local Development Runtime Contract](../wayfinder/tickets/WF-002-local-development-runtime-contract.md)
is its current human review frontier. The bounded alignment TASKs do not authorize the broader application map.

## Active Work

<!-- planning:active -->
None.
<!-- /planning:active -->

## Ready Frontier

<!-- planning:ready -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 2 | [TASK-00008](00008-TASK.md) | Install and Verify the Stable Fight Package Baseline | [TICKET-00003 — Adopt Stable Fight Common and AccessControl Contracts](../tickets/00003-TICKET.md) | ready-for-agent | [TASK-00007](00007-TASK.md) | — |
<!-- /planning:ready -->

## Waiting

<!-- planning:waiting -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 3 | [TASK-00009](00009-TASK.md) | Replace the Disposable Build with a Running-Container PHP Gate | [TICKET-00004 — Verify Yii Through a Running-Container PHP Gate](../tickets/00004-TICKET.md) | ready-for-agent | [TASK-00008](00008-TASK.md) | — |
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
| — | [TASK-00001](00001-TASK.md) | Establish the Governed Yii Starter Foundation | [TICKET-00001 — Yii Starter Product and Walking-Slice Acceptance](../tickets/00001-TICKET.md) | done | — | — |
| — | [TASK-00002](00002-TASK.md) | Adopt Fight Common 1.2 | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | [TASK-00004](00004-TASK.md) | — |
| — | [TASK-00004](00004-TASK.md) | Establish the Yii Complete Platform Profile | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00005](00005-TASK.md) | Establish the Lean Yii Quality Gate and Harden FPM | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00006](00006-TASK.md) | Re-certify Rewritten Fight Common Candidate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | done | — | — |
| — | [TASK-00010](00010-TASK.md) | Establish the Lean Yii Pre-Submit Quality Gate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](../tickets/00002-TICKET.md) | wontfix | — | — |
<!-- /planning:closed -->
