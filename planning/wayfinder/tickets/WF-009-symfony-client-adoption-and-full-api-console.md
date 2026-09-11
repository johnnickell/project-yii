# Symfony Client Adoption and Full API Console

**Labels:** `wayfinder:grilling`, `frontend`
**Mode:** HITL
**Status:** Open
**Gate:** Immutable accepted Symfony client reference
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-003, WF-005, WF-007, WF-008

## Question

How will Yii adopt the completed Symfony administration client as editable project source and provide a coherent UI
for every exposed HTTP workflow without creating a shared frontend runtime package?

## Must decide

- The exact upstream Symfony commit/release and source directories copied into Yii after the Symfony client and
  canonical wire contract are complete; record the immutable source reference, exact copy manifest, attribution,
  and a repeatable provenance/update procedure.
- A complete editable React/TypeScript/ESBuild/Sass application under `client/`, with generated assets emitted to
  `public/dist/*`; decide whether generated assets are committed or build-only.
- Keep domain screens, routing, generated API types/client behavior, validation mapping, auth lifecycle, access
  control, SSE handling, tests, and accessibility behavior wire-compatible with Symfony.
- Regenerate API types and bindings from Yii's own one-pass OpenAPI document; never retain bindings generated from
  Symfony's artifact as Yii authority.
- Adapt only framework-facing boot/runtime integration: base/public paths, asset manifest loading, Twig host shell,
  environment injection, API/Mercure origins, development server/proxy behavior, and repository wrappers.
  Any substantive source divergence requires an explicit cross-starter contract review.
- UI coverage for every HTTP operation in WF-008: Users, Roles, Permissions, invitations/activation, password and
  email security, sessions, managed policy where exposed, Agents, credentials, permissions, and direct authority.
- Navigation and capability-aware affordances, loading/empty/error/success states, destructive confirmation,
  optimistic versus authoritative refresh behavior, and accessibility/responsive requirements.
- In-memory access-token and HttpOnly refresh-cookie behavior adopted from Symfony, plus private SSE notifications
  that trigger authoritative API refetch rather than mutating local state as truth.
- Explicit documentation, not empty screens, for CLI-only, worker-only, composition-only, and unsupported operations.

## Acceptance evidence

- A screen-to-operation matrix with every exposed HTTP workflow covered and every non-HTTP capability documented.
- A source-diff policy proving Yii changes are limited to framework integration unless a cross-starter contract change
  is first resolved by the owning Symfony decision.
- Planned build, type, lint, unit/component, and high-value browser journey gates with no implementation in this ticket.

## Resolution boundary

This ticket may settle source adoption, framework integration, UI coverage, and generated-output policy. It cannot
begin before the Symfony client is complete, create a shared runtime package, fork wire semantics, add decorative
screens for non-HTTP operations, or copy/build frontend code during charting.
