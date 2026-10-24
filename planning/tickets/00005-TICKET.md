---
id: TICKET-00005
epic: EPIC-00003
title: Adopt Shared Engineering and Agent Workflow Conventions
status: ready-for-agent
order: 1
---

# Adopt Shared Engineering and Agent Workflow Conventions

## Problem and outcome

Make the shared Agent OS engineering, planning, documentation, and delivery conventions the default for Yii,
with a concise target-owned binding and explicit exceptions for Yii-native integration and stronger local
requirements. Consume installed skills and shared guidance by reference, not copied catalogs or private paths.
The maintainer approved this broader adoption and a separate conventions slice on 2026-10-24.

## Use cases and contracts

A maintainer or Agent invokes a shared skill from the Yii checkout. The skill resolves Yii as the target,
loads the applicable local rules and templates, and follows shared workflow/report contracts with documented
substitutions. The provider owns its skills, harness, and generic orchestration; Yii owns target policy,
framework composition, commands, planning, and acceptance. A missing provider/resource or unresolved conflict
produces a precise prerequisite/owner handoff, never a fallback to provider planning or a guessed private path.

Adopt ownership/layering, interface simplicity, CQRS, safe transport, naming/PHP, meaningful tests, instruction
quality, independent Review/QA, and delivery evidence principles. Keep Yii configuration providers, container,
routing, HTTP/console entrypoints, Twig, and selected package adapters. Provider Slim, container-module layout,
PostgreSQL, frontend, and managed-execution examples are not Yii implementation requirements. Do not create
speculative layers or rename public package contracts to imitate the provider.

This changes engineering and operator instructions, not business commands, queries, events, endpoints, or
permissions. Its effects are scoped documentation/planning updates and ignored execution evidence. Skill
availability is not authority to install, enroll, disclose private source, publish, release, or provision services.

## Acceptance requirements

- Provide a concise binding reachable from AGENTS.md for execution/substantive planning, without adding broad
  orientation reads to next. Resolve provider resources from supplied skill/package metadata; preserve local
  authority, record applicable shared guidance and explicit exceptions, and keep private source identities out
  of portable documents. Missing optional profiles alone do not block work.
- Cover all installed skill categories: next; work/build/review/qa/land; grill/to-tickets/to-tasks/wayfinder/fix;
  adopt/audit/architecture/security-audit/research; design/prototype/design-review/graphify/writing-for-agents;
  and release as unavailable for mutation without separate accepted local release scope. Resolve local template,
  foundation/context, map-phase, PR-body, and runtime substitutions rather than requiring provider-only files.
- Adopt canonical base-owned `.runs/build/<TASK-ID>/`, `.runs/reviews/<TASK-ID>/`, `.runs/qa/<TASK-ID>/`, and
  task evidence conventions. Supersede the conflicting dated coordinate-build scratch rule for new work;
  preserve historical runs and current evidence ownership without automatic moves or deletion. Keep application
  runtime and tool caches in their Yii-owned `var/` locations unless a separate scoped change is approved.
- Keep next read-only: validate once, use current Board decisions/all active TASKs or the first executable ready
  TASK, inspect bounded current evidence and Wayfinder inventory, honor completed blockers and planning phases,
  and recommend then stop. Explicit-only next/build skills must be discoverable through supplied metadata or
  the binding without automatic invocation or a private hardcoded provider path.
- Document work/review/QA/land responsibilities, canonical report identity and independent acceptance, Build's
  delegated commits/publication and revision ledger, checkout selection, and local versus hosted evidence.
  Keep next recommendations distinct from workflow authorization. Preserve branch/base conventions, automatic
  parent completion, explicit-only archive operations, and any required hosted/merge checks.
- Retain exact 100% Unit-only owned-production statement coverage, missing-file/ignore rejection, and the
  no-certification/no-tests-of-tools rules. Do not substitute the provider's weaker Adapter coverage policy.
  Route package/PHPCS implementation to TASK-00008 and gate/test-tooling implementation to TASK-00009; resolve
  concrete denominator/test-quality conflicts without suppression or silently dropping accepted requirements.
- Document harness installation separately from skill discovery; project-local `.pi/` copies are not required.
  Jev enrollment/setup remain optional, native tools remain ordinary Pi tools, and roles/fresh processes are not
  sandboxing. No settings/authentication changes, model selection, paid qualification, source disclosure, or
  source-provider enrollment is needed merely to adopt instructions. Preserve independently selected tool policy.
- Verify ordinary and failure/ambiguity instruction journeys directly, including missing resources, stale
  reports, blocked Wayfinder, and no enrollment. Use actual installed capability metadata, planning validation,
  and the canonical local gate; do not claim full delegated runtime qualification from document walkthroughs.

## Exclusions

No copying shared skills, global standards rollout, harness implementation, dependency upgrade, gate replacement,
new application behavior, automatic environment isolation, browser workflow, release/distribution, publication,
or historical evidence relocation. Concrete source gaps outside existing TASK scope require separately accepted
follow-up work, not a claim that instruction adoption proves implementation conformance.

## TASKs

<!-- planning:children -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 2 | [TASK-00012](../tasks/00012-TASK.md) | Bind Shared Engineering and Agent Workflows to Yii | [TICKET-00005 — Adopt Shared Engineering and Agent Workflow Conventions](00005-TICKET.md) | ready-for-agent | [TASK-00011](../tasks/00011-TASK.md) | — |
<!-- /planning:children -->

## Progress

Requirement and separate implementation slice approved in the 2026-10-24 adoption conversation.
[TASK-00012](../tasks/00012-TASK.md) owns implementation after the planning amendment in
[TASK-00011](../tasks/00011-TASK.md). This is the preferred first adoption slice; package and gate implementation
retain their existing owners. No further decomposition is pending for this bounded requirement. Bindings and
instruction changes are not yet implemented, and no end-to-end Pi workflow or harness qualification is claimed.
