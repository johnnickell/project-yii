---
id: TICKET-00004
epic: EPIC-00003
title: Verify Yii Through a Running-Container PHP Gate
status: ready-for-agent
order: 2
---

# Verify Yii Through a Running-Container PHP Gate

## Problem and outcome

Replace the historical TASK-00005 clean-clone one-command gate with explicit environment preparation followed by
one local/hosted verification command. `bin/build` becomes a thin entrypoint into a PHP phase orchestrator in the
already-running Compose PHP service. It must not build images, install/update dependencies, or manufacture a
second production installation.

## Use cases and contracts

A developer starts the environment and installs the lock with repository-owned commands, then runs `./bin/build`.
CI performs equivalent preparation and invokes the same gate. Missing services or dependencies fail promptly with
a useful setup instruction; the gate does not silently bootstrap or mutate the dependency graph.

Yii owns orchestration, wrappers, and hosted setup. Existing planning validation remains its owning tool rather
than being rewritten in PHP. Package-owned PHPCS is consumed directly after TICKET-00003. No business commands,
queries, events, permissions, or API changes are introduced; failure handling concerns tool exit status and
operator guidance. Secrets must not enter diagnostics.

## Acceptance requirements

- Ordered named, fail-fast PHP phases perform Composer validation, PHP syntax/formatting, planning validation,
  PHPCS, PHPStan, Deptrac, Rector dry-run, and every configured Unit/Integration/Functional suite exactly once.
  Retain justified frontend/browser suites only where configured; do not invent them to test tooling.
- Retain exact 100% Unit-only coverage of every owned-production `src/` statement, including missing-file/ignore
  rejection. Direct Unit tests use `#[CoversClass]`; retained framework boundary/journey suites use
  `#[CoversNothing]`. Replace bespoke policy/coverage-verifier machinery with direct Unit attribution and standard
  coverage-tool enforcement rather than a renamed custom verifier. Broader suites cannot inflate coverage.
  Prefer one coherent PHPUnit run only with demonstrated Unit-only denominator/attribution. No weakened threshold,
  baseline, coverage-ignore directive, suppression, or exclusion migration is implied.
- Remove remaining candidate validation, lowest/latest lanes, receipts/authorities, auxiliary locks/digests, and
  Fight Common certification journeys from ordinary builds; record already-absent machinery accurately. Preserve
  meaningful Yii boundaries and valuable journeys, with framework types in Adapter/composition and dependencies
  directed Adapter → Application → Domain.
- Remove image creation, Composer installation, and disposable `--no-dev` boot from the canonical gate. Retain
  HTTP/console and deployment-credential behavioral proof, and document the intentionally retired installation
  probe rather than claiming equivalent production-install coverage.
- Local and hosted preparation are explicit; CI still delegates verification to `./bin/build` and reports failure.
- Preserve applicable FPM validation, non-root runtime, and focused wrappers; no tests of scripts or tools.

## Exclusions

No full Wayfinder topology, workers, production deployment, release pipeline, or dependency maintenance inside
the gate. Installed-version adoption is TICKET-00003 and precedes this work.

## TASKs

<!-- planning:children -->
| Order | ID | Title | Parent | Status | Blocked by | PR |
| --- | --- | --- | --- | --- | --- | --- |
| 3 | [TASK-00009](../tasks/00009-TASK.md) | Replace the Disposable Build with a Running-Container PHP Gate | [TICKET-00004 — Verify Yii Through a Running-Container PHP Gate](00004-TICKET.md) | ready-for-agent | [TASK-00008](../tasks/00008-TASK.md) | — |
<!-- /planning:children -->

## Progress

The maintainer explicitly requested this changed gate lifecycle. It supersedes conflicting TASK-00005 requirements
only when implemented; the historical acceptance remains intact. TASK-00009 owns runtime and CI verification.

On 2026-09-28 the maintainer directed adoption of the standards and consolidation without losing work or deferring
the overlap. The legacy gate plan in PR #8 is retained as [TASK-00010](../tasks/00010-TASK.md) under
[TICKET-00002](00002-TICKET.md), closed only as a duplicate assignment with a complete destination mapping.
Its gate requirements are incorporated above and in TASK-00009; stable package/PHPCS integration stays in
TICKET-00003 / TASK-00008. Coverage policy is retained while bespoke enforcement/certification machinery is removed;
Composer validation stays in the gate while installation is setup. TASK-00009 follows TASK-00008. This resolved
planning consolidation claims no gate implementation and leaves no overlap-related needs-info blocker.
