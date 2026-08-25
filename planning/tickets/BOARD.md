# Ticket Board

Ticket files are canonical for status and blockers; this board is canonical for recommended execution order.

## "What's Next?" Contract

When an unqualified "What's next?" is asked:

1. **Human decision:** return the item under **Now** when it still requires judgment.
2. **Implementation:** return the first ticket under **Ready Frontier**.
3. If the question is unqualified, return both targets. Never choose by ticket number alone.

## Now

No local decision is pending. Later product capabilities require separately authorized tickets.

## Wayfinder Review

No active Wayfinder map currently exists. When an active map has an unblocked frontier ticket, list it here.
When asked for the next wayfinder target, offer to chart a new feature rather than fabricating one.

## Ready Frontier

No executable local ticket is planned.

## Waiting

No ticket is currently waiting on an unfinished local dependency.

## Needs Info

No tickets currently require a decision authority.

## Recently Done

| Ticket | Parent PRD | Outcome |
|--------|------------|---------|
| [T-00001 — Establish the Governed Yii Starter Foundation](00001-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Local and hosted `./bin/build` receipts are green. The governed bootstrap handoff is accepted. |