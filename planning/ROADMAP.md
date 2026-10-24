# Roadmap

## EPIC progress

<!-- planning:epics -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | [EPIC-00003](epics/00003-EPIC.md) | Adopt Shared Standards and Current Stable Fight Packages | — | in-progress | — | — |
| — | [EPIC-00001](epics/00001-EPIC.md) | Governed Yii Starter Foundation | — | done | — | — |
| — | [EPIC-00002](epics/00002-EPIC.md) | Maintained Yii Package Composition and Quality | — | needs-info | — | — |
<!-- /planning:epics -->

## Planning Frontier

<!-- planning:frontier -->
None.
<!-- /planning:frontier -->

## Route to comprehensive adoption

1. EPIC → TICKET → TASK reconciliation is complete in [TASK-00007](tasks/00007-TASK.md), preserving all local
   planning from current `develop`, historical evidence, and upstream identities without archiving.
2. The maintainer approved comprehensive adoption on 2026-10-24, using the main checkout on feature branches.
   [TASK-00011](tasks/00011-TASK.md) records and verifies the amended plan; it changes no dependency/runtime behavior.
   [EPIC-00003](epics/00003-EPIC.md#progress) owns the accepted three-area scope and current planning handoff.
3. Adopt shared engineering, planning, Pi skill, and delivery conventions with explicit Yii-native/stronger-policy
   exceptions through [TICKET-00005](tickets/00005-TICKET.md) / [TASK-00012](tasks/00012-TASK.md). This is preferred
   first after the planning amendment; no global installation, enrollment, copied skill catalog, or sandbox claim.
4. Install and verify stable Fight Common `^1.4` / AccessControl `^0.8`, targeting the latest verified releases
   1.4.0/0.8.0, and Common's shipped coding standard through [TICKET-00003](tickets/00003-TICKET.md) /
   [TASK-00008](tasks/00008-TASK.md). Registry evidence is not installation or public-workflow proof.
5. Deliver the complete lean local/hosted gate through [TICKET-00004](tickets/00004-TICKET.md) /
   [TASK-00009](tasks/00009-TASK.md): explicit environment preparation, running-container PHP orchestration,
   direct exact Unit-only coverage, retained Yii journeys, and removal of bespoke verification/certification
   machinery and disposable production installation. Coverage guarantees survive; obsolete machinery does not.
   The later legacy gate plan merged in PR #8 is preserved as [TASK-00010](tasks/00010-TASK.md), closed only as a
   duplicate assignment with every requirement mapped to TASK-00008/00009. No gate decision is deferred.
6. Continue the broader [Wayfinder](wayfinder/yii-access-control-application-map.md) only through its own decision
   frontier; these maintenance TASKs do not authorize full runtime, persistence, authentication, or client work.
7. Revisit Common 2.0 only after its contract, deprecation-removal inventory, and migration guide exist.

### Current planning handoff

The broadened EPIC and all three requirement areas are accepted and decomposed; no further TICKET/TASK split is
pending for this adoption scope. [TASK-00011](tasks/00011-TASK.md#accepted-candidate-and-delivery-closeout) is
independently accepted and QA-passed, integrated through PR #12; PR #13's separate runtime repair is also merged.
TASK-00012 has implemented and self-verified the [Yii binding](agents/shared-workflows.md) and guidance, with independent acceptance,
QA and publication pending. TASK-00008 and TASK-00009 remain unimplemented and retain package/gate ownership.
The broader application map remains in its decision phase with no executable frontier; its later decomposition
is not implied by this bounded adoption portfolio.

## Completed / Released

The governed Yii Starter Foundation is complete. Historical TASK-00002 records verified bounded Yii Fight Common
1.2 candidate composition, production graph, fail-closed deployment credentials, and canonical repair build.
Certification fixed JWT to HS256 and kept Yii Mail/Session unselected while retaining Symfony Mailer.
TASK-00005 established exact coverage and the non-root FPM gate; TASK-00006 recertified the rewritten candidate.
These are historical completion facts, not claims that the current lock already contains stable releases.
TASK-00007 completed reconciliation of the later legacy plan without discarding its work; the fresh canonical
gate passed. Fresh independent review accepted the reconciled head `97bf944` against `6aa5539`; landing records
publication separately from human approval or merge. Stable adoption in TASK-00008 and the consolidated gate in
TASK-00009 remain unimplemented; the approved 2026-10-24 amendment adds shared-convention implementation in
TASK-00012 and raises the target stable versions without rewriting those historical completion facts.
TASK-00010's duplicate closeout is not implementation evidence.
