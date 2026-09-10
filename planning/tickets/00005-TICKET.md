---
id: T-00005
prd: PRD-00002
title: Establish the Lean Yii Quality Gate and Harden FPM
status: done
blocked_by:
---

# Establish the Lean Yii Quality Gate and Harden FPM

## Outcome

Make `./bin/build` the permanent clean-clone completion gate for owned application code and the few runtime
journeys that provide behavioral evidence. Harden the existing two-service FPM runtime without growing the
starter's product scope.

## Portfolio Provenance

- Fight Common [T-00087](https://github.com/johnnickell/fight-common/blob/develop/planning/tickets/00087-TICKET.md)
- Fight Common PRD-00018

## Scope

- In scope: ordinary Composer validation and locked installation; PHPCS; PHPStan level 6; Deptrac; Rector dry-run;
  one Unit test per `src/` class; exact 100% Unit-suite statement coverage of every `src/` file; valuable
  container and adapter Integration journeys; focused HTTP, console, and production `--no-dev` boot journeys;
  hosted CI delegation to `./bin/build`; and the existing two-service Compose runtime using a non-root FPM user,
  bounded `ondemand` pool configuration, and FIFO error-log streaming.
- Out of scope: selecting unavailable Yii Queue or unsupported native adapters, copied Fight package source,
  testing documentation, scripts, configuration contents, workflows, Dockerfiles, or test machinery; dependency
  lane and receipt certification; new business capabilities; releases; or central builds.

## Acceptance Criteria

- [x] A clean clone can run only `./bin/build` and receive the complete ordered local verdict.
- [x] PHPCS, PHPStan, Deptrac, Rector dry-run, and PHPUnit are locked development dependencies and execute
      inside the build image without baselines or suppressed failures.
- [x] Deptrac enforces Adapter to Application to Domain, rejects unclassified production code, and keeps Yii
      types at Adapter and configuration-provider boundaries.
- [x] Every owned production class has a `#[CoversClass]` Unit test and the Unit suite alone covers every `src/`
      statement exactly; Integration and Functional tests use `#[CoversNothing]`.
- [x] The untested coverage verifier rejects production coverage-ignore directives, missing or malformed Clover,
      missing `src/` files, and any result below exact statement equality.
- [x] Retained Integration tests prove real container or collaborating-adapter behavior; Functional tests prove
      the home HTTP route, Yii console entry point, and the same essential behavior after `--no-dev` installation.
- [x] Root `composer.lock` is the only committed dependency lock; obsolete lowest/latest lanes, support receipts,
      external receipt authority, profiles, and source-scanning governance are removed.
- [x] The FPM Dockerfile and Compose configuration run non-root with bounded `ondemand` pool settings, clean FIFO
      stdout streaming, and only the Yii starter's required application and server services.
- [x] `.github/workflows/build.yml` invokes `./bin/build` without duplicating its ordered checks.

## Verification

- Focused Unit, Integration, and Functional suites.
- FPM configuration validation with `php-fpm -tt`.
- Isolated Compose HTTP 200 and UID 1000 FPM process proof.
- `./bin/planning-check`
- `./bin/build`

## Completion Notes

Completed on 2026-09-10 from fixed point `df169ef` on `feature/t00005-canonical-quality-gate`. The Unit suite
provides exact 232/232 statement coverage of `src/`; retained Integration and Functional suites prove the
selected collaborating seams. The isolated Compose runtime returned HTTP 200 with both FPM master and worker
running as UID/GID 1000, and the task-owned project was stopped after verification.
