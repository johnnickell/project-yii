# Authentication and Account Security

**Labels:** `wayfinder:grilling`, `security`
**Mode:** HITL
**Status:** Open
**Gate:** Symfony authentication contract
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-003, WF-004

## Question

How will Yii adopt Symfony-led JWT and refresh-session behavior while enforcing the same account-security contract
through Yii-owned middleware and composition?

## Must decide

- Exact access-token and refresh-session issuance, expiry, claims, signing/key rotation, validation, revocation,
  reuse detection, and session-family rotation semantics adopted from Symfony.
- The expected browser lifecycle: in-memory access token plus Secure, HttpOnly refresh cookie unless Symfony's
  active Wayfinder settles a safer compatible contract; define cookie scope, SameSite, expiry, and clearing.
- Yii PSR-15 middleware ordering for request-scoped authentication, principal attachment, authorization, validation,
  exception translation, correlation, CORS/origin checks, CSRF defenses, and response hardening.
- Login, refresh, logout, logout-all/session revocation, invitation activation, password reset, password change,
  email-change request/confirmation, lock/disable, and recovery effects.
- Per-operation throttling and enumeration resistance, generic responses where required, credential/session rotation
  after sensitive changes, and audit/event requirements.
- How human grants are represented and exposed to Actions without embedding policy decisions in middleware or UI.
- Configuration validation and fail-closed behavior for keys, cookie security, trusted origins, clocks, and proxies.
- Rate-limit authentication and recovery by appropriate account/source dimensions and keep credentials, tokens,
  secrets, and raw sensitive request material out of logs.

## Threat-model evidence

- Abuse cases for token theft, refresh replay, fixation, CSRF, cross-origin refresh, credential stuffing, account
  enumeration, stale grants, concurrent rotation, disabled principals, and compromised sessions.
- Wire-level parity checks against the final Symfony contract for successful and rejected authentication journeys.

## Resolution boundary

This ticket adopts the Symfony-led external security contract and settles Yii-specific enforcement seams. It may not
weaken or independently fork wire semantics, add self-registration, implement authentication, or place authorization
policy in Actions, middleware, or the client.
