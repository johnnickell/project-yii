# Complete HTTP Operation Matrix

**Labels:** `wayfinder:grilling`, `api`
**Mode:** HITL
**Status:** Open
**Gate:** Symfony operation matrix
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-001, WF-003, WF-005, WF-006, WF-007

## Question

Which released consumer capabilities are exposed over HTTP, and how does Yii prove exact operation-by-operation
compatibility with Symfony while documenting intentional CLI, worker, and composition-only exclusions?

## Must decide

- For Users: list/detail/current-authority, administrative lifecycle changes, role/permission views and assignments,
  disable/enable or equivalent released transitions, and any safe profile operations.
- For Roles and Permissions: list/detail, authored lifecycle where public, assignments/removals, reference integrity,
  and managed-policy constraints.
- For invitations and activation: administrative invitation, resend/revoke where public, token inspection rules,
  acceptance, activation, expiry, and enumeration-safe responses.
- For password and email security: login/logout/refresh, password change, reset request/confirmation, email-change
  request/confirmation/cancellation where public, and resulting session/credential rotation.
- For sessions: current/list, revoke one, revoke others/all, reuse/compromise responses, and device metadata limits.
- For managed policy: read/preview/reconcile only if publicly supported, collision/error behavior, and explicit CLI-only
  treatment for operator workflows that should not be remote APIs.
- For Agents: lifecycle, display/name changes where public, signed authentication/challenge inputs, credential issue,
  list, rotate, revoke, permission grants/removals, current authority, and disabled/deleted behavior.
- For direct authority: all released User and Agent grant/revoke/read operations, subject/resource constraints, and
  audit requirements without exposing internal coordinators.
- For every row: audience, method/path, operation ID, request/response schemas, authorization, idempotency, errors,
  audit/event effects, client screen, and disposition as HTTP, CLI, worker, composition-only, or unsupported.
- A completeness reconciliation against WF-001 and Symfony: no unclassified consumer-relevant capability and no Yii
  endpoint absent from the canonical client-facing operation matrix.
- For every HTTP row, identify the Yii Action/Responder pair, Fight Common command/query dispatch, package use case,
  transaction owner, audit effect, and post-commit side effects.
- Record denied, unauthenticated, invalid, missing, conflicting, expired, revoked, replayed, throttled, and concurrent
  outcomes wherever applicable.

## Acceptance evidence

- A bidirectional traceability matrix from released capability to disposition and, for HTTP rows, to Symfony/Yii
  OpenAPI operation, Action/Responder seam, authorization rule, event, acceptance journey, and console surface.
- Explicit non-HTTP exclusions with an operator/developer documentation destination instead of decorative screens.

## Resolution boundary

This ticket accounts for the full public consumer surface; it does not require one endpoint per class, expose package
internals, override Symfony's wire authority, invent UI-only behavior, or implement operations.
