# Wayfinder Maps

Wayfinder maps chart an uncertain feature before it becomes an EPIC, requirement TICKET, or implementation TASK. A map is an
index of linked decision tickets, not a second source of decisions. Start with an active map's **Frontier**; when
none is available, offer to chart a new feature.

## Active maps

| Map | Status | Frontier | Outcome |
|---|---|---|---|
| [Yii AccessControl Starter Application](yii-access-control-application-map.md) | Active | None executable — [WF-001](tickets/WF-001-released-package-contract-audit.md) awaits verified stable installation; WF-002 is closed | Implementation-ready EPIC, requirement TICKETs, vertical TASKs, journeys, and documentation for a complete local-development Yii AccessControl starter. |

Use `_MAP_TEMPLATE.md` and `tickets/_WAYFINDER_TICKET_TEMPLATE.md` for new work. `research/` holds linked
evidence, never a parallel decision record. Archive only through `../../bin/archive-planning` after a map is Closed,
its decisions are Closed, its frontier is empty, and its implementation handoff is linked.
