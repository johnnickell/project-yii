# Wayfinder Maps

Wayfinder maps chart an uncertain feature before it becomes an EPIC, requirement TICKET, or implementation TASK. A map is an
index of linked decision tickets, not a second source of decisions. Start with an active map's **Frontier**; when
none is available, offer to chart a new feature.

## Active maps

| Map | Status | Frontier | Outcome |
|---|---|---|---|
| [Yii AccessControl Starter Application](yii-access-control-application-map.md) | Active | No available frontier: WF-001 and WF-002 are Closed; [WF-003](tickets/WF-003-yii-adr-and-shared-openapi-contract.md) is next but waits for the Symfony wire-contract owner's immutable accepted artifact (repository, revision, path and acceptance reference), then Yii map-owner gate verification | Implementation-ready EPIC, requirement TICKETs, vertical TASKs, journeys, and documentation for a complete local-development Yii AccessControl starter. |

Use `_MAP_TEMPLATE.md` and `tickets/_WAYFINDER_TICKET_TEMPLATE.md` for new work. `research/` holds linked
evidence, never a parallel decision record. Archive only through `../../bin/archive-planning` after a map is Closed,
its decisions are Closed, its frontier is empty, and its implementation handoff is linked.
