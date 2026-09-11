# Yii AccessControl Starter Application

**Label:** `wayfinder:map`
**Status:** Active

> This map is an **index, not a store**. Each material decision lives in exactly one linked ticket under
> `tickets/`; this map only summarizes the linked decision frontier and its blocking relationships.

## Destination

Produce an implementation-ready planning handoff for a complete local-development Yii starter that composes
the released public contracts of Fight Common and Fight AccessControl. The planned product includes MySQL,
Redis, Nginx, PHP-FPM, PHP-CLI workers, Cron scheduling, Mercure private SSE, a versioned JSON API, and a full
React administration console whose editable source lives under `client/` and builds to `public/dist/`.

The API uses Yii-native routing, invokable Actions, dependency injection, PSR-15 middleware, and project-owned
Responders. Symfony remains the wire-contract leader: Yii reproduces its paths, methods, operation IDs, payload
schemas, error semantics, authentication behavior, and realtime event shapes while generating its own complete
OpenAPI document and keeping framework boot, composition, persistence adapters, console commands, and presentation
project-owned.

**Done** = every linked decision ticket is closed; every released consumer-relevant package capability is
classified as HTTP, CLI, worker, or composition-only; cross-repository dependencies are resolved or explicitly
excluded; and [WF-010](tickets/WF-010-implementation-handoff-acceptance-contract.md) links the resulting epic,
PRDs, vertical implementation tickets, acceptance journeys, documentation requirements, and dependency order.

## Notes

- This is planning-only charting. It does not authorize runtime, API, persistence, worker, or frontend changes.
- Starter implementation is gated on installable `fight-common` `v1.2.0` and `fight-access-control` `v0.2.0`;
  development branches or invented `0.1.x` releases cannot satisfy the package audit.
- Fight AccessControl owns scan-only reusable component schemas under `resources/openapi/`. Yii owns one complete
  OpenAPI 3.1 document, generated in one pass from those installed resources and Yii-owned Actions, DTOs, routes,
  security declarations, and project-specific components.
- `Domain <- Application <- Adapter` remains the dependency direction. Fight packages are public Composer
  dependencies; their source and internal coordinators are not copied into the starter.
- Authoritative domain commands execute synchronously. Workers are reserved for post-commit mail and realtime
  publication, retries, and scheduled expiry work.
