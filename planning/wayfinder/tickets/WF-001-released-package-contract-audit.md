# Released Package Contract Audit

**Labels:** `wayfinder:research`
**Mode:** AFK
**Status:** Closed
**Gate:** Verified installed stable Common ^1.4 / AccessControl ^0.8 contracts
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** —

## Question

What is the complete, released, public consumer contract that the Yii starter must compose from
`fight-access-control` `^0.8` and `fight-common` `^1.4`?

The maintainer raised the baseline on 2026-10-24 to the latest verified stable releases: AccessControl 0.8.0 and
Common 1.4.0. [TICKET-00003](../../tickets/00003-TICKET.md#progress) records public registry references and the
prior baseline history. [TASK-00008](../../tasks/00008-TASK.md) now records independently accepted local installation and bounded
compatibility verification with the complete gate passed; [PR #15](https://github.com/johnnickell/project-yii/pull/15)
is integrated per its [current handoff](../../tasks/00008-TASK.md#current-integration--2026-10-25). Its
[baseline findings](../../../docs/stable-package-baseline.md) identify installed Common 1.4.0 at
`87b9580c072f3149d574e09865daa79620c92118` and AccessControl 0.8.0 at
`319738255e682d587642b67856039f882b9588d6`, transaction compatibility and stale package publication prose.
At intake the full capability audit remained unperformed. The resolution below now owns the
complete released-contract inventory; the bounded baseline alone did not establish it. Newer out-of-envelope releases require an explicit scope amendment.
Shared-convention adoption in TASK-00012 neither satisfies this gate nor closes the decision.

## Must decide

- Inventory every public symbol and documented consumer workflow from installed release artifacts, release notes,
  and package documentation; do not infer a release contract from a development branch.
- Classify each consumer-relevant capability as HTTP, CLI, worker, composition-only, or explicitly unsupported.
- Cover both human and Agent principals and workflows, including agent identity, signed authentication,
  credential lifecycle, direct authority, role/permission assignment, and current-authority reads.
- Separate stable public contracts from internal coordinators, storage assumptions, framework adapters, and
  implementation details that the starter must not expose or copy.
- Record the exact package-version compatibility envelope and any missing or ambiguous consumer documentation.
- Produce traceable input for the persistence, principal, async, and HTTP-operation decisions without prescribing
  their framework implementation.

## Evidence required

- Composer-installed archives or tags for the exact released versions.
- Public API and documentation inventories with source locations and a capability-classification table.
- A completeness check showing that every released consumer-relevant capability has one disposition.

## Resolution boundary

This ticket may classify the released package surface and identify consumer obligations. It may not make private
package internals public, turn each class into an endpoint, select Yii adapters, implement integrations, or treat
an observed tag, development branch, or unverified installation as a completed released-contract audit.

## Resolution — 2026-10-25

Closed for research with the [released-package audit](../research/wf-001-released-package-contract-audit.md),
[complete declaration/member/schema census](../research/wf-001-public-surface.md) and
[consumer-document inventory](../research/wf-001-consumer-documents.md).

- Verified the exact installed Common 1.4.0 / AccessControl 0.8.0 dist identities against lock/installed metadata and
  compared scoped installed files with the exact public archives: 807 Common / 498 AccessControl files matched,
  zero mismatches or extra files in the enumerated trees. No development branch, package update or install was used.
- Accounted for 1,106 scoped PHP files, 1,124 unique named declarations, 4,817 directly declared public members,
  125 distinct opt-in value schemas and all 135 shipped docs Markdown files. Every declaration has one primary
  HTTP-candidate, CLI, worker, composition-only or explicit unsupported disposition, with immutable source links.
- Covered User and direct-Permission Agent workflows, synchronous secret-bearing methods, current authority,
  managed/custom policy, Features, delivery/recovery/maintenance and optional Common components. Values, facts,
  ports, internal coordinators, worker messages and schema anchors are not independently exposed as endpoints.
- Recorded exact compatibility limits, required persistence/transaction/authority/provider obligations and missing
  real-consumer proof. Documented `openapi/` as the actual schema path, stale package publication/View prose, and
  the documented-but-`@internal` User-resolver composition ambiguity for the AccessControl owner and WF-006.
- Kept self-registration out of this map despite its presence in the release. No Yii adapter, endpoint, worker,
  database schema, UI, package release, certification mechanism or implementation TASK was created.

Completeness is static surface/workflow accounting, not exhaustive method-body review, independent Review/QA or
runtime qualification. Owning guides and exact declarations retain their contracts; this research does not make
internal APIs public or assert that optional components are enabled.

## Current handoff

[WF-003 — Yii ADR and Shared OpenAPI Contract](WF-003-yii-adr-and-shared-openapi-contract.md) is next in map order,
with WF-001 and WF-002 closed, but its external Symfony gate has no linked immutable accepted wire artifact here.
The Symfony wire-contract owner must provide repository, full revision, artifact path and acceptance reference;
the Yii map owner verifies that gate before starting the prototype. This is an evidence/input gap, not an inferred
state of another checkout. WF-004–WF-008 receive the audit's explicit persistence/security/async/operation inputs.

Next: obtain that Symfony artifact, then `/skill:wayfinder work WF-003`. No second decision is started by this closeout.
