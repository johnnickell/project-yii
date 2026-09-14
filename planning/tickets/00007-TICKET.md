---
id: T-00007
prd: PRD-00002
title: Establish the Lean Yii Pre-Submit Quality Gate
status: ready-for-agent
blocked_by:
---

# Establish the Lean Yii Pre-Submit Quality Gate

## Outcome

Make the Yii-owned `./bin/build` the lean, sole pre-submit gate described by Fight Common
[T-00087](https://github.com/johnnickell/fight-common/blob/develop/planning/tickets/00087-TICKET.md) and
[ADR 0026](https://github.com/johnnickell/fight-common/blob/develop/planning/adr/0026-lean-pre-submit-and-release-qualification.md).

## Scope

- Require `johnnickell/fight-common:^1.2`, the installed `FightCommon` PHPCS standard, and repository-owned scan
  paths/exclusions.
- `./bin/build` is the only local and hosted pre-submit gate, running each retained Unit, Integration,
  Functional, frontend, and browser suite once with Composer validation, syntax/formatting, PHPCS, PHPStan,
  Deptrac, and Rector dry-run.
- Direct Unit tests use `#[CoversClass]` and alone prove exact 100% owned-production statement coverage.
  Retained framework boundaries and valuable journeys use `#[CoversNothing]`.
- Preserve Yii-native boundary coverage and valuable product journeys; keep framework types in Adapter/composition
  under Adapter -> Application -> Domain.

## Exclusions and Cleanup

- Remove remaining policy and coverage-verifier machinery, candidate validation, lowest/latest lanes, receipts and
  authorities, auxiliary locks/digests, clean production-install inspection, and Fight Common certification
  journeys from ordinary builds.
- Do not test build scripts, CI, configuration, coverage tooling, receipts, certification-only fixtures, or docs.
  Hosted CI invokes `./bin/build` only; hosted status is separate delivery evidence.

## Acceptance Criteria

- [ ] The canonical gate executes each retained suite once and all retained code-quality checks.
- [ ] Installed package/standard integration and direct Unit-only exact coverage are enforced without baselines or
      coverage-ignore directives.
- [ ] `#[CoversClass]` direct Unit tests and `#[CoversNothing]` retained boundary/journey suites preserve the
      coverage ownership boundary.
- [ ] Yii composition boundaries and valuable application journeys remain while policy/coverage-verifier and
      certification machinery is absent from ordinary builds.

## Verification

- Run focused retained checks during implementation, then `./bin/build`.
- Inspect CI to confirm it delegates only to `./bin/build`; record hosted status separately.
