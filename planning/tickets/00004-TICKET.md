---
id: T-00004
prd: PRD-00002
title: Establish the Yii Complete Platform Profile
status: done
blocked_by:
---

# Establish the Yii Complete Platform Profile

## Outcome

A new Project Yii clone resolves Fight Common commit
`4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16` from its VCS repository through
`dev-develop#<commit>` and boots every Fight Common service through project-owned Yii configuration groups,
providers, and safe defaults. Application developers write only Domain/Application services and application
configuration; they do not discover, adapt, or wire Fight Common services.

## Acceptance Criteria

- [x] Composer uses the Fight Common VCS repository with the exact `dev-develop` commit pin and the authorized
  compatibility alias; it records Composer's actual resolved version and source reference.
- [x] Default Yii composition registers and proves validation, security, cache, persistence/event store, synchronous
  and asynchronous messaging, HTTP/PSR-18, request/response, filesystem/storage, transfer, process, scheduler,
  routing, mail, templating, observability, SMS, Mercure/private publication, and every selected fallback.
- [x] Symfony Messenger has a working default asynchronous fallback with complete command/event envelopes. Stable
  Yii Queue remains a separate capability and is recorded as unavailable until a supported integration exists.
- [x] Secrets, application routes, templates, and Domain/Application code remain project-owned and configurable.
- [x] A booted profile journey and configuration tests prove services are available from a clean Composer install.

## Verification

Run the complete-profile focused journeys, `./bin/planning-check`, and the repository-owned `./bin/build`.

The original 2026-08-31 inventory proof was superseded by T-00002's bounded booted request, messaging, stateful,
integration-fallback, dependency-lane, and receipt-authority journeys.

## Scope Boundary

This ticket blocks T-00002's support receipt. It does not publish a package, tag a release, create a project
distribution, or modify a remote pull request.
