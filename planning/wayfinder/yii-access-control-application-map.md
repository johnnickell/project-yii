# Yii AccessControl Starter Application

**Label:** `wayfinder:map`
**Status:** Active

> This map is an **index, not a store**. Each material decision lives in exactly one linked ticket under
> `tickets/`; this map only summarizes the linked decision frontier and its blocking relationships.

## Destination

Produce an implementation-ready planning handoff for a complete local-development Yii starter that composes
the released public contracts of Fight Common and Fight AccessControl. The planned product includes MySQL,
Redis, Nginx, PHP-FPM, PHP-CLI workers, Cron scheduling, Mercure private SSE, development-only Mailpit,
a versioned JSON API, and a full React administration console whose editable source lives under `client/`
and builds to `public/dist/`.

The API uses Yii-native routing, invokable Actions, dependency injection, PSR-15 middleware, and project-owned
Responders. Symfony remains the wire-contract leader: Yii reproduces its paths, methods, operation IDs, payload
schemas, error semantics, authentication behavior, and realtime event shapes while generating its own complete
OpenAPI document and keeping framework boot, composition, persistence adapters, console commands, and presentation
project-owned.

**Done** = every linked decision ticket is closed; every released consumer-relevant package capability is
classified as HTTP, CLI, worker, or composition-only; cross-repository dependencies are resolved or explicitly
excluded; and [WF-010](tickets/WF-010-implementation-handoff-acceptance-contract.md) links the resulting EPIC,
requirement TICKETs, vertical implementation TASKs, acceptance journeys, documentation requirements, and dependency order.

## Notes

