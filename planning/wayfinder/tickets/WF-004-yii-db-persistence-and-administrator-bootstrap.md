# Yii DB Persistence and Administrator Bootstrap

**Labels:** `wayfinder:prototype`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-001, WF-002, WF-003

## Question

How should the starter persist the complete AccessControl contract in MySQL and establish the first administrator
through a safe, invitation-led console workflow?

## Must decide

- Yii DB repository implementations and mappings for every persistence port found by WF-001, with no package
  Domain/Application source duplication and no Active Record leakage across the adapter boundary.
- Migration ownership, table/index/foreign-key shape, identifiers, UTC/time precision, secrets and token material,
  JSON fields where justified, forward compatibility, and local schema bootstrap.
- Transaction boundaries for commands, audit writes, credential/session rotation, invitation and reset consumption,
  and post-commit effect dispatch so authoritative state and audit history cannot diverge.
- Optimistic/pessimistic concurrency behavior, uniqueness races, replay/idempotency behavior, deadlock retry limits,
  and deterministic error translation.
- Managed role/permission reference-data reconciliation with stable ownership, collision detection, dry-run/preview,
  non-purging preservation of unrelated authored records, and atomic auditability.
- A non-public console command that creates the first pending administrator invitation through application contracts;
  activation, password setup, and authentication continue through the ordinary account lifecycle.
- Safe repeated bootstrap, already-initialized behavior, noninteractive invocation, secret handling, and operator docs.

## Prototype evidence

- Disposable MySQL proofs for repository round trips, transaction rollback, uniqueness/concurrency conflicts,
  managed-policy reconciliation, and audit atomicity.
- A console-flow proof showing invitation-led bootstrap and fail-closed repeat behavior without a public endpoint.

## Resolution boundary

This ticket settles persistence and initial-administrator seams. It does not implement migrations or repositories,
introduce self-registration, expose public bootstrap, choose production database operations, or move domain policy
into Yii adapters.