- Yii's official action and routing contracts are navigation evidence for the project-owned HTTP adapter:
  [Yii Actions](https://yiisoft.github.io/docs/guide/structure/action.html) and
  [Yii Routing](https://yiisoft.github.io/docs/guide/runtime/routing.html).
- Compose topology, ports, volumes, health checks, and bootstrap behavior are local-development decisions only;
  they do not imply a production deployment design.

## Decisions so far

1. **[Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) is open and externally gated.** It inventories released public contracts without turning every package class into an endpoint.
2. **[Local Development Runtime Contract](tickets/WF-002-local-development-runtime-contract.md) is open.** It is the sole current frontier and settles the complete local Compose lifecycle and isolation contract.
3. **[Yii ADR and Shared OpenAPI Contract](tickets/WF-003-yii-adr-and-shared-openapi-contract.md) is open and waiting.** It proves Yii-native Action-Domain-Responder composition while adopting Symfony's finished wire contract.
4. **[Yii DB Persistence and Administrator Bootstrap](tickets/WF-004-yii-db-persistence-and-administrator-bootstrap.md) is open and waiting.** It settles MySQL adapters, atomicity, reconciliation, and invitation-led CLI bootstrap.
5. **[Authentication and Account Security](tickets/WF-005-authentication-and-account-security.md) is open and waiting.** It adopts Symfony-led token behavior and decides Yii middleware and browser defenses.
6. **[Principal and Authorization Mapping](tickets/WF-006-principal-and-authorization-mapping.md) is open and waiting.** It composes package-owned User and Agent principals without duplicating authorization policy.
7. **[Messenger, Scheduling, and Mercure Contract](tickets/WF-007-messenger-scheduling-and-mercure-contract.md) is open and waiting.** It decides post-commit effects, operational failure handling, scheduling, and private SSE.
8. **[Complete HTTP Operation Matrix](tickets/WF-008-complete-http-operation-matrix.md) is open and waiting.** It accounts for every consumer workflow and records intentional non-HTTP boundaries.
9. **[Symfony Client Adoption and Full API Console](tickets/WF-009-symfony-client-adoption-and-full-api-console.md) is open and waiting.** It copies the completed editable Symfony client, then adapts only framework-facing integration.
10. **[Implementation Handoff Acceptance Contract](tickets/WF-010-implementation-handoff-acceptance-contract.md) is open and waiting.** It converts closed decisions into the executable planning portfolio.

## Tickets

| Ticket | Type | Mode | Status | Depends On | Gate |
|---|---|---|---|---|---|
| [WF-001 — Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) | Research | AFK | **Open** | — | Installable Fight Common 1.2.0 and Fight AccessControl 0.2.0 |
| [WF-002 — Local Development Runtime Contract](tickets/WF-002-local-development-runtime-contract.md) | Grilling | HITL | **Open** | — | — |
| [WF-003 — Yii ADR and Shared OpenAPI Contract](tickets/WF-003-yii-adr-and-shared-openapi-contract.md) | Prototype | HITL | **Open** | WF-001, WF-002 | Symfony canonical wire contract |
| [WF-004 — Yii DB Persistence and Administrator Bootstrap](tickets/WF-004-yii-db-persistence-and-administrator-bootstrap.md) | Prototype | HITL | **Open** | WF-001, WF-002, WF-003 | — |
| [WF-005 — Authentication and Account Security](tickets/WF-005-authentication-and-account-security.md) | Grilling | HITL | **Open** | WF-003, WF-004 | Symfony authentication contract |
| [WF-006 — Principal and Authorization Mapping](tickets/WF-006-principal-and-authorization-mapping.md) | Grilling | HITL | **Open** | WF-001, WF-003, WF-004, WF-005 | — |
| [WF-007 — Messenger, Scheduling, and Mercure Contract](tickets/WF-007-messenger-scheduling-and-mercure-contract.md) | Prototype | HITL | **Open** | WF-002 through WF-006 | Symfony realtime contract |
| [WF-008 — Complete HTTP Operation Matrix](tickets/WF-008-complete-http-operation-matrix.md) | Grilling | HITL | **Open** | WF-001, WF-003, WF-005 through WF-007 | Symfony operation matrix |
| [WF-009 — Symfony Client Adoption and Full API Console](tickets/WF-009-symfony-client-adoption-and-full-api-console.md) | Grilling | HITL | **Open** | WF-003, WF-005, WF-007, WF-008 | Immutable accepted Symfony client reference |
| [WF-010 — Implementation Handoff Acceptance Contract](tickets/WF-010-implementation-handoff-acceptance-contract.md) | Grilling | HITL | **Open** | WF-001 through WF-009 | Human approval of the handoff |

## Blocking relationships

```text
installable package releases ──→ WF-001 ───────────────┐
                                                       ├──→ WF-004 ──→ WF-005 ──→ WF-006 ──┐
WF-002 (sole frontier) ──→ WF-003 ─────────────────────┘                                   │
Symfony wire/auth/realtime decisions ──→ WF-003/WF-005/WF-007                              ├──→ WF-008
WF-002 + WF-003 + WF-004 + WF-005 + WF-006 ──→ WF-007 ────────────────────────────────────┘
WF-003 + WF-005 + WF-007 + WF-008 + completed Symfony client ──→ WF-009
WF-001 through WF-009 ──→ WF-010 ──→ epic + PRDs + vertical implementation tickets
```

## Frontier

[WF-002 — Local Development Runtime Contract](tickets/WF-002-local-development-runtime-contract.md) is the
one next grillable decision. It is unblocked, HITL, and can settle the local runtime contract without assuming
the missing package release or unfinished Symfony wire and client decisions.

## Not yet specified (fog)

- Exact service image versions, port assignments, health probes, and worktree-derived Compose naming.
- Symfony's final OpenAPI, authentication, realtime-event, and client-source contracts.
- The final released public capability inventory for Fight Common `v1.2.0` and its compatibility with
  Fight AccessControl `v0.2.0`.
- Whether generated `public/dist/*` assets are committed or remain build-only output.
- The final epic/PRD split and implementation-ticket granularity; WF-010 owns that handoff after decisions close.

## Out of scope

- Any implementation while this map is active, including Docker/Compose files, API Actions, database adapters,
  migrations, middleware, workers, event publication, Twig shells, React code, or compiled assets.
- Production deployment, hosting, infrastructure provisioning, secret distribution, backup, restore, monitoring,
  scaling, and production-readiness claims.
- Self-registration or a public administrator-bootstrap endpoint.
- Releases, tags, Packagist publication, template enablement, and `create-project` distribution.
- Copying Fight package source or consuming unpublished/internal coordinators.
- A shared frontend runtime package between Symfony and Yii; Yii receives a source copy only after Symfony's
  implementation and wire contract are complete.
- Decorative UI for intentionally CLI-only or composition-only capabilities; those require documentation.
