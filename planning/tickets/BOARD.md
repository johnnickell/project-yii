# Ticket Board

Ticket files are canonical for status and blockers; this board is canonical for recommended execution order.

## "What's Next?" Contract

When an unqualified "What's next?" is asked:

1. **Human decision:** return the item under **Now** when it still requires judgment.
2. **Implementation:** return the first ticket under **Ready Frontier**.
3. If the question is unqualified, return both targets. Never choose by ticket number alone.

## Now

No local human decision is pending. Fight Common 2.0 remains `needs-info` until its contract,
deprecation-removal inventory, and migration guide exist.

## Wayfinder Review

No active Wayfinder map currently exists. When an active map has an unblocked frontier ticket, list it here.
When asked for the next wayfinder target, offer to chart a new feature rather than fabricating one.

## Ready Frontier

| Rank | Ticket | Parent PRD | Why now |
| --- | --- | --- | --- |
| 1 | [T-00002 — Adopt Fight Common 1.2](00002-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Record the complete profile in the canonical support receipt. |

## Waiting

No ticket is currently waiting on an unfinished local dependency.

## Needs Info

| Ticket | Parent PRD | Missing decision or evidence |
| --- | --- | --- |
| [T-00003 — Prepare Fight Common 2.0 Migration](00003-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Fight Common 2.0 contract, deprecation-removal inventory, and migration guide. |

## Recently Done

| Ticket | Parent PRD | Outcome |
|--------|------------|---------|
| [T-00004 — Establish the Yii Complete Platform Profile](00004-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Complete default Yii service composition and booted profile journey verified. |
| [T-00001 — Establish the Governed Yii Starter Foundation](00001-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Local and hosted `./bin/build` receipts are green. The governed bootstrap handoff is accepted. |
