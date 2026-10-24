---
id: TICKET-00003
epic: EPIC-00003
title: Adopt Stable Fight Common and AccessControl Contracts
status: done
order: 2
---

# Adopt Stable Fight Common and AccessControl Contracts

## Problem and outcome

The root lock and installed metadata currently contain development references. Move the starter to released
Fight Common `^1.4` and AccessControl `^0.8`, targeting the latest stable releases verified at approval:
Common 1.4.0 and AccessControl 0.8.0. Preserve the already selected Yii capabilities and public package
ownership. A branch alias or a provider checkout's installed version is not the target release baseline.

## Use cases and contracts

Maintainers install the locked graph using repository commands; existing HTTP, console, messaging, security,
cache, observability, storage, and transaction journeys continue to operate. Packages own capability signatures;
Yii owns bindings and framework adapters. Inspect release documentation and installed public signatures before
changing adapters, especially AccessControl's transaction requirements and Yii DB/View compatibility. The
currently installed development AccessControl handlers require compatibility `UnitOfWork`, while Common's Yii
transaction adapter implements `TransactionalUnitOfWork` only. Reinspect the released signatures; passing a
rollback test does not establish handler constructor compatibility. Do not conceal a missing contract with a
consumer alias or start an unapproved AccessControl workflow.

No new business command, query, event, endpoint, permission, or user journey is introduced. Deployment credentials
must still fail closed and JWT remains HS256. Resolve dependency constraints explicitly rather than using ignored
platform requirements or copying source. Any missing public capability becomes a surfaced blocker.

## Acceptance requirements

- Stable version constraints and a regenerated root lock replace Fight development pins/aliases; record resolved
  versions and references, not neighboring checkout branches. Initially resolve Common 1.4.0 / AccessControl
  0.8.0; recheck for newer stable releases at execution and report anything outside `^1.4` / `^0.8` for scope
  amendment before installation. No development candidate or unapproved version-line upgrade.
- Review published changes/migration notes between the installed development references and selected releases;
  inventory exact public transaction, messaging, security, and Yii adapter contracts before changing bindings.
  Resolve the complete graph using normal platform requirements and retain meaningful compatibility evidence.
- Existing selected capabilities remain behaviorally verified, including rejected credentials and rollback.
- Common's shipped PHPCS standard is selected with compatible consumer development dependencies and repository-owned
  scan paths/exclusions; do not install all upstream require-dev packages or suppress failures to make the migration pass.
- Local documentation and WF-001 release-baseline references reflect Common `^1.4` / AccessControl `^0.8`
  and exact installed artifacts without claiming completion of the whole capability inventory. Fight Common
  2.0 remains separately blocked; no automatic migration to another version line is included.

## Exclusions

No login/persistence workflow, package release, native adapter reselection, copied contracts, or runtime expansion.
The subsequent build-lifecycle replacement belongs to TICKET-00004.

## TASKs

<!-- planning:children -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 3 | [TASK-00008](../tasks/00008-TASK.md) | Install and Verify the Stable Fight Package Baseline | [TICKET-00003 — Adopt Stable Fight Common and AccessControl Contracts](00003-TICKET.md) | done | [TASK-00007](../tasks/00007-TASK.md), [TASK-00011](../tasks/00011-TASK.md) | https://github.com/johnnickell/project-yii/pull/15 |
<!-- /planning:children -->

## Progress

On 2026-10-24 the maintainer approved latest stable package adoption in the main checkout on a feature branch.
[Common Packagist metadata](https://repo.packagist.org/p2/johnnickell/fight-common.json) identifies v1.4.0 at
`87b9580c072f3149d574e09865daa79620c92118`; [AccessControl Packagist metadata](https://repo.packagist.org/p2/johnnickell/fight-access-control.json)
identifies v0.8.0 at `319738255e682d587642b67856039f882b9588d6`. Both require PHP >=8.5; AccessControl declares
Common `^1.3`, which permits 1.4.0. This is registry metadata, not proof that the complete Yii graph installs or
behaves correctly. At approval the Yii lock and installed metadata contained Common development reference
`fad24ae9fdcf4ac00fa55c59ef7d35f7c7531911` and AccessControl development reference
`4259ea0b77bc2e1d87e62ab70b64af63bae99477`. TASK-00008 owns the upgrade and verification after TASK-00011's
planning amendment is integrated. TASK-00012's shared instructions are now integrated through merged PR #14;
gate replacement remains TASK-00009. No further TASK decomposition is pending for this accepted requirement.

TASK-00008 was independently accepted and QA-passed at exact candidate
`402ffa5fe601c29fa3a01a5be278f5b74bf8c7fa`, then published as [PR #15](https://github.com/johnnickell/project-yii/pull/15).
[Installed artifact/contract findings](../../docs/stable-package-baseline.md) record verified Common 1.4.0 /
AccessControl 0.8.0, released Yii collaborator changes and narrow transaction compatibility, consumer-owned PHPCS
dependencies and bounded behavior checks. All child TASKs are terminal, so automatic parent completion marks this
TICKET done; publication does not imply review approval, merge, release, or deployment. No AccessControl workflow
is selected and WF-001's full public inventory remains open.

### Historical baseline approval

The maintainer approved stable adoption. Upstream release metadata confirms AccessControl v0.4.0 requires Common
`^1.2` and PHP >=8.5; the whole Yii graph and adapter compatibility still require Composer and behavioral proof.
The same package/PHPCS requirement from the later legacy gate plan is consolidated here, not lost or implemented
again; [TASK-00010](../tasks/00010-TASK.md) retains the source contract and full destination mapping.
