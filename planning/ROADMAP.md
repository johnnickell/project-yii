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

1. Migrate local governance to EPIC → TICKET → TASK in [TASK-00007](tasks/00007-TASK.md), preserving completed
   evidence and upstream identities without archiving or inventing runtime progress.
2. Install and verify stable Fight Common `^1.2` / AccessControl `^0.4` and Common's shipped coding standard through
   [TICKET-00003](tickets/00003-TICKET.md) / [TASK-00008](tasks/00008-TASK.md).
3. Replace disposable-image/install verification with explicit environment preparation and a running-container,
   PHP-orchestrated gate through [TICKET-00004](tickets/00004-TICKET.md) / [TASK-00009](tasks/00009-TASK.md).
   Preserve exact Unit-only coverage and meaningful behavioral evidence; document the retired `--no-dev` probe.
4. Continue the broader [Wayfinder](wayfinder/yii-access-control-application-map.md) only through its own decision
   frontier; these maintenance TASKs do not authorize full runtime, persistence, authentication, or client work.
5. Revisit Common 2.0 only after its contract, deprecation-removal inventory, and migration guide exist.

## Completed / Released

The governed Yii Starter Foundation is complete. Historical TASK-00002 records verified bounded Yii Fight Common
1.2 candidate composition, production graph, fail-closed deployment credentials, and canonical repair build.
Certification fixed JWT to HS256 and kept Yii Mail/Session unselected while retaining Symfony Mailer.
TASK-00005 established exact coverage and the non-root FPM gate; TASK-00006 recertified the rewritten candidate.
These are historical completion facts, not claims that the current lock already contains stable releases.
TASK-00007 completed the EPIC/TICKET/TASK planning migration with the existing canonical gate green; stable package
adoption in TASK-00008 is next, followed by the running-container gate in TASK-00009.
