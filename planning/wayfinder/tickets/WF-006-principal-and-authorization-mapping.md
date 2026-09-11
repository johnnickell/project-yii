# Principal and Authorization Mapping

**Labels:** `wayfinder:grilling`, `authorization`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-001, WF-003, WF-004, WF-005

## Question

How should package-owned User and Agent principals flow through Yii route and application authorization without
duplicating access-control policy in the framework layer?

## Must decide

- A request-scoped authenticated-principal abstraction that preserves the distinction between human User and Agent
  identities, authentication method, stable subject, current authority, and audit actor/subject.
- Mapping from JWT or signed Agent authentication into package-owned principal contracts without starter-owned
  shadow principal models or implicit conversion between User and Agent.
- Route-level coarse requirements versus application-level resource and direct-authority decisions; Actions express
  required intent and delegate decisions to public package contracts.
- Human roles/permissions, Agent permissions, direct authority operations, managed-policy constraints, ownership,
  and any organization/resource scope discovered in WF-001.
- Denial semantics, absent/deleted/disabled principals, stale grants, confused-deputy prevention, authority snapshots,
  and audit evidence for both allowed and denied sensitive operations.
- DI bindings and middleware/application seams that remain Yii-owned while policy remains package-owned.

## Acceptance evidence

- A principal matrix covering User and Agent authentication, authority lookup, route access, application decisions,
  denial responses, and audit attribution.
- Traced representative journeys proving no Action, middleware, repository, or client duplicates package policy.

## Resolution boundary

This ticket settles mapping and delegation boundaries. It does not invent policy, copy principal classes, make an
Agent a human session, expose internal coordinators, or implement middleware and authorization checks.
