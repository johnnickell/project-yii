# Ticket Board

Ticket files are canonical for status and blockers; this board is canonical for recommended execution order.

## What’s Next? Contract

For an unqualified “what’s next?”, return the decision under **Now** and the first executable item under **Ready
Frontier**. Do not select work by ID alone.

## Now

Await the first hosted pull-request build and the immutable clean-clone receipt
for T-00001 before treating the bootstrap handoff as accepted.

## Ready Frontier

No executable local ticket is planned.

## Waiting

No ticket is currently waiting on an unfinished local dependency.

## Recently Done

| Ticket | Parent PRD | Outcome |
| --- | --- | --- |
| [T-00001 — Establish the Governed Yii Starter Foundation](00001-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Established local authority, Docker-backed wrappers, canonical CI gate, public-source guidance, and a native hello-world foundation; hosted-build and clean-clone receipt evidence remain pending. |
