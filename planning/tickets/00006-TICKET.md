---
id: T-00006
prd: PRD-00002
title: Re-certify Rewritten Fight Common Candidate
status: done
blocked_by:
---

# Re-certify Rewritten Fight Common Candidate

## Outcome

Re-certified the existing Fight Common 1.2 support profile against the tree-equivalent commit identity produced by
the authorship-only history rewrite.

## Scope

- In scope: exact old-to-new candidate mapping, current Composer constraint, latest and lowest locks, executable
  verification references, canonical receipt digests, planning provenance, and the complete local build.
- Out of scope: runtime or public API changes, advancing to a newer Fight Common tree, Fight Common 2.0 migration,
  release publication, and backup cleanup.

## Acceptance Criteria

- [x] The rewritten candidate mapping is `4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16 -> fad24ae9fdcf4ac00fa55c59ef7d35f7c7531911`, and both commits have the same tree.
- [x] Composer latest and lowest lanes resolve the rewritten candidate without changing the certified Fight Common tree.
- [x] The canonical support receipt records the rewritten reference and regenerated lock, content, and receipt digests.
- [x] `./bin/planning-check` and `./bin/build` pass for the complete consumer profile.

## Verification

Regenerate the repository-owned latest and lowest dependency lanes, regenerate the canonical
`fight-common.framework-support-receipt/v1` receipt, run `./bin/planning-check`, and run `./bin/build`.

## Completion Notes

Verified 2026-09-09. The authorship-only Fight Common rewrite preserved the certified source tree; this ticket
records the replacement commit identity and fresh consumer-owned dependency and receipt evidence. Historical
certification statements remain intact in their original ticket.
