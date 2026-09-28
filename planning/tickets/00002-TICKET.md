---
id: TICKET-00002
epic: EPIC-00002
legacy_id: PRD-00002
title: Fight Common Version Adoption and Yii Quality
status: needs-info
---

# Fight Common Version Adoption and Yii Quality

## Problem Statement

The starter must consume Fight Common through public Composer contracts and keep its owned Yii composition safe
to change without retaining historical certification machinery in the everyday development gate.

## Solution

The root lockfile records the selected dependency graph. Exact Unit coverage protects application code; selected
Integration and Functional journeys prove the configured Yii composition and production `--no-dev` boot.
Symfony Messenger remains the selected envelope-transport fallback, not a claim of queue-worker delivery; stable
Yii Queue remains explicitly unavailable. Version 2.0 stays non-executable until its owning authority exists.

## Implementation Decisions

- The root lockfile and booted journeys are the maintained adoption evidence.
- Yii owns configuration-provider and DI composition; Fight Common remains a Composer dependency only.
- JWT encoding and decoding are fixed to HS256; algorithm selection is not a public application setting.
- Yii Mail and Yii Session are forbidden unselected dependencies: neither Composer lock installs them, and Symfony
  Mailer remains the selected mail fallback.
- No speculative 2.0 breaking changes are defined here.

## Testing Decisions

- Unit coverage is code-only and exact; broader suites cannot mask a missing Unit statement.
- Integration tests are retained only for real container composition and collaborating adapters.
- Functional tests prove configured HTTP, Yii console, and production `--no-dev` boot behavior.
- Deployment security proof uses environment-sourced HMAC/JWT inputs, lazy fail-closed resolution, and explicit
  verification-only fixtures; no deployable credential fallback is accepted.
- The canonical build delegates to focused production-code tools and executable behavior, not tests of its own
  scripts, documentation, configuration text, workflow contents, or historical receipts.

## Out of Scope

- Copied source, nested applications, central builds, releases, and publication.

## Further Notes

The 2026-09-09 authorship-only Fight Common rewrite changed the certified candidate identity without changing its
tree. TASK-00006 records the exact mapping, regenerated consumer evidence, and replacement certification while
preserving the original ticket's historical statements.

Fight Common T-00087 transfers the remaining lean pre-submit cleanup to [TASK-00010](../tasks/00010-TASK.md),
imported from the later legacy T-00007 in [PR #8](https://github.com/johnnickell/project-yii/pull/8).
TASK-00005 remains the historical code-only gate and hardened two-service FPM outcome; TASK-00010 retains the
source requirements and maps all of them to TASK-00008/00009, closing only the duplicate assignment. Remaining
policy/coverage-verifier removal, direct Unit coverage, and meaningful Yii boundaries are consolidated in TASK-00009.
The separate 2.0 migration stays blocked on Fight Common's future authority.
PR #4 intentionally carried TASK-00004 together with its inseparable TASK-00002 support receipt under John's
approved two-ticket exception (the historical execution terminology); this does not establish a general
multi-TASK delivery convention.

Migrated from PRD-00002. The recorded production `--no-dev` gate is the current/historical contract, not the
approved future direction. [TICKET-00004](00004-TICKET.md) owns its explicit replacement; this migration does not
change runtime behavior or reopen completed TASKs.

## Reconciliation decision

PR #8 marked this requirement `in-progress` with its legacy successor ready. On 2026-09-28 the maintainer directed
adoption of the new standards and consolidation without losing work. TASK-00010 retains the legacy contract and
maps stable-package/PHPCS adoption to [TICKET-00003](00003-TICKET.md) / TASK-00008, and all gate/coverage cleanup
to [TICKET-00004](00004-TICKET.md) / TASK-00009. The latter preserves exact Unit-only and missing-file/ignore
rejection while replacing bespoke verification machinery with direct attribution and standard coverage tools.
No gate outcome is discarded or claimed complete; only the duplicate assignment closes as `wontfix`.

This historical requirement returns to `needs-info` solely because its remaining Common 2.0 TASK-00003 still
lacks upstream migration authority. The stable-adoption and consolidated gate requirements remain executable
in dependency order under EPIC-00003; there is no overlap-related needs-info blocker or automatic parent closeout.

## TASKs

<!-- planning:children -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| — | [TASK-00002](../tasks/00002-TASK.md) | Adopt Fight Common 1.2 | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](00002-TICKET.md) | done | [TASK-00004](../tasks/00004-TASK.md) | — |
| — | [TASK-00003](../tasks/00003-TASK.md) | Prepare Fight Common 2.0 Migration | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](00002-TICKET.md) | needs-info | — | — |
| — | [TASK-00004](../tasks/00004-TASK.md) | Establish the Yii Complete Platform Profile | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](00002-TICKET.md) | done | — | — |
| — | [TASK-00005](../tasks/00005-TASK.md) | Establish the Lean Yii Quality Gate and Harden FPM | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](00002-TICKET.md) | done | — | — |
| — | [TASK-00006](../tasks/00006-TASK.md) | Re-certify Rewritten Fight Common Candidate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](00002-TICKET.md) | done | — | — |
| — | [TASK-00010](../tasks/00010-TASK.md) | Establish the Lean Yii Pre-Submit Quality Gate | [TICKET-00002 — Fight Common Version Adoption and Yii Quality](00002-TICKET.md) | wontfix | — | — |
<!-- /planning:children -->
