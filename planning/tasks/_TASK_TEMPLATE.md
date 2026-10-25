---
id: TASK-NNNNN
ticket: TICKET-NNNNN
kind: feature
title: Bounded executable outcome
status: needs-triage
order:
blocked_by:
pr:
delivery:
---

# Bounded executable outcome

## Outcome

State the independently reviewable vertical slice and its accepted requirement/decision authority.
For standalone bugs/chores use an empty ticket field and the appropriate kind.
Leave delivery empty until Land verifies an exact OPEN publication; then use `awaiting-merge` with its PR URL.
See [delivery conventions](../CONVENTIONS.md#live-handoff-verification).

## Scope

- In scope:
- Out of scope:

## Acceptance Criteria

- [ ] Observable behavior or artifact, including rejection/failure evidence where applicable.

## Verification

List focused checks and the canonical `./bin/build`. For bugs, record a failing regression before repair or explain
why reproduction is infeasible. Validate tools directly, not with product tests or invalid fixtures.

## Completion Notes

Record actual results, warnings, omissions, and independent-review/PR state. No unverified done status.
