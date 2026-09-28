---
id: TICKET-00003
epic: EPIC-00003
title: Adopt Stable Fight Common and AccessControl Contracts
status: ready-for-agent
order: 1
---

# Adopt Stable Fight Common and AccessControl Contracts

## Problem and outcome

The root lock and installed metadata currently contain development references. Move the starter to released
Fight Common `^1.2` and AccessControl `^0.4` while preserving the already selected Yii capabilities and public
package ownership. A branch alias is not a stable release.

## Use cases and contracts

Maintainers install the locked graph using repository commands; existing HTTP, console, messaging, security,
cache, observability, storage, and transaction journeys continue to operate. Packages own capability signatures;
Yii owns bindings and framework adapters. Inspect release documentation and installed public signatures before
changing adapters, especially AccessControl's transaction requirements and Yii DB/View compatibility.

No new business command, query, event, endpoint, permission, or user journey is introduced. Deployment credentials
must still fail closed and JWT remains HS256. Resolve dependency constraints explicitly rather than using ignored
platform requirements or copying source. Any missing public capability becomes a surfaced blocker.

## Acceptance requirements

- Stable version constraints and a regenerated root lock replace Fight development pins/aliases; record resolved
  versions and references, not neighboring checkout branches. No unapproved major-version upgrade.
- Existing selected capabilities remain behaviorally verified, including rejected credentials and rollback.
- Common's shipped PHPCS standard is selected with compatible consumer development dependencies and repository-owned
  scan paths/exclusions; do not install all upstream require-dev packages or suppress failures to make the migration pass.
- Local documentation and WF-001 release-baseline references reflect 1.2+/0.4+ without claiming completion of its
  whole capability inventory. Fight Common 2.0 remains separately blocked.

## Exclusions

No login/persistence workflow, package release, native adapter reselection, copied contracts, or runtime expansion.
The subsequent build-lifecycle replacement belongs to TICKET-00004.

## TASKs

<!-- planning:children -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 2 | [TASK-00008](../tasks/00008-TASK.md) | Install and Verify the Stable Fight Package Baseline | [TICKET-00003 — Adopt Stable Fight Common and AccessControl Contracts](00003-TICKET.md) | ready-for-agent | [TASK-00007](../tasks/00007-TASK.md) | — |
<!-- /planning:children -->

## Progress

The maintainer approved stable adoption. Upstream release metadata confirms AccessControl v0.4.0 requires Common
`^1.2` and PHP >=8.5; the whole Yii graph and adapter compatibility still require Composer and behavioral proof.
The same package/PHPCS requirement from the later legacy gate plan is consolidated here, not lost or implemented
again; [TASK-00010](../tasks/00010-TASK.md) retains the source contract and full destination mapping.
