# Planning Authority

This directory is the canonical planning surface for Project Yii. It is independent of Fight Common umbrella
records: this repository owns Yii scope, status, acceptance, build evidence, and documentation updates.

| Surface | Authority |
| --- | --- |
| `specs/` | Product requirements and enduring acceptance decisions. |
| `tickets/` | Executable vertical slices, status, blockers, and recommended order. |
| `ROADMAP.md` | Capability sequence, not ticket status. |
| `agents/` | Focused architecture, tracking, and triage instructions. |

`tickets/BOARD.md` answers “what is next?”; each ticket remains canonical for its own acceptance criteria and
blocking edges. The external [Fight Common PRD-00016](https://github.com/johnnickell/fight-common/blob/develop/planning/specs/00016-PRD.md)
and [Fight Common PRD-00018](https://github.com/johnnickell/fight-common/blob/develop/planning/specs/00018-PRD.md)
are portfolio-bootstrap provenance, not a competing status system. Local EPIC, PRD, and ticket sequences begin at
`00001`; this bootstrap has no repository-local epic.
