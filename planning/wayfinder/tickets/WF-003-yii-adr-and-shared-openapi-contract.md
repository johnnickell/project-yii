# Yii ADR and Shared OpenAPI Contract

**Labels:** `wayfinder:prototype`
**Mode:** HITL
**Status:** Open
**Gate:** Symfony canonical wire contract
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-001, WF-002

## Question

How will Yii implement a versioned Action-Domain-Responder API while remaining exactly wire-compatible with the
Symfony starter's canonical client-facing wire contract?

## Must decide

- Prototype an invokable Yii Action resolved through DI, a project-owned Responder, route registration, request
  parsing and validation, domain command/query invocation, and PSR-7 response production through the real stack.
- Define the Action/Responder responsibility split so Actions translate transport input, application services
  produce authoritative results synchronously, and Responders alone translate outcomes to wire responses.
- Adopt Symfony's exact version prefix, paths, methods, operation IDs, parameters, schemas, status codes, JSend
  success/fail/error envelopes, validation failures, domain failures, and unexpected-error semantics.
- Decide content negotiation, JSON media type, pagination/filter conventions, correlation metadata, idempotency
  inputs where required, and stable error identifiers without leaking internal exception or coordinator types.
- Generate exactly one Yii-owned OpenAPI 3.1 document in one pass by scanning installed Fight AccessControl
  `resources/openapi/` schemas plus Yii Actions, request/response DTOs, routes, security declarations, and local
  components; do not copy Symfony's complete artifact or merge independently generated specifications.
- Own the generator command, checked-in artifact, Swagger UI integration, drift check, servers, tags, paths,
  operations, security schemes, status codes, and Yii-specific errors while comparing normalized client-facing
  semantics with Symfony.
- Record how Yii-native Actions and routing are used without importing Symfony controllers or framework adapters.

## Prototype evidence

- A disposable proof covering one representative write and read through Yii's real router and middleware stack.
- A wire-level comparison against the settled Symfony OpenAPI operation, including invalid input and domain error.
- Findings only: prototype code is not production implementation and is not retained as an unreviewed product slice.

## Resolution boundary

This ticket may settle Yii's internal HTTP adapter pattern and adoption mechanics. It cannot redefine Symfony-led
wire semantics, invent operations before the operation matrix, expose package internals, or implement the API.
