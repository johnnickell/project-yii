---
id: T-00001
parent: PRD-00001
title: Establish the Governed Yii Starter Foundation
status: done
---

# Establish the Governed Yii Starter Foundation

## Acceptance

- Repository-local planning, architecture, triage, and public-source guidance are canonical.
- Docker-backed Composer, PHPUnit, lifecycle, exec, and Yii-native console wrappers exist.
- `./bin/build` validates governance and the established hello-world foundation; hosted CI invokes that exact
  command for push and pull-request events on `develop`, `main`, and `release/**`.
- MIT, contribution, and security policies are present.

## Exclusions

No login, persistence, browser UAT, client, realtime, release, tag, Packagist publication, template enablement, or
create-project distribution is included.
