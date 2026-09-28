# Released Package Contract Audit

**Labels:** `wayfinder:research`
**Mode:** AFK
**Status:** Open
**Gate:** Verified installed stable Common 1.2+ / AccessControl 0.4+ contracts
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** —

## Question

What is the complete, released, public consumer contract that the Yii starter must compose from
`fight-access-control` `^0.4` and `fight-common` `^1.2`?

The maintainer raised the baseline during alignment planning. v0.4.0/v1.2.0 tags and Composer metadata have been
observed, but local installation and the full capability audit remain unverified. [TASK-00008](../../tasks/00008-TASK.md)
performs bounded dependency adoption; this decision still owns the complete released-contract inventory.

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
