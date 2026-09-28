# Roadmap

## EPIC progress

<!-- planning:epics -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | [EPIC-00003](epics/00003-EPIC.md) | Align Stable Fight Dependencies and the Yii Development Gate | — | ready-for-agent | — | — |
| — | [EPIC-00001](epics/00001-EPIC.md) | Governed Yii Starter Foundation | — | done | — | — |
| — | [EPIC-00002](epics/00002-EPIC.md) | Maintained Yii Package Composition and Quality | — | needs-info | — | — |
<!-- /planning:epics -->

## Planning Frontier

<!-- planning:frontier -->
None.
<!-- /planning:frontier -->

## Route to the aligned baseline

1. EPIC → TICKET → TASK reconciliation is complete in [TASK-00007](tasks/00007-TASK.md), preserving all local
   planning from current `develop`, historical evidence, and upstream identities without archiving.
2. Install and verify stable Fight Common `^1.2` / AccessControl `^0.4` and Common's shipped coding standard through
   [TICKET-00003](tickets/00003-TICKET.md) / [TASK-00008](tasks/00008-TASK.md).
3. Deliver the complete lean local/hosted gate through [TICKET-00004](tickets/00004-TICKET.md) /
   [TASK-00009](tasks/00009-TASK.md): explicit environment preparation, running-container PHP orchestration,
   direct exact Unit-only coverage, retained Yii journeys, and removal of bespoke verification/certification
   machinery and disposable production installation. Coverage guarantees survive; obsolete machinery does not.
   The later legacy gate plan merged in PR #8 is preserved as [TASK-00010](tasks/00010-TASK.md), closed only as a
   duplicate assignment with every requirement mapped to TASK-00008/00009. No gate decision is deferred.
4. Continue the broader [Wayfinder](wayfinder/yii-access-control-application-map.md) only through its own decision
   frontier; these maintenance TASKs do not authorize full runtime, persistence, authentication, or client work.
5. Revisit Common 2.0 only after its contract, deprecation-removal inventory, and migration guide exist.

## Completed / Released

The governed Yii Starter Foundation is complete. Historical TASK-00002 records verified bounded Yii Fight Common
1.2 candidate composition, production graph, fail-closed deployment credentials, and canonical repair build.
Certification fixed JWT to HS256 and kept Yii Mail/Session unselected while retaining Symfony Mailer.
TASK-00005 established exact coverage and the non-root FPM gate; TASK-00006 recertified the rewritten candidate.
These are historical completion facts, not claims that the current lock already contains stable releases.
TASK-00007 completed reconciliation of the later legacy plan without discarding its work; the fresh canonical
gate passed. Fresh independent review accepted the reconciled head `97bf944` against `6aa5539`; landing records
publication separately from human approval or merge. Stable adoption in TASK-00008 and the consolidated gate in
TASK-00009 remain unimplemented. TASK-00010's duplicate closeout is not implementation evidence.
