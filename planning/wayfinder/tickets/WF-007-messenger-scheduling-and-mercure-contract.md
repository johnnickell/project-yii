# Messenger, Scheduling, and Mercure Contract

**Labels:** `wayfinder:prototype`, `operations`
**Mode:** HITL
**Status:** Open
**Gate:** Symfony realtime contract
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** WF-002, WF-003, WF-004, WF-005, WF-006

## Question

How will the Yii starter use Symfony Messenger with Redis streams, Cron scheduling, and Mercure private SSE for
reliable post-commit effects without making asynchronous work authoritative?

## Must decide

- Keep domain commands synchronous through commit and return authoritative results directly; enqueue only
  post-commit mail, realtime publication, retries, and scheduled expiry work.
- Symfony Messenger transport configuration over Redis streams, message allowlisting/versioning, serialization,
  consumer groups, acknowledgement, visibility/redelivery, idempotency, ordering expectations, and backpressure.
- Transactional handoff/outbox or equivalent proof that effects are published only after commit and are recoverable
  after process failure without making Redis the source of truth.
- Worker topology, retry/backoff limits, poison-message isolation, failure transport, inspection, replay, discard,
  graceful shutdown, health/readiness, and local operator commands.
- Cron-triggered scheduler execution, overlap locks, UTC/timezone behavior, catch-up/misfire policy, and expiry tasks
  for invitations, resets, email changes, sessions, Agent credentials, or other released time-bound capabilities.
- An allowlisted, versioned minimal invalidation-event catalog adopted from Symfony; private Mercure topic derivation
  and claims; rejection of unauthorized subscription; no secrets or excessive personal data; reconnect/resume
  behavior; and authoritative API refetch after notification.
- Audit and observability boundaries across command commit, effect enqueue, worker attempt, final failure, recovery,
  scheduled execution, and SSE publication.

## Prototype evidence

- Disposable failure-window proofs for commit/enqueue, duplicate delivery, retry exhaustion, worker restart,
  scheduler overlap, and unauthorized Mercure subscription.
- A representative mutation showing synchronous authoritative response, post-commit event publication, private SSE
  notification, and client refetch from the API.

## Resolution boundary

This ticket settles asynchronous effect and local-operability contracts. It does not move authoritative commands to
workers, guarantee exactly-once delivery, treat SSE as authoritative state, implement processes, or define production
scaling and monitoring.
