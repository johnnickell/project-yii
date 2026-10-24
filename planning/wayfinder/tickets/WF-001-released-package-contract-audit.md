# Released Package Contract Audit

**Labels:** `wayfinder:research`
**Mode:** AFK
**Status:** Open
**Gate:** Verified installed stable Common ^1.4 / AccessControl ^0.8 contracts
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** —

## Question

What is the complete, released, public consumer contract that the Yii starter must compose from
`fight-access-control` `^0.8` and `fight-common` `^1.4`?

The maintainer raised the baseline on 2026-10-24 to the latest verified stable releases: AccessControl 0.8.0 and
Common 1.4.0. [TICKET-00003](../../tickets/00003-TICKET.md#progress) records public registry references and the
prior baseline history. [TASK-00008](../../tasks/00008-TASK.md) now records local installation and bounded
compatibility verification with the complete gate passed, pending independent acceptance. Its
[baseline findings](../../../docs/stable-package-baseline.md) identify installed Common 1.4.0 at
`87b9580c072f3149d574e09865daa79620c92118` and AccessControl 0.8.0 at
`319738255e682d587642b67856039f882b9588d6`, transaction compatibility and stale package publication prose.
The full capability audit remains unperformed. This decision still owns the
complete released-contract inventory. Newer out-of-envelope releases require an explicit scope amendment.
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
