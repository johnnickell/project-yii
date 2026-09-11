# Implementation Handoff Acceptance Contract

**Labels:** `wayfinder:grilling`, `planning`
**Mode:** HITL
**Status:** Open
**Gate:** Human approval of the handoff
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-001, WF-002, WF-003, WF-004, WF-005, WF-006, WF-007, WF-008, WF-009

## Question

What epic, PRDs, vertical implementation tickets, acceptance journeys, documentation, and dependency order are
required to implement the closed Wayfinder without losing its boundaries or cross-repository blockers?

## Must decide

- Create one destination epic and the smallest coherent PRD set covering runtime foundation, persistence/bootstrap,
  HTTP/security/authorization, async/realtime operations, and client adoption without layer-only delivery phases.
- Produce independently verifiable vertical T-tickets that cross configuration, Action/application contract,
  adapter, presentation, and behavioral proof only where the slice needs them.
- Order work by actual blockers: released package contract, local runtime, shared wire/auth/event contracts,
  persistence, principals, operation groups, async effects, and completed Symfony client adoption.
- Preserve visible `needs-info` states for unfinished package or Symfony dependencies; do not label blocked work
  executable merely because the Yii-side design is understood.
- Define acceptance journeys for local bootstrap/teardown, initial administrator invitation and activation,
  authentication/session rotation, human and Agent authorization, administrative workflows, worker failure/recovery,
  scheduled expiry, private SSE notification plus authoritative refetch, and full console coverage.
- Define meaningful Unit coverage for production code, real Integration boundary tests, and limited high-value
  Functional journeys; use real commands and human inspection for wrappers, configuration, generated docs, and assets.
- Require checked-in OpenAPI and architecture/operations/security/API/client documentation, `.env.example`, local
  runbooks, non-HTTP capability guidance, and Symfony contract/provenance notes.
- Establish per-ticket verification, final `./bin/build` qualification, planning synchronization, and explicit
  separation of local proof, hosted CI, release qualification, and production readiness.
- Require one valid checked-in Yii-generated OpenAPI document and rendered local Swagger UI, normalized Symfony
  semantic parity, generated-client compilation, unauthorized operation/private-subscription rejection, JWT
  rotation/reuse, invitation-led bootstrap, queue/outbox retry and recovery, scheduler overlap, client-source drift,
  and failure-recovery journeys.

## Handoff acceptance

- Every closed WF resolution maps to at least one epic/PRD requirement, implementation ticket, acceptance journey,
  documentation obligation, or explicit exclusion.
- Every implementation ticket has clear ownership, dependencies, behavior-focused acceptance criteria, exclusions,
  and repository-owned verification commands.
- The map closes only after it links the accepted handoff and has no remaining frontier or unexplained fog.

## Resolution boundary

This ticket creates implementation planning only. It does not implement code, start services, archive the Wayfinder,
commit or publish changes, create releases, enable templates, distribute via `create-project`, or claim production
readiness. Archive remains a separate explicit operation after all closure rules are satisfied.
