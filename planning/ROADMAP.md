# Roadmap

## EPIC progress

<!-- planning:epics -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | [EPIC-00003](epics/00003-EPIC.md) | Adopt Shared Standards and Current Stable Fight Packages | — | done | — | — |
| — | [EPIC-00001](epics/00001-EPIC.md) | Governed Yii Starter Foundation | — | done | — | — |
| — | [EPIC-00002](epics/00002-EPIC.md) | Maintained Yii Package Composition and Quality | — | needs-info | — | — |
<!-- /planning:epics -->

## Current Execution

<!-- planning:execution -->
| Route | TASK | Title | Status | Unresolved blockers |
| --- | --- | --- | --- | --- |
| Needs Info | [TASK-00003](tasks/00003-TASK.md) | Prepare Fight Common 2.0 Migration | needs-info | — |

No TASK is currently executable; unresolved work may remain.
<!-- /planning:execution -->

This generated execution route is separate from the authored planning/decomposition handoff below.
Current publication snapshots live on the [Board](tasks/BOARD.md#current-delivery); verify with
`./bin/planning-check --check-delivery` before routing a delivery handoff.

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

The maintainer approved standalone [TASK-00014](tasks/00014-TASK.md) on 2026-10-25 to prevent repeated stale
post-merge routing, adapting Fight Common's live checks, explicit reconciliation and generated current summaries.
Its scope/record is accepted; no parent decomposition is needed. The planning baseline was integrated through
PR #17 at `30c02ce69625eecafe8884eda83d4e4715bfd900`. Implementation is complete in the main checkout
on the approved TASK branch. Candidate A is independently accepted and QA-passed. At publication,
[PR #18](https://github.com/johnnickell/project-yii/pull/18) awaited human review/merge; intake reconciliation now
verifies it MERGED and locally integrated at `f8d41eefa2eb3b4cb550ab7bb940248be30b2d38`, with
[exact delivery evidence](tasks/00014-TASK.md#delivery-reconciliation--pr-18). No human merge or routine cleanup
handoff remains. Planning publication is historical, not implementation delivery; this does not reopen EPIC-00003.

The broadened EPIC and all three requirement areas are accepted and decomposed; no further TICKET/TASK split is
pending for this adoption scope. [TASK-00011](tasks/00011-TASK.md#accepted-candidate-and-delivery-closeout) is
independently accepted and QA-passed, integrated through PR #12; PR #13's separate runtime repair is also merged.
TASK-00012 independently accepted candidate A, independently QA-passed, and is integrated through merged
[PR #14](https://github.com/johnnickell/project-yii/pull/14) on the TASK-00008 base; its administrative closeout B
changed delivery metadata only. TASK-00008 is independently accepted and QA-passed at its exact candidate, with
[PR #15](https://github.com/johnnickell/project-yii/pull/15) merged at
`f40543de48705007f17cd8c02ea29c1c2c015a11` and [bounded artifact/contract findings](../docs/stable-package-baseline.md).
TICKET-00003 is done. TASK-00009 independently accepted candidate A
`d810303ebeeea9c1114e77ac2025da3a3138d197` after the
[owner's PHP-contract clarification](tasks/00009-TASK.md#current-work--owner-clarification-applied), resolved
REV-01, and independently QA-passed seven scenarios (visual N/A). Its administrative closeout B records
[PR #16](https://github.com/johnnickell/project-yii/pull/16) and automatic parent completion only; it changes no
implementation behavior. Hosted CI remains optional and unobserved. With all three requirement TICKETs terminal,
EPIC-00003 completes automatically. [Current integration](tasks/00009-TASK.md#current-integration--pr-16) verifies
PR #16 merged into `develop` on 2026-10-25 at `26f8ebb06fd07e9aebeb7fb51a33e32ca32509c8` and is locally
integrated; no human merge handoff remains. No new decomposition or second gate TASK is needed.
The broader application map remains in its decision phase. [WF-001](wayfinder/tickets/WF-001-released-package-contract-audit.md)
now closes its separate [released-contract research](wayfinder/research/wf-001-released-package-contract-audit.md),
with complete declaration/workflow dispositions and explicit consumer-proof limits; bounded adoption alone did not
complete it. [WF-003](wayfinder/tickets/WF-003-yii-adr-and-shared-openapi-contract.md) is next after closed WF-001/WF-002,
but no available decision frontier is evidenced until the Symfony wire-contract owner supplies the immutable accepted
artifact (repository, full revision, path and acceptance reference) and the Yii map owner verifies that gate.
No broader-application EPIC/TICKET/TASK decomposition or product implementation is authorized by this research.

## Completed / Released

The governed Yii Starter Foundation is complete. Historical TASK-00002 records verified bounded Yii Fight Common
1.2 candidate composition, production graph, fail-closed deployment credentials, and canonical repair build.
Certification fixed JWT to HS256 and kept Yii Mail/Session unselected while retaining Symfony Mailer.
TASK-00005 established exact coverage and the non-root FPM gate; TASK-00006 recertified the rewritten candidate.
These are historical completion facts, not claims that the current lock already contains stable releases.
TASK-00007 completed reconciliation of the later legacy plan without discarding its work; the fresh canonical
gate passed. Fresh independent review accepted the reconciled head `97bf944` against `6aa5539`; landing records
publication separately from human approval or merge. At that historical checkpoint, stable adoption in TASK-00008
and the consolidated gate in TASK-00009 were unimplemented. The approved 2026-10-24 amendment adds shared-convention
implementation in TASK-00012 and raises the target stable versions without rewriting those historical completion
facts; the current handoff above records subsequent progress.
TASK-00010's duplicate closeout is not implementation evidence.
