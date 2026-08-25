---
id: T-00001
prd: PRD-00001
title: Establish the Governed Yii Starter Foundation
status: done
blocked_by:
---

# Establish the Governed Yii Starter Foundation

## Outcome

Repository-local planning, architecture, triage, and public-source guidance are canonical. Docker-backed Composer, PHPUnit, lifecycle, Yii-native console wrappers exist. `./bin/build` validates the public Composer boundary and the Yii DI-backed hello-world seam; hosted CI invokes that exact command.

## Scope

- In scope: local planning authority, Docker-backed tooling, `./bin/build` gate, hosted CI, MIT/CONTRIBUTING/SECURITY policies.
- Out of scope: login, persistence, browser UAT, client, realtime, release, tag, Packagist publication, template enablement, create-project distribution.

## Acceptance Criteria

- [x] Repository-local planning, architecture, triage, and public-source guidance are canonical.
- [x] Docker-backed Composer, PHPUnit, lifecycle, and Yii-native console wrappers exist.
- [x] `./bin/build` validates the public Composer boundary and the Yii DI-backed hello-world seam; hosted CI invokes that exact command.
- [x] MIT, contribution, and security policies are present.

## Verification

- `./bin/build` passes locally and in hosted CI.

## Completion Notes

Local and hosted `./bin/build` receipts are green. The governed bootstrap handoff is accepted.