- This is planning-only charting. It does not authorize runtime, API, persistence, worker, or frontend changes.
- The 2026-10-24 approved package baseline is stable `fight-common` `^1.4` and `fight-access-control` `^0.8`,
  targeting the latest verified releases 1.4.0/0.8.0. [Release evidence and prior baseline history](../tickets/00003-TICKET.md#progress)
  distinguish registry metadata from installation. Development branches or aliases cannot satisfy the package
  audit. [TASK-00008](../tasks/00008-TASK.md) now records independently accepted installed 1.4.0/0.8.0 archives and
  bounded [contract/behavior evidence](../../docs/stable-package-baseline.md), with
  [PR #15](https://github.com/johnnickell/project-yii/pull/15) integrated per the TASK handoff.
  [WF-001's released-contract audit](research/wf-001-released-package-contract-audit.md) now completes the static
  public capability inventory with explicit dispositions and consumer-proof limits. Neither bounded adoption nor
  that research completes this map or authorizes product implementation. Shared-convention adoption in TASK-00012
  did not satisfy the package gate.
- Fight AccessControl 0.8.0 owns scan-only reusable component schemas under `openapi/` (verified by WF-001),
  loaded explicitly through `openapi/bootstrap.php`. Yii owns one complete
  OpenAPI 3.1 document, generated in one pass from those installed resources and Yii-owned Actions, DTOs, routes,
  security declarations, and project-specific components.
- `Domain <- Application <- Adapter` remains the dependency direction. Fight packages are public Composer
  dependencies; their source and internal coordinators are not copied into the starter.
- Authoritative domain commands execute synchronously. Workers are reserved for post-commit mail and realtime
  publication, retries, and scheduled expiry work.
- Yii's official action and routing contracts are navigation evidence for the project-owned HTTP adapter:
  [Yii Actions](https://yiisoft.github.io/docs/guide/structure/action.html) and
  [Yii Routing](https://yiisoft.github.io/docs/guide/runtime/routing.html).
- [WF-002](tickets/WF-002-local-development-runtime-contract.md) settles the project-owned local stack behind
  Fight Agent OS-owned shared HTTPS ingress. It keeps `bin/up` a thin Compose wrapper and setup, migrations,
  and application/browser verification explicit. Per-worktree execution isolation belongs upstream, not Yii.
  This is accepted planning, not an installed ingress dependency or a production deployment design.

## Decisions so far

1. **[Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) is closed.** Its [source-linked census and workflow classifications](research/wf-001-released-package-contract-audit.md) account for the exact installed releases, including internal/unsupported boundaries and downstream consumer obligations; this is not implementation or consumer qualification.
2. **[Local Development Runtime Contract](tickets/WF-002-local-development-runtime-contract.md) is closed.** The confirmed resolution defines project service/storage/health boundaries and acceptance journeys, shared upstream ingress, thin Compose wrappers, explicit setup, and upstream ownership of isolated execution.
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
| [WF-001 — Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) | Research | AFK | **Closed** | — | Verified installed stable Common ^1.4 / AccessControl ^0.8 contracts |
| [WF-002 — Local Development Runtime Contract](tickets/WF-002-local-development-runtime-contract.md) | Grilling | HITL | **Closed** | — | — |
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
verified installed stable contracts ──→ WF-001
WF-001 + WF-002 (closed) ──→ WF-003
WF-001 + WF-002 + WF-003 ──→ WF-004
WF-003 + WF-004 ──→ WF-005
WF-001 + WF-003 + WF-004 + WF-005 ──→ WF-006
Symfony wire/auth/realtime decisions ──→ WF-003/WF-005/WF-007
WF-002 + WF-003 + WF-004 + WF-005 + WF-006 ──→ WF-007
WF-001 + WF-003 + WF-005 + WF-006 + WF-007 ──→ WF-008
WF-003 + WF-005 + WF-007 + WF-008 + completed Symfony client ──→ WF-009
WF-001 through WF-009 ──→ WF-010 ──→ EPIC + TICKETs + vertical implementation TASKs
```

## Frontier

No decision is currently evidenced as available. [WF-001 — Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md)
and WF-002 are Closed. [WF-003 — Yii ADR and Shared OpenAPI Contract](tickets/WF-003-yii-adr-and-shared-openapi-contract.md)
is next in authored order, but its Symfony canonical wire-contract gate has no linked immutable accepted artifact here.
The Symfony wire-contract owner must supply repository, full revision, artifact path and acceptance reference;
the Yii map owner verifies satisfaction before `/skill:wayfinder work WF-003`. This is an input gap, not an inferred
state of the Symfony repository. All later decisions retain their dependencies; no prototype or decomposition is
started by WF-001's research closeout.

## Not yet specified (fog)

- Implementation must select exact compatible pinned image/tool versions, service/environment names, and
  health probes within WF-002's accepted boundaries; these are not unresolved runtime-policy decisions.
- Upstream shared-ingress availability/enrollment must be verified before browser acceptance. Automatic
  per-worktree stacks and execution isolation remain upstream responsibilities.
- Redis role/durability and detailed background behavior remain with WF-007; restricted development-UI access
  follows the owning HTTP/security decisions. A live-reload service is a future opt-in extension, not initial scope.
- Symfony's final OpenAPI, authentication, realtime-event, and client-source contracts.
- WF-001's [audit](research/wf-001-released-package-contract-audit.md) settles installed stable surface accounting,
  not real persistence/crypto/provider qualification. WF-006 needs the AccessControl owner's clarification of the
  documented User-resolver wiring whose implementation is annotated `@internal`; no public API promotion is inferred.
- Whether generated `public/dist/*` assets are committed or remain build-only output.
- The final EPIC/TICKET split and implementation-TASK granularity; WF-010 owns that handoff after decisions close.

## Out of scope

- Any implementation while this map is active, including Docker/Compose files, API Actions, database adapters,
  migrations, middleware, workers, event publication, Twig shells, React code, or compiled assets.
- Production deployment, hosting, infrastructure provisioning, secret distribution, backup, restore, monitoring,
  scaling, and production-readiness claims.
- Yii-owned shared proxy provisioning, automatic per-worktree stacks, execution sandboxing, and preview
  environments; Fight Agent OS owns the applicable upstream infrastructure/execution contracts.
- Self-registration or a public administrator-bootstrap endpoint.
- Releases, tags, Packagist publication, template enablement, and `create-project` distribution.
- Copying Fight package source or consuming unpublished/internal coordinators.
- A shared frontend runtime package between Symfony and Yii; Yii receives a source copy only after Symfony's
  implementation and wire contract are complete.
- Decorative UI for intentionally CLI-only or composition-only capabilities; those require documentation.